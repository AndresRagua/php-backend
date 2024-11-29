<?php

use Mockery;
use PHPUnit\Framework\TestCase;
use App\Http\Controllers\CategoryController;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Http\Response;

class CategoryControllerTest extends TestCase
{
    protected $categoryController;
    protected $mockCategoryModel;
    protected $mockResponse;

    public function setUp(): void
    {
        parent::setUp();

        // Creamos el mock de la clase Response
        $this->mockResponse = Mockery::mock('alias:Response');
        
        // Mock del modelo Category
        $this->mockCategoryModel = Mockery::mock(Category::class);
        
        // Mock del controlador CategoryController
        $this->categoryController = new CategoryController();
    }

    public function testIndex()
    {
        // Definimos el comportamiento de Category::all() en el modelo mockeado
        $categories = [
            Mockery::mock(Category::class)->shouldReceive('getAttribute')->with('name')->andReturn('Category A')->getMock(),
            Mockery::mock(Category::class)->shouldReceive('getAttribute')->with('name')->andReturn('Category B')->getMock(),
        ];

        // Mockeamos la respuesta de Category::all()
        $this->mockCategoryModel->shouldReceive('all')->once()->andReturn($categories);
        
        // Llamamos al método index del controlador
        $response = $this->categoryController->index();

        // Verificamos que la respuesta es un JSON con las categorías
        $this->assertEquals(200, $response->status());
        $this->assertJson($response->content());
    }

    public function testShow()
    {
        // Creamos un mock de una categoría
        $category = Mockery::mock(Category::class);
        $category->shouldReceive('getAttribute')->with('name')->andReturn('Category A');
        $category->shouldReceive('getAttribute')->with('id')->andReturn(1);

        // Mockeamos el comportamiento de Category::findOrFail()
        Category::shouldReceive('findOrFail')->with(1)->once()->andReturn($category);

        // Llamamos al método show del controlador
        $response = $this->categoryController->show(1);

        // Verificamos que la respuesta es correcta
        $this->assertEquals(200, $response->status());
        $this->assertJson($response->content());
    }

    public function testStore()
    {
        // Creamos un mock para Request
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('input')->with('name')->andReturn('New Category');

        // Creamos un mock para la nueva categoría
        $category = Mockery::mock(Category::class);
        $category->shouldReceive('save')->once()->andReturn(true);

        // Mockeamos el comportamiento de Category::create()
        Category::shouldReceive('create')->once()->with(['name' => 'New Category'])->andReturn($category);

        // Llamamos al método store del controlador
        $response = $this->categoryController->store($request);

        // Verificamos que la respuesta es correcta
        $this->assertEquals(201, $response->status());
        $this->assertJson($response->content());
    }

    public function testUpdate()
    {
        // Mockeamos la categoría existente
        $category = Mockery::mock(Category::class);
        $category->shouldReceive('update')->once()->with(['name' => 'Updated Category'])->andReturn(true);

        // Mockeamos el comportamiento de Category::findOrFail()
        Category::shouldReceive('findOrFail')->with(1)->once()->andReturn($category);

        // Creamos un mock para Request
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('input')->with('name')->andReturn('Updated Category');

        // Llamamos al método update del controlador
        $response = $this->categoryController->update($request, 1);

        // Verificamos que la respuesta es correcta
        $this->assertEquals(200, $response->status());
        $this->assertJson($response->content());
    }

    public function testDestroy()
    {
        // Mockeamos la categoría existente
        $category = Mockery::mock(Category::class);
        $category->shouldReceive('delete')->once()->andReturn(true);

        // Mockeamos el comportamiento de Category::findOrFail()
        Category::shouldReceive('findOrFail')->with(1)->once()->andReturn($category);

        // Llamamos al método destroy del controlador
        $response = $this->categoryController->destroy(1);

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
