<?php
include("./database.php");
$fname_valid = $lname_valid = $phone_valid = $mail_valid = $passwd_valid = $confirm_passwd_valid = $file_valid = $checkbox_valid = true;
if (isset($_POST["btn_register"])) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $phone = $_POST['phoneNumber'];
    $mail = $_POST['mailAddress'];
    $passwd = $_POST['passwd'];
    $confirm_passwd = $_POST['confirmPasswd'];
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
    if (empty($passwd)) {
        $passwd_valid = false;
        $passwd_error = 'Password is required';
    } else {
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $passwd)) {
            $passwd_valid = false;
            $passwd_error = 'Your password must be at least 8 characters long, contain at least one number and a special character and have a mixture of uppercase and lowercase letters.';
        }
    }
    if (empty($confirm_passwd)) {
        $confirm_passwd_valid = false;
        $confirm_passwd_error = 'Confirm password is required';
    } else {
        if ($passwd !== $confirm_passwd) {
            $confirm_passwd_valid = false;
            $confirm_passwd_error = 'Passwords aren\'t match';
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
    if (!isset($_POST['agreeCheck'])) {
        $checkbox_valid = false;
        $checkbox_error = 'Please indicate that you have read and agree to the Terms and Conditions and Privacy Policy';
    }
    if ($fname_valid && $lname_valid && $phone_valid && $mail_valid && $passwd_valid && $confirm_passwd_valid && $file_valid && $checkbox_valid) {
        $hashed_passwd = password_hash($passwd, PASSWORD_DEFAULT);
        $upload_folder = "uploads/resume/";
        $file_name = $resume['name'];
        move_uploaded_file($resume['tmp_name'], $upload_folder . $file_name);
        $sql = "INSERT INTO `tbl_employee`(`first_name`, `last_name`, `phone_number`, `email_address`, `password`, `resume`) VALUES ('$fname','$lname','$phone','$mail','$hashed_passwd','$file_name')";
        if ($conn->query($sql)) {
            $success_msg = "Account created successfully";
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
        <?php
        if (isset($success_msg)) {
        ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $success_msg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php
        }
        ?>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <div class="row">
                    <div class="col">
                        <label for="fname" class="form-label">First Name</label>
                        <input type="text" class="form-control <?php echo $fname_valid ? '' : 'is-invalid'; ?>" name="fname" id="fname" aria-describedby="helpId" placeholder="Enter your first name here">
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
                        <input type="text" class="form-control <?php echo $lname_valid ? '' : 'is-invalid'; ?>" name="lname" id="lname" aria-describedby="helpId" placeholder="Enter your last name here">
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
                <input type="tel" class="form-control <?php echo $phone_valid ? '' : 'is-invalid'; ?>" name="phoneNumber" id="phoneNumber" placeholder="Enter your phone number here">
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
                <input type="email" class="form-control <?php echo $mail_valid ? '' : 'is-invalid'; ?>" name="mailAddress" id="mailAddress" placeholder="Enter your mail address here">
                <?php if (!$mail_valid) { ?>
                    <div class="invalid-feedback">
                        <?= $mail_error; ?>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="mb-3">
                <label for="passwd" class="form-label">Password</label>
                <input type="password" name="passwd" id="passwd" class="form-control <?php echo $passwd_valid ? '' : 'is-invalid'; ?>" aria-describedby="passwordHelpBlock" placeholder="Enter your password here">
                <?php if (!$passwd_valid) { ?>
                    <div class="invalid-feedback">
                        <?= $passwd_error; ?>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="mb-3">
                <label for="confirmPasswd" class="form-label">Confirm Password</label>
                <input type="password" name="confirmPasswd" id="confirmPasswd" class="form-control <?php echo $confirm_passwd_valid ? '' : 'is-invalid'; ?>" aria-describedby="passwordHelpBlock" placeholder="Enter your password again here">
                <?php if (!$confirm_passwd_valid) { ?>
                    <div class="invalid-feedback">
                        <?= $confirm_passwd_error; ?>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="mb-3">
                <label for="resumeFile" class="form-label">Resume</label>
                <input class="form-control <?php echo $file_valid ? '' : 'is-invalid'; ?>" type="file" name="resumeFile" id="resumeFile">
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
            <div class="form-check mb-3">
                <input class="form-check-input <?php echo $checkbox_valid ? '' : 'is-invalid'; ?>" type="checkbox" name="agreeCheck" id="agreeCheck">
                <label class="form-check-label" for="agreeCheck">
                    I agree to the <a href="#">Terms and Conditions and Privacy Policy</a>
                </label>
                <?php if (!$checkbox_valid) { ?>
                    <div class="invalid-feedback">
                        <?= $checkbox_error; ?>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="d-grid gap-2">
                <button class="btn btn-primary" type="submit" name="btn_register">Create account</button>
            </div>
        </form>
        <div class="mt-3 text-center">
            <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="./login.php">
                Already have an account? Log in
            </a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>