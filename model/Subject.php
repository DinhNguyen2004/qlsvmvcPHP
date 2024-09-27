<?php
// Khai báo class (Khuông bánh)
class Subject
{
    // Khai báo phương thức
    public $id;
    public $name;
    public $number_of_credit;
    // Hàm xây dựng (hàm khởi tạo đối tượng)
    // Mục tiêu chủ yếu là truyền dữ liệu cho thuộc tính
    function __construct($id, $name, $number_of_credit)
    {
        // $this là một biến đặc biệt, đang chứa đối tượng được sinh ra từ class Subject, mặc định của 4 thuộc tính là null
        // muốn gọi thuộc tính/hàm là phải dùng dấu ->
        $this->id = $id;
        $this->name = $name;
        $this->number_of_credit = $number_of_credit;
    }
}
