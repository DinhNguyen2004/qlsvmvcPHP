<?php
class RegisterController
{
    // không cần thuộc tính => không cần hàm __construct()
    // Hiển thị danh sách đăng ký môn học

    public function index()
    {
        $item_per_page = 4;
        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? null;
        $registerRepository = new RegisterReposiroty();
        if ($search) {
            $registers = $registerRepository->getByPattern($search, $page,  $item_per_page);
            $totalRegisters = $registerRepository->getByPattern($search, $page,  $item_per_page);
        } else {
            $registers = $registerRepository->getAll($page, $item_per_page);
            $totalRegisters = $registerRepository->getAll();
        }
        // ceil là làm tròn lên
        // ceil(1.2) => 2
        // ceil(1.8) => 2
        $totalPage = ceil(count($totalRegisters) / $item_per_page); //later
        require 'view/register/index.php';
    }
    // Hiển thị form thêm đăng ký môn học

    public function create()
    {
        $studentRepository = new StudentReposiroty();
        $students = $studentRepository->getAll();

        $subjectRepository = new SubjectReposiroty();
        $subjects = $subjectRepository->getAll();
        require 'view/register/create.php';
    }
    // Lưu đăng ký môn học vào database
    public function store()
    {
        $registerRepository = new RegisterReposiroty();
        if ($registerRepository->save($_POST)) {
            $student_id = $_POST['student_id'];
            $studentRepository = new StudentReposiroty();
            $student = $studentRepository->find($student_id);
            $student_name = $student->name;

            $subject_id = $_POST['subject_id'];
            $subjectRepository = new SubjectReposiroty();
            $subject = $subjectRepository->find($subject_id);
            $subject_name = $subject->name;
            $_SESSION['success'] = "Sinh viên $student_name đăng ký môn $subject_name thành công";
            header('location: /?c=register');
            exit;
        };
        $_SESSION['error'] = $registerRepository->error;
        header('location: /?c=register');
    }

    // Hiển thị trang chỉnh sửa đăng ký môn học

    public function edit()
    {
        $id = $_GET['id'];
        $registerRepository = new RegisterReposiroty();
        $register = $registerRepository->find($id);
        require 'view/register/edit.php';
    }

    // Cập nhật đăng ký môn học xuống database
    public function update()
    {
        $id = $_POST['id'];
        $score = $_POST['score'];

        // Lấy register từ database
        $registerRepository = new RegisterReposiroty();
        $register = $registerRepository->find($id);

        // Cập nhật giá trị mới  vào đối tượng register
        $register->score = $score;

        $student_name = $register->student_name;
        $subject_name = $register->subject_name;

        // Update đối tượng xuống database
        if ($registerRepository->update($register)) {
            $_SESSION['success'] = "Sinh viên $student_name thi môn $subject_name được $score điểm";
            header('location: /?c=register');
            exit;
        }
        $_SESSION['error'] = $registerRepository->error;
        header('location:/?c=register ');
    }

    // Xóa đăng ký môn học

    public function destroy()
    {
        $id = $_GET['id'];
        // Lấy register từ database
        $registerRepository = new RegisterReposiroty();
        $register = $registerRepository->find($id);

        if ($registerRepository->delete($id)) {
            $_SESSION['success'] = "Đã xóa đăng ký môn học
 thành công";
            header('location: /?c=register');
            exit;
        }
        $_SESSION['error'] = $registerRepository->error;
        header('location:/?c=register ');
    }
}