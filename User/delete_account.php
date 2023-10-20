<?php
include("../database.php");
session_start();
if (isset($_POST['delete_account'])) {
    $_SESSION['user_id'] = $_POST['delete_account'];
}
$sql = "SELECT `employee_id`,`password` FROM `tbl_employee` WHERE `employee_id` ='{$_SESSION['user_id']}'";
$result = $conn->query($sql);
$data = $result->fetch_assoc();
if (isset($_POST['cancel_action'])) {
    header('Location: ./index.php');
}
$passwd_valid = true;
if (isset($_POST['permanent_delete'])) {
    if (empty($_POST['passwd'])) {
        $passwd_valid = false;
        $passwd_error = 'Password is required';
    } else {
        if (!password_verify($_POST['passwd'], $data['password'])) {
            $passwd_valid = false;
            $passwd_error = 'Password is incorrect';
        }
    }
    if ($passwd_valid) {
        $sql = "DELETE FROM `tbl_employee` WHERE `employee_id` = '{$_SESSION['user_id']}'";
        if ($conn->query($sql)) {
            header("Location: ../index.php");
        }
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Delete account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <div class="container-fluid w-50 shadow my-4 p-4 bg-body-tertiary rounded">
        <form action="" method="post">
            <div class="p-3">
                <p class="fs-4 fw-bold text">Are you sure want to delete this account permanently?</p>
                <p class="text-muted">This action cannot be undone!</p>
            </div>
            <div class="mb-3">
                <input type="password" name="passwd" id="passwd" class="form-control <?php echo $passwd_valid ? '' : 'is-invalid'; ?>" aria-describedby="passwordHelpBlock" placeholder="Enter the account's password here to continue">
                <?php if (!$passwd_valid) { ?>
                    <div class="invalid-feedback">
                        <?= $passwd_error; ?>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="row">
                <div class="col">
                    <div class="d-grid gap-2 mb-4">
                        <button class="btn btn-danger btn-sm" value="<?= $data['employee_id']; ?>" name="permanent_delete" type="submit">Delete</button>
                    </div>
        </form>
    </div>
    <div class="col">
        <form action="" method="POST">
            <div class="d-grid gap-2 mb-4">
                <button class="btn btn-outline-success btn-sm" name="cancel_action" type="submit">Cancel</button>
            </div>
        </form>
    </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>