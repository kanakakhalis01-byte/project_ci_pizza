<?php

namespace Tests\App\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;
use App\Models\ProductModel;
use CodeIgniter\Test\FeatureTestTrait;

class ProductModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait, FeatureTestTrait;

    protected $migrate = true;
    protected $migrateOnce = false;
    protected $refresh = true;
    protected $namespace = 'App';

    public function testSearchProductByName()
    {
        $model = new ProductModel();
        
        $model->insert([
            'name' => 'Meat Lover Pizza',
            'price' => 50000,
            'img' => 'meat.jpg',
            'desc' => 'Delicious meat pizza'
        ]);

        $results = $model->like('name', 'Meat')->findAll();

        $this->assertNotEmpty($results);
        
        $this->assertStringContainsString('Meat', $results[0]['name']);
    }

    public function testXssAttemptInProductName()
    {
        $sessionData = ['logged_in' => true, 'role' => 'admin'];
        
        $result = $this->withSession($sessionData)->post('/admin/products/create', [
            'name'        => "<script>alert('xss')</script>",
            'price'       => 50000,
            'description' => 'XSS Test'
        ]);

        $result->assertStatus(302); 
    }
}