<?php

use Mockery;
use PHPUnit\Framework\TestCase;
use App\Http\Controllers\ProductController;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Http\Response;

class ProductControllerTest extends TestCase
{
    protected $productController;
    protected $mockProductModel;
    protected $mockResponse;

    public function setUp(): void
    {
        parent::setUp();

        // Creamos el mock de la clase Response
        $this->mockResponse = Mockery::mock('alias:Response');
        
        // Mock del modelo Product
        $this->mockProductModel = Mockery::mock(Product::class);
        
        // Mock del controlador ProductController
        $this->productController = new ProductController();
    }

    public function testIndex()
    {
        // Definimos el comportamiento de Product::all() en el modelo mockeado
        $products = [
            Mockery::mock(Product::class)->shouldReceive('getAttribute')->with('name')->andReturn('Product 1')->getMock(),
            Mockery::mock(Product::class)->shouldReceive('getAttribute')->with('name')->andReturn('Product 2')->getMock(),
        ];
        
        // Mockeamos la respuesta de Product::all()
        $this->mockProductModel->shouldReceive('all')->once()->andReturn($products);
        
        // Llamamos al método index del controlador
        $response = $this->productController->index();

        // Verificamos que la respuesta es un JSON con los productos
        $this->assertEquals(200, $response->status());
        $this->assertJson($response->content());
    }

    public function testShow()
    {
        // Creamos un mock de un producto
        $product = Mockery::mock(Product::class);
        $product->shouldReceive('getAttribute')->with('name')->andReturn('Product 1');
        $product->shouldReceive('getAttribute')->with('id')->andReturn(1);

        // Mockeamos el comportamiento de Product::findOrFail()
        Product::shouldReceive('findOrFail')->with(1)->once()->andReturn($product);

        // Llamamos al método show del controlador
        $response = $this->productController->show(1);

        // Verificamos que la respuesta es correcta
        $this->assertEquals(200, $response->status());
        $this->assertJson($response->content());
    }

    public function testStore()
    {
        // Creamos un mock para Request
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('input')->with('name')->andReturn('New Product');
        $request->shouldReceive('input')->with('price')->andReturn(100);

        // Creamos un mock para el nuevo producto
        $product = Mockery::mock(Product::class);
        $product->shouldReceive('save')->once()->andReturn(true);

        // Mockeamos el comportamiento de Product::create()
        Product::shouldReceive('create')->once()->with(['name' => 'New Product', 'price' => 100])->andReturn($product);

        // Llamamos al método store del controlador
        $response = $this->productController->store($request);

        // Verificamos que la respuesta es correcta
        $this->assertEquals(201, $response->status());
        $this->assertJson($response->content());
    }

    public function testUpdate()
    {
        // Mockeamos el producto existente
        $product = Mockery::mock(Product::class);
        $product->shouldReceive('update')->once()->with(['name' => 'Updated Product', 'price' => 150])->andReturn(true);

        // Mockeamos el comportamiento de Product::findOrFail()
        Product::shouldReceive('findOrFail')->with(1)->once()->andReturn($product);

        // Creamos un mock para Request
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('input')->with('name')->andReturn('Updated Product');
        $request->shouldReceive('input')->with('price')->andReturn(150);

        // Llamamos al método update del controlador
        $response = $this->productController->update($request, 1);

        // Verificamos que la respuesta es correcta
        $this->assertEquals(200, $response->status());
        $this->assertJson($response->content());
    }

    public function testDestroy()
    {
        // Mockeamos el producto existente
        $product = Mockery::mock(Product::class);
        $product->shouldReceive('delete')->once()->andReturn(true);

        // Mockeamos el comportamiento de Product::findOrFail()
        Product::shouldReceive('findOrFail')->with(1)->once()->andReturn($product);

        // Llamamos al método destroy del controlador
        $response = $this->productController->destroy(1);

        // Verificamos que la respuesta es correcta
        $this->assertEquals(200, $response->status());
        $this->assertJson($response->content());
    }

    public function tearDown(): void
    {
        // Cerramos Mockery después de cada prueba
        Mockery::close();
        parent::tearDown();
    }
}