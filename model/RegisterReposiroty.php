<?php
// Làm việc với database
class RegisterReposiroty
{
    // lưu trữ lỗi nếu có
    public $error;
    // Lấy các dòng dữ liệu trong table và chuyển sang đối tượng
    // $cond mặc định null vì hỗ trợ cho trường hợp lấy hết các dòng dữ liệu
    function fetch($cond = null, $page = null, $item_per_page = null)
    {
        // bên trong hàm không nhìn thấy biến bên ngoài hàm và ngược lại
        // muốn bên trong hàm nhìn thấy biến bên ngoài hàm, thì phải dùng từ khóa global
        global $conn;
        $sql = "SELECT register.*, student.name AS student_name, subject.name AS subject_name FROM register
        JOIN student on student.id = register.student_id
        JOIN subject on subject.id = register.subject_id";;
        // if ($cond) {
        //     $sql .= "WHERE $cond";
        // }
        // $result = $conn->query($sql); chuyển sang trycatch
        try {
            if ($cond) {
                $sql .= " WHERE $cond ";
            }
            if ($page && $item_per_page) {
                $start_now = ($page - 1) * $item_per_page;
                $limit = "LIMIT $start_now, $item_per_page";
                $sql .= " $limit";
            }
            $result = $conn->query($sql);
            $registers = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['id'];
                    $student_id = $row['student_id'];
                    $subject_id = $row['subject_id'];
                    $score = $row['score'];
                    $student_name = $row['student_name'];
                    $subject_name = $row['subject_name'];
                    $register = new Register($id, $student_id, $subject_id, $score, $student_name, $subject_name);
                    // thêm 1 phần tử bên phải dấu = vào cuối danh sách
                    $registers[] = $register;
                }
            }
            return $registers;
        } catch (Exception $exception) {
            $_SESSION['error'] = "Lỗi: $sql " .  $exception->getMessage();
        }
    }
    // Lấy danh sách register dựa vào từ khóa tìm kiếm
    function getByPattern($search, $page = null, $item_per_page = null)
    {
        $cond = "student.name LIKE '%$search%' OR subject.name LIKE '%$search%'";
        $registers = $this->fetch($cond, $page, $item_per_page);
        return $registers;
    }
    function getAll($page = null, $item_per_page = null)
    {
        return $this->fetch(null, $page, $item_per_page);
    }
    public function save($data)
    {
        global $conn;
        $student_id = $data['student_id'];
        $subject_id = $data['subject_id'];
        $sql = "INSERT INTO register (student_id, subject_id) VALUES('$student_id', '$subject_id')";
        if ($conn->query($sql)) {
            return true;
        }
        $this->error = $sql . '<br>' . $conn->error;
        return false;
    }
    public function find($id)
    {
        $cond = "  register.id=$id";
        $registers = $this->fetch($cond);
        // $register = $registers[0];
        $register = current($registers);
        return $register;
    }
    public function update($register)
    {
        global $conn;
        $id = $register->id;
        $score = $register->score;
        $sql = "UPDATE register SET score='$score' WHERE id=$id";
        if ($conn->query($sql)) {
            return true;
        } else {
            $this->error = $sql . '<br>' . $conn->error;
            return false;
        }
    }
    public function delete($id)
    {
        global $conn;
        $sql = "DELETE FROM register WHERE id=$id";
        if ($conn->query($sql)) {
            return true;
        } else {
            $this->error = $sql . '<br>' . $conn->error;
            return false;
        }
    }
    function getByStudentId($student_id)
    {
        $cond = "student_id =$student_id";
        $registers = $this->fetch($cond);
        return $registers;
    }

    function getBySubjectId($subject_id)
    {
        $cond = "subject_id =$subject_id";
        $registers = $this->fetch($cond);
        return $registers;
    }
}
