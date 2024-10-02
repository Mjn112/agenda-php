<?php
// app/Controllers/UserController.php
namespace App\Controllers;

use App\Models\UserModel;

class UserController extends BaseController
{
    public function register()
    {
        return view('register'); // Exibe o formulário de cadastro
    }

    public function login()
    {
        return view('login'); // Exibe o formulário de login
    }

    public function storeUser()
    {
        $userModel = new UserModel();

        // Valida o formulário de cadastro
        $validation = $this->validate([
            'login' => 'required|min_length[3]',
            'senha' => 'required|min_length[6]',
        ]);

        if (!$validation) {
            return view('register', ['validation' => $this->validator]);
        }

        // Inserir o usuário no banco de dados
        $data = [
            'login' => $this->request->getPost('login'),
            'senha' => password_hash($this->request->getPost('senha'), PASSWORD_DEFAULT), // Hash da senha
        ];

        $userModel->save($data);

        return redirect()->to('/login'); // Redireciona para a página de login
    }

    public function authenticate()
    {
        $userModel = new UserModel();
        $login = $this->request->getPost('login');
        $senha = $this->request->getPost('senha');

        $user = $userModel->where('login', $login)->first();

        if ($user) {
            if (password_verify($senha, $user['senha'])) {
                // Inicia a sessão do usuário
                session()->set('user', $user['id']);
                return redirect()->to('/activities'); // Redireciona para o painel de atividades
            } else {
                return redirect()->back()->with('error', 'Senha incorreta.');
            }
        } else {
            return redirect()->back()->with('error', 'Usuário não encontrado.');
        }
    }

    public function logout()
    {
        session()->destroy(); // Destroi a sessão do usuário
        return redirect()->to('/login');
    }
}
