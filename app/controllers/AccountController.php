<?php
require_once('app/config/database.php');
require_once('app/models/AccountModel.php');
require_once('app/utils/JWTHandler.php');
class AccountController
{
    private $accountModel;
    private $db;
    private $jwtHandler;
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->accountModel = new AccountModel($this->db);
        $this->jwtHandler = new JWTHandler();
    }
    function register()
    {
        include_once 'app/views/account/register.php';
    }
    public function login()
    {
        include_once 'app/views/account/login.php';
    }
    function save()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $username = $_POST['username'] ?? '';
            $fullName = $_POST['fullname'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirmpassword'] ?? '';
            $errors = [];
            if (empty($username)) {
                $errors['username'] = "Vui long nhap userName!";
            }
            if (empty($fullName)) {
                $errors['fullname'] = "Vui long nhap fullName!";
            }
            if (empty($password)) {
                $errors['password'] = "Vui long nhap password!";
            }
            if ($password != $confirmPassword) {
                $errors['confirmPass'] = "Mat khau va xac nhan chua dung";
            }
            //kiểm tra username đã được đăng ký chưa?
            $account = $this->accountModel->getAccountByUsername($username);
            if ($account) {
                $errors['account'] = "Tai khoan nay da co nguoi dang ky!";
            }
            if (count($errors) > 0) {
                include_once 'app/views/account/register.php';            } else {
                // Password sẽ được hash trong AccountModel, không cần hash ở đây
                $role = $_POST['role'] ?? 'user'; // Lấy role từ form, mặc định là 'user'
                $email = $_POST['email'] ?? null;
                $phone = $_POST['phone'] ?? null;
                $result = $this->accountModel->save($username, $fullName, $password, $role, $email, $phone);
                if ($result) {
                    header('Location: /hoangduyminh/account/login');
                }
            }
        }
    }
    function logout()
    {
        unset($_SESSION['username']);
        unset($_SESSION['role']);
        header('Location: /hoangduyminh/product');
    }
    public function checkLogin()
    {
        // Clean any previous output
        ob_clean();
        header('Content-Type: application/json');
        
        $data = json_decode(file_get_contents("php://input"), true);
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        
        $user = $this->accountModel->getAccountByUsername($username);
        
        if ($user && password_verify($password, $user->password)) {
            $token = $this->jwtHandler->encode([
                'id' => $user->id,
                'username' => $user->username,
                'role' => $user->role ?? 'user'
            ]);
            
            echo json_encode([
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'fullname' => $user->fullname ?? '',
                    'role' => $user->role ?? 'user'
                ]
            ]);
        } else {
            http_response_code(401);
            echo json_encode(['message' => 'Invalid credentials']);
        }
        exit; // Ensure no additional output
    }
    public function list()
    {
        // Lấy danh sách tài khoản từ model
        $accounts = $this->accountModel->getAllAccounts();
        
        // Thiết lập hằng số cho avatar mặc định
        if (!defined('DEFAULT_AVATAR')) {
            define('DEFAULT_AVATAR', 'public/images/default-avatar.png');
        }
        
        // Thiết lập BASE_URL
        if (!defined('BASE_URL')) {
            define('BASE_URL', '/hoangduyminh/');
        }
        
        // Hiển thị view
        include_once 'app/views/account/list.php';
    }
    
    public function delete($id)
    {
        if (!$id) {
            header('Location: /hoangduyminh/account/list');
            return;
        }
        
        // Kiểm tra tài khoản tồn tại
        $account = $this->accountModel->getAccountById($id);
        if (!$account) {
            header('Location: /hoangduyminh/account/list');
            return;
        }
        
        // Không cho phép xóa tài khoản admin cuối cùng
        if ($account->role === 'admin') {
            $adminCount = $this->accountModel->countAdminAccounts();
            if ($adminCount <= 1) {
                $_SESSION['error'] = "Không thể xóa tài khoản admin cuối cùng!";
                header('Location: /hoangduyminh/account/list');
                return;
            }
        }
        
        // Thực hiện xóa
        $result = $this->accountModel->delete($id);
        if ($result) {
            $_SESSION['success'] = "Xóa tài khoản thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi xóa tài khoản!";
        }
        
        header('Location: /hoangduyminh/account/list');
    }
    
    public function edit($id)
    {
        if (!$id) {
            header('Location: /hoangduyminh/account/list');
            return;
        }
        
        // Lấy thông tin tài khoản từ ID
        $account = $this->accountModel->getAccountById($id);
        
        // Thiết lập hằng số cho avatar mặc định
        if (!defined('DEFAULT_AVATAR')) {
            define('DEFAULT_AVATAR', 'public/images/default-avatar.png');
        }
        
        // Thiết lập BASE_URL
        if (!defined('BASE_URL')) {
            define('BASE_URL', '/hoangduyminh/');
        }
        
        // Hiển thị view
        include_once 'app/views/account/edit.php';
    }
    
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST' || !$id) {
            header('Location: /hoangduyminh/account/list');
            return;
        }
        
        // Lấy dữ liệu từ form
        $username = $_POST['username'] ?? '';
        $fullName = $_POST['fullname'] ?? '';
        $role = $_POST['role'] ?? 'user';
        $email = $_POST['email'] ?? null;
        $phone = $_POST['phone'] ?? null;
        
        // Kiểm tra avatar mới
        $avatar = null;
        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $maxSize = 2 * 1024 * 1024; // 2MB
            
            if (!in_array($_FILES['avatar']['type'], $allowedTypes)) {
                $_SESSION['error'] = "Chỉ chấp nhận file hình ảnh (JPG, PNG, GIF)!";

                header('Location: /hoangduyminh/account/edit/' . $id);
                return;
            }
            
            if ($_FILES['avatar']['size'] > $maxSize) {
                $_SESSION['error'] = "Kích thước file quá lớn (tối đa 2MB)!";
                header('Location: /hoangduyminh/account/edit/' . $id);
                return;
            }
            
            $fileName = uniqid() . '_' . basename($_FILES['avatar']['name']);
            $uploadPath = 'public/uploads/avatars/' . $fileName;
            
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $uploadPath)) {
                $avatar = $uploadPath;
            }
        }
        
        // Cập nhật tài khoản
        $result = $this->accountModel->update($id, $username, $fullName, $role, $email, $phone, $avatar);
        
        if ($result) {
            $_SESSION['success'] = "Cập nhật tài khoản thành công!";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra khi cập nhật tài khoản!";
        }
        
        header('Location: /hoangduyminh/account/list');
    }
    
    // Phương thức để tạo tài khoản admin mới
    
        
        
    
}
