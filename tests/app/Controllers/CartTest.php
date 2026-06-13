<?php

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;

class CartTest extends CIUnitTestCase
{
    use FeatureTestTrait;
    use \CodeIgniter\Test\DatabaseTestTrait;

    protected $migrate = true;
    protected $migrateOnce = false;
    protected $refresh = true;
    protected $namespace = 'App';

    public function testAddToCartSuccess(): void
    {
        $model = new \App\Models\ProductModel();
        $model->insert([
            'id' => 1,
            'name' => 'Dummy Pizza',
            'price' => 50000,
            'img' => 'dummy.jpg',
            'desc' => 'Dummy desc'
        ]);

        $sessionData = [
            'logged_in' => true,
            'cart' => []
        ];

        $result = $this->withSession($sessionData)
                       ->withHeaders(['Referer' => '/menu'])
                       ->post('/cart/add', ['product_id' => 1]);

        $result->assertRedirect();
        $this->assertCount(1, session()->get('cart'));
    }
}
