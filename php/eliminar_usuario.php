<?php

$user_id_del = limpiar_cadena($_GET['user_id_del']);

//verificando usuario
$check_usuario = conexion();
$check_usuario = $check_usuario->query("SELECT usuario_id FROM usuario WHERE usuario_id = '$user_id_del'");

if ($check_usuario->rowCount() == 1) {
    //verificando usuario
    $check_producto = conexion();
    $check_producto = $check_producto->query("SELECT usuario_id FROM producto WHERE usuario_id = '$user_id_del' LIMIT 1");

    if ($check_producto->rowCount() <= 1) {
        $eliminar_usuario = conexion();
        $eliminar_usuario = $eliminar_usuario->prepare("DELETE FROM usuario WHERE usuario_id = :user_id_del");
        $eliminar_usuario->execute([":user_id_del" => $user_id_del]);

        if($eliminar_usuario->rowCount() == 1){
            echo '<div class="notification is-success is-light">
            <strong>¡Listo!</strong><br>
            El usuario ha sido eliminado.
            </div>';
        }else{
            echo '<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No se puedo eliminar el usuario, intentelo de nuevo.
            </div>';
        }
        $eliminar_usuario = null;
    } else {
        echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El usuario tiene productos registrados y no es posible eliminarlo.
        </div>';
    }
        $check_producto = null;
} else {
    echo '<div class="notification is-danger is-light">
    <strong>¡Ocurrio un error inesperado!</strong><br>
    El usuario que intenta eliminar no existe.
    </div>';
}
$check_usuario = null;