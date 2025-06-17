<?php
class AccountModel
{
    private $conn;
    private $table_name = "account";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    // Lấy tài khoản theo username
    public function getAccountByUsername($username)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Lưu tài khoản mới
    public function save($username, $fullName, $password, $role = 'user', $email = null, $phone = null, $avatar = null)
    {
        if ($this->getAccountByUsername($username)) {
            return false;
        }
        $query = "INSERT INTO " . $this->table_name . " SET username=:username, fullname=:fullname, password=:password, role=:role, email=:email, phone=:phone, avatar=:avatar";
        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $username = htmlspecialchars(strip_tags($username));
        $fullName = htmlspecialchars(strip_tags($fullName));
        $password = password_hash($password, PASSWORD_BCRYPT);
        $role = htmlspecialchars(strip_tags($role));
        $email = $email ? htmlspecialchars(strip_tags($email)) : null;
        $phone = $phone ? htmlspecialchars(strip_tags($phone)) : null;

        // Bind các tham số
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":fullname", $fullName);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":role", $role);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":avatar", $avatar);

        return $stmt->execute();
    }

    // Lấy tất cả tài khoản
    public function getAllAccounts()
    {
        $query = "SELECT * FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    // Xóa tài khoản
    public function delete($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Lấy tài khoản theo ID
    public function getAccountById($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = :id LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    // Cập nhật tài khoản
    public function update($id, $username, $fullName, $role, $email = null, $phone = null, $avatar = null)
    {
        $query = "UPDATE " . $this->table_name . " SET username=:username, fullname=:fullname, role=:role, email=:email, phone=:phone";

        // Nếu có avatar mới thì cập nhật
        if ($avatar !== null) {
            $query .= ", avatar=:avatar";
        }

        $query .= " WHERE id=:id";

        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $username = htmlspecialchars(strip_tags($username));
        $fullName = htmlspecialchars(strip_tags($fullName));
        $role = htmlspecialchars(strip_tags($role));
        $email = $email ? htmlspecialchars(strip_tags($email)) : null;
        $phone = $phone ? htmlspecialchars(strip_tags($phone)) : null;

        // Bind các tham số
        $stmt->bindParam(":username", $username);
        $stmt->bindParam(":fullname", $fullName);
        $stmt->bindParam(":role", $role);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":phone", $phone);
        $stmt->bindParam(":id", $id);

        // Bind avatar nếu có
        if ($avatar !== null) {
            $stmt->bindParam(":avatar", $avatar);
        }

        return $stmt->execute();
    }

    // Cập nhật chỉ avatar
    public function updateAvatar($id, $avatar)
    {
        $query = "UPDATE " . $this->table_name . " SET avatar=:avatar WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":avatar", $avatar);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    // Cập nhật mật khẩu
    public function updatePassword($id, $newPassword)
    {
        $query = "UPDATE " . $this->table_name . " SET password=:password WHERE id=:id";
        $stmt = $this->conn->prepare($query);
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $stmt->bindParam(":password", $hashedPassword);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }
}
?>