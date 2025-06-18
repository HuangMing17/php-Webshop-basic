<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');
require_once('app/utils/JWTHandler.php');

class CategoryApiController
{
    private $categoryModel;
    private $db;
    private $jwtHandler;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
        $this->jwtHandler = new JWTHandler();
    }

    private function authenticate()
    {
        $headers = apache_request_headers();
        if (isset($headers['Authorization'])) {
            $authHeader = $headers['Authorization'];
            $arr = explode(" ", $authHeader);
            $jwt = $arr[1] ?? null;
            if ($jwt) {
                $decoded = $this->jwtHandler->decode($jwt);
                return $decoded ? true : false;
            }
        }
        return false;
    }

    // Lấy danh sách danh mục (không yêu cầu đăng nhập)
    public function index()
    {
        header('Content-Type: application/json');
        $categories = $this->categoryModel->getcategory();
        echo json_encode($categories);
    }

    // Lấy thông tin danh mục theo ID (không yêu cầu đăng nhập)
    public function show($id)
    {
        header('Content-Type: application/json');
        $category = $this->categoryModel->getCategoryById($id);
        if ($category) {
            echo json_encode($category);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Category not found']);
        }
    }

    // Thêm danh mục mới
    public function store()
    {
        if (!$this->authenticate()) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            return;
        }

        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';

        if (empty($name)) {
            http_response_code(400);
            echo json_encode(['message' => 'Tên danh mục không được để trống']);
            return;
        }

        $result = $this->categoryModel->addCategory($name, $description);
        
        if ($result) {
            http_response_code(201);
            echo json_encode(['message' => 'Category created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Category creation failed']);
        }
    }

    // Cập nhật danh mục theo ID
    public function update($id)
    {
        if (!$this->authenticate()) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            return;
        }

        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';

        if (empty($name)) {
            http_response_code(400);
            echo json_encode(['message' => 'Tên danh mục không được để trống']);
            return;
        }

        $updateData = [
            'id' => $id,
            'name' => $name,
            'description' => $description
        ];

        $result = $this->categoryModel->editCategory($updateData);
        
        if ($result) {
            echo json_encode(['message' => 'Category updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Category update failed']);
        }
    }

    // Xóa danh mục theo ID
    public function destroy($id)
    {
        if (!$this->authenticate()) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            return;
        }

        header('Content-Type: application/json');
        $result = $this->categoryModel->deleteCategory($id);
        
        if ($result) {
            echo json_encode(['message' => 'Category deleted successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Category deletion failed']);
        }
    }
}
?>