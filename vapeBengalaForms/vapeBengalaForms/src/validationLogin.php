<?php
session_name('Bengala');
session_start();
require_once('functions.php');
require_once('functions-login.php');
require_once('functions-validation.php');

if (is_user_logged_in()) {
    redirect_to('../index.php');
}
/** 
 * array de inputs y errores 
 */
$inputsLogin = [];
$errorsLogin = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    /**
     * Sanitiza y valida los inputs
     */
    [$inputsLogin, $errorsLogin] = filter($_POST, [
        'username' => 'string|alphanumeric|required',
        'password' => 'string|alphanumeric|required'
    ]);
    /**
     *  Si hay errores redirige al login
     */
    if ($errorsLogin) {
        redirect_with('../login.php', [
            'errorsLogin' => $errorsLogin,
            'inputsLogin' => $inputsLogin
        ]);
    }
    /**
     * Verifica si el inicio de sesión usando los datos proporcionados 
     */
    if (!login($inputsLogin['username'], $inputsLogin['password'])) {

        $errorsLogin['login'] = 'Usuario o contraseña incorrecto';

        redirect_with('../login.php', [
            'errorsLogin' => $errorsLogin,
            'inputsLogin' => $inputsLogin
        ]);
    }

    /**
     * Si el login es correcto te lleva al formulario
     */
    redirect_to('../index.php');
}
