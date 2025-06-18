<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');
require_once('app/utils/JWTHandler.php'); //
class ProductApiController
{
    private $productModel;
    private $db;
    private $jwtHandler; //
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
        $this->jwtHandler = new JWTHandler(); //
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
    // Lấy danh sách sản phẩm (không yêu cầu đăng nhập)
    public function index()
    {
        ob_clean();
        header('Content-Type: application/json');
        $products = $this->productModel->getProducts();
        echo json_encode($products);
        exit;
    }
    // Lấy thông tin sản phẩm theo ID (không yêu cầu đăng nhập)
    public function show($id)
    {
        ob_clean();
        header('Content-Type: application/json');
        $product = $this->productModel->getProductById($id);
        if ($product) {
            echo json_encode($product);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Product not found']);
        }
        exit;
    }
    // Thêm sản phẩm mới
    public function store()
    {
        if (!$this->authenticate()) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            return;
        }

        header('Content-Type: application/json');
        
        // Check if request contains file upload
        if (!empty($_FILES)) {
            // Handle form data with file upload
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            $SoLuong = $_POST['SoLuong'] ?? 1;
            
            $image = '';
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                try {
                    $image = $this->uploadImage($_FILES['image']);
                } catch (Exception $e) {
                    http_response_code(400);
                    echo json_encode(['message' => $e->getMessage()]);
                    return;
                }
            }
        } else {
            // Handle JSON data without file
            $data = json_decode(file_get_contents("php://input"), true);
            $name = $data['name'] ?? '';
            $description = $data['description'] ?? '';
            $price = $data['price'] ?? '';
            $category_id = $data['category_id'] ?? null;
            $SoLuong = $data['SoLuong'] ?? 1;
            $image = null;
        }
        
        $result = $this->productModel->addProduct(
            $name,
            $description,
            $price,
            $category_id,
            $image,
            $SoLuong
        );
        
        if (is_array($result)) {
            http_response_code(400);
            echo json_encode(['errors' => $result]);
        } else {
            http_response_code(201);
            echo json_encode(['message' => 'Product created successfully']);
        }
    }
    // Cập nhật sản phẩm theo ID
    public function update($id)
    {
        if (!$this->authenticate()) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            return;
        }

        header('Content-Type: application/json');
        
        // Check if request contains file upload
        if (!empty($_FILES)) {
            // Handle form data with file upload
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            $SoLuong = $_POST['SoLuong'] ?? 1;
            
            $image = $_POST['existing_image'] ?? null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                try {
                    $image = $this->uploadImage($_FILES['image']);
                } catch (Exception $e) {
                    http_response_code(400);
                    echo json_encode(['message' => $e->getMessage()]);
                    return;
                }
            }
        } else {
            // Handle JSON data without file
            $data = json_decode(file_get_contents("php://input"), true);
            $name = $data['name'] ?? '';
            $description = $data['description'] ?? '';
            $price = $data['price'] ?? '';
            $category_id = $data['category_id'] ?? null;
            $SoLuong = $data['SoLuong'] ?? 1;
            $image = $data['existing_image'] ?? null;
        }
        
        $result = $this->productModel->updateProduct(
            $id,
            $name,
            $description,
            $price,
            $category_id,
            $image,
            $SoLuong
        );
        
        if ($result) {
            echo json_encode(['message' => 'Product updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Product update failed']);
        }
    }
    // Xóa sản phẩm theo ID
    public function destroy($id)
    {
        if (!$this->authenticate()) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            return;
        }

        header('Content-Type: application/json');
        $result = $this->productModel->deleteProduct($id);
        if ($result) {
            echo json_encode(['message' => 'Product deleted successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Product deletion failed']);
        }
    }

    // Upload image method (copied from ProductController)
    private function uploadImage($file)
    {
        $target_dir = "uploads/";
        // Kiểm tra và tạo thư mục nếu chưa tồn tại
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        
        // Kiểm tra xem file có phải là hình ảnh không
        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            throw new Exception("File không phải là hình ảnh.");
        }
        
        // Kiểm tra kích thước file (10 MB = 10 * 1024 * 1024 bytes)
        if ($file["size"] > 10 * 1024 * 1024) {
            throw new Exception("Hình ảnh có kích thước quá lớn.");
        }
        
        // Chỉ cho phép một số định dạng hình ảnh nhất định
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            throw new Exception("Chỉ cho phép các định dạng JPG, JPEG, PNG và GIF.");
        }
        
        // Tạo tên file unique để tránh trùng lặp
        $unique_filename = uniqid() . '_' . basename($file["name"]);
        $target_file = $target_dir . $unique_filename;
        
        // Lưu file
        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
        }
        
        return $target_file;
    }

    // API endpoint riêng cho upload image
    public function uploadImageOnly()
    {
        if (!$this->authenticate()) {
            http_response_code(401);
            echo json_encode(['message' => 'Unauthorized']);
            return;
        }

        header('Content-Type: application/json');
        
        if (!isset($_FILES['image']) || $_FILES['image']['error'] != 0) {
            http_response_code(400);
            echo json_encode(['message' => 'No image file provided']);
            return;
        }

        try {
            $imagePath = $this->uploadImage($_FILES['image']);
            echo json_encode([
                'message' => 'Image uploaded successfully',
                'image_path' => $imagePath
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['message' => $e->getMessage()]);
        }
    }
}
?>