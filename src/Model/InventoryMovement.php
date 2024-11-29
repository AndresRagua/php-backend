<?php

namespace Model;

use PDO;
use Utils\Database;

class InventoryMovement
{
    private $conn;

    public function __construct()
    {
        $database = new Database();
        $this->conn = $database->getConnection();
    }

    public function recordMovement($product_id, $quantity, $movement_type, $user_id)
    {
        $this->conn->beginTransaction();

        try {
            $sql = "INSERT INTO inventory_movements (product_id, quantity, movement_type, user_id) 
                    VALUES (:product_id, :quantity, :movement_type, :user_id)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
            $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
            $stmt->bindParam(':movement_type', $movement_type, PDO::PARAM_STR);
            $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();


            $updateStockSql = "";
            if ($movement_type === 'entry') {
                $updateStockSql = "UPDATE products SET stock = stock + :quantity WHERE id = :product_id";
            } elseif ($movement_type === 'exit') {
                $updateStockSql = "UPDATE products SET stock = stock - :quantity WHERE id = :product_id";
            }

            if (!empty($updateStockSql)) {
                $stmt = $this->conn->prepare($updateStockSql);
                $stmt->bindParam(':quantity', $quantity, PDO::PARAM_INT);
                $stmt->bindParam(':product_id', $product_id, PDO::PARAM_INT);
                $stmt->execute();
            }

            $this->conn->commit();

            return true;
        } catch (\Exception $e) {
            $this->conn->rollBack();
            return false;
        }
    }

    // Obtener todos los movimientos de inventario
    public function getInventoryMovements()
    {
        $sql = "SELECT * FROM inventory_movements";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return null;
    }
}
