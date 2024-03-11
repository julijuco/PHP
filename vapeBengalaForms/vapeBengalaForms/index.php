<?php
session_name('Bengala');
session_start();
require_once('src/functions-structure.php');
require_once('data/datos.php');
require_once('src/functions.php');
require_once('src/functions-login.php');
myHead('Vape Bengala');
myMenu();
require_login();
if (isset($_SESSION['inputs'])) {
    $inputs = $_SESSION['inputs'];
}
if (isset($_SESSION['errors'])) {
    $errors = $_SESSION['errors'];
}
?>

<body>
    <div class="container-fluid">
        <!-- Form dirección de envío -->
        <div class="content">
            <form action="src/validationIndex.php" method="post">
                <div class="row pt-5 justify-content-center">
                    <div class="col-xxl-5 p-4" style="background:linear-gradient(180deg, #D65573 -0.35%, #DC1040 -0.34%, #F59448 99.65%); border-radius: 23px;">
                        <ul>
                            <li class="mb-4">
                                <h2 class="text-white">Dirección de envío</h2>
                            </li>
                            <!-- Nombre -->
                            <li>
                                <label class="text-white" for="full-name">Nombre</label>
                                <input type="text" name="full-name" id="full-name" class="form-control border-2" minlength="1" maxlength="30" value="<?php echo $inputs['full-name'] ?? '' ?>">
                                <small><?php echo $errors['full-name'] ?? ' ' ?></small>
                            </li>
                            <!-- Apellidos -->
                            <li>
                                <label class="text-white" for="apellidos">Apellidos</label>
                                <input type="text" name="apellidos" id="apellidos" class="form-control border-2" minlength="1" maxlength="30" value="<?php echo $inputs['apellidos'] ?? '' ?>">
                                <small><?php echo $errors['apellidos'] ?? ' ' ?></small>
                            </li>
                            <!-- Telefono -->
                            <li>
                                <label class="text-white" for="telefono">Número de teléfono</label>
                                <input type="text" name="telefono" id="telefono" class="form-control border-2" maxlength="9" value="<?php echo $inputs['telefono'] ?? '' ?>" max="9">
                                <small><?php echo $errors['telefono'] ?? ' ' ?></small>
                            </li>
                            <!-- Correo-->
                            <li>
                                <label class="text-white" for="correo">Correo</label>
                                <input type="email" name="correo" id="correo" class="form-control border-2" maxlength="50" value="<?php echo $inputs['correo'] ?? '' ?>">
                                <small><?php echo $errors['correo'] ?? ' ' ?></small>
                            </li>
                            <!-- Direccion -->
                            <li>
                                <label class="text-white" for="direccion">Línea de dirección 1</label>
                                <input type="text" name="direccion" id="direccion" min="15" class="form-control border-2" value="<?php echo $inputs['direccion'] ?? '' ?>">
                                <small><?php echo $errors['direccion'] ?? ' ' ?></small>
                            </li>
                            <!-- Código postal -->
                            <li>
                                <div class="d-flex">
                                    <div class="me-5">
                                        <label class="text-white" for="codigo-postal">Código postal</label>
                                        <input type="text" name="codigo-postal" id="codigo-postal" class="form-control border-2" value="<?php echo $inputs['codigo-postal'] ?? '' ?>" maxlength="5">
                                        <small><?php echo $errors['codigo-postal'] ?? ' ' ?></small>
                                    </div>
                                    <!-- Select de ciudades -->
                                    <div class="w-100">
                                        <label class="text-white" for="ciudad">Ciudad</label>
                                        <select name="seleccionCiudad[]" id="seleccionCiudad" class="form-control border-2">
                                            <?php
                                            foreach ($ciudades as $ciudad) {
                                                $selected = selected($ciudad, $_SESSION['selected_ciudad'] ?? []);
                                                echo "<option value=\"$ciudad\" $selected>$ciudad</option>";
                                            }
                                            ?>
                                        </select>
                                        <small><?php echo $errors['ciudad'] ?? ' ' ?></small>
                                    </div>
                                </div>
                            </li>
                            <!-- Select de Provincias -->
                            <li>
                                <label class="text-white" for="provincia">Provincia</label>
                                <select name="seleccionProvincia[]" id="seleccionProvincia" class="form-control border-2">
                                    <?php
                                    foreach ($provincias as $provincia) {
                                        $selected = selected($provincia, $_SESSION['selected_provincia'] ?? []);
                                        echo "<option value=\"$provincia\" $selected>$provincia</option>";
                                    }
                                    ?>
                                </select>
                                <small><?php echo $errors['provincia'] ?? ' ' ?></small>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Form método de pago -->
                <div class="row p-5 justify-content-center">
                    <div class="col-xxl-5 p-4" style="background:linear-gradient(180deg, #D65573 -0.35%, #DC1040 -0.34%, #F59448 99.65%); border-radius: 23px;">
                        <ul>
                            <li class="mb-3">
                                <h2 class="text-white">Método de pago</h2>
                            </li>
                            <!-- Número de la tarjeta -->
                            <li class="d-flex align-items-center p-1">
                                <label for="numeroTarjeta" class="text-white pr-2 w-25">Número de la tarjeta</label>
                                <div>
                                    <input type="text" name="numeroTarjeta" id="numeroTarjeta" class="form-control border-2" style="width: 500px;" maxlength="16" value="<?php echo $inputs['numeroTarjeta'] ?? '' ?>">
                                    <small class=""><?php echo $errors['numeroTarjeta'] ?? ' ' ?></small>
                                </div>
                            </li>
                            <!-- Nombre de la tarjeta -->
                            <li class="d-flex align-items-center p-1">
                                <label for="nombreTarjeta" class="text-white pr-2 w-25">Nombre en la tarjeta</label>
                                <div>
                                    <input type="text" name="nombreTarjeta" id="nombreTarjeta" class="form-control border-2" minlength="10" maxlength="50" style="width: 500px;" value="<?php echo $inputs['nombreTarjeta'] ?? '' ?>">
                                    <small><?php echo $errors['nombreTarjeta'] ?? ' ' ?></small>
                                </div>
                            </li>
                            <!-- Fecha de Vencimiento -->
                            <li class="d-flex align-items-center p-1">
                                <label for="fechaVencimiento" class="text-white pr-2 w-25">Fecha vencimiento</label>
                                <div>
                                    <!-- Select de los meses -->
                                    <select name="mesVencimiento[]" id="fechaVencimiento" class="form-control border-2" style="width: 250px; background-color: #b5b5b5;" value="mesVencimiento[04]">
                                        <?php
                                        foreach ($meses as $mes) {
                                            $selected = selected($mes, $_SESSION['selected_mes'] ?? []);
                                            echo "<option value=\"$mes\" $selected>$mes</option>";
                                        } ?>
                                    </select>

                                    <small><?php echo $errors['mesVencimiento'] ?? ' ' ?></small>
                                </div>
                                <div>
                                    <!-- Select de los años -->
                                    <select name="anyoVencimiento[]" id="fechaVencimiento" class="form-control border-2" style="width: 250px; background-color: #b5b5b5;">
                                        <?php
                                        foreach ($años as $año) {
                                            $selected = selected($año, $_SESSION['selected_anyo'] ?? []);
                                            echo "<option value=\"$año\" $selected>$año</option>";
                                        } ?>
                                    </select>
                                    <small><?php echo $errors['anyoVencimiento'] ?? ' ' ?></small>
                                </div>
                            </li>
                            <!-- Codigo de seguridad -->
                            <li class="d-flex align-items-center p-1">
                                <label for="codigoSeguridadTarjeta" class="text-white pr-2 w-25">Codigo de seguridad (CVV)</label>
                                <div>
                                    <input type="number" name="codigoSeguridadTarjeta" id="codigoSeguridadTarjeta" class="form-control border-2" style="width: 500px;" min="100" max="999" value="<?php echo $inputs['codigoSeguridadTarjeta'] ?? '' ?>">
                                    <small><?php echo $errors['codigoSeguridadTarjeta'] ?? ' ' ?></small>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Ofertars del dia -->
                <div class="row justify-content-center mb-5">
                    <div class="col-xxl-7 p-5" style="background:linear-gradient(180deg, #D65573 -0.35%, #DC1040 -0.34%, #F59448 99.65%); border-radius: 23px;">
                        <ul>
                            <li>
                                <div class="form-floating mb-3">
                                    <h2 style="color: white;">Ofertas del día</h2></br>
                                    <div class="d-flex justify-content-between mx-2 text-center px-5">
                                        <!--Select de Vapers -->
                                        <?php $contador = 0 ?>
                                        <?php foreach ($vapers as $nombreVaper => $precio) : ?>
                                            <?php $contador++; ?>

                                            <div class="justify-content-center">
                                                <img src="./img/oferta<?php echo $contador ?>.jpg" width="180px" height="180px" style="border-radius: 30px;" class="mb-4">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div class="d-flex align-items-center justify-content-center me-2">
                                                        <input class="me-2" type="checkbox" name="vapers[]" value="<?php echo $nombreVaper ?>" id="vapers_<?php echo $nombreVaper ?>" <?php echo checked($nombreVaper, $_SESSION['selected_vapers'] ?? []) ?> />
                                                        <label for="vapers_<?php echo $nombreVaper ?>" style="color: white;"><?php echo ucfirst($nombreVaper) ?></label>
                                                    </div>
                                                    <span style="color: white;"><?php echo $precio . ' €' ?></span>
                                                </div>
                                                <div class="d-flex justify-content-center align-items-center">
                                                    <p class="me-1" style="color: white;">Cantidad</p>
                                                    <input class="ms-1" type="number" name="cantidadOferta<?php echo $contador ?>" min="1" max="10" value="<?php echo $inputs["cantidadOferta$contador"] ?? '1' ?>" />
                                                </div>
                                                <div class="text-center mt-5">
                                                    <small><?php echo $errors["cantidadOferta$contador"] ?? '' ?></small>
                                                </div>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                    <div class="text-center">
                                        <p><?php echo $errors['vapers'] ?? '' ?></p>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>


                <!-- Form vaper personalizado-->
                <div class="row justify-content-center">
                    <div class="col-xxl-7 p-5" style="background:linear-gradient(180deg, #D65573 -0.35%, #DC1040 -0.34%, #F59448 99.65%); border-radius: 23px;">
                        <div class="form-floating d-flex align-items-center">
                            <input id="vaperChecked" class="me-3" type="checkbox" name="vaperChecked" width="100px" onclick="alternarVaperPersonalizado()" <?php echo checked("vaperChecked", $_SESSION['vaperChecked'] ?? []) ?>>
                            <h2 class="text-white">Vaper personalizado</h2>
                        </div>
                        <ul id="personalizacionVaper" class="d-none">
                            <!-- Cantidad -->
                            <li>
                                <div class="form-floating mt-5 mb-4">
                                    <h2 style="color: white;">Cantidad<input type="number" name="cantidadVaper" min="1" max="10" class="mx-4" value="<?php echo $inputs['cantidadVaper'] ?? '1' ?>"></h2><br>
                                    <small><?php echo $errors['cantidadVaper'] ?? '' ?></small>
                                </div>
                            </li>
                            <!-- Select de Tamaños -->
                            <li>
                                <div class="d-flex justify-content-between mx-2 text-center px-5">
                                    <?php $contador = 0 ?>
                                    <?php foreach ($tamaños as $tamaño => $precio) : ?>
                                        <?php $contador++; ?>
                                        <div class="justify-content-center">
                                            <img src="./img/tamaño<?php echo $contador ?>.png" width="180px" height="180px" style="border-radius: 30px;" class="mb-4">
                                            <div class="d-flex align-items-center justify-content-center">
                                                <input type="radio" name="tamaños" id="tamaño_<?php echo $tamaño ?>" value="<?php echo $tamaño ?>" <?php echo checked($tamaño, $_SESSION['selected_tamaño'] ?? []) ?> />
                                                <label class="mx-2" style="color: white;" for="tamaño<?php echo $tamaño ?>"><?php echo ucfirst($tamaño) ?></label>
                                                <span style="color: white;"> <?php echo $precio  . ' €' ?></span>
                                            </div>
                                        </div>
                                    <?php endforeach ?>
                                </div>
                                <div class="text-center mt-4">
                                    <p><?php echo $errors['tamaño'] ?? '' ?></p>
                                </div>
                            </li>
                            <!-- Select de Sabores del Vaper -->
                            <li>
                                <div class="form-floating mb-5 d-flex mt-4">
                                    <h2 style="color: white;">Sabores del vaper</h2>
                                    <select type="select" name="seleccionVaper[]" id="seleccionVaper" class="btn btn-light mx-4 ">
                                        <?php
                                        foreach ($sabores as $sabor) {
                                            $selected = selected($sabor, $_SESSION['selected_sabor'] ?? []);
                                            echo "<option value=\"$sabor\" $selected>$sabor</option>";
                                        } ?>
                                    </select>
                                </div>
                            </li>
                            <!-- Select de Complementos -->
                            <li>
                                <div class="form-floating mb-3">
                                    <h2 style="color: white;">Complementos</h2></br>
                                    <div class="d-flex justify-content-between mx-2 text-center px-5">
                                        <?php $contador = 0 ?>
                                        <?php foreach ($complementos as $nombreComplemento => $precio) : ?>
                                            <?php $contador++; ?>
                                            <div>
                                                <img src="./img/pod<?php echo $contador ?>.png" width="180px">
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <input class="me-3" type="checkbox" name="complementos[]" value="<?php echo $nombreComplemento ?>" id="complementos_<?php echo $nombreComplemento ?>" <?php echo checked($nombreComplemento, $_SESSION['selected_complementos'] ?? []) ?> />
                                                    <label for="complementos_<?php echo $nombreComplemento ?>" style="color: white;"><?php echo ucfirst($nombreComplemento) ?></label>
                                                </div>
                                                <span style="color: white;"><?php echo $precio . ' €' ?></span>
                                            </div>
                                        <?php endforeach ?>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <!-- Boton de compra -->
                <div class="text-center py-5">
                    <button type="submit" name="submit" class="btn rounded-pill p-3 text-white">
                        <h5>Comprar ahora</h5>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <?php
    myFooter();
    ?>
    <!-- Muestra o esconde el apartado de vaper personalizados -->
    <script>
        alternarVaperPersonalizado()

        function alternarVaperPersonalizado() {
            const btnVaperPersonalizado = document.getElementById('vaperChecked'); // radioBtn vaper personalizdo
            const ulPersonalizacionVape = document.getElementById('personalizacionVaper'); // lista vapers personalizados
            if (btnVaperPersonalizado.checked) {
                ulPersonalizacionVape.classList.replace("d-none", "d-block");
            } else if (!btnVaperPersonalizado.checked) {
                ulPersonalizacionVape.classList.replace("d-block", "d-none");
            }
        }
    </script>
</body>

</html>