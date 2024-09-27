<?php
// Làm việc với database
class StudentReposiroty
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
        $sql = "SELECT * FROM student";
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
            $students = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['id'];
                    $name = $row['name'];
                    $birthday = $row['birthday'];
                    $gender = $row['gender'];
                    $student = new Student($id, $name, $birthday, $gender);
                    // thêm 1 phần tử bên phải dấu = vào cuối danh sách
                    $students[] = $student;
                }
                return $students;
            }
        } catch (Exception $exception) {
            $_SESSION['error'] = "Lỗi: $sql " .  $exception->getMessage();
        }
    }
    // Lấy danh sách student dựa vào từ khóa tìm kiếm
    function getByPattern($search, $page, $item_per_page)
    {
        $cond = "name LIKE '%$search%'";
        $students = $this->fetch($cond, $page, $item_per_page);
        return $students;
    }
    function getAll($page = null, $item_per_page = null)
    {
        return $this->fetch(null, $page, $item_per_page);
    }
    public function save($data)
    {
        global $conn;
        $name = $data['name'];
        $birthday = $data['birthday'];
        $gender = $data['gender'];
        $sql = "INSERT INTO student (name, birthday, gender) VALUES('$name', '$birthday','$gender')";
        if ($conn->query($sql)) {
            return true;
        }
        $this->error = $sql . '<br>' . $conn->error;
        return false;
    }
    public function find($id)
    {
        $cond = "id=$id";
        $students = $this->fetch($cond);
        // $student = $students[0];
        $student = current($students);
        return $student;
    }
    public function update($student)
    {
        global $conn;
        $id = $student->id;
        $name = $student->name;
        $birthday = $student->birthday;
        $gender = $student->gender;
        $sql = "UPDATE student SET name='$name', birthday='$birthday', gender='$gender' WHERE id=$id";
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
        $sql = "DELETE FROM student WHERE id=$id";
        if ($conn->query($sql)) {
            return true;
        } else {
            $this->error = $sql . '<br>' . $conn->error;
            return false;
        }
    }
}
