<?php
include("../database.php");
session_start();
if (!isset($_SESSION["user_id"], $_SESSION['user_mail'])) {
    header('Location: ../login.php');
}
$sql = "SELECT * FROM `tbl_employee` WHERE `employee_id` = '{$_SESSION['user_id']}'";
$result = $conn->query($sql);
$data = $result->fetch_assoc();

// Logout
if (isset($_POST['btn_logout'])) {
    if (session_destroy()) {
        header('Location: ../login.php');
    }
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>
    <nav class="navbar bg-body-secondary">
        <div class="container-fluid">
            <a class="navbar-brand"><?= $data['first_name'] . ' ' . $data['last_name']; ?></a>
            <form action="" method="POST" class="d-flex">
                <button class="btn btn-outline-danger" name="btn_logout" type="submit">Log out</button>
            </form>
        </div>
    </nav>
    <div class="container-fluid w-50 shadow my-4 p-4 bg-body-tertiary rounded">
        <?php
        if (isset($_GET['msg'])) {
        ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?= $_GET['msg']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php
        }
        ?>
        <form action="./edit.php" method="POST" class="d-flex flex-row-reverse mb-4">
            <button class="btn btn-outline-success px-4" name="btn_edit" type="submit" value="<?= $data['employee_id']; ?>">Edit</button>
        </form>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="row mb-4">
                <div class="col">
                    <div class="input-group">
                        <span class="input-group-text" id="inputGroup-sizing-default">First name</span>
                        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" readonly value="<?= $data['first_name']; ?>">
                    </div>
                </div>
                <div class="col">
                    <div class="input-group">
                        <span class="input-group-text" id="inputGroup-sizing-default">Last name</span>
                        <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" readonly value="<?= $data['last_name']; ?>">
                    </div>
                </div>
            </div>
            <div class="input-group mb-4">
                <span class="input-group-text" id="inputGroup-sizing-default">Phone number</span>
                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" readonly value="<?= $data['phone_number']; ?>">
            </div>
            <div class="input-group mb-4">
                <span class="input-group-text" id="inputGroup-sizing-default">Email address</span>
                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" readonly value="<?= $data['email_address']; ?>">
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text" id="inputGroup-sizing-default">Resume</span>
                <input type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-default" readonly value="<?= $data['resume']; ?>">
            </div>
        </form>
        <div class="row">
            <div class="col">
                <form action="change_passwd.php" method="POST">
                    <div class="d-grid gap-2 mb-4">
                        <button class="btn btn-outline-secondary btn-sm" name="change_passwd" value="<?= $data['employee_id']; ?>" type="submit">Change password</button>
                    </div>
                </form>
            </div>
            <div class="col">
                <form action="delete_account.php" method="POST">
                    <div class="d-grid gap-2 mb-4">
                        <button class="btn btn-danger btn-sm" name="delete_account" value="<?= $data['employee_id']; ?>" type="submit">Delete my account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>