<?php

require_once "main.php";

/*== Almacenando id ==*/
$id_producto = limpiar_cadena($_POST['img_del_id']);

/*== Verificando producto ==*/
$check_producto = conexion();
$check_producto = $check_producto->query("SELECT * FROM producto WHERE producto_id='$id_producto'");

if ($check_producto->rowCount() == 1) {
    $datos = $check_producto->fetch();
} else {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            La imagen del producto no existe en el sistema.
        </div>';
    exit();
}
$check_producto = null;

/*== Directorio de imágenes ==*/
$img_dir = "../img/producto/";
chmod($img_dir, 0777);
if (is_file($img_dir . $datos['producto_foto'])) {
    chmod($img_dir . $datos['producto_foto'],0777);
    if (!unlink($img_dir . $datos['producto_foto'])) {
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                La imagen del producto no se pudo eliminar del sistema.
            </div>';
        exit();
    }
}

$actualizar_producto = conexion();

// evitando inyección SQL
$actualizar_producto = $actualizar_producto->prepare("UPDATE producto 
SET producto_foto = :foto
WHERE producto_id = :id");

$marcadores = [
    ":foto" => "",  // asignando una cadena vacía para eliminar la imagen
    ":id" => $id_producto
];

$actualizar_producto->execute($marcadores);

if ($actualizar_producto->rowCount() == 1) {
    echo '
        <div class="notification is-success is-light">
            <strong>¡LISTO!</strong><br>
            Se ha eliminado con éxito la foto del producto. Pulsa aceptar para recargar los cambios.
            <p class="has-text-centered pt5 pb-5">
                <a href="index.php?vista=producto_imagen&producto_id_up=' . $id_producto . '" class="button is-link is-rounded">Aceptar</a>
            </p>
        </div>';
} else {
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No se pudo eliminar la foto del producto de la base de datos. Pulsa aceptar para recargar los cambios.
            <p class="has-text-centered pt5 pb-5">
                <a href="index.php?vista=producto_imagen&producto_id_up=' . $id_producto . '" class="button is-link is-rounded">Aceptar</a>
            </p>
        </div>';
}

$actualizar_producto = null;