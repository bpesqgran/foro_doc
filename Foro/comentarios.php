<?php
/**
 * Este archivo muestra los comentarios de un tema seleccionado.
 *
 * Dependiendo del tipo de usuario (admin, creador, comentador), se tendrán diferentes opciones de menú en función de lo que pueden hacer.
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
 * @var string|null $tipo_usuario El rol del usuario actual.
 */
$tipo_usuario = isset($_SESSION['rol_usuario']) ? $_SESSION['rol_usuario'] : null;

/**
 * Incluye el menú correspondiente al rol del usuario.
 */
if ($_SESSION['rol_usuario'] == 'admin' || $_SESSION['rol_usuario'] == 'borja') {
    include 'menu_admin.php';
} else if ($_SESSION['rol_usuario'] == 'admin') {
    include 'menu_creador.php';
}

/**
 * Obtiene el tema seleccionado de la URL.
 */
if (isset($_GET['tema'])) {
    /**
     * @var int $tema_seleccionado El tema seleccionado.
     */
    $tema_seleccionado = $_GET['tema'];
    /**
     * @var string $sql La consulta SQL para obtener los comentarios del tema.
     * @var mysqli_result|bool $resultado El resultado de la consulta SQL.
     */
    $sql = "SELECT comentarios.*, usuarios.usuario AS nome_usuario 
    FROM comentarios 
    LEFT JOIN usuarios ON comentarios.cod_tema = usuarios.id 
    WHERE comentarios.cod_tema = $tema_seleccionado";

    $resultado = mysqli_query($conexion, $sql);
    if (!$resultado) {
        echo "Erro ao obter os comentarios: " . mysqli_error($conexion);
        exit;
    }
}

/**
 * @var string $sql_tema La consulta SQL para obtener el nombre del tema seleccionado.
 * @var mysqli_result|bool $resultado_tema El resultado de la consulta SQL.
 */
$sql_tema = "SELECT nome FROM temas WHERE cod = $tema_seleccionado";
$resultado_tema = mysqli_query($conexion, $sql_tema);
if (!$resultado_tema) {
    echo "Erro ao obter o tema: " . mysqli_error($conexion);
    exit;
}
/**
 * @var array $fila_tema Los datos del tema obtenidos de la base de datos.
 * @var string $nome_tema El nombre del tema.
 */
$fila_tema = mysqli_fetch_assoc($resultado_tema);
$nome_tema = $fila_tema['nome'];

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <title>Comentarios do foro</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="estilos.css">
</head>

