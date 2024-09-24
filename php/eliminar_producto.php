<?php

$producto_id_del = limpiar_cadena($_GET['producto_id_del']);

//verificando producto
$check_producto = conexion();
$check_producto = $check_producto->query("SELECT * FROM producto WHERE producto_id = '$producto_id_del'");

if ($check_producto->rowCount() == 1) {
    $datos = $check_producto->fetch();
        $eliminar_producto = conexion();
        $eliminar_producto = $eliminar_producto->prepare("DELETE FROM producto WHERE producto_id = :producto_id_del");
        $eliminar_producto->execute([":producto_id_del" => $producto_id_del]);

        if($eliminar_producto->rowCount() == 1){
            if(is_file("./img/producto/".$datos['producto_foto'])){
                chmod("./img/producto/".$datos['producto_foto'], 0777);
                unlink("./img/producto/".$datos['producto_foto']);
            }
            echo '<div class="notification is-success is-light">
            <strong>¡Listo!</strong><br>
            El producto ha sido eliminado.
            </div>';
        }else{
            echo '<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No se pudo eliminar el producto, intentelo de nuevo.
            </div>';
        }
        $eliminar_producto = null;

} else {
    echo '<div class="notification is-danger is-light">
    <strong>¡Ocurrio un error inesperado!</strong><br>
    El producto que intenta eliminar no existe.
    </div>';
}
$check_producto = null;