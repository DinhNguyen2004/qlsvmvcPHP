<?php
class StudentController
{
    // không cần thuộc tính => không cần hàm __construct()
    // Hiển thị danh sách sinh viên
    public function index()
    {
        $item_per_page = 4;
        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? null;
        $studentRepository = new StudentReposiroty();
        if ($search) {
            $students = $studentRepository->getByPattern($search, $page, $item_per_page);
            $totalStudents = $studentRepository->getByPattern($search, $page, $item_per_page);
        } else {
            $students = $studentRepository->getAll($page, $item_per_page);
            $totalStudents = $studentRepository->getAll();
        }
        // ceil là làm tròn lên
        // ceil(1.2) => 2
        // ceil(1.8) => 2
        $totalPage = ceil(count($totalStudents) / $item_per_page); //later
        require 'view/student/index.php';
    }
    // Hiển thị form thêm sinh viên
    public function create()
    {
        require 'view/student/create.php';
    }
    // Lưu sinh viên vào database
    public function store()
    {
        $studentRepository = new StudentReposiroty();
        if ($studentRepository->save($_POST)) {
            $name = $_POST['name'];
            $_SESSION['success'] = "Đã thêm sinh viên $name thành công";
            header('location: /');
            exit;
        };
        $_SESSION['error'] = $studentRepository->error;
        header('location: /');
    }

    // Hiển thị trang chỉnh sửa sinh viên
    public function edit()
    {
        $id = $_GET['id'];
        $studentRepository = new StudentReposiroty();
        $student = $studentRepository->find($id);
        require 'view/student/edit.php';
    }

    // Cập nhật sinh viên xuống database
    public function update()
    {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $birthday = $_POST['birthday'];
        $gender = $_POST['gender'];

        // Lấy student từ database
        $studentRepository = new StudentReposiroty();
        $student = $studentRepository->find($id);

        // Cập nhật giá trị mới  vào đối tượng student
        $student->name = $name;
        $student->birthday = $birthday;
        $student->gender = $gender;

        // Update đối tượng xuống database
        if ($studentRepository->update($student)) {
            $_SESSION['success'] = "Đã cập nhật sinh viên $name thành công";
            header('location: /');
            exit;
        }
        $_SESSION['error'] = $studentRepository->error;
        header('location:/ ');
    }

    // Xóa sinh viên
    public function destroy()
    {
        $id = $_GET['id'];
        // Lấy student từ database
        $studentRepository = new StudentReposiroty();
        $student = $studentRepository->find($id);
        $name = $student->name;
        // Kiểm tra sinh viên đã đăng ký môn học chưa
        $registerRepository = new RegisterReposiroty();
        // Tìm những đăng ký môn học của 1 sinh viên cụ thể
        $registers = $registerRepository->getByStudentId($id);
        if (count($registers) > 0) {
            $num = count($registers);
            $_SESSION['error'] = "Sinh viên $name đã đăng ký $num môn học, không thể xóa";
            header('location: /');
            exit;
        } // Xóa student khỏi database
        if ($studentRepository->delete($id)) {
            $_SESSION['success'] = "Đã xóa sinh viên thành công";
            header('location: /');
            exit;
        }
        $_SESSION['error'] = $studentRepository->error;
        header('location:/ ');
    }
}
