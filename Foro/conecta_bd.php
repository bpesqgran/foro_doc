<?php
/**
 * Este archivo establece una conexión con la base de datos.
 *
 * Utiliza la extensión mysqli de PHP para conectar con una base de datos MySQL.
 * Si la conexión falla, se muestra un mensaje de error.
 *
 * @category Foro
 * @package  Foro
 */

/**
 * @var string $servidor El nombre del servidor de la base de datos.
 * @var string $usuario El nombre de usuario para la base de datos.
 * @var string $contrasinal La contraseña para la base de datos.
 * @var string $bd El nombre de la base de datos.
 */
$servidor = "localhost";
$usuario = "root";
$contrasinal = "";
$bd = "foro";

/**
 * @var mysqli $conexion La conexión a la base de datos.
 */
$conexion = mysqli_connect($servidor,$usuario,$contrasinal,$bd);

/**
 * Comprueba si la conexión fue exitosa.
 * Si hubo un error, muestra un mensaje de error.
 */
if (mysqli_connect_errno()) {
    echo "Non se puido conectar á base de datos. Erro: " . mysqli_connect_error();
}

// Inicia una nueva sesión
session_start();
