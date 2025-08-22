<?php
include 'config.php';

// Upload function
function uploadFile($fileInput, $folder = "uploads/")
{
    if (!empty($_FILES[$fileInput]['name']) && $_FILES[$fileInput]['error'] === 0) {
        $filename = time() . "_" . basename($_FILES[$fileInput]['name']);
        $targetPath = $folder . $filename;
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }
        if (move_uploaded_file($_FILES[$fileInput]['tmp_name'], $targetPath)) {
            return $filename;
        }
    }
    return null;
}

// Collect form data
$project_name = $_POST['project_name'] ?? '';
$description = $_POST['description'] ?? '';
$sub_heading = $_POST['sub_heading'] ?? '';
$project_heading = $_POST['project_heading'] ?? '';
$project_details = $_POST['project_details'] ?? '';
$starting_price = $_POST['starting_price'] ?? '';
$payment_plan = $_POST['payment_plan'] ?? '';
$handover = $_POST['handover'] ?? '';
$aed_per_sqft = $_POST['aed_per_sqft'] ?? '';
$starting_area = $_POST['starting_area'] ?? '';
$location = $_POST['location'] ?? '';
$extra_text = $_POST['extra_text'] ?? '';
$amenities = isset($_POST['amenities']) ? implode(", ", $_POST['amenities']) : "";

// File uploads
$brochure = uploadFile("brochure");
$main_picture = uploadFile("main_picture");
$image2 = uploadFile("image2");
$image3 = uploadFile("image3");
$image4 = uploadFile("image4");
$floor_plan = uploadFile("floor_plan");

// Insert Query
$sql = "INSERT INTO properties 
(project_name, description, sub_heading, brochure, project_heading, project_details, starting_price, payment_plan, handover, main_picture, image2, image3, image4, amenities, floor_plan, aed_per_sqft, starting_area, location, extra_text) 
VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Prepare failed: " . $conn->error);
}

if (
    !$stmt->bind_param(
        "sssssssssssssssssss",
        $project_name,
        $description,
        $sub_heading,
        $brochure,
        $project_heading,
        $project_details,
        $starting_price,
        $payment_plan,
        $handover,
        $main_picture,
        $image2,
        $image3,
        $image4,
        $amenities,
        $floor_plan,
        $aed_per_sqft,
        $starting_area,
        $location,
        $extra_text
    )
) {
    die("Bind failed: " . $stmt->error);
}

if ($stmt->execute()) {
    echo "<script>
                alert('✅ Form submitted successfully!');
                window.onload = function() {
                    document.getElementById('propertyForm').reset();
                }
              </script>";
} else {
    echo "<script>alert('❌ Error while saving data');</script>";
}

// Show All Properties
$result = $conn->query("SELECT * FROM properties ORDER BY id DESC");

echo "<h2>Properties List</h2>";
if ($result && $result->num_rows > 0) {
    echo "<table border='1' cellpadding='8' cellspacing='0'>
            <tr>
                <th>ID</th>
                <th>Project Name</th>
                <th>Sub Heading</th>
                <th>Description</th>
                <th>Project Heading</th>
                <th>Project Details</th>
                <th>Starting Price</th>
                <th>Payment Plan</th>
                <th>Handover</th>
                <th>AED per Sqft</th>
                <th>Starting Area</th>
                <th>Location</th>
                <th>Extra Info</th>
                <th>Amenities</th>
                <th>Brochure</th>
                <th>Main Picture</th>
                <th>Image 2</th>
                <th>Image 3</th>
                <th>Image 4</th>
                <th>Floor Plan</th>
            </tr>";
    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row['id'] . "</td>
                <td>" . $row['project_name'] . "</td>
                <td>" . $row['sub_heading'] . "</td>
                <td>" . $row['description'] . "</td>
                <td>" . $row['project_heading'] . "</td>
                <td>" . $row['project_details'] . "</td>
                <td>" . $row['starting_price'] . "</td>
                <td>" . $row['payment_plan'] . "</td>
                <td>" . $row['handover'] . "</td>
                <td>" . $row['aed_per_sqft'] . "</td>
                <td>" . $row['starting_area'] . "</td>
                <td>" . $row['location'] . "</td>
                <td>" . $row['extra_text'] . "</td>
                <td>" . $row['amenities'] . "</td>
                <td>" . (!empty($row['brochure']) ? "<a href='uploads/" . $row['brochure'] . "' target='_blank'>View</a>" : "") . "</td>
                <td>" . (!empty($row['main_picture']) ? "<img src='uploads/" . $row['main_picture'] . "' width='80'>" : "") . "</td>
                <td>" . (!empty($row['image2']) ? "<img src='uploads/" . $row['image2'] . "' width='80'>" : "") . "</td>
                <td>" . (!empty($row['image3']) ? "<img src='uploads/" . $row['image3'] . "' width='80'>" : "") . "</td>
                <td>" . (!empty($row['image4']) ? "<img src='uploads/" . $row['image4'] . "' width='80'>" : "") . "</td>
                <td>" . (!empty($row['floor_plan']) ? "<a href='uploads/" . $row['floor_plan'] . "' target='_blank'>View</a>" : "") . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "<p>No properties found.</p>";
}

$conn->close();
?>