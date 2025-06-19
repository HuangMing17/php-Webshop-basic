<?php
class CartController
{
    public function __construct()
    {
        // Constructor không cần database connection vì sử dụng client-side cart
    }

    // Hiển thị trang giỏ hàng
    public function cart()
    {
        include 'app/views/Cart/Cart.php';
    }

    // Hiển thị trang checkout
    public function checkout()
    {
        include 'app/views/Cart/Checkout.php';
    }

    // Hiển thị trang xác nhận đơn hàng
    public function orderConfirmation()
    {
        include 'app/views/Cart/orderConfirmation.php';
    }

    // API endpoint để xử lý checkout (nếu muốn lưu vào database)
    public function processCheckout()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['message' => 'Method not allowed']);
            return;
        }

        // Get JSON data
        $input = file_get_contents("php://input");
        $data = json_decode($input, true);

        if (!$data) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid JSON data']);
            return;
        }

        // Validate required fields
        $requiredFields = ['customer_info', 'items'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                http_response_code(400);
                echo json_encode(['message' => "Missing required field: $field"]);
                return;
            }
        }

        $customerInfo = $data['customer_info'];
        $items = $data['items'];

        // Validate customer info
        $customerRequiredFields = ['fullname', 'phone', 'email', 'address'];
        foreach ($customerRequiredFields as $field) {
            if (empty($customerInfo[$field])) {
                http_response_code(400);
                echo json_encode(['message' => "Missing customer info: $field"]);
                return;
            }
        }

        // Validate items
        if (empty($items)) {
            http_response_code(400);
            echo json_encode(['message' => 'Cart is empty']);
            return;
        }

        try {
            // In a real application, you would:
            // 1. Save order to database
            // 2. Update product stock
            // 3. Send email confirmation
            // 4. Process payment if needed

            $orderId = 'ORD' . time() . rand(100, 999);
            
            // For now, just return success with order ID
            echo json_encode([
                'success' => true,
                'message' => 'Order processed successfully',
                'order_id' => $orderId
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'message' => 'Error processing order: ' . $e->getMessage()
            ]);
        }
    }

    // API endpoint để lấy thông tin đơn hàng
    public function getOrder($orderId)
    {
        header('Content-Type: application/json');
        
        // In a real application, you would fetch from database
        // For now, return mock data or from localStorage
        
        echo json_encode([
            'id' => $orderId,
            'status' => 'pending',
            'created_at' => date('c'),
            'message' => 'Order details would be fetched from database'
        ]);
    }
}
?>