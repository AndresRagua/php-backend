<?php

namespace Controller;

use Model\Product;
use Utils\Response;

class ProductController
{
    // Crear producto
    public function createProduct()
    {
        $data = json_decode(file_get_contents("php://input"));

        // Validación de datos
        if (empty($data->name) || empty($data->price) || empty($data->stock) || empty($data->category_id)) {
            Response::send(['status' => 'error', 'message' => 'Faltan datos obligatorios'], 400);
            return;
        }

        // Validar que price sea un número y stock un entero positivo
        if (!is_numeric($data->price) || $data->price < 0) {
            Response::send(['status' => 'error', 'message' => 'El precio debe ser un número válido y positivo'], 400);
            return;
        }

        if (!is_int($data->stock) || $data->stock < 0) {
            Response::send(['status' => 'error', 'message' => 'El stock debe ser un número entero no negativo'], 400);
            return;
        }

        // Crear el producto
        $productModel = new Product();
        $result = $productModel->createProduct($data->name, $data->description, $data->price, $data->stock, $data->category_id);

        if ($result) {
            Response::send(['status' => 'success', 'message' => 'Producto creado exitosamente']);
        } else {
            Response::send(['status' => 'error', 'message' => 'Error al crear el producto'], 500);
        }
    }

    // Leer un producto por ID
    public function getProduct($id)
    {
        $productModel = new Product();
        $product = $productModel->getProductById($id);

        if ($product) {
            Response::send(['status' => 'success', 'data' => $product]);
        } else {
            Response::send(['status' => 'error', 'message' => 'Producto no encontrado'], 404);
        }
    }

    // Obtener todos los productos
    public function getAllProducts()
    {
        $productModel = new Product();
        $products = $productModel->getAllProducts(); // Suponiendo que este método devuelve un array de productos

        if ($products) {
            Response::send(['status' => 'success', 'data' => $products]);
        } else {
            Response::send(['status' => 'error', 'message' => 'No se encontraron productos'], 404);
        }
    }

    // Actualizar un producto
    public function updateProduct($id)
    {
        $data = json_decode(file_get_contents("php://input"));

        // Validación de datos
        if (empty($data->name) || empty($data->price) || empty($data->stock) || empty($data->category_id)) {
            Response::send(['status' => 'error', 'message' => 'Faltan datos obligatorios'], 400);
            return;
        }

        // Validar que price sea un número y stock un entero positivo
        if (!is_numeric($data->price) || $data->price < 0) {
            Response::send(['status' => 'error', 'message' => 'El precio debe ser un número válido y positivo'], 400);
            return;
        }

        if (!is_int($data->stock) || $data->stock < 0) {
            Response::send(['status' => 'error', 'message' => 'El stock debe ser un número entero no negativo'], 400);
            return;
        }

        // Actualizar el producto
        $productModel = new Product();
        $result = $productModel->updateProduct($id, $data->name, $data->description, $data->price, $data->stock, $data->category_id);

        if ($result) {
            Response::send(['status' => 'success', 'message' => 'Producto actualizado exitosamente']);
        } else {
            Response::send(['status' => 'error', 'message' => 'Error al actualizar el producto'], 500);
        }
    }

    // Eliminar un producto
    public function deleteProduct($id)
    {
        $productModel = new Product();
        $result = $productModel->deleteProduct($id);

        if ($result) {
            Response::send(['status' => 'success', 'message' => 'Producto eliminado exitosamente']);
        } else {
            Response::send(['status' => 'error', 'message' => 'Error al eliminar el producto'], 500);
        }
    }
}
