<?php
/**
 * Este archivo es la página de inicio de un foro que muestra las categorías disponibles.
 *
 * PHP version 8
 * @author Borja
 * @category Foro
 * @package  Foro
 */

/** 
 * Incluye el archivo de conexión a la base de datos y la barra de menú.
 */
require 'conecta_bd.php';
include 'menu.php';
/**
 * @var string $tipo_usuario El rol del usuario actual.
 */
$tipo_usuario = $_SESSION['rol_usuario'];
/**
 * Incluye el menú correspondiente al rol del usuario.
 */
if ($_SESSION['rol_usuario'] == 'admin' || $_SESSION['rol_usuario'] == 'borja') {
    include 'menu_admin.php';
} else if ($_SESSION['rol_usuario'] == 'admin') {
    include 'menu_creador.php';
}

// Selección de categorías
$sql = 'SELECT * FROM categorias';
/**
 * @var mysqli_result|bool $resultado El resultado de la consulta SQL.
 */
$resultado = mysqli_query($conexion, $sql);

if (!$resultado) {
    echo "Erro ao obter as táboas: " . mysqli_error($conexion);
    exit;
}

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>Categorías do foro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" />
    <div class="container">
        <h3>Categorías dispoñibeis</h3><br>
        <div class="card row-hover pos-relative py-3 px-3 mb-3 border-warning border-top-0 border-right-0 border-bottom-0 rounded-0">
            <div class="row align-items-center">
                <div class="col-md-12 mb-3 mb-sm-0">
                    <?php
                    /**
                     * Muestra las categorías disponibles.
                     */
                    while ($fila = mysqli_fetch_array($resultado)) {                        
                        print ('<div class="list-group-item list-group-item-action"><h4>'
                        . $fila['cod'] . '.- <a href="temas.php?categoria=' . $fila['cod'] . '"><b class="text-primary">'
                        . $fila['nome'] . '</b></h4></a><br>' . $fila['descripcion']
                        . '<br><br><h6>Categoría creada o ' . $fila['data_creacion'] . '</h6></div>');
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript"></script>
</body>

</html>