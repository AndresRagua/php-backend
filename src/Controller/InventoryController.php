<?php

namespace Controller;

use Model\Product;
use Model\InventoryMovement;
use Utils\Response;

class InventoryController
{
    // Registrar un movimiento de inventario (entrada o salida)
    public function recordMovement()
    {
        $data = json_decode(file_get_contents("php://input"));

        // Validación de datos
        if (empty($data->product_id) || empty($data->quantity) || empty($data->movement_type) || empty($data->user_id)) {
            Response::send(['status' => 'error', 'message' => 'Faltan datos obligatorios']);
            return;
        }

        // Verificar que el tipo de movimiento sea válido
        if (!in_array($data->movement_type, ['entry', 'exit'])) {
            Response::send(['status' => 'error', 'message' => 'Tipo de movimiento no válido']);
            return;
        }

        // Verificar que la cantidad no sea negativa o cero
        if ($data->quantity <= 0) {
            Response::send(['status' => 'error', 'message' => 'La cantidad debe ser mayor que cero']);
            return;
        }

        // Verificar que el producto exista
        $productModel = new Product();
        $product = $productModel->getProductById($data->product_id);

        if (!$product) {
            Response::send(['status' => 'error', 'message' => 'Producto no encontrado']);
            return;
        }

        // Registrar el movimiento
        $movementModel = new InventoryMovement();
        $result = $movementModel->recordMovement($data->product_id, $data->quantity, $data->movement_type, $data->user_id);

        if ($result) {
            Response::send(['status' => 'success', 'message' => 'Movimiento registrado exitosamente']);
        } else {
            Response::send(['status' => 'error', 'message' => 'Error al registrar el movimiento']);
        }
    }

    // Obtener los movimientos de inventario
    public function getInventoryMovements()
    {
        $movementModel = new InventoryMovement();
        $movements = $movementModel->getInventoryMovements();

        if ($movements) {
            Response::send(['status' => 'success', 'data' => $movements]);
        } else {
            Response::send(['status' => 'error', 'message' => 'No se encontraron movimientos']);
        }
    }


    // Obtener los productos más vendidos
    public function getTopSellingProducts()
    {
        $productModel = new Product();
        $topSelling = $productModel->getTopSellingProducts();

        if ($topSelling) {
            Response::send(['status' => 'success', 'data' => $topSelling]);
        } else {
            Response::send(['status' => 'error', 'message' => 'No se encontraron productos más vendidos']);
        }
    }

    // Obtener productos con stock bajo
    public function getLowStockProducts()
    {
        $productModel = new Product();
        $lowStock = $productModel->getLowStockProducts();

        if ($lowStock) {
            Response::send(['status' => 'success', 'data' => $lowStock]);
        } else {
            Response::send(['status' => 'error', 'message' => 'No se encontraron productos con stock bajo']);
        }
    }
}
