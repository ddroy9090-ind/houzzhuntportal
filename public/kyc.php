<?php
declare(strict_types=1);

use App\Csrf;
require_once __DIR__ . '/../src/Csrf.php';

// README: copy config/app.example.php to config/app.php and adjust DB credentials.
// Place this file under a web server document root.

function current_user_id(): int { return 123; }

$token = Csrf::token();
$countries = [
    'India',
    'United Arab Emirates',
    'United States',
    'United Kingdom',
    'Canada',
    'Australia'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>KYC – Channel Partner</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .step { display: none; }
        .step.active { display: block; }
        .checklist li.valid { color: green; }
    </style>
</head>
<body>
<div class="container py-4">
    <h1 class="mb-4">KYC – Channel Partner</h1>
    <div class="row">
        <div class="col-md-8">
            <form id="kycForm" enctype="multipart/form-data">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($token) ?>">
                <!-- Step 1: Profile Type -->
                <div class="step active" data-step="1">
                    <div class="mb-3">
                        <label class="form-label">Are You an Agent or Agency?</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="profile_type" value="Agent" required>
                            <label class="form-check-label">Agent</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="profile_type" value="Agency" required>
                            <label class="form-check-label">Agency</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="profile_type" value="Other" required>
                            <label class="form-check-label">Other</label>
                        </div>
                        <div class="invalid-feedback d-block" data-field="profile_type"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Country</label>
                        <input list="country-list" class="form-control" name="country" required>
                        <datalist id="country-list">
                            <?php foreach ($countries as $c): ?>
                                <option value="<?= htmlspecialchars($c) ?>"></option>
                            <?php endforeach; ?>
                        </datalist>
                        <div class="invalid-feedback" data-field="country"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Regulatory Certificate</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="has_regulatory_cert" value="1" required>
                            <label class="form-check-label">Yes</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="has_regulatory_cert" value="0" required>
                            <label class="form-check-label">No</label>
                        </div>
                        <div class="invalid-feedback d-block" data-field="has_regulatory_cert"></div>
                    </div>
                    <button type="button" class="btn btn-primary next">Next</button>
                </div>

                <!-- Step 2: Agent / Company Details -->
                <div class="step" data-step="2">
                    <h4>Agent / Company Details</h4>
                    <div class="mb-3">
                        <label class="form-label">Name (as per Trade/Business License)</label>
                        <input type="text" class="form-control" name="company_name" maxlength="150">
                        <div class="invalid-feedback" data-field="company_name"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Years in Business/Experience</label>
                        <input type="number" class="form-control" name="years_experience" min="0" max="60" required>
                        <div class="invalid-feedback" data-field="years_experience"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Areas of Operation / Geographical Coverage</label>
                        <textarea class="form-control" name="areas" maxlength="1000"></textarea>
                        <div class="invalid-feedback" data-field="areas"></div>
                    </div>
                    <button type="button" class="btn btn-secondary back">Back</button>
                    <button type="button" class="btn btn-primary next">Next</button>
                </div>

                <!-- Step 3: Authorized Signatory Details -->
                <div class="step" data-step="3">
                    <h4>Authorized Signatory Details</h4>
                    <div class="mb-3">
                        <label class="form-label">Name (as per Passport)</label>
                        <input type="text" class="form-control" name="signatory_name" required>
                        <div class="invalid-feedback" data-field="signatory_name"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Nationality</label>
                        <input type="text" class="form-control" name="nationality" required>
                        <div class="invalid-feedback" data-field="nationality"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Designation</label>
                        <input type="text" class="form-control" name="designation">
                        <div class="invalid-feedback" data-field="designation"></div>
                    </div>
                    <button type="button" class="btn btn-secondary back">Back</button>
                    <button type="button" class="btn btn-primary next">Next</button>
                </div>

                <!-- Step 4: Contact Details -->
                <div class="step" data-step="4">
                    <h4>Contact Details</h4>
                    <div class="mb-3">
                        <label class="form-label">Telephone/Alternate Mobile</label>
                        <input type="tel" class="form-control" name="telephone">
                        <div class="invalid-feedback" data-field="telephone"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mobile</label>
                        <input type="tel" class="form-control" name="mobile" required>
                        <div class="invalid-feedback" data-field="mobile"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                        <div class="invalid-feedback" data-field="email"></div>
                    </div>
                    <button type="button" class="btn btn-secondary back">Back</button>
                    <button type="button" class="btn btn-primary next">Next</button>
                </div>

                <!-- Step 5: Upload Required Documents -->
                <div class="step" data-step="5">
                    <h4>Upload Required Documents</h4>
                    <div class="mb-3">
                        <label class="form-label">Valid Trade / Business License / Personal Identification Document</label>
                        <input type="file" class="form-control" name="doc_trade_license" accept=".doc,.docx,.xls,.xlsx,.ppt,.pptx,.pdf,.png,.jpg,.jpeg,.webp,.mp4,.mp3" required>
                        <div class="form-text">File limit: 10MB</div>
                        <div class="invalid-feedback" data-field="doc_trade_license"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Regulatory Registration/Certificate (If applicable)</label>
                        <input type="file" class="form-control" name="doc_regulatory_cert" accept=".doc,.docx,.xls,.xlsx,.ppt,.pptx,.pdf,.png,.jpg,.jpeg,.webp,.mp4,.mp3">
                        <div class="form-text">Required if Regulatory Certificate = Yes</div>
                        <div class="invalid-feedback" data-field="doc_regulatory_cert"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Passport of Authorized Signatory</label>
                        <input type="file" class="form-control" name="doc_passport" accept=".doc,.docx,.xls,.xlsx,.ppt,.pptx,.pdf,.png,.jpg,.jpeg,.webp,.mp4,.mp3" required>
                        <div class="invalid-feedback" data-field="doc_passport"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Company MOA/POA (If applicable)</label>
                        <input type="file" class="form-control" name="doc_moa_poa" accept=".doc,.docx,.xls,.xlsx,.ppt,.pptx,.pdf,.png,.jpg,.jpeg,.webp,.mp4,.mp3">
                        <div class="invalid-feedback" data-field="doc_moa_poa"></div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Proof of Address</label>
                        <input type="file" class="form-control" name="doc_proof_address" accept=".doc,.docx,.xls,.xlsx,.ppt,.pptx,.pdf,.png,.jpg,.jpeg,.webp,.mp4,.mp3" required>
                        <div class="invalid-feedback" data-field="doc_proof_address"></div>
                    </div>
                    <button type="button" class="btn btn-secondary back">Back</button>
                    <button type="submit" class="btn btn-success" id="submitBtn" disabled>Submit</button>
                </div>
            </form>
        </div>
        <div class="col-md-4">
            <ul class="list-unstyled checklist" id="checklist">
                <li>Profile Type</li>
                <li>Agent / Company Details</li>
                <li>Authorized Signatory Details</li>
                <li>Contact Details</li>
                <li>Upload Documents</li>
            </ul>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function(){
    const steps = document.querySelectorAll('.step');
    const checklistItems = document.querySelectorAll('#checklist li');
    let current = 0;

    function showStep(i){
        steps[current].classList.remove('active');
        steps[i].classList.add('active');
        current = i;
    }

    document.querySelectorAll('.next').forEach(btn => {
        btn.addEventListener('click', () => {
            const form = document.getElementById('kycForm');
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            checklistItems[current].classList.add('valid');
            showStep(current+1);
            toggleSubmit();
        });
    });
    document.querySelectorAll('.back').forEach(btn => {
        btn.addEventListener('click', () => {
            showStep(current-1);
        });
    });

    // Conditional requirements
    const profileRadios = document.querySelectorAll('input[name="profile_type"]');
    const companyName = document.querySelector('input[name="company_name"]');
    profileRadios.forEach(r => r.addEventListener('change', () => {
        if (r.checked && (r.value === 'Agency' || r.value === 'Other')) {
            companyName.required = true;
        } else if (r.checked && r.value === 'Agent') {
            companyName.required = false;
        }
    }));

    const regRadios = document.querySelectorAll('input[name="has_regulatory_cert"]');
    const regDoc = document.querySelector('input[name="doc_regulatory_cert"]');
    function toggleRegDoc(){
        regDoc.required = document.querySelector('input[name="has_regulatory_cert"][value="1"]').checked;
    }
    regRadios.forEach(r => r.addEventListener('change', toggleRegDoc));
    toggleRegDoc();

    // File validation & preview
    const allowedMimes = [
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'application/vnd.ms-excel',
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'application/vnd.ms-powerpoint',
        'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'image/jpeg','image/png','image/webp',
        'video/mp4','audio/mpeg'
    ];
    document.querySelectorAll('input[type="file"]').forEach(inp => {
        inp.addEventListener('change', () => {
            const file = inp.files[0];
            const feedback = document.querySelector(`[data-field="${inp.name}"]`);
            if (!file){ feedback.textContent=''; return; }
            if (file.size > 10 * 1024 * 1024 || !allowedMimes.includes(file.type)) {
                feedback.textContent = 'Invalid file';
                inp.value = '';
            } else {
                feedback.textContent = `${file.name} (${(file.size/1024/1024).toFixed(2)} MB)`;
            }
            toggleSubmit();
        });
    });

    function toggleSubmit(){
        const form = document.getElementById('kycForm');
        document.getElementById('submitBtn').disabled = !form.checkValidity();
    }
    document.getElementById('kycForm').addEventListener('change', toggleSubmit);

    document.getElementById('kycForm').addEventListener('submit', function(e){
        e.preventDefault();
        const form = e.target;
        if (!form.checkValidity()) { form.reportValidity(); return; }
        const fd = new FormData(form);
        fetch('kyc_submit.php', {method:'POST', body:fd})
            .then(r => r.json())
            .then(data => {
                if(data.success){
                    alert('KYC submitted. Status: Pending Review.');
                    form.reset();
                    location.reload();
                } else {
                    Object.entries(data.errors).forEach(([k,v]) => {
                        const err = document.querySelector(`[data-field="${k}"]`);
                        if(err) err.textContent = v;
                    });
                }
            })
            .catch(()=>alert('Submission failed'));
    });
})();
</script>
</body>
</html>
