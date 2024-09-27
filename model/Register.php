<?php
// Khai báo class (Khuông bánh)
class Register
{
    // Khai báo phương thức
    public $id;
    public $student_id;
    public $subject_id;
    public $score;
    public $student_name;
    public $subject_name;

    // Hàm xây dựng (hàm khởi tạo đối tượng)
    // Mục tiêu chủ yếu là truyền dữ liệu cho thuộc tính
    function __construct($id, $student_id, $subject_id, $score, $student_name, $subject_name)
    {
        // $this là một biến đặc biệt, đang chứa đối tượng được sinh ra từ class Register, mặc định của 4 thuộc tính là null
        // muốn gọi thuộc tính/hàm là phải dùng dấu ->
        $this->id = $id;
        $this->student_id = $student_id;
        $this->subject_id = $subject_id;
        $this->score = $score;
        $this->student_name = $student_name;
        $this->subject_name = $subject_name;
    }
}