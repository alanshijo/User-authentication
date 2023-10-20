<?php
include("../database.php");
session_start();
if (isset($_POST["change_passwd"])) {
    $_SESSION['user_id'] = $_POST['change_passwd'];
}
$sql = "SELECT `password` FROM tbl_employee WHERE `employee_id` = '{$_SESSION['user_id']}'";
$result = $conn->query($sql);
$data = $result->fetch_assoc();

// Update password
$current_passwd_false = $passwd_valid = $confirm_passwd_valid = true;
if (isset($_POST["btn_update_passwd"])) {
    $current_passwd = $_POST["current_passwd"];
    $new_passwd = $_POST["new_passwd"];
    $confirm_passwd = $_POST["confirm_passwd"];
    if (empty($current_passwd)) {
        $current_passwd_false = false;
        $current_passwd_error = 'Current password is required';
    } else {
        if (!password_verify($current_passwd, $data['password'])) {
            $current_passwd_false = false;
            $current_passwd_error = 'The current password you entered is incorrect';
        }
    }
    if (empty($new_passwd)) {
        $passwd_valid = false;
        $passwd_error = 'Password is required';
    } else {
        if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $new_passwd)) {
            $passwd_valid = false;
            $passwd_error = 'Your password must be at least 8 characters long, contain at least one number and a special character and have a mixture of uppercase and lowercase letters.';
        }
    }
    if (empty($confirm_passwd)) {
        $confirm_passwd_valid = false;
        $confirm_passwd_error = 'Confirm password is required';
    } else {
        if ($new_passwd !== $confirm_passwd) {
            $confirm_passwd_valid = false;
            $confirm_passwd_error = 'Passwords aren\'t match';
        }
    }
    if ($current_passwd_false && $passwd_valid && $confirm_passwd_valid) {
        $hashed_passwd = password_hash($confirm_passwd, PASSWORD_DEFAULT);
        $sql = "UPDATE `tbl_employee` SET `password`='$hashed_passwd' WHERE `employee_id` = '{$_SESSION['user_id']}'";
        if ($conn->query($sql)) {
            header("Location: ./index.php?msg='Password updated successfully'");
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
    <h1 class="text-center text-decoration-underline">Change password</h1>
    <div class="container-fluid w-50 shadow my-4 p-4 bg-body-tertiary rounded">
        <form action="" method="post">
            <div class="mb-3">
                <label for="current_passwd" class="form-label">Current Password</label>
                <input type="password" name="current_passwd" id="current_passwd" class="form-control <?php echo $passwd_valid ? '' : 'is-invalid'; ?>" aria-describedby="passwordHelpBlock" placeholder="Enter your password here">
                <?php if (!$current_passwd_false) { ?>
                    <div class="invalid-feedback">
                        <?= $current_passwd_error; ?>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="mb-3">
                <label for="new_passwd" class="form-label">New Password</label>
                <input type="password" name="new_passwd" id="new_passwd" class="form-control <?php echo $passwd_valid ? '' : 'is-invalid'; ?>" aria-describedby="passwordHelpBlock" placeholder="Enter your password here">
                <?php if (!$passwd_valid) { ?>
                    <div class="invalid-feedback">
                        <?= $passwd_error; ?>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="mb-3">
                <label for="confirm_passwd" class="form-label">Confirm Password</label>
                <input type="password" name="confirm_passwd" id="confirm_passwd" class="form-control <?php echo $confirm_passwd_valid ? '' : 'is-invalid'; ?>" aria-describedby="passwordHelpBlock" placeholder="Enter your password again here">
                <?php if (!$confirm_passwd_valid) { ?>
                    <div class="invalid-feedback">
                        <?= $confirm_passwd_error; ?>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="d-grid gap-2">
                <button class="btn btn-primary" type="submit" name="btn_update_passwd">Update password</button>
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>