<?php
class CategoryModel
{
    private $conn;
    private $table_name = "category";
    public function __construct($db)
    {
        $this->conn = $db;
    }
    public function getcategory()
    {
        $query = "SELECT id, name, description FROM " . $this->table_name;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_OBJ);
        return $result;
    }
    public function getCategoryById($id)
    {
        $query = "SELECT id, name, description FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result;
    }

    //add category

    public function addCategory($name, $description)
    {
        $sql = "INSERT INTO category (name, description) VALUES (?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$name, $description]);
    }
    //edit category
    public function editCategory($data)
    {
        $query = "UPDATE " . $this->table_name . " SET name = ?, description = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([$data['name'], $data['description'], $data['id']]);
        return $result;
    }
    //delete category
    public function deleteCategory($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $result = $stmt->execute([$id]);
        return $result;
    }
}
?>