<?php
session_name('Bengala');
session_start();
require_once('src/functions-structure.php');
require_once('src/functions.php');
require_once('src/functions-login.php');
require('vendor/fpdf/fpdf.php');

if (isset($_SESSION['inputs'])) {
    $inputs = $_SESSION['inputs'];
}
if (!isset($_SESSION['productos'])) {
    redirect_to('index.php');
}
//calcular iva
$iva = $inputs['total'] * 0.21;
$precioTotal = $inputs['total'] + $iva;

generarPDF($inputs);
myHead('Ticket de compra');
myMenu();
require_login();
?>

<body class="d-flex flex-column vh-100">
    <div class="container-fluid h-100">
        <!-- Form dirección de envío -->
        <div class="content">
            <div class="row justify-content-center align-items-center h-100">
                <div class="col-xxl-5 my-5 p-4 px-5 text-white" style="background:linear-gradient(180deg, #D65573 -0.35%, #DC1040 -0.34%, #F59448 99.65%); border-radius: 23px;">
                    <h1>Resumen del pedido</h1>
                    <div class="d-flex mt-4">
                        <div>
                            <div class="mb-4">
                                <h4>1 Dirección de envío</h4>
                                <div>
                                    <!-- Dirección de envío -->
                                    <ul>
                                        <li><?php echo $inputs['full-name']; ?></li>
                                        <li><?php echo $inputs['direccion']; ?></li>
                                        <li><?php echo $_SESSION['selected_ciudad'][0] . ", " . $_SESSION['selected_provincia'][0] . ", " . $inputs['codigo-postal']; ?></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="mb-4">
                                <h4>2 Metódo de Pago</h4>
                                <div>
                                    <ul>
                                        <!-- Metdódo de Pago -->
                                        <li><?php echo "Pagada con Visa / 4B / Euro6000 " . substr($inputs['numeroTarjeta'], -4) ?></li>
                                    </ul>
                                </div>
                            </div>
                            <div>
                                <h4>3 Productos</h4>
                                <div>
                                    <!-- For each con los productos -->
                                    <?php foreach ($_SESSION['productos'] as $producto) {
                                        foreach ($producto as $key => $value) {
                                            echo $key . ": " . $value;
                                            echo "<br>";
                                        }
                                        echo "<br>";
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>

                        <div class="ms-auto me-3 align-self-center">
                            <h4>Resumen del pedido</h4>
                            <div>
                                <ul>
                                    <!-- Resumen del pedido -->
                                    <li><?php echo "Productos: " . number_format($inputs['total'], 2) . " €" ?></li>
                                    <li><?php echo "Total sin IVA: " . number_format($inputs['total'], 2) . " €" ?></li>
                                    <li><?php echo "IVA estimado: " . number_format($iva, 2) . " €" ?></li>
                                    <li><b><?php echo "Importe total: " . number_format($precioTotal, 2) . " €" ?></b></li>
                                </ul>
                            </div>
                            <form method="post">
                                <button class="btn btn-outline-light btn-lg mt-4" type="submit" name="generate_pdf">Descargar Ticket PDF</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    myFooter();
    ?>
</body>

</html>