<?php

require_once('functions.php');
/**
 * La funcion devuelve un valor booleano (bool) 
 * que indica si el usuario está logueado o no. 
 */
function is_user_logged_in(): bool
{
    return isset($_SESSION['username']);
}

/**
 * Devuelve el nombre del usuario si esta logueado, 
 * sino devuelve null 
 */
function current_user()
{
    if (is_user_logged_in()) {
        return $_SESSION['username'];
    }
    return null;
}
/**
 * Si el user no esta logueado re redirige al login
 * asi evitamos que entre al formulario
 */
function require_login(): void
{
    if (!is_user_logged_in()) {
        redirect_to('login.php');
    }
}
/**
 * Esta funcion te cierra la sesion del usuario y
 * te devuelve al login
 */
function logout(): void
{
    if (is_user_logged_in()) {
        unset($_SESSION['username'], $_SESSION['user_id']);
        session_destroy();
        redirect_to('../login.php');
    }
}
/**
 * Esta funcion lee un archivo CSV que contiene datos de usuarios 
 * y los devuelve en forma de array 
 */
function obtenerUserDatosCSV()
{
    $filename = '../data/datosUsers.csv';
    $data = [];

    $f = fopen($filename, 'r');

    if ($f === false) {
        die('No se ha podido abrir el archivo ' . $filename);
    }

    while (($row = fgetcsv($f)) !== false) {
        $data[] = $row;
    }

    fclose($f);

    return $data;
}
/**
 * Busca al usuario por su nombre 
 * y devuelve su fila
 * @param string username
 */
function find_user_by_username(string $username)
{
    $users = obtenerUserDatosCSV();
    foreach ($users as $userArray) {
        foreach ($userArray as $userData) {
            if ($userData == $username) {
                return $userArray;
            }
        }
    }
    return false;
}
/**
 *  Se encarga de verificar las credenciales de un usuario 
 * (nombre de usuario y contraseña) para permitir o denegar el acceso
 * @param string username
 * @param string password
 */
function login(string $username, string $password): bool
{
    $user = find_user_by_username($username);

    if ($user && password_verify($password, password_hash($user[1], PASSWORD_DEFAULT))) {

        session_regenerate_id();

        $_SESSION['username'] = $user[0];
        $_SESSION['user_id']  = $user['id'];

        return true;
    }

    return false;
}
