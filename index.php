<?php
session_start();
// router
// c là controller
// a là action (hàm trong controller đó)
// controller student là mặc định
// method index cũng là mặc định
$c = $_GET['c'] ?? 'student';
$a = $_GET['a'] ?? 'index';

// mục tiêu muốn call (gọi) hàm action $a của controller $c
// ucfirst() là viết hoa ký tự đầu tiên của chuỗi
$strController = ucfirst($c) . 'Controller';

// import controller vào hệ thống chạy
require ABSPATH_SITE . "controller/$strController.php";
// import model vào hệ thống chạy
require ABSPATH_SITE . "model/Student.php";
require ABSPATH_SITE . "model/StudentReposiroty.php";

require ABSPATH_SITE . "model/Subject.php";
require ABSPATH_SITE . "model/SubjectReposiroty.php";

require ABSPATH_SITE . "model/Register.php";
require ABSPATH_SITE . "model/RegisterReposiroty.php";

// import file cấu hình
require 'config.php';
require 'connectDB.php';

// khởi tạo đối tượng controller
$controller = new $strController();

// gọi hàm chạy
$controller->$a();