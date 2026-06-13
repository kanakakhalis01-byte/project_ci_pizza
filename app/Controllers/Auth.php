<?php namespace App\Controllers;
use App\Models\UserModel; 

class Auth extends BaseController {
    
    public function login() {
        if(session()->get('logged_in')) {
            return redirect()->to('/');
        }
        return view('auth/login');
    }

    public function processLogin() {
        $userModel = new UserModel();
        
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $userModel->where('username', $username)->first();

        if ($user) {
            if (password_verify($password, $user['password'])) {
                $sessionData = [
                    'user_id'   => $user['id'],
                    'username'  => $user['username'],
                    'logged_in' => true 
                ];
                session()->set($sessionData);
                
                return redirect()->to('/');
            } else {
                return redirect()->back()->with('error', 'Invalid password.');
            }
        } else {
            return redirect()->back()->with('error', 'No user found with that username.');
        }
    }

    public function logout() {
        session()->destroy();
        return redirect()->to('/login');
    }

    public function register() {
        if(session()->get('logged_in')) return redirect()->to('/');
        return view('auth/register');
    }

    public function processRegister() {
        $userModel = new \App\Models\UserModel();
        
        $password = $this->request->getPost('password');
        $confirm_password = $this->request->getPost('confirm_password');

        if($password !== $confirm_password) {
            return redirect()->back()->with('error', 'Password konfirmasi tidak cocok!');
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'password' => password_hash($password, PASSWORD_DEFAULT) 
        ];

        $userModel->save($data);
        return redirect()->to('/login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
}