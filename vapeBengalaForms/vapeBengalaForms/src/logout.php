<?php
session_name('Bengala');
session_start();
require_once('functions-login.php');
/**
 * Llama la función logout, 
 * es decir que cierra la sesion
 */
logout();