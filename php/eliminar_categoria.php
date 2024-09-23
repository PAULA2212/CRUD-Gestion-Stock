<?php 

$categoria_id_del = limpiar_cadena($_GET['categoria_id_del']);

//verificando categoria
$check_categoria = conexion();
$check_categoria = $check_categoria->query("SELECT categoria_id FROM categoria WHERE categoria_id = '$categoria_id_del'");

if ($check_categoria->rowCount() == 1) {
    //verificando usuario
    $check_producto = conexion();
    $check_producto = $check_producto->query("SELECT categoria_id FROM producto WHERE categoria_id = '$categoria_id_del' LIMIT 1");

    if ($check_producto->rowCount() <= 1) {
        $eliminar_usuario = conexion();
        $eliminar_usuario = $eliminar_usuario->prepare("DELETE FROM categoria WHERE categoria_id = :categoria_id_del");
        $eliminar_usuario->execute([":categoria_id_del" => $categoria_id_del]);

        if($eliminar_usuario->rowCount() == 1){
            echo '<div class="notification is-success is-light">
            <strong>¡Listo!</strong><br>
            La categoria ha sido eliminada.
            </div>';
        }else{
            echo '<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No se pudo eliminar la categoria, intentelo de nuevo.
            </div>';
        }
        $eliminar_usuario = null;
    } else {
        echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        La categoria tiene productos registrados y no es posible eliminarlo.
        </div>';
    }
        $check_producto = null;
} else {
    echo '<div class="notification is-danger is-light">
    <strong>¡Ocurrio un error inesperado!</strong><br>
    La categoria que intenta eliminar no existe.
    </div>';
}
$check_categoria = null;
