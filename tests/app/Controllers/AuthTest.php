<?php

namespace App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class AuthTest extends CIUnitTestCase
{
    use FeatureTestTrait, DatabaseTestTrait;

    protected $migrate = true;
    protected $refresh = true;
    protected $namespace = 'App';

    public function testLoginSuccess()
    {
        $db = \Config\Database::connect();
        $db->table('users')->insert([
            'id'       => 1,
            'username' => 'admin',
            'password' => password_hash('admin123', PASSWORD_DEFAULT)
        ]);

        $result = $this->post('/login', [
            'username' => 'admin',
            'password' => 'admin123'
        ]);

        $result->assertRedirect();
        $this->assertTrue(session()->has('logged_in'));
    }

    public function testUnauthorizedAccessToAdminPanel()
    {
        $sessionData = [
            'logged_in' => true,
            'user_id'   => 2,
            'username'  => 'customer'
        ];

        $result = $this->withSession($sessionData)->get('/admin/dashboard');

        $result->assertStatus(302); 
    }

    public function testSqlInjectionAttemptOnLogin()
    {
        $result = $this->post('/login', [
            'username' => "' OR '1'='1", 
            'password' => 'admin123'
        ]);

        $this->assertFalse(session()->has('logged_in')); 
    }

    public function testCsrfValidationBlocksRequestWithoutToken()
    {
        $url = '/login';

        $data = [
            'username' => 'admin',
            'password' => 'admin123'
        ];

        $result = $this->post($url, $data);

        $result->assertStatus(403);
    }
}