<body>
    <link href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css" rel="stylesheet" />
    <div class="container">
        <h3>Comentarios do tema <b><?php echo $nome_tema; ?></b></h3><br>
        <div class="card row-hover pos-relative py-3 px-3 mb-3 border-warning border-top-0 border-right-0 border-bottom-0 rounded-0">
            <div class="row align-items-center">
                <div class="col-md-12 mb-3 mb-sm-0">
                    <?php
                    while ($fila = mysqli_fetch_array($resultado)) {
                        echo '<div class="list-group-item list-group-item-action">';
                        echo '<div class="d-flex justify-content-between align-items-center">';
                        echo '<h5>' . $fila['titulo'] . '</h5>';
                        echo '<div>';                        
                        if (
                            $tipo_usuario == 'admin' || ($tipo_usuario == 'creador' && isset($fila['rol_usuario']) && $fila['rol_usuario'] == 'creador')
                            || ($tipo_usuario == 'comentador' && isset($fila['rol_usuario']) && $fila['rol_usuario'] == 'comentador')
                        ) {                            
                            echo '<form method="post" class="d-inline-block">';
                            echo '<input type="hidden" name="cod_comentario" value="' . $fila['cod'] . '">';
                            echo '<input type="submit" class="btn btn-primary" name="editar_' . $fila['cod'] . '" value="Editar">';
                            echo '<input type="submit" class="btn btn-danger" name="eliminar_' . $fila['cod'] . '" value="Eliminar">';
                            echo '</form>';
                        }

                        echo '</div>' .
                            '</div><i>' .
                            $fila['comentario'] . '</i><br>' .
                            '<br>Comentario de: <i>' . $fila['nome_usuario'] . '</i><br>' .
                            '<h6>Data: ' . $fila['data_creacion'] . '</h6>';

                        echo '<br><form method="post">';
                        echo '<input type="hidden" name="cod_comentario" value="' . $fila['cod'] . '">'; // Aquí engado o campo oculto co código do comentario pai
                        echo '<div class="row align-items-center">';
                        echo '<div class="col-md-3 mr-0">';
                        echo '<input type="text" class="form-control ml-0" name="titulo_resposta" placeholder="Título da resposta">';
                        echo '</div>';
                        echo '<div class="col-md-6">';
                        echo '<textarea class="form-control" name="respostar_' . $fila['cod'] . '" placeholder="Respostar ao comentario"></textarea>';
                        echo '</div>';
                        echo '<div class="col-md-2">';
                        echo '<input type="submit" class="btn btn-secondary" name="respostar_' . $fila['cod'] . '" value="Enviar">';
                        echo '</div>';
                        echo '</div>';
                        echo '</form>';


                        echo '</div>';
                        echo '</div>';
                        echo '</div>';

                        echo '<div class="d-flex justify-content-between align-items-center">';
                        if (isset($_POST['editar_' . $fila['cod']])) {
                            echo '<div class="col-md-10 mb-3 mb-sm-0">';
                            echo '<form method="post">';                            
                            echo '<input type="hidden" name="cod_comentario" value="' . $fila['cod'] . '">';
                            echo '<input type="text" name="novo_titulo" placeholder="Novo título">';
                            echo '<textarea name="novo_comentario" placeholder="Novo comentario"></textarea>';
                            echo '<input type="submit" name="gardar" value="Gardar">';
                            echo '</form>';
                            echo '</div>';
                        }
                        if (isset($_POST['eliminar_' . $fila['cod']])) {
                            echo '<div class="col-md-10 mb-3 mb-sm-0 ml-6">';
                            echo '<form method="post">';
                            echo '<div class="alert alert-danger" role="alert">';
                            echo 'Está seguro de querer eliminar o comentario sinalado?<br>';
                            echo '<input type="hidden" name="cod_comentario" value="' . $fila['cod'] . '">';
                            echo '<button type="submit" class="btn btn-danger mr-3" name="eliminar_comentario">Si</button>';
                            echo '<button type="submit" class="btn btn-secondary" name="non">Non</button>';
                            echo '</div>';
                            echo '</form>';
                            echo '</div>';
                        }
                        echo '</div>';
                        if (isset($_POST['respostar_' . $fila['cod']])) {
                            $cod_usuario = $_SESSION['usuario_id'];
                            $titulo_resposta = $_POST['titulo_resposta'];                            
                            $cod_comentario_pai = $_POST['cod_comentario'];                            
                            $contido_resposta = $_POST['respostar_' . $fila['cod']];                            
                            $sql_insert_resposta = "INSERT INTO comentarios (cod_tema, titulo, cod_usuario, comentario, cod_comentario_pai, data_creacion) 
                                                    VALUES ('$tema_seleccionado', '$titulo_resposta', '$cod_usuario', '$contido_resposta', '$cod_comentario_pai', CURRENT_TIMESTAMP)";
                            if (mysqli_query($conexion, $sql_insert_resposta)) {
                                echo '<div class="alert alert-success" role="alert">Resposta engadida correctamente.</div>';
                            } else {
                                echo '<div class="alert alert-danger" role="alert">Erro ao engadir resposta: ' . mysqli_error($conexion) . '</div>';
                            }
                        }
                    }
                    if (isset($_POST['comentar'])) {                        
                        if ($tipo_usuario == 'admin' || $tipo_usuario == 'creador' || $tipo_usuario == 'comentador') {
                            $titulo_comentario = $_POST['titulo_comentario'];
                            $cod_usuario = $_SESSION['usuario_id'];
                            $novo_comentario = $_POST['novo_comentario'];
                            $sql = "INSERT INTO comentarios (titulo, comentario, cod_tema, cod_usuario, data_creacion)
                            VALUES ('$titulo_comentario', '$novo_comentario', '$tema_seleccionado', '$cod_usuario', CURRENT_TIMESTAMP)";

                            if (!mysqli_query($conexion, $sql)) {
                                echo 'Erro ao tentar insertar na táboa: ' . mysqli_error($conexion);
                            } else {
                                echo '<div class="alert alert-success" role="alert">Comentario engadido correctamente.</div>';
                            }
                        } else {
                            echo '<div class="alert alert-warning" role="alert">Non tes permiso para comentar.</div>';
                        }
                    }
                    if (isset($_POST['gardar'])) {
                        $cod_comentario = $_POST['cod_comentario'];
                        $novo_titulo = $_POST['novo_titulo'];
                        $novo_comentario = $_POST['novo_comentario'];
                        $sql = "UPDATE `comentarios` SET `titulo` = '$novo_titulo', `comentario` = '$novo_comentario' WHERE `comentarios`.`cod` = '" . $cod_comentario . "'";
                        mysqli_query($conexion, $sql);
                        echo '<div class="alert alert-success" role="alert">Comentario editado correctamente.</div>';
                    }
                    if (isset($_POST['eliminar_comentario'])) {
                        $cod_comentario = $_POST['cod_comentario'];
                        $sql = "DELETE FROM `comentarios` WHERE `comentarios`.`cod` = '$cod_comentario'";
                        mysqli_query($conexion, $sql);

                        if (!mysqli_query($conexion, $sql)) {
                            echo 'Erro ao intentar entrar na táboa: ' . mysqli_error($conexion);
                        } else {
                            $cod_usuario = $_SESSION['usuario_id'];                            
                            $sql_actualizar_n_post = "UPDATE usuarios SET n_post = n_post - 1 WHERE id = $cod_usuario";
                            mysqli_query($conexion, $sql_actualizar_n_post);

                            echo '<div class="alert alert-success" role="alert">Comentario eliminado correctamente.</div>';
                        }
                    }
                    if (isset($_POST['non'])) {
                        echo '<div class="alert alert-primary" role="alert">
                            Cancelación de eliminación.
                         </div>';
                    }                    
                    if ($tipo_usuario == 'admin' || $tipo_usuario == 'creador' || $tipo_usuario == 'comentador') {
                        echo '<br><form method="post" class="ml-5">';
                        echo '<h5>Engadir un comentario</h5>';
                        echo '<div class="row">';
                        echo '<div class="col-md-8">';
                        echo '<input type="text" class="form-control ml-2" name="titulo_comentario" placeholder="Inserte título do comentario">';
                        echo '</div>';
                        echo '<div class="col-md-8 mt-3">';
                        echo '<textarea class="form-control" name="novo_comentario" placeholder="Engadir comentario"></textarea>';
                        echo '</div><br>';
                        echo '<div class="col-md-2 mt-3">';
                        echo '<input type="submit" class="btn btn-primary" name="comentar" value="Comentar">';
                        echo '</div>';
                        echo '</div>';
                        echo '</form>';
                    } else {
                        echo '<div class="alert alert-warning" role="alert">Non tes permiso para comentar.</div>';
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