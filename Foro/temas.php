<?php
/**
 * Este archivo muestra los temas de una categoría seleccionada.
 *
 * Dependiendo del tipo de usuario (admin, creador), se tendrán diferentes opciones de menú en función de lo que pueden hacer.
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

/**
 * Obtiene la categoría seleccionada de la URL.
 */
if (isset($_GET['categoria'])) {
    /**
     * @var int $categoria_seleccionada La categoría seleccionada.
     */
    $categoria_seleccionada = $_GET['categoria'];

    /**
     * @var string $sql_categoria La consulta SQL para obtener el nombre de la categoría.
     * @var mysqli_result|bool $resultado_categoria El resultado de la consulta SQL.
     */
    $sql_categoria = "SELECT nome FROM categorias WHERE cod = $categoria_seleccionada";
    $resultado_categoria = mysqli_query($conexion, $sql_categoria);

    /**
     * Verifica si se obtuvo correctamente el resultado de la consulta.
     */
    if ($resultado_categoria) {
        /**
         * @var array $fila_categoria Los datos de la categoría obtenidos de la base de datos.
         * @var string $nome_categoria El nombre de la categoría.
         */
        $fila_categoria = mysqli_fetch_assoc($resultado_categoria);
        $nome_categoria = $fila_categoria['nome'];

        /**
         * @var string $sql La consulta SQL para obtener los temas de la categoría.
         * @var mysqli_result|bool $resultado El resultado de la consulta SQL.
         */
        $sql = "SELECT temas.*, usuarios.usuario AS nome_usuario, usuarios.rol_usuario
        FROM temas 
        LEFT JOIN usuarios ON temas.cod_usuario = usuarios.id 
        WHERE temas.cod_categoria = $categoria_seleccionada";

        $resultado = mysqli_query($conexion, $sql);
        if (!$resultado) {
            echo "Erro ao obter os comentarios: " . mysqli_error($conexion);
            exit;
        }
    } else {
        echo "Categoría non atopada.";
    }
} else {
    echo "Non se proporcionou unha categoría.";
    header('Location: inicio.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Temas do foro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" />
    <div class="container">
        <h3>Temas sobre <?php echo isset($nome_categoria) ? $nome_categoria : ''; ?></h3><br>
        <div class="card row-hover pos-relative py-3 px-3 mb-3 border-warning border-top-0 border-right-0 border-bottom-0 rounded-0">
            <div class="row align-items-center">
                <div class="col-md-12 mb-3 mb-sm-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <?php
                        if (isset($resultado)) {
                            while ($fila = mysqli_fetch_array($resultado)) {
                                // Uso temas.php?tema=' . $fila['cod'] para redireccionar a comentarios.php con el tema seleccionado
                                echo '<div class="list-group-item list-group-item-action">';
                                echo '<div class="d-flex justify-content-between align-items-center">';
                                echo '<b class="text-primary"><a href="comentarios.php?tema=' . $fila['cod'] . '"><h5>' . $fila['nome'] . '</h5></a></b>';
                                echo '<div>';
                                // Se o usuario é admin ou creador pode editar ou eliminar o tema:
                                if ($tipo_usuario == 'admin' || ($tipo_usuario == 'creador' && isset($fila['rol_usuario']) && $fila['rol_usuario'] == 'creador')) {
                                    // Uso a clase bootstrap 'class="d-inline-block">' para alinear 'Editar' i 'Eliminar'
                                    echo '<form method="post" class="d-inline-block">';
                                    echo '<input type="hidden" name="cod_tema" value="' . $fila['cod'] . '">';
                                    echo '<input type="submit" class="btn btn-primary" name="editar_' . $fila['cod'] . '" value="Editar">';
                                    echo '<input type="submit" class="btn btn-danger" name="eliminar_' . $fila['cod'] . '" value="Eliminar">';
                                    echo '</form>';
                                }

                                echo '</div>' .
                                    '</div>' .
                                    $fila['descripcion'] . '<br>' .
                                    '<br>Tema creado por: <i>' . $fila['nome_usuario'] . '</i><br>' .
                                    '<h6>Data: ' . $fila['data_creacion'] . '</h6>' .
                                    '</div>';
                                echo '</div>'; // Fin de contenedor para botóns
                                echo '</div>'; // Fin de contenedor para título e botóns

                                echo '<div class="d-flex justify-content-between align-items-center">';
                                if (isset($_POST['editar_' . $fila['cod']])) {
                                    echo '<div class="col-md-10 mb-3 mb-sm-0">';
                                    echo '<form method="post">';
                                    // Engado agochado con 'hidden' o código de tema para manexalo despois:
                                    echo '<input type="hidden" name="cod_tema" value="' . $fila['cod'] . '">';
                                    echo '<input type="text" name="novo_nome" placeholder="Novo nome">';
                                    echo '<textarea name="nova_descripcion" placeholder="Nova descripción"></textarea>';
                                    echo '<input type="submit" name="gardar" value="Gardar">';
                                    echo '</form>';
                                    echo '</div>';
                                }
                                if (isset($_POST['eliminar_' . $fila['cod']])) {
                                    echo '<div class="col-md-10 mb-3 mb-sm-0 ml-6">';
                                    echo '<form method="post">';
                                    echo '<div class="alert alert-danger" role="alert">';
                                    echo 'Está seguro de querer eliminar o tema sinalado?<br>';
                                    echo '<input type="hidden" name="cod_tema" value="' . $fila['cod'] . '">';
                                    echo '<button type="submit" class="btn btn-danger mr-3" name="eliminar_tema">Si</button>';
                                    echo '<button type="submit" class="btn btn-secondary" name="non">Non</button>';
                                    echo '</div>';
                                    echo '</form>';
                                    echo '</div>';
                                }
                                echo '</div>';
                            }
                        }

                        if (isset($_POST['gardar'])) {
                            $cod_tema = $_POST['cod_tema'];
                            $novo_nome = $_POST['novo_nome'];
                            $nova_descripcion = $_POST['nova_descripcion'];
                            $sql = "UPDATE `temas` SET `nome` = '$novo_nome', `descripcion` = '$nova_descripcion', `data_peche` = NULL WHERE `temas`.`cod` = '" . $cod_tema . "'";

                            mysqli_query($conexion, $sql);
                            echo '<div class="alert alert-success" role="alert">Tema editado correctamente.</div>';
                        }

                        if (isset($_POST['eliminar_tema'])) {
                            $cod_tema = $_POST['cod_tema'];
                            $sql = "DELETE FROM `temas` WHERE `temas`.`cod` = '$cod_tema'";
                            mysqli_query($conexion, $sql);

                            if (!mysqli_query($conexion, $sql)) {
                                echo 'Erro ao intentar entrar na táboa: ' . mysqli_error($conexion);
                            } else {
                                $cod_usuario = $_SESSION['usuario_id'];
                                // Actualizo o número de temas (n_temas na táboa usuarios) do usuario
                                $sql_actualizar_n_temas = "UPDATE usuarios SET n_temas = n_temas - 1 WHERE id = $cod_usuario";
                                mysqli_query($conexion, $sql_actualizar_n_temas);

                                echo '<div class="alert alert-success" role="alert">Tema eliminado correctamente.</div>';
                            }
                        }

                        if (isset($_POST['non'])) {
                            echo '<div class="alert alert-primary" role="alert">
                                Cancelación de eliminación.
                             </div>';
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