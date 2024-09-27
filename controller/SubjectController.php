<?php
class SubjectController
{
    // không cần thuộc tính => không cần hàm __construct()
    // Hiển thị danh sách môn học
    public function index()
    {
        $item_per_page = 4;
        $page = $_GET['page'] ?? 1;
        $search = $_GET['search'] ?? null;
        $subjectRepository = new SubjectReposiroty();
        if ($search) {
            $subjects = $subjectRepository->getByPattern($search, $page,  $item_per_page);
            $totalSubjects = $subjectRepository->getByPattern($search, $page,  $item_per_page);
        } else {
            $subjects = $subjectRepository->getAll($page, $item_per_page);
            $totalSubjects = $subjectRepository->getAll();
        }
        // ceil là làm tròn lên
        // ceil(1.2) => 2
        // ceil(1.8) => 2
        $totalPage = ceil(count($totalSubjects) / $item_per_page); //later
        require 'view/subject/index.php';
    }
    // Hiển thị form thêm môn học
    public function create()
    {
        require 'view/subject/create.php';
    }
    // Lưu môn học vào database
    public function store()
    {
        $subjectRepository = new SubjectReposiroty();
        if ($subjectRepository->save($_POST)) {
            $name = $_POST['name'];
            $_SESSION['success'] = "Đã thêm môn học $name thành công";
            header('location: /?c=subject');
            exit;
        };
        $_SESSION['error'] = $subjectRepository->error;
        header('location: /?c=subject');
    }

    // Hiển thị trang chỉnh sửa môn học
    public function edit()
    {
        $id = $_GET['id'];
        $subjectRepository = new SubjectReposiroty();
        $subject = $subjectRepository->find($id);
        require 'view/subject/edit.php';
    }

    // Cập nhật môn học xuống database
    public function update()
    {
        $id = $_POST['id'];
        $name = $_POST['name'];
        $number_of_credit = $_POST['number_of_credit'];

        // Lấy subject từ database
        $subjectRepository = new SubjectReposiroty();
        $subject = $subjectRepository->find($id);

        // Cập nhật giá trị mới  vào đối tượng subject
        $subject->name = $name;
        $subject->number_of_credit = $number_of_credit;

        // Update đối tượng xuống database
        if ($subjectRepository->update($subject)) {
            $_SESSION['success'] = "Đã cập nhật môn học $name thành công";
            header('location: /?c=subject');
            exit;
        }
        $_SESSION['error'] = $subjectRepository->error;
        header('location:/?c=subject ');
    }

    // Xóa môn học
    public function destroy()
    {
        $id = $_GET['id'];
        // Lấy subject từ database
        $subjectRepository = new SubjectReposiroty();
        $subject = $subjectRepository->find($id);
        $name = $subject->name;

        // Kiểm tra môn học đã được sinh viên đăng ký chưa?
        $registerRepository = new RegisterReposiroty();
        // Tìm những đăng ký môn học của 1 sinh viên cụ thể
        $registers = $registerRepository->getBySubjectId($id);
        if (count($registers) > 0) {
            $num = count($registers);
            $_SESSION['error'] = "Môn học $name đã đăng ký $num sinh viên, không thể xóa";
            header('location: /?c=subject');
            exit;
        }
        if ($subjectRepository->delete($id)) {
            $_SESSION['success'] = "Đã xóa môn học thành công";
            header('location: /?c=subject');
            exit;
        }
        $_SESSION['error'] = $subjectRepository->error;
        header('location:/?c=subject ');
    }
}
