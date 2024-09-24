<?php

require_once "main.php";

/*== Almacenando id ==*/
$id_producto = limpiar_cadena($_POST['img_up_id']);

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

$img_dir = "../img/producto/";
if(!isset($_FILES['producto_foto']['name']) && $_FILES['producto_foto']['size']==0){
    echo '
        <div class="notification is-danger is-light">
            <strong>¡Ocurrió un error inesperado!</strong><br>
            No se ha selecionado ninguna imagen valida.
        </div>';
    exit();
}
    if(!file_exists($img_dir)){
        if(!mkdir($img_dir,0777)){
            echo '<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No se pudo crear el directorio para guardar la imagen
            </div>';
            exit();    
        }
    }

    chmod($img_dir, 0777);
    if(mime_content_type($_FILES['producto_foto']['tmp_name']) != "image/jpeg" && mime_content_type($_FILES['producto_foto']['tmp_name']) != "image/png"){
        echo '<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El formato de la imagen no es admitido, utiliza un jpeg o png.
            </div>';
            exit();
    }
    //verificar el peso de la imagen:
    if(($_FILES['producto_foto']['size'])/1024 > 3072){
        echo '<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            La imagen seleccionada supera el peso permitido.
            </div>';
            exit();
    }

    //extension de la imagen:
    switch(mime_content_type($_FILES['producto_foto']['tmp_name'])){
        case 'image/png':
            $img_ext = ".png";
            break;
        case 'image/jpeg':
            $img_ext = ".jpg";
    }

    $img_nombre=renombrar_fotos($datos['producto_nombre']);
    $foto= $img_nombre.$img_ext;
     //moviendo imagen al directorio:
     if(!move_uploaded_file($_FILES['producto_foto']['tmp_name'],$img_dir.$foto)){
        echo '<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            La imagen seleccionada no se ha podido mover al directorio de imagenes.
            </div>';
            exit();
    }
    if(is_file($img_dir.$datos['producto_foto']) && $datos['producto_foto'] != $foto){
        chmod($img_dir.$datos['producto_foto'], 0777);
        unlink($img_dir.$datos['producto_foto']);
    }

    
    $actualizar_producto = conexion();

    // evitando inyección SQL
    $actualizar_producto = $actualizar_producto->prepare("UPDATE producto 
    SET producto_foto = :foto
    WHERE producto_id = :id");

    $marcadores = [
        ":foto" => $foto,  // asignando una cadena vacía para eliminar la imagen
        ":id" => $id_producto
    ];

    $actualizar_producto->execute($marcadores);

    if ($actualizar_producto->rowCount() == 1) {
        echo '
            <div class="notification is-success is-light">
                <strong>¡LISTO!</strong><br>
                Se ha actualizado con éxito la foto del producto. Pulsa aceptar para recargar los cambios.
                <p class="has-text-centered pt5 pb-5">
                    <a href="index.php?vista=producto_imagen&producto_id_up=' . $id_producto . '" class="button is-link is-rounded">Aceptar</a>
                </p>
            </div>';
    } else {
        if(is_file($img_dir.$foto)){
            chmod($img_dir.$foto, 0777);
            unlink($img_dir.$foto);
        }
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrió un error inesperado!</strong><br>
                No se pudo actualizar la foto del producto de la base de datos. Pulsa aceptar para recargar los cambios.
                
            </div>';
    }

    $actualizar_producto = null;