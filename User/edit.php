<?php
include("../database.php");
session_start();
if (isset($_POST["btn_edit"])) {
    $_SESSION['edit_id'] = $_POST["btn_edit"];
}
$sql = "SELECT `employee_id`,`first_name`, `last_name`, `phone_number`, `email_address`, `resume` FROM `tbl_employee` WHERE `employee_id` = '{$_SESSION['edit_id']}'";
$result = $conn->query($sql);
$data = $result->fetch_assoc();
// Update
$fname_valid = $lname_valid = $phone_valid = $mail_valid = $file_valid = true;
if (isset($_POST["btn_update"])) {
    $user_id = $_POST["btn_update"];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phoneNumber'];
    $mail = $_POST['mailAddress'];
    $resume = $_FILES['resumeFile'];
    if (empty($fname)) {
        $fname_valid = false;
        $fname_error = 'First name is required';
    } else {
        if (!preg_match('/^[A-Za-z]+$/', $fname)) {
            $fname_valid = false;
            $fname_error = 'Alphabets only';
        }
    }
    if (empty($lname)) {
        $lname_valid = false;
        $lname_error = 'Last name is required';
    } else {
        if (!preg_match('/^[A-Za-z]+$/', $lname)) {
            $lname_valid = false;
            $lname_error = 'Alphabets only';
        }
    }
    if (empty($phone)) {
        $phone_valid = false;
        $phone_error = 'Phone number is required';
    } else {
        if (!preg_match('/^(?!(\d)\1{9})(?!0123456789|1234567890|0987654321)\d{10}$/', $phone)) {
            $phone_valid = false;
            $phone_error = 'Invalid phone number';
        }
    }
    if (empty($mail)) {
        $mail_valid = false;
        $mail_error = 'Email address is required';
    } else {
        if (!preg_match('/^[0-9a-zA-Z-_\$#]+@[0-9a-zA-Z-_\$#]+\.[a-zA-Z]{2,5}/', $mail)) {
            $mail_valid = false;
            $mail_error = 'Invalid Email address';
        }
    }
    if ($resume['error'] == 4 || ($resume['size'] == 0 && $resume['error'] == 0)) {
        $file_valid = false;
        $file_error = 'Resume is required';
    } else {
        $valid_extensions = array('application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        if (!in_array($resume['type'], $valid_extensions)) {
            $file_valid = false;
            $file_error = 'The file isn\'t in pdf or doc format';
        }
        if ($resume['size'] > 5242880) {
            $file_valid = false;
            $file_error = "The file size is larger than 5MB";
        }
    }
    if ($fname_valid && $lname_valid && $phone_valid && $mail_valid && $file_valid) {
        $upload_folder = "uploads/resume/";
        $file_name = $resume['name'];
        move_uploaded_file($resume['tmp_name'], $upload_folder . $file_name);
        $sql = "UPDATE `tbl_employee` SET `first_name`='$fname',`last_name`='$lname',`phone_number`='$phone',`email_address`='$mail',`resume`='$file_name' WHERE `employee_id` = '$user_id'";
        if ($conn->query($sql)) {
            header("Location: ./index.php?msg='Profile updated successfully'");
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>Nintriva</title>
</head>

<body>
    <h1 class="text-center text-decoration-underline">Register</h1>
    <div class="container-fluid w-50 shadow my-4 p-4 bg-body-tertiary rounded">
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <div class="row">
                    <div class="col">
                        <label for="fname" class="form-label">First Name</label>
                        <input type="text" class="form-control <?php echo $fname_valid ? '' : 'is-invalid'; ?>" name="fname" id="fname" aria-describedby="helpId" placeholder="Enter your first name here" value="<?= $data['first_name']; ?>">
                        <?php if (!$fname_valid) { ?>
                            <div class="invalid-feedback">
                                <?= $fname_error; ?>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                    <div class="col">
                        <label for="lname" class="form-label">Last Name</label>
                        <input type="text" class="form-control <?php echo $lname_valid ? '' : 'is-invalid'; ?>" name="lname" id="lname" aria-describedby="helpId" placeholder="Enter your last name here" value="<?= $data['last_name']; ?>">
                        <?php if (!$lname_valid) { ?>
                            <div class="invalid-feedback">
                                <?= $lname_error; ?>
                            </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="phoneNumber" class="form-label">Phone number</label>
                <input type="tel" class="form-control <?php echo $phone_valid ? '' : 'is-invalid'; ?>" name="phoneNumber" id="phoneNumber" placeholder="Enter your phone number here" value="<?= $data['phone_number']; ?>">
                <?php if (!$phone_valid) { ?>
                    <div class="invalid-feedback">
                        <?= $phone_error; ?>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="mb-3">
                <label for="mailAddress" class="form-label">Email address</label>
                <input type="email" class="form-control <?php echo $mail_valid ? '' : 'is-invalid'; ?>" name="mailAddress" id="mailAddress" placeholder="Enter your mail address here" value="<?= $data['email_address']; ?>">
                <?php if (!$mail_valid) { ?>
                    <div class="invalid-feedback">
                        <?= $mail_error; ?>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="mb-3">
                <label for="resumeFile" class="form-label">Resume</label>
                <input class="form-control <?php echo $file_valid ? '' : 'is-invalid'; ?>" type="file" name="resumeFile" id="resumeFile" value="<?= $data['resume']; ?>">
                <?php if (!$file_valid) { ?>
                    <div class="invalid-feedback">
                        <?= $file_error; ?>
                    </div>
                <?php
                }
                ?>
                <div class="mt-2 text-secondary small">
                    <ul>
                        <li>The file should be in PDF or Word format.</li>
                        <li>The size of the file must be less than 5MB.</li>
                    </ul>
                </div>
            </div>
            <div class="d-grid gap-2">
                <button class="btn btn-primary" type="submit" name="btn_update" value="<?= $data['employee_id']; ?>">Update profile</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>