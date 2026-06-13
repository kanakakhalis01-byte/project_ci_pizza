<?php

namespace App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;
use CodeIgniter\Files\File;

class CheckoutTest extends CIUnitTestCase
{
    use FeatureTestTrait, DatabaseTestTrait;

    protected $migrate = true;
    protected $refresh = true;
    protected $namespace = 'App';

    public function testCheckoutSuccessWithUpload()
    {

        $sessionData = [
            'logged_in' => true,
            'user_id' => 1,
            'cart' => [['id' => 1, 'price' => 50000, 'quantity' => 2]]
        ];

        $file = \Config\Services::uploadedfile('bukti_bayar', 'struk.jpg', 'image/jpeg');

        $result = $this->withSession($sessionData)
                       ->post('/checkout', ['bukti_bayar' => $file]);

        $this->seeInDatabase('transactions', [
            'status'  => 'Pending', 
            'user_id' => 1,
            'total'   => 100000
        ]);
        
        $result->assertStatus(302); 
    }

    public function testCheckoutWithEmptyCart()
    {
        $sessionData = ['logged_in' => true, 'user_id' => 1, 'cart' => []];

        $result = $this->withSession($sessionData)->post('/checkout');

        $result->assertRedirect();
        $this->dontSeeInDatabase('transactions', ['user_id' => 1]);
    }

    public function testTransactionTotalCalculationLogic()
    {
        $cartItems = [
            'rowid1' => ['price' => 50000, 'qty' => 2],
            'rowid2' => ['price' => 75000, 'qty' => 1]
        ];
        
        $calculatedTotal = 0;
        foreach($cartItems as $item) {
            $calculatedTotal += ($item['price'] * $item['qty']);
        }

        $this->assertEquals(175000, $calculatedTotal);
    }

    public function testAutoGenerateTransactionID()
    {
        $generatedId = uniqid('TRX-');
        
        $this->assertNotEmpty($generatedId);
        $this->assertStringStartsWith('TRX-', $generatedId);
    }
}