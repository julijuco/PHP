<?php
session_name('Bengala');
session_start();
require_once('src/functions-structure.php');
myHead('Inicio de sesión');

if (isset($_SESSION['inputsLogin'])) {
    $inputsLogin = $_SESSION['inputsLogin'];
}
if (isset($_SESSION['errorsLogin'])) {
    $errorsLogin = $_SESSION['errorsLogin'];
}
?>

<body>
    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <!-- Muestra los errores del logeo -->
                    <?php if (isset($errorsLogin['login'])) : ?>
                        <div class="alert alert-danger">
                            <?= $errorsLogin['login'] ?>
                        </div>
                    <?php endif ?>
                    <div class="card text-white" style="background:linear-gradient(180deg, #D65573 -0.35%, #DC1040 -0.34%, #F59448 99.65%); border-radius: 23px;">
                        <div class="card-body p-5 text-center">
                            <form action="src/validationLogin.php" method="post" class="w-100">
                                <div class="mb-md-4 mt-md-3 ">
                                    <h2 class="fw-bold mb-2 text-uppercase mb-5">Iniciar Sesión</h2>
                                    <!-- Usuario -->
                                    <div class="form-floating form-white">
                                        <input type="text" id="username" name="username" class="form-control form-control-lg" value="<?php echo $inputsLogin['username'] ?? '' ?>" placeholder="userexample@gmail.com" />
                                        <label class="text-secondary" for="username">Nombre de usuario</label>
                                        <p style="height: 30px;"><?= $errorsLogin['username'] ?? '' ?></p>
                                    </div>
                                    <!-- Contraseñas -->
                                    <div class="form-floating form-white mb-4">
                                        <input type="password" id="password" name="password" class="form-control form-control-lg" placeholder="xxxxxxxx" />
                                        <label class="text-secondary" for="password">Contraseña</label>
                                        <p style="height: 30px;"><?= $errorsLogin['password'] ?? '' ?></p>
                                    </div>
                                    <!-- Boton de login -->
                                    <button class="btn btn-outline-light btn-lg px-5" type="submit">Iniciar Sesión</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>

<?php 
unset($_SESSION['inputsLogin']);
unset($_SESSION['errorsLogin']);
?>

</html>