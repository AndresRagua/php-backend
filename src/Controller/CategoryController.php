<?php

namespace Controller;

use Model\Category;
use Utils\Response;

class CategoryController
{
    // Crear una categoría
    public function createCategory()
    {
        $data = json_decode(file_get_contents("php://input"));

        // Validar datos
        if (empty($data->name)) {
            Response::send(['status' => 'error', 'message' => 'El nombre de la categoría es obligatorio'], 400);
        }

        $categoryModel = new Category();
        $result = $categoryModel->createCategory($data->name);

        if ($result) {
            Response::send(['status' => 'success', 'message' => 'Categoría creada exitosamente']);
        } else {
            Response::send(['status' => 'error', 'message' => 'Error al crear la categoría'], 500);
        }
    }

    // Obtener todas las categorías
    public function getAllCategories()
    {
        $categoryModel = new Category();
        $categories = $categoryModel->getAllCategories();

        if ($categories) {
            Response::send(['status' => 'success', 'data' => $categories]);
        } else {
            Response::send(['status' => 'error', 'message' => 'No se encontraron categorías'], 404);
        }
    }

    // Obtener una categoría por ID
    public function getCategory($id)
    {
        $categoryModel = new Category();
        $category = $categoryModel->getCategoryById($id);

        if ($category) {
            Response::send(['status' => 'success', 'data' => $category]);
        } else {
            Response::send(['status' => 'error', 'message' => 'Categoría no encontrada'], 404);
        }
    }

    // Actualizar una categoría por ID
    public function updateCategory($id)
    {
        $data = json_decode(file_get_contents("php://input"));

        // Validar datos
        if (empty($data->name)) {
            Response::send(['status' => 'error', 'message' => 'El nombre de la categoría es obligatorio'], 400);
        }

        $categoryModel = new Category();
        $result = $categoryModel->updateCategory($id, $data->name);

        if ($result) {
            Response::send(['status' => 'success', 'message' => 'Categoría actualizada exitosamente']);
        } else {
            Response::send(['status' => 'error', 'message' => 'Error al actualizar la categoría'], 500);
        }
    }

    // Eliminar una categoría por ID
    public function deleteCategory($id)
    {
        $categoryModel = new Category();
        $result = $categoryModel->deleteCategory($id);

        if ($result) {
            Response::send(['status' => 'success', 'message' => 'Categoría eliminada exitosamente']);
        } else {
            Response::send(['status' => 'error', 'message' => 'Error al eliminar la categoría'], 500);
        }
    }
}
