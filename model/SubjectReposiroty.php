<?php
// Làm việc với database
class SubjectReposiroty
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
        $sql = "SELECT * FROM subject";
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
            $subjects = [];
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $id = $row['id'];
                    $name = $row['name'];
                    $number_of_credit = $row['number_of_credit'];
                    $subject = new Subject($id, $name, $number_of_credit);
                    // thêm 1 phần tử bên phải dấu = vào cuối danh sách
                    $subjects[] = $subject;
                }
                return $subjects;
            }
        } catch (Exception $exception) {
            $_SESSION['error'] = "Lỗi: $sql " .  $exception->getMessage();
        }
    }
    // Lấy danh sách subject dựa vào từ khóa tìm kiếm
    function getByPattern($search, $page, $item_per_page)
    {
        $cond = "name LIKE '%$search%'";
        $subjects = $this->fetch($cond, $page, $item_per_page);
        return $subjects;
    }
    function getAll($page = null, $item_per_page = null)
    {
        return $this->fetch(null, $page, $item_per_page);
    }
    public function save($data)
    {
        global $conn;
        $name = $data['name'];
        $number_of_credit = $data['number_of_credit'];
        $sql = "INSERT INTO subject (name, number_of_credit) VALUES('$name', '$number_of_credit')";
        if ($conn->query($sql)) {
            return true;
        }
        $this->error = $sql . '<br>' . $conn->error;
        return false;
    }
    public function find($id)
    {
        $cond = "id=$id";
        $subjects = $this->fetch($cond);
        // $subject = $subjects[0];
        $subject = current($subjects);
        return $subject;
    }
    public function update($subject)
    {
        global $conn;
        $id = $subject->id;
        $name = $subject->name;
        $number_of_credit = $subject->number_of_credit;
        $sql = "UPDATE subject SET name='$name', number_of_credit='$number_of_credit' WHERE id=$id";
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
        $sql = "DELETE FROM subject WHERE id=$id";
        if ($conn->query($sql)) {
            return true;
        } else {
            $this->error = $sql . '<br>' . $conn->error;
            return false;
        }
    }
}
