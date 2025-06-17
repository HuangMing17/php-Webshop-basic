<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');

class CategoryController
{
    private $categoryModel;
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }
    public function index()
    {
        $category = $this->categoryModel->getcategory();
        include 'app/views/category/list.php';
    }

    // READ - List all categories
    public function list()
    {
        $category = $this->categoryModel->getcategory();
        require_once 'app/views/category/list.php';
    }

    // add - Show add form and handle creation
    public function add()
    {
        // Check if form was submitted
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->save();
        } else {
            // Just display the add form
            include 'app/views/category/add.php';
        }

    }
    // SAVE - Handle form submission for adding a new category
    public function save()
    {
        // Get the form data
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';

        // Validate name is not empty
        if (empty($name)) {
            $error = "Tên danh mục không được để trống";
            include 'app/views/category/add.php';
            return;
        }

        // Try to create the category
        if ($this->categoryModel->addcategory($name, $description)) {
            header('Location: /hoangduyminh/Category/list');
            exit;
        } else {
            $error = "Có lỗi xảy ra khi thêm danh mục";
            include 'app/views/category/add.php';
        }
    }


    // READ - Show single category
    public function show($id)
    {
        $category = $this->categoryModel->getCategoryById($id);
        if ($category) {
            include 'app/views/category/show.php';
        } else {
            header('Location: /hoangduyminh/Category/list');
            exit;
        }
    }

    // UPDATE - Show edit form and handle updates
    public function edit($id)
    {
        $category = $this->categoryModel->getCategoryById($id);

        if (!$category) {
            header('Location: /hoangduyminh/Category/list');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            if (empty($name)) {
                $error = "Tên danh mục không được để trống";
                include 'app/views/category/edit.php';
                return;
            }

            if ($this->categoryModel->updateCategory($id, $name, $description)) {
                header('Location: /hoangduyminh/Category/show/' . $id);
                exit;
            } else {
                $error = "Có lỗi xảy ra khi cập nhật danh mục";
                include 'app/views/category/edit.php';
            }
        } else {
            include 'app/views/category/edit.php';
        }
    }

    // DELETE - Handle category deletion
    public function delete($id)
    {
        if ($this->categoryModel->deleteCategory($id)) {
            header('Location: /hoangduyminh/Category/list');
            exit;
        } else {
            $error = "Có lỗi xảy ra khi xóa danh mục";
            $category = $this->categoryModel->getcategory();
            include 'app/views/category/list.php';
        }
    }



}
?>