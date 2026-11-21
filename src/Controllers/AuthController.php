<?php
declare(strict_types=1);

namespace Controllers;

use Core\Pages;
use Core\Session;
use Models\UserModel;

/**
 * Autenticación: comprueba credenciales y abre/cierra la sesión.
 * NO es el controlador de UserModel (para eso está UserController).
 */
final class AuthController
{
    // Etapa 5: leed los flash 'error' e 'info' con Session::getFlash()
    // y pasadlos a la vista (la vista no accede a Session).
    public function showLogin(): void
    {
        (new Pages())->render('auth/login', [
            'error' => Session::getFlash('error'), 
            'info' => Session::getFlash('info')
        ]);
    }

    public function login(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $userEmailCompr = (new UserModel())->findByEmail($email);

        if ($userEmailCompr !== null &&
            password_verify($password, $userEmailCompr['hash']))
        {
            Session::regenerar();
            Session::set('user_id', $userEmailCompr['id']);
            redirect('perfil');
        }

        Session::setFlash('error','Correo o Contraseña Incorrectos');
        redirect('login');
    }

    public function logout(): void
    {
        // TODO (Etapa 2): Session::destruir() y redirect('login')
        Session::destruir();

        Session::reiniciar();

        Session::setFlash('info','Has cerrado la sesion correctamente');

        redirect('login');
    }
}
