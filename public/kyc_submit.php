<?php
declare(strict_types=1);

use App\{Csrf, Validator, Upload, Db, RateLimiter};

require_once __DIR__ . '/../src/Csrf.php';
require_once __DIR__ . '/../src/Validator.php';
require_once __DIR__ . '/../src/Upload.php';
require_once __DIR__ . '/../src/Db.php';
require_once __DIR__ . '/../src/RateLimiter.php';

function current_user_id(): int { return 123; }

header('Content-Type: application/json');

$limiter = new RateLimiter(sys_get_temp_dir(), 30, 60);
if (!$limiter->allow($_SERVER['REMOTE_ADDR'] ?? 'cli')) {
    http_response_code(429);
    echo json_encode(['success' => false, 'errors' => ['general' => 'Too many requests']]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'errors' => ['general' => 'Invalid method']]);
    exit;
}

if (!Csrf::validate($_POST['csrf_token'] ?? '')) {
    echo json_encode(['success' => false, 'errors' => ['general' => 'CSRF validation failed']]);
    exit;
}

$validator = new Validator();
$profileType = $_POST['profile_type'] ?? null;
$country = trim((string)($_POST['country'] ?? ''));
$hasRegCert = isset($_POST['has_regulatory_cert']) ? (int)$_POST['has_regulatory_cert'] : null;
$companyName = trim((string)($_POST['company_name'] ?? ''));
$years = $_POST['years_experience'] ?? null;
$areas = trim((string)($_POST['areas'] ?? ''));
$signatory = trim((string)($_POST['signatory_name'] ?? ''));
$nationality = trim((string)($_POST['nationality'] ?? ''));
$designation = trim((string)($_POST['designation'] ?? ''));
$telephone = trim((string)($_POST['telephone'] ?? ''));
$mobile = trim((string)($_POST['mobile'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));

$validator->required('profile_type', $profileType);
$validator->required('country', $country);
$validator->required('has_regulatory_cert', $hasRegCert);
$validator->intRange('years_experience', $years, 0, 60);
$validator->stringLen('areas', $areas, 0, 1000);
$validator->stringLen('signatory_name', $signatory, 2, 120);
$validator->stringLen('nationality', $nationality, 2, 60);
$validator->stringLen('designation', $designation, 0, 80);
$validator->phoneE164('telephone', $telephone, false);
$validator->phoneE164('mobile', $mobile, true);
$validator->email('email', $email);

if (in_array($profileType, ['Agency','Other'], true)) {
    $validator->stringLen('company_name', $companyName, 1, 150);
}
if ($hasRegCert === 1) {
    // will validate file later
}

$config = require __DIR__ . '/../config/app.php';
$uploadDir = $config['paths']['upload_dir'];

$trade = Upload::handle('doc_trade_license', true, $uploadDir);
$reg = Upload::handle('doc_regulatory_cert', $hasRegCert === 1, $uploadDir);
$passport = Upload::handle('doc_passport', true, $uploadDir);
$moa = Upload::handle('doc_moa_poa', false, $uploadDir);
$proof = Upload::handle('doc_proof_address', true, $uploadDir);

foreach ([
    'doc_trade_license' => $trade,
    'doc_regulatory_cert' => $reg,
    'doc_passport' => $passport,
    'doc_moa_poa' => $moa,
    'doc_proof_address' => $proof
] as $field => $res) {
    if (isset($res['error'])) {
        $validator->errors[$field] = $res['error'];
    }
}

if ($validator->errors) {
    echo json_encode(['success' => false, 'errors' => $validator->errors]);
    exit;
}

$pdo = Db::pdo();
$stmt = $pdo->prepare("INSERT INTO kyc_submissions
    (user_id, profile_type, country, has_regulatory_cert, company_name, years_experience, areas,
     signatory_name, nationality, designation, telephone, mobile, email,
     doc_trade_license_path, doc_regulatory_cert_path, doc_passport_path, doc_moa_poa_path, doc_proof_address_path)
     VALUES
    (:user_id,:profile_type,:country,:has_regulatory_cert,:company_name,:years_experience,:areas,
     :signatory_name,:nationality,:designation,:telephone,:mobile,:email,
     :doc_trade_license_path,:doc_regulatory_cert_path,:doc_passport_path,:doc_moa_poa_path,:doc_proof_address_path)
    ON DUPLICATE KEY UPDATE
     profile_type=VALUES(profile_type), country=VALUES(country), has_regulatory_cert=VALUES(has_regulatory_cert),
     company_name=VALUES(company_name), years_experience=VALUES(years_experience), areas=VALUES(areas),
     signatory_name=VALUES(signatory_name), nationality=VALUES(nationality), designation=VALUES(designation),
     telephone=VALUES(telephone), mobile=VALUES(mobile), email=VALUES(email),
     doc_trade_license_path=VALUES(doc_trade_license_path), doc_regulatory_cert_path=VALUES(doc_regulatory_cert_path),
     doc_passport_path=VALUES(doc_passport_path), doc_moa_poa_path=VALUES(doc_moa_poa_path),
     doc_proof_address_path=VALUES(doc_proof_address_path)");

$stmt->execute([
    ':user_id' => current_user_id(),
    ':profile_type' => $profileType,
    ':country' => $country,
    ':has_regulatory_cert' => $hasRegCert,
    ':company_name' => $companyName ?: null,
    ':years_experience' => $years,
    ':areas' => $areas ?: null,
    ':signatory_name' => $signatory,
    ':nationality' => $nationality,
    ':designation' => $designation ?: null,
    ':telephone' => $telephone ?: null,
    ':mobile' => $mobile,
    ':email' => $email,
    ':doc_trade_license_path' => $trade['path'],
    ':doc_regulatory_cert_path' => $reg['path'] ?? null,
    ':doc_passport_path' => $passport['path'],
    ':doc_moa_poa_path' => $moa['path'] ?? null,
    ':doc_proof_address_path' => $proof['path'],
]);

echo json_encode(['success' => true]);
