<?php

use Mockery;
use PHPUnit\Framework\TestCase;
use App\Http\Controllers\InventoryController;
use Illuminate\Http\Request;
use App\Models\Inventory;
use Illuminate\Http\Response;

class InventoryControllerTest extends TestCase
{
    protected $inventoryController;
    protected $mockInventoryModel;
    protected $mockResponse;

    public function setUp(): void
    {
        parent::setUp();

        // Creamos el mock de la clase Response
        $this->mockResponse = Mockery::mock('alias:Response');
        
        // Mock del modelo Inventory
        $this->mockInventoryModel = Mockery::mock(Inventory::class);
        
        // Mock del controlador InventoryController
        $this->inventoryController = new InventoryController();
    }

    public function testIndex()
    {
        // Definimos el comportamiento de Inventory::all() en el modelo mockeado
        $inventories = [
            Mockery::mock(Inventory::class)->shouldReceive('getAttribute')->with('product_name')->andReturn('Product A')->getMock(),
            Mockery::mock(Inventory::class)->shouldReceive('getAttribute')->with('product_name')->andReturn('Product B')->getMock(),
        ];
        
        // Mockeamos la respuesta de Inventory::all()
        $this->mockInventoryModel->shouldReceive('all')->once()->andReturn($inventories);
        
        // Llamamos al método index del controlador
        $response = $this->inventoryController->index();

        // Verificamos que la respuesta es un JSON con los inventarios
        $this->assertEquals(200, $response->status());
        $this->assertJson($response->content());
    }

    public function testShow()
    {
        // Creamos un mock de un inventario
        $inventory = Mockery::mock(Inventory::class);
        $inventory->shouldReceive('getAttribute')->with('product_name')->andReturn('Product A');
        $inventory->shouldReceive('getAttribute')->with('id')->andReturn(1);

        // Mockeamos el comportamiento de Inventory::findOrFail()
        Inventory::shouldReceive('findOrFail')->with(1)->once()->andReturn($inventory);

        // Llamamos al método show del controlador
        $response = $this->inventoryController->show(1);

        // Verificamos que la respuesta es correcta
        $this->assertEquals(200, $response->status());
        $this->assertJson($response->content());
    }

    public function testStore()
    {
        // Creamos un mock para Request
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('input')->with('product_name')->andReturn('Product X');
        $request->shouldReceive('input')->with('quantity')->andReturn(50);

        // Creamos un mock para el nuevo inventario
        $inventory = Mockery::mock(Inventory::class);
        $inventory->shouldReceive('save')->once()->andReturn(true);

        // Mockeamos el comportamiento de Inventory::create()
        Inventory::shouldReceive('create')->once()->with(['product_name' => 'Product X', 'quantity' => 50])->andReturn($inventory);

        // Llamamos al método store del controlador
        $response = $this->inventoryController->store($request);

        // Verificamos que la respuesta es correcta
        $this->assertEquals(201, $response->status());
        $this->assertJson($response->content());
    }

    public function testUpdate()
    {
        // Mockeamos el inventario existente
        $inventory = Mockery::mock(Inventory::class);
        $inventory->shouldReceive('update')->once()->with(['product_name' => 'Updated Product', 'quantity' => 100])->andReturn(true);

        // Mockeamos el comportamiento de Inventory::findOrFail()
        Inventory::shouldReceive('findOrFail')->with(1)->once()->andReturn($inventory);

        // Creamos un mock para Request
        $request = Mockery::mock(Request::class);
        $request->shouldReceive('input')->with('product_name')->andReturn('Updated Product');
        $request->shouldReceive('input')->with('quantity')->andReturn(100);

        // Llamamos al método update del controlador
        $response = $this->inventoryController->update($request, 1);

        // Verificamos que la respuesta es correcta
        $this->assertEquals(200, $response->status());
        $this->assertJson($response->content());
    }

    public function testDestroy()
    {
        // Mockeamos el inventario existente
        $inventory = Mockery::mock(Inventory::class);
        $inventory->shouldReceive('delete')->once()->andReturn(true);

        // Mockeamos el comportamiento de Inventory::findOrFail()
        Inventory::shouldReceive('findOrFail')->with(1)->once()->andReturn($inventory);

        // Llamamos al método destroy del controlador
        $response = $this->inventoryController->destroy(1);

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