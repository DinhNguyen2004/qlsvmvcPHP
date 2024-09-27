<?php
// Khai báo class (Khuông bánh)
class Student
{
    // Khai báo phương thức
    public $id;
    public $name;
    public $birthday;
    public $gender;
    // Hàm xây dựng (hàm khởi tạo đối tượng)
    // Mục tiêu chủ yếu là truyền dữ liệu cho thuộc tính
    function __construct($id, $name, $birthday, $gender)
    {
        // $this là một biến đặc biệt, đang chứa đối tượng được sinh ra từ class Student, mặc định của 4 thuộc tính là null
        // muốn gọi thuộc tính/hàm là phải dùng dấu ->
        $this->id = $id;
        $this->name = $name;
        $this->birthday = $birthday;
        $this->gender = $gender;
    }
}