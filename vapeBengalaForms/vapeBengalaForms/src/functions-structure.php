<?php
/**
 * Cabecera
 */
function myHead($title)
{
    echo <<< HEAD
    <!DOCTYPE html>
    <html lang="en">

    <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- links to Bootstrap -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script> -->

    <!-- Bootstrap local links -->
    <link rel="stylesheet" href="./vendor/bootstrap-5.3.2-dist/css/bootstrap.min.css">
    <script type="text/javascript" src="./vendor/bootstrap-5.3.2-dist/js/bootstrap.bundle.min.js"></script>

    <!-- files CSS -->
    <link rel="stylesheet" href="css/style.css">
    
    <title>$title</title>
    </head>

    HEAD;
}
/**
 * Barra de Menú
 */
function myMenu()
{
    $current_user = current_user();

    echo <<< MENU
    <body>
        <header>
            <nav class="navbar navbar-expand-lg navbar-dark p-0" style="background-color: #faba42">
            <div class="container">
                <a class="navbar-brand me-5" href="./index.php" style="flex:1;"><img src="./img/Yoshifumon.png" alt="Yoshi Fum" width="70px"> <img src="./img/nombre.png" width="220px"></a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <div class="d-flex ms-auto py-3">
                        <ul class="navbar-nav flex-column ms-auto text-white">
                            <li class="nav-item d-flex align-items-end">
                                <img src="./img/usuario.png" alt="Usuario icono" height="30px">
                                <h5 class="lh-1 ms-1">$current_user</h5>
                            </li>
                            <li class="nav-item">
                                <a href="src/logout.php" class="text-decoration-none text-white"><p class="lh-1">Cerrar sesión</p></a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>
            </nav>
        </header>
    MENU;
}

/**
 * Footer de la página
 */
function myFooter(): void
{
    echo <<<FOOTER
    <footer class="bg-dark text-white py-3 mt-auto">
        <div class="container">
            <div class="row">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="mb-3 mb-md-0">&copy; Bengala Spain 2023</span>
                    </div>
                    <div class="text-end" id="real-time-clock">
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Función para actualizar la hora en tiempo real
        function updateClock() {
            const clockElement = document.getElementById("real-time-clock");
            const now = new Date();
            const formattedTime = now.toLocaleString("es-ES", { hour: "numeric", minute: "numeric", second: "numeric" });
            clockElement.textContent = "La fecha es: " + now.toLocaleDateString("es-ES") + " y la hora es " + formattedTime;
        }

        // Actualizar la hora cada segundo
        setInterval(updateClock, 1000);
    </script>
    FOOTER;
}
