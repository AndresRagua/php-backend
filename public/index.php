<?php

require_once '../vendor/autoload.php';

use Controller\ProductController;
use Controller\CategoryController;
use Controller\InventoryController;
use Utils\Router;

// Instanciamos los controladores
$productController = new ProductController();
$categoryController = new CategoryController();
$inventoryController = new InventoryController();

// Crear el enrutador
$router = new Router();

// Habilitar CORS
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");


$router->addRoute('GET', '/gestion_inventario/public/index.php/api/product/(\d+)', function ($matches) use ($productController) {
    $productController->getProduct($matches[1]);
});

$router->addRoute('GET', '/gestion_inventario/public/index.php/api/products', function () use ($productController) {
    $productController->getAllProducts();
});

$router->addRoute('POST', '/gestion_inventario/public/index.php/api/product', function () use ($productController) {
    $productController->createProduct();
});

$router->addRoute('PUT', '/gestion_inventario/public/index.php/api/product/(\d+)', function ($matches) use ($productController) {
    $productController->updateProduct($matches[1]);
});

$router->addRoute('DELETE', '/gestion_inventario/public/index.php/api/product/(\d+)', function ($matches) use ($productController) {
    $productController->deleteProduct($matches[1]);
});

$router->addRoute('GET', '/gestion_inventario/public/index.php/api/category/(\d+)', function ($matches) use ($categoryController) {
    $categoryController->getCategory($matches[1]);
});

$router->addRoute('GET', '/gestion_inventario/public/index.php/api/categories', function () use ($categoryController) {
    $categoryController->getAllCategories();
});

$router->addRoute('POST', '/gestion_inventario/public/index.php/api/category', function () use ($categoryController) {
    $categoryController->createCategory();
});

$router->addRoute('PUT', '/gestion_inventario/public/index.php/api/category/(\d+)', function ($matches) use ($categoryController) {
    $categoryController->updateCategory($matches[1]);
});

$router->addRoute('DELETE', '/gestion_inventario/public/index.php/api/category/(\d+)', function ($matches) use ($categoryController) {
    $categoryController->deleteCategory($matches[1]);
});

$router->addRoute('POST', '/gestion_inventario/public/index.php/api/inventory-movement', function () use ($inventoryController) {
    $inventoryController->recordMovement();
});

$router->addRoute('GET', '/gestion_inventario/public/index.php/api/inventory-movements', function () use ($inventoryController) {
    $inventoryController->getInventoryMovements();
});

$router->addRoute('GET', '/gestion_inventario/public/index.php/api/products/top-selling', function () use ($inventoryController) {
    $inventoryController->getTopSellingProducts();
});

$router->addRoute('GET', '/gestion_inventario/public/index.php/api/products/low-stock', function () use ($inventoryController) {
    $inventoryController->getLowStockProducts();
});

// Despachar la solicitud
$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];
$router->dispatch($requestUri, $requestMethod);
