<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');
class AccountController
{
    private $accountModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
    }

    // Trang đăng ký tài khoản
    public function register()
    {
        include_once 'app/views/account/register.php';
    }

    // Trang đăng nhập
    public function login()
    {
        include_once 'app/views/account/login.php';
    }

    // Xử lý đăng ký tài khoản
    public function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Lấy thông tin từ form
            $username = $_POST['username'] ?? '';
            $fullName = $_POST['fullname'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            $role = $_POST['role'] ?? 'user';
            $email = $_POST['email'] ?? null;
            $phone = $_POST['phone'] ?? null;
            $avatar = null;
            $errors = [];

            // Xử lý upload avatar nếu có
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
                $avatar = $this->uploadAvatar($_FILES['avatar']);
                if (is_string($avatar) && strpos($avatar, 'Error:') === 0) {
                    $errors['avatar'] = $avatar;
                    $avatar = null;
                }
            }

            // Kiểm tra các trường
            if (empty($username))
                $errors['username'] = "Vui lòng nhập username!";
            if (empty($fullName))
                $errors['fullname'] = "Vui lòng nhập fullname!";
            if (empty($password))
                $errors['password'] = "Vui lòng nhập password!";
            if ($password != $confirmPassword)
                $errors['confirmPass'] = "Mật khẩu và xác nhận chưa khớp!";
            if (!in_array($role, ['admin', 'user']))
                $role = 'user';
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL))
                $errors['email'] = "Email không hợp lệ!";
            if (!empty($phone) && !preg_match("/^[0-9]{10,11}$/", $phone))
                $errors['phone'] = "Số điện thoại không hợp lệ!";

            // Kiểm tra tài khoản đã tồn tại chưa
            if ($this->accountModel->getAccountByUsername($username)) {
                $errors['account'] = "Tài khoản này đã được đăng ký!";
            }

            // Nếu có lỗi, quay lại trang đăng ký
            if (count($errors) > 0) {
                include_once 'app/views/account/register.php';
            } else {
                // Lưu tài khoản mới
                $result = $this->accountModel->save($username, $fullName, $password, $role, $email, $phone, $avatar);
                if ($result) {
                    header('Location: /hoangduyminh/account/login');
                    exit;
                }
            }
        }
    }

    // Đăng xuất
    public function logout()
    {
        session_start();
        unset($_SESSION['username']);
        unset($_SESSION['role']);
        header('Location: /hoangduyminh/product');
        exit;
    }

    // Kiểm tra đăng nhập
    public function checkLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';
            $account = $this->accountModel->getAccountByUsername($username);

            if ($account && password_verify($password, $account->password)) {
                session_start();
                if (!isset($_SESSION['username'])) {
                    $_SESSION['username'] = $account->username;
                    $_SESSION['role'] = $account->role;
                    $_SESSION['user_id'] = $account->id;
                    $_SESSION['avatar'] = $account->avatar;
                }
                header('Location: /hoangduyminh/product');
                exit;
            } else {
                $error = $account ? "Mật khẩu không đúng!" : "Không tìm thấy tài khoản!";
                include_once 'app/views/account/login.php';
                exit;
            }
        }
    }

    // Danh sách tài khoản (admin)
    public function list()
    {
        // Kiểm tra quyền admin
        if ($_SESSION['role'] !== 'admin') {
            header('Location: /hoangduyminh/product');
            exit;
        }

        $accounts = $this->accountModel->getAllAccounts();
        include_once 'app/views/account/list.php'; // Hiển thị danh sách tài khoản
    }

    // Xóa tài khoản (admin)
    public function delete($id)
    {
        if ($_SESSION['role'] !== 'admin') {
            header('Location: /hoangduyminh/product');
            exit;
        }

        $this->accountModel->delete($id);
        header('Location: /hoangduyminh/account/list');
        exit;
    }

    // Chỉnh sửa tài khoản (admin)
    public function edit($id)
    {
        if ($_SESSION['role'] !== 'admin') {
            header('Location: /hoangduyminh/product');
            exit;
        }

        $account = $this->accountModel->getAccountById($id);
        include_once 'app/views/account/edit.php'; // Hiển thị form chỉnh sửa
    }

    // Cập nhật tài khoản
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $fullName = $_POST['fullname'] ?? '';
            $role = $_POST['role'] ?? 'user';
            $email = $_POST['email'] ?? null;
            $phone = $_POST['phone'] ?? null;
            $avatar = null;
            $currentAccount = $this->accountModel->getAccountById($id);

            // Xử lý upload avatar mới nếu có
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
                $avatar = $this->uploadAvatar($_FILES['avatar']);
                if (is_string($avatar) && strpos($avatar, 'Error:') === 0) {
                    // Nếu có lỗi upload, giữ lại avatar cũ
                    $avatar = null;
                    $error = substr($avatar, 7); // Bỏ phần "Error: " ở đầu
                }
            }

            // Cập nhật thông tin tài khoản
            $this->accountModel->update($id, $username, $fullName, $role, $email, $phone, $avatar);

            // Nếu đang cập nhật tài khoản đang đăng nhập, cập nhật luôn session
            if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $id) {
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;
                if ($avatar) {
                    $_SESSION['avatar'] = $avatar;
                }
            }

            header('Location: /hoangduyminh/account/list');
            exit;
        }
    }

    // Profile người dùng
    public function profile()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /hoangduyminh/account/login');
            exit;
        }

        $account = $this->accountModel->getAccountById($_SESSION['user_id']);
        include_once 'app/views/account/profile.php';
    }

    // Cập nhật profile người dùng
    public function updateProfile()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /hoangduyminh/account/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_SESSION['user_id'];
            $fullName = $_POST['fullname'] ?? '';
            $email = $_POST['email'] ?? null;
            $phone = $_POST['phone'] ?? null;
            $avatar = null;
            $currentAccount = $this->accountModel->getAccountById($id);

            // Xử lý upload avatar mới nếu có
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
                $avatar = $this->uploadAvatar($_FILES['avatar']);
                if (is_string($avatar) && strpos($avatar, 'Error:') === 0) {
                    // Nếu có lỗi upload, giữ lại avatar cũ
                    $avatar = null;
                    $error = substr($avatar, 7); // Bỏ phần "Error: " ở đầu
                }
            }

            // Cập nhật thông tin tài khoản (giữ nguyên username và role)
            $this->accountModel->update($id, $currentAccount->username, $fullName, $currentAccount->role, $email, $phone, $avatar);

            // Cập nhật session
            if ($avatar) {
                $_SESSION['avatar'] = $avatar;
            }

            header('Location: /hoangduyminh/account/profile');
            exit;
        }
    }

    // Cập nhật mật khẩu
    public function updatePassword()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /hoangduyminh/account/login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_SESSION['user_id'];
            $currentPassword = $_POST['current_password'] ?? '';
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $errors = [];

            $account = $this->accountModel->getAccountById($id);

            // Kiểm tra mật khẩu hiện tại
            if (!password_verify($currentPassword, $account->password)) {
                $errors['current_password'] = "Mật khẩu hiện tại không đúng!";
            }

            // Kiểm tra mật khẩu mới
            if (empty($newPassword)) {
                $errors['new_password'] = "Vui lòng nhập mật khẩu mới!";
            }

            // Kiểm tra xác nhận mật khẩu
            if ($newPassword != $confirmPassword) {
                $errors['confirm_password'] = "Mật khẩu mới và xác nhận không khớp!";
            }

            if (count($errors) > 0) {
                // Nếu có lỗi, hiển thị lại form với thông báo lỗi
                include_once 'app/views/account/changePassword.php';
            } else {
                // Cập nhật mật khẩu
                $this->accountModel->updatePassword($id, $newPassword);
                $success = "Mật khẩu đã được cập nhật thành công!";
                include_once 'app/views/account/changePassword.php';
            }
        } else {
            include_once 'app/views/account/changePassword.php';
        }
    }

    // Xử lý upload avatar
    private function uploadAvatar($file)
    {
        $targetDir = "public/uploads/avatars/";

        // Tạo thư mục nếu chưa tồn tại
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }

        // Giới hạn kích thước file (2MB)
        if ($file["size"] > 2 * 1024 * 1024) {
            return "Error: Kích thước file quá lớn. Tối đa 2MB.";
        }

        // Chỉ cho phép một số định dạng hình ảnh
        $imageFileType = strtolower(pathinfo($file["name"], PATHINFO_EXTENSION));
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            return "Error: Chỉ chấp nhận file JPG, JPEG, PNG & GIF.";
        }

        // Tạo tên file duy nhất
        $uniqueName = uniqid() . "_" . basename($file["name"]);
        $targetFile = $targetDir . $uniqueName;

        // Upload file
        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            return $targetFile;
        } else {
            return "Error: Có lỗi xảy ra khi upload file.";
        }
    }
}
?>