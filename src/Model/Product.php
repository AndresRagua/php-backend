<?php

namespace Model;

use PDO;
use Utils\Database;
use Exception;
use Model\Category; 

class Product
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function getTopSellingProducts()
    {
        $sql = "SELECT p.id, p.name, SUM(sp.quantity) as total_sales
                FROM products p
                JOIN sale_products sp ON p.id = sp.product_id
                GROUP BY p.id
                ORDER BY total_sales DESC
                LIMIT 5"; // Limitar a los 5 productos más vendidos
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para obtener productos con stock bajo
    public function getLowStockProducts($threshold = 5)
    {
        $sql = "SELECT id, name, stock FROM products WHERE stock <= :threshold";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':threshold', $threshold, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Método para verificar si un producto existe por su ID
    public function productExists($product_id)
    {
        $sql = "SELECT COUNT(*) FROM products WHERE id = :product_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
        $stmt->execute();

        // Si la consulta devuelve un valor mayor a 0, el producto existe
        return $stmt->fetchColumn() > 0;
    }

    public function getAllProducts()
    {
        try {
            // Consultamos todos los productos
            $sql = "SELECT * FROM products";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();

            // Si la consulta es exitosa, devolvemos todos los productos
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            // Si ocurre un error, devolvemos null o un array vacío
            return null;
        }
    }
    
    // Crear producto
    public function createProduct($name, $description, $price, $stock, $category_id)
    {
        try {
            $categoryModel = new Category();
            $category = $categoryModel->getCategoryById($category_id);

            if (!$category) {
                return ['status' => 'error', 'message' => 'Categoría no encontrada'];
            }

            $sql = "INSERT INTO products (name, description, price, stock, category_id) 
                    VALUES (:name, :description, :price, :stock, :category_id)";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':category_id', $category_id);

            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    // Obtener producto por ID
    public function getProductById($id)
    {
        try {
            $sql = "SELECT * FROM products WHERE id = :id LIMIT 1";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                return $stmt->fetch(\PDO::FETCH_ASSOC);
            } else {
                return null;
            }
        } catch (Exception $e) {
            return null;
        }
    }

    // Actualizar producto
    public function updateProduct($id, $name, $description, $price, $stock, $category_id)
    {
        try {
            $sql = "UPDATE products SET name = :name, description = :description, price = :price, stock = :stock, category_id = :category_id WHERE id = :id";
            $stmt = $this->conn->prepare($sql);

            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':category_id', $category_id);

            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }

    // Eliminar producto
    public function deleteProduct($id)
    {
        try {
            $sql = "DELETE FROM products WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
}
