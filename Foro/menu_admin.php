<?php
/**
 * Este archivo muestra el menú de administración.
 *
 * Permite a los administradores crear nuevos usuarios, categorías y temas.
 *
 * @category Foro
 * @package  Foro
 */

/**
 * @var string $sql La consulta SQL para obtener las categorías.
 * @var mysqli_result|bool $result El resultado de la consulta SQL.
 * @var array $categorias Un array para almacenar las categorías obtenidas de la base de datos.
 */
$sql = "SELECT cod, nome FROM categorias";
$result = mysqli_query($conexion, $sql);
$categorias = [];
if ($result) {
    if (mysqli_num_rows($result) > 0) {
        while ($fila = mysqli_fetch_assoc($result)) {
            $categorias[] = $fila;
        }
    } else {
        echo "Non hai categorías dispoñibeis.";
    }
} else {
    echo "Erro ao obter as categorías: " . mysqli_error($conexion);
}

/**
 * Comprueba si se ha enviado una petición POST.
 */
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    /**
     * Comprueba si se ha enviado el formulario de creación de usuario.
     */
    if (isset($_POST['crear_usuario'])) {
        /**
         * @var string $nome_usuario El nombre del nuevo usuario.
         * @var string $rol_usuario El rol del nuevo usuario.
         * @var string $sql La consulta SQL para insertar el nuevo usuario en la base de datos.
         */
        $nome_usuario = $_POST['nome_usuario'];
        $rol_usuario = $_POST['rol_usuario'];

        $sql = "INSERT INTO `usuarios` (`id`, `usuario`, `contrasinal`, `email`, `n_temas`, `n_post`, `rol_usuario`, `avatar`)
        VALUES (NULL, '$nome_usuario', 'abc123.', '$nome_usuario@gmail.com', '0', '0', '$rol_usuario', '')";

        if (!mysqli_query($conexion, $sql)) {
            echo 'Erro ao intentar entrar na táboa: ' . mysqli_error($conexion);
        } else {
            echo '<div class="alert alert-success" role="alert">Usuario ' . $nome_usuario . ' rexistrado correctamente.</div>';
        }
    }
    /**
     * Comprueba si se ha enviado el formulario de creación de categoría.
     */
    if (isset($_POST['crear_categoria'])) {
        /**
         * @var string $categoria_nova El nombre de la nueva categoría.
         * @var string $descripcion La descripción de la nueva categoría.
         * @var string $sql La consulta SQL para insertar la nueva categoría en la base de datos.
         */
        $categoria_nova = $_POST['nome_categoria'];
        $descripcion = $_POST['descripcion_categoria'];

        $sql = "INSERT INTO categorias (cod, nome, descripcion, data_creacion) VALUES (
            NULL, '$categoria_nova', '$descripcion', CURRENT_TIMESTAMP)";

        if (!mysqli_query($conexion, $sql)) {
            echo 'Erro ao intentar entrar na táboa: ' . mysqli_error($conexion);
        } else {
            echo '<div class="alert alert-success" role="alert">Categoría ' . $categoria_nova . ' creada correctamente.</div>';
        }
    }
    /**
     * Comprueba si se ha enviado el formulario de creación de tema.
     */
    if (isset($_POST['crear_tema'])) {
        /**
         * @var int $categoria_escollida La categoría seleccionada para el nuevo tema.
         * @var string $tema El nombre del nuevo tema.
         * @var string $descripcion_tema La descripción del nuevo tema.
         * @var int $cod_usuario El ID del usuario que crea el tema.
         * @var string $sql La consulta SQL para insertar el nuevo tema en la base de datos.
         */
        $categoria_escollida = $_POST['escoller_categoria'];
        $tema = $_POST['tema'];
        $descripcion_tema = $_POST['descripcion_tema'];
        $cod_usuario = $_SESSION['usuario_id'];

        $sql = "INSERT INTO temas (cod,nome,descripcion,n_resp,data_creacion,cod_categoria,cod_usuario) VALUES (
            null, '$tema', '$descripcion_tema','' ,CURRENT_TIMESTAMP, '$categoria_escollida', '$cod_usuario')";

        if (!mysqli_query($conexion, $sql)) {
            echo 'Erro ao intentar entrar na táboa: ' . mysqli_error($conexion);
        } else {
            // Actualizo o número de temas (n_temas na táboa usuarios) do usuario
            $sql_actualizar_n_temas = "UPDATE usuarios SET n_temas = n_temas + 1 WHERE id = $cod_usuario";
            mysqli_query($conexion, $sql_actualizar_n_temas);

            echo '<div class="alert alert-success" role="alert">Tema ' . $tema . ' creado correctamente.</div>';
        }
    }
}
?>

<h4>Crear novo usuario:</h4>
<form action="" method="post">
    <label for="nome_usuario">Nome do usuario:</label>
    <input type="text" id="nome_usuario" name="nome_usuario" required>
    <label for="rol_usuario">Rol de usuario: </label>
    <select id="rol_usuario" name="rol_usuario">
        <option value="creador">Creador</option>
        <option value="comentador">Comentador</option>
        <option value="lector">Lector</option><br>
    </select><br>
    <input type="submit" value="Crear usuario" name="crear_usuario"><br>
</form>
<h4>Crear nova categoría:</h4>
<form action="" method="post">
    <label for="nome_categoria">Categoría</label>
    <input type="text" name="nome_categoria" id="nome_categoria" required>
    <label for="descripcion_cat">Descripción</label>
    <textarea name="descripcion_cat" id="descripcion_cat"></textarea><br>
    <input type="submit" value="Crear categoría" name="crear_categoria"><br>
</form>
<h4>Crear novo tema:</h4>
<form action="" method="post">
    <label for="escoller_categoria">Escolla categoría</label>
    <select name="escoller_categoria" id="escoller_categoria" required>
        <?php foreach ($categorias as $categoria) : ?>
            <option value="<?= $categoria['cod'] ?>"><?= $categoria['nome'] ?></option>
        <?php endforeach; ?>
    </select>
    <label for="tema">Tema</label>
    <input type="text" name="tema" id="tema" required><br><br>
    <label for="descripcion_tema">Descripción</label>
    <textarea name="descripcion_tema" id="descripcion_tema"></textarea><br>
    <input type="submit" value="Crear tema" name="crear_tema">
</form>