<?php
// Inicia la sesision
session_name('Bengala');
session_start();

// Requiere de los archivos necesarios
require_once('../data/datos.php');
require_once('functions.php');
require_once('errorMessages.php');

// Inicializa los arrays para almacenar los datos del formulario
$inputs = [];
$errors = [];

$inputs['total'] = 0;
$_SESSION['productos'] = [];

define('VALIDATION_ERRORS', VALIDATION_ERRORS_ES);

// Verifica si la solicitud es un POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validacion y saneamiento del campo "Nombre"
    if (isset($_POST['full-name'])) {
        //Sanea y almacena el nombre
        $full_name = htmlspecialchars($_POST['full-name'], ENT_QUOTES, 'UTF-8');
        if (!empty($full_name)) {
            if (strlen($full_name) > 1 && strlen($full_name) < 30) {
                if (preg_match("/^[\p{L}' ]+$/u", $full_name)) {
                    $inputs['full-name'] = trim($full_name);
                } else {
                    $errors['full-name'] = sprintf(VALIDATION_ERRORS['full-name'], 'nombre');
                }
            } else {
                $errors['full-name'] = 'Los apellidos debe contener entre 1 y 30 caracteres';
            }
        } else {
            $errors['full-name'] = sprintf(VALIDATION_ERRORS['required'], 'nombre');
        }
    }

    // Validacion y saneamiento del campo "Apellidos"
    if (isset($_POST['apellidos'])) {
        //Sanea y almacena los apellidos
        $apellidos = htmlspecialchars($_POST['apellidos'], ENT_QUOTES, 'UTF-8');
        if (!empty($apellidos)) {
            if (strlen($apellidos) > 1 && strlen($apellidos) < 30) {
                if (preg_match("/^[\p{L}' ]+$/u", $apellidos)) {
                    $inputs['apellidos'] = trim($apellidos);
                } else {
                    $errors['apellidos'] = sprintf(VALIDATION_ERRORS['full-name'], 'apellido');
                }
            } else {
                $errors['apellidos'] = 'Los apellidos debe contener entre 1 y 30 caracteres';
            }
        } else {
            $errors['apellidos'] = sprintf(VALIDATION_ERRORS['required'], 'apellidos');
        }
    }

    // Validacion y saneamiento del campo "Numero de telefono"
    if (isset($_POST['telefono'])) {
        // Sanea y valida el numero de telefono
        $telefono = filter_input(INPUT_POST, 'telefono', FILTER_SANITIZE_NUMBER_INT);

        // Verifica si el numero de telefono tiene el formato válido
        if (!empty($telefono)) {
            // El numero de telefono tiene 9 digitos, que es un formato comun
            if (preg_match('/^\d{9}$/', $telefono)) {
                $inputs['telefono'] = trim($telefono);
            } else {
                $errors['telefono'] = sprintf(VALIDATION_ERRORS['telefono'], 'numero');
            }
        } else {
            $errors['telefono'] = sprintf(VALIDATION_ERRORS['required'], 'numero');
        }
    }

    // Validacion y saneamiento del campo "Correo"
    if (isset($_POST['correo'])) {
        // Sanea y valida la direccion de correo
        $correo = filter_input(INPUT_POST, 'correo', FILTER_SANITIZE_EMAIL);

        if (!empty($correo)) {
            if (filter_var($correo, FILTER_VALIDATE_EMAIL)) {
                $inputs['correo'] = trim($correo);
            } else {
                $errors['correo'] = sprintf(VALIDATION_ERRORS['correo'], 'correo');
            }
        } else {
            $errors['correo'] = sprintf(VALIDATION_ERRORS['required'], 'correo');
        }
    }

    // Validacion y saneamiento del campo "Linea de direccion 1"
    if (isset($_POST['direccion'])) {
        // Si se indica el campo 'direccion' esta presenta en la solicitud 'POST'
        $direccion = filter_input(INPUT_POST, 'direccion', FILTER_SANITIZE_STRING); // Sanea el campo para evitar ataques de seguridad

        if (!empty($direccion)) {
            // Si la 'direccion' no esta vacia
            if (ctype_alnum($direccion)) {     
                $inputs['direccion'] = trim($direccion);
            } else {
                $errors['direccion'] = sprintf(VALIDATION_ERRORS['direccion'], 'direccion');
            }
        } else {
            $errors['direccion'] = sprintf(VALIDATION_ERRORS['required'], 'direccion');
        }
    }

    // Validacion y saneamiento del campo "Codigo postal"
    if (isset($_POST['codigo-postal'])) {
        // Sanea y valida el codigo postal
        $codigo_postal = filter_input(INPUT_POST, 'codigo-postal', FILTER_SANITIZE_NUMBER_INT); // Sanea el campo y elimina caracteres no numericos

        if (!empty($codigo_postal)) {
            // Si el campo 'codigo-postal' no es valido

            if (preg_match('/^[0-9]{5}$/', $codigo_postal)) {
                // La expresion regular verifica que el codigo postal contenga exactamente 5 digitos numericos.

                $inputs['codigo-postal'] = trim($codigo_postal); // Se almacena el codigo postal saneado y sin espacios en blanco.
            } else {
                // Si el codigo postal no cumple con el formato esperado...

                $errors['codigo-postal'] = sprintf(VALIDATION_ERRORS['codigo-postal'], 'codigo postal'); // Genera un mensaje de error indicando que el 'codigo postal' no es valido
            }
        } else {
            // Si el campo 'codigo-postal' esta vacio...
            $errors['codigo-postal'] = sprintf(VALIDATION_ERRORS['required'], 'codigo postal'); // Generamos un mensaje de error indicando que el 'codigo postal' es un campo requerido.
        }
    }

    // Validacion y saneamiento del select "Ciudad"
    $selected_ciudad = filter_input(
        INPUT_POST,
        'seleccionCiudad',
        FILTER_SANITIZE_STRING,
        FILTER_REQUIRE_ARRAY
    );

    $_SESSION['selected_ciudad'] = [];

    foreach ($selected_ciudad as $ciudad) {
        $_SESSION['selected_ciudad'][] = $ciudad;
    }

    // Validacion y saneamiento del select "Provincia"
    $selected_provincia = filter_input(
        INPUT_POST,
        'seleccionProvincia',
        FILTER_SANITIZE_STRING,
        FILTER_REQUIRE_ARRAY
    );

    $_SESSION['selected_provincia'] = [];

    foreach ($selected_provincia as $provincia) {
        $_SESSION['selected_provincia'][] = $provincia;
    }

    //------------------------------------------------------- VAIDAR CAMPOS METODO DE PAGO -------------------------------------------------------

    // Validacion y saneamiento del campo "Numero de tarjeta"
    if (isset($_POST['numeroTarjeta'])) {
        $numeroTarjeta = filter_input(INPUT_POST, 'numeroTarjeta', FILTER_SANITIZE_NUMBER_INT);

        if (!empty($numeroTarjeta)) {
            // Puedes realizar validaciones adicionales para el numero de tarjeta segun tus requisitos
            if (preg_match('/^\d{16}$/', $numeroTarjeta)) {
                if (filter_var($numeroTarjeta, FILTER_VALIDATE_INT)) {
                    $inputs['numeroTarjeta'] = trim($numeroTarjeta);
                } else {
                    $errors['numeroTarjeta'] = 'El numero de tarjeta no es válido';
                }
            } else {
                $errors['numeroTarjeta'] = sprintf(VALIDATION_ERRORS['numeroTarjeta'], 'numero de la tarjeta');
            }
        } else {
            $errors['numeroTarjeta'] = sprintf(VALIDATION_ERRORS['required'], 'numero de la tarjeta');
        }
    }

    // Validacion y saneamiento del campo "Nombre en la tarjeta"
    if (isset($_POST['nombreTarjeta'])) {
        $nombreTarjeta = htmlspecialchars($_POST['nombreTarjeta'], ENT_QUOTES, 'UTF-8');
        if (!empty($nombreTarjeta)) {
            if (preg_match("/^[\p{L}' ]+$/u", $nombreTarjeta)) {
                $inputs['nombreTarjeta'] = trim($nombreTarjeta);
            } else {
                $errors['nombreTarjeta'] = sprintf(VALIDATION_ERRORS['nombreTarjeta'], 'nombre de la tarjeta');
            }
        } else {
            $errors['nombreTarjeta'] = sprintf(VALIDATION_ERRORS['required'], 'nombre de la tarjeta');
        }
    }

    // Validacion y saneamiento del campo "mes Vencimiento"
    $selected_mes = filter_input(
        INPUT_POST,
        'mesVencimiento',
        FILTER_SANITIZE_STRING,
        FILTER_REQUIRE_ARRAY
    );

    $_SESSION['selected_mes'] = [];

    foreach ($selected_mes as $mes) {
        $_SESSION['selected_mes'][] = $mes;
    }

    // Validacion y saneamiento del campo "anyo Vencimiento"
    $selected_anyo = filter_input(
        INPUT_POST,
        'anyoVencimiento',
        FILTER_SANITIZE_STRING,
        FILTER_REQUIRE_ARRAY
    );

    $_SESSION['selected_anyo'] = [];

    foreach ($selected_anyo as $anyo) {
        $_SESSION['selected_anyo'][] = $anyo;
    }


    // Validacion y saneamiento del campo "Codigo de Seguridad de la Tarjeta"
    if (isset($_POST['codigoSeguridadTarjeta'])) {
        $codigoSeguridadTarjeta = filter_input(INPUT_POST, 'codigoSeguridadTarjeta', FILTER_SANITIZE_NUMBER_INT);

        if (!empty($codigoSeguridadTarjeta)) {
            if (preg_match('/^\d{3}$/', $codigoSeguridadTarjeta)) {
                if (filter_var($codigoSeguridadTarjeta, FILTER_VALIDATE_INT)) {
                    $inputs['codigoSeguridadTarjeta'] = trim($codigoSeguridadTarjeta);
                } else {
                    $errors['codigoSeguridadTarjeta'] = sprintf(VALIDATION_ERRORS['codigoSeguridadTarjeta'], 'CVV');
                }
            } else {
                $errors['codigoSeguridadTarjeta'] = sprintf(VALIDATION_ERRORS['codigoSeguridadTarjeta'], 'CVV');
            }
        } else {
            $errors['codigoSeguridadTarjeta'] = sprintf(VALIDATION_ERRORS['required'], 'CVV');
        }
    }

    //------------------------------------------------------- VALIDAR OFERTAS DEL DIA ------------------------------
    // sanitize the inputs
    $selected_vapers = filter_input(
        INPUT_POST,
        'vapers',
        FILTER_SANITIZE_STRING,
        FILTER_REQUIRE_ARRAY
    ) ?? [];

    // select the topping names
    $vaperNombres = array_keys($vapers);

    $_SESSION['selected_vapers'] = []; // for storing selected toppings
    $total = 0; // for storing total

    // check data against the original values
    if ($selected_vapers) {

        foreach ($selected_vapers as $vaper) {
            $posicion = array_search($vaper, array_keys($vapers)) + 1;

            $cantidadOferta = filter_input(INPUT_POST, "cantidadOferta$posicion", FILTER_VALIDATE_INT);
            $inputs["cantidadOferta$posicion"] = $cantidadOferta;
            if (in_array($vaper, $vaperNombres)) {
                $_SESSION['selected_vapers'][] = $vaper;
                $total += $vapers[$vaper] * $cantidadOferta;
                $_SESSION['productos'][] = array(
                    'Nombre' => $vaper,
                    'Precio' => $vapers[$vaper],
                    'Cantidad' => $cantidadOferta
                );
            }
        }
        $inputs['total'] += $total;
    }
    if (!$_SESSION['selected_vapers']) {
        $errors['vapers'] = "No has seleccionado ningun vaper";
    }

    $marcado = htmlspecialchars($_POST['vaperChecked'], ENT_QUOTES, 'UTF-8');

    $_SESSION['vaperChecked'] = [];

    if ($marcado) {
        $_SESSION['vaperChecked'][] = 'vaperChecked';

        //--------------------------------------------------------- VALIDAR CANTIDAD ---------------------------
        if (isset($_POST['cantidadVaper'])) {
            $cantidadVaper = filter_input(INPUT_POST, 'cantidadVaper', FILTER_SANITIZE_NUMBER_INT);

            if (!empty($cantidadVaper)) {
                if (filter_var($cantidadVaper, FILTER_VALIDATE_INT)) {
                    $inputs['cantidadVaper'] = $cantidadVaper;
                } else {
                    $errors['cantidadVaper'] = sprintf(VALIDATION_ERRORS['cantidadVaper']);
                }
            } else {
                $errors['cantidadVaper'] = sprintf(VALIDATION_ERRORS['required'], 'cantidad de vapers');
            }
        }

        //----------------------------------------------------------------------------------------
        // sanitize tamaños
        $tamaño = filter_input(INPUT_POST, 'tamaños', FILTER_SANITIZE_STRING);
        $_SESSION['selected_tamaño'] = []; // for storing selected tamaños
        $total = 0; // for storing total

        // check the selected value against the original values
        if ($tamaño && array_key_exists($tamaño, $tamaños)) {
            $cantidadVaper = filter_input(INPUT_POST, "cantidadVaper", FILTER_VALIDATE_INT);
            $_SESSION['selected_tamaño'][] = $tamaño;
            $total += $tamaños[$tamaño] * $cantidadVaper;
            $inputs['total'] += $total;
        } else {
            $errors['tamaño'] = sprintf(VALIDATION_ERRORS['tamaño']);
        }


        //--------------------------------------------------------- VALIDAR SABOR ---------------------------
        $selected_sabor = filter_input(
            INPUT_POST,
            'seleccionVaper',
            FILTER_SANITIZE_STRING,
            FILTER_REQUIRE_ARRAY
        );

        $_SESSION['selected_sabor'] = [];

        foreach ($selected_sabor as $sabor) {
            $_SESSION['selected_sabor'][] = $sabor;
        }

        if ($selected_sabor) {
            $_SESSION['productos'][] = array(
                'Nombre' => 'Vaper ' . $_SESSION['selected_sabor'][0],
                'Tamaño' => $_SESSION['selected_tamaño'][0],
                'Precio' => $tamaños[$_SESSION['selected_tamaño'][0]],
                'Cantidad' => $inputs['cantidadVaper']
            );
        }

        //--------------------------------------------------------- VALIDAR COMPLEMENTOS ---------------------------
        // sanitize the inputs
        $selected_complementos = filter_input(
            INPUT_POST,
            'complementos',
            FILTER_SANITIZE_STRING,
            FILTER_REQUIRE_ARRAY
        ) ?? [];

        // select the topping names
        $vaperComplementos = array_keys($complementos);

        $_SESSION['selected_complementos'] = []; // for storing selected toppings
        $total = 0; // for storing total

        // check data against the original values
        if ($selected_complementos) {

            foreach ($selected_complementos as $complemento) {
                if (in_array($complemento, $vaperComplementos)) {
                    $_SESSION['selected_complementos'][] = $complemento;
                    $total += $complementos[$complemento];
                    $_SESSION['productos'][] = array(
                        'Complemento' => $complemento,
                        'Precio' => $complementos[$complemento]
                    );
                }
            }
            $inputs['total'] += $total;
        }
    }

    // Verifica si hay errores
    if (!empty($errors)) {
        // Redirige al usuario a la página anterior (el formulario) con un mensaje de error
        redirect_with('../index.php', [
            'inputs' => $inputs,
            'errors' => $errors
        ]);
    }
    // Verifica si hay errores
    if (empty($errors)) {
        // Redirige al usuario a la página anterior (el formulario) con un mensaje de error
        redirect_with('../ticket.php', [
            'inputs' => $inputs
        ]);
    }
}
