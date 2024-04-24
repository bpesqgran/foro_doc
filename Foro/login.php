<?php
/**
 * Este archivo maneja el inicio de sesión de los usuarios.
 *
 * Dependiendo del tipo de usuario (admin, creador, comentador, lector),
 * se tendrán diferentes opciones de menú en función de lo que pueden hacer.
 *
 * PHP versión 8
 *
 * @category Foro
 * @package  Foro
 */

/** 
 * Incluye el archivo de menú y conexión a la base de datos.
 */
include 'menu.php';
require 'conecta_bd.php';
/**
 * Comprueba si se ha enviado una petición POST.
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    /**
     * Comprueba si se ha enviado el formulario de inicio de sesión.
     */
    if (isset($_POST['login'])) {
        /**
         * @var string $usuario El nombre de usuario enviado en el formulario.
         * @var string $contrasinal La contraseña enviada en el formulario.
         */
        $usuario = $_POST['usuario'];
        $contrasinal = $_POST['contrasinal'];

        /**
         * @var string $sql La consulta SQL para buscar al usuario en la base de datos.
         */
        $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";

        /**
         * @var mysqli_result|bool $result El resultado de la consulta SQL.
         */
        $result = mysqli_query($conexion, $sql);

        /**
         * Comprueba si la consulta fue exitosa y si se encontró al usuario.
         */
        if ($result && mysqli_num_rows($result) > 0) {
            /**
             * @var array $usuario Los datos del usuario obtenidos de la base de datos.
             */
            $usuario = mysqli_fetch_assoc($result);

            /**
             * Comprueba si la contraseña enviada coincide con la del usuario.
             */
            if ($contrasinal === $usuario['contrasinal']) {
                /**
                 * Inicia una nueva sesión y guarda los datos del usuario en la sesión.
                 */
                session_start();
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario'] = $usuario['usuario'];
                $_SESSION['rol_usuario'] = $usuario['rol_usuario'];

                /**
                 * Redirige al usuario a la página de inicio.
                 */
                header("Location: inicio.php");
                exit();
            } else {
                /**
                 * Muestra un mensaje de error si los datos son incorrectos.
                 */
                echo '<div class="alert alert-danger" role="alert">
                        Datos incorrectos. Inténteo de novo.
                    </div>';
            }
        } else {
            /**
             * Muestra un mensaje de error si el usuario no se encuentra.
             */
            echo '<div class="alert alert-danger" role="alert">
                    Usuario non atopado.
                </div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>

<body>
    <br><br>
    <div id="template-bg-1">
        <div class="d-flex flex-column min-vh-100 justify-content-center align-items-center">
            <div class="card p-4 text-light bg-dark mb-5">
                <div class="card-header">
                    <h3>Iniciar sesión </h3>
                </div>
                <div class="card-body w-100">
                    <form name="login" action="" method="post">
                        <div class="input-group form-group mt-3">
                            <div class="bg-secondary rounded-start">
                                <span class="m-3"><i class="fas fa-usuario$usuario mt-2"></i></span>
                            </div>
                            <input type="text" class="form-control" placeholder="Usuario" name="usuario">
                        </div>
                        <div class="input-group form-group mt-3">
                            <div class="bg-secondary rounded-start">
                                <span class="m-3"><i class="fas fa-key mt-2"></i></span>
                            </div>
                            <input type="password" class="form-control" placeholder="Contrasinal" name="contrasinal">
                        </div>

                        <div class="form-group mt-3">
                            <input type="submit" value="Acceder" class="btn bg-secondary float-end text-white w-100" name="login">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>