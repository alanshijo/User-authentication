<?php
include("./database.php");
$mail_valid = $passwd_valid = true;
if (isset($_POST["btn_login"])) {
    $mail = $_POST['mailAddress'];
    $passwd = $_POST['passwd'];
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
    }
    if ($mail_valid && $passwd_valid) {
        $sql = "SELECT `employee_id`,`email_address`,`password` FROM `tbl_employee` WHERE `email_address` = '$mail'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            if (password_verify($passwd, $data["password"])) {
                session_start();
                $_SESSION['user_id'] = $data['employee_id'];
                $_SESSION['user_mail'] = $data['email_address'];
                header('Location: User/index.php');
            } else {
                $error_msg = 'Invalid credentials';
            }
        } else {
            $error_msg = 'User doesn\'t exist';
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
    <h1 class="text-center text-decoration-underline mt-4">Log In</h1>
    <div class="container-fluid w-50 shadow my-4 p-4 bg-body-tertiary rounded">
        <?php
        if (isset($error_msg)) {
        ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?= $error_msg; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php
        }
        ?>
        <form action="" method="post">
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
            <div class="d-grid gap-2">
                <button class="btn btn-primary" type="submit" name="btn_login">Sign in</button>
            </div>
        </form>
        <div class="mt-3 text-center">
            <a class="link-offset-2 link-offset-3-hover link-underline link-underline-opacity-0 link-underline-opacity-75-hover" href="./index.php">
                Don't have an account? Register
            </a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>