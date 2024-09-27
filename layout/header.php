<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Quản lý sinh viên</title>
    <link rel="stylesheet" href="public/vendor/bootstrap-4.5.3-dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/vendor/fontawesome-free-5.15.1-web/css/all.min.css">
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="shortcut icon" href="public/favicon.ico" type="image/x-icon">
</head>
<?php
global $c;
?>

<body>
    <div class="container" style="margin-top:20px;">
        <a href="/" class="<?= $c == 'student' ? 'active' : '' ?> btn btn-info">Students</a>
        <a href="/?c=subject" class="<?= $c == 'subject' ? 'active' : '' ?> btn btn-info">Subject</a>
        <a href="?c=register" class="<?= $c == 'register' ? 'active' : '' ?> btn btn-info">Register</a>
        <?php
        $message = '';
        $status_class = '';
        // !empty đọc là có
        // !empty() kiểm tra phần tử có tồn tại hay không và giá trị của phần tử đó có phải khác empty
        // các giá trị sau được xem là empty: 0, false, '', null
        if (!empty($_SESSION['success'])) {
            $message = $_SESSION['success'];
            // xóa phần tử có key là success 
            unset($_SESSION['success']);
            $status_class = 'success';
        } else if (!empty($_SESSION['error'])) {
            $message = $_SESSION['error'];
            // xóa phần tử có key là error 
            unset($_SESSION['error']);
            $status_class = 'danger';
        }

        ?>
        <!-- .alert.alert-success -->
        <?php if ($message) { ?>
            <div class="alert alert-<?= $status_class ?> mt-3"><?= $message ?></div>
        <?php } ?>