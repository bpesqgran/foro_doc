<?php
/**
 * Este archivo muestra el menú del creador.
 *
 * Permite a los creadores crear nuevos temas.
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
<h4>Crear novo tema</h4>
<form action="" method="post">
    <label for="escoller_categoria">Escolla categoría</label>
    <select name="escoller_categoria" id="escoller_categoria" required>
        <?php foreach ($categorias as $categoria) : ?>
            <option value="<?= $categoria['cod'] ?>"><?= $categoria['nome'] ?></option>
        <?php endforeach; ?>
    </select><br>
    <label for="tema">Tema</label>
    <input type="text" name="tema" id="tema" required><br>
    <label for="descripcion_tema">Descripción</label>
    <textarea name="descripcion_tema" id="descripcion_tema"></textarea><br>
    <input type="submit" value="Crear tema" name="crear_tema">
</form>