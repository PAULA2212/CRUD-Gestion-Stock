<?php
require_once('main.php');
require_once('../inc/session_start.php');

//almacenando datos:
$codigo = limpiar_cadena($_POST['producto_codigo']);
$nombre = limpiar_cadena($_POST['producto_nombre']);
$precio = limpiar_cadena($_POST['producto_precio']);
$stock = limpiar_cadena($_POST['producto_stock']);
$categoria = limpiar_cadena($_POST['producto_categoria']);

if ($codigo == "" or $nombre == "" or $precio == "" or $stock == "" or $categoria == "") {
    echo '<div class="notification is-danger is-light">
    <strong>¡Ocurrio un error inesperado!</strong><br>
    No has llenado todos los campos que son obligatorios
    </div>';
    exit();
}
if (verificar_datos("[a-zA-Z0-9- ]{1,70}", $codigo)) {
    echo '<div class="notification is-danger is-light">
    <strong>¡Ocurrio un error inesperado!</strong><br>
    El codigo no es valido
    </div>';
    exit();
}
if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}", $nombre)) {
    echo '<div class="notification is-danger is-light">
    <strong>¡Ocurrio un error inesperado!</strong><br>
    El nombre no es valido
    </div>';
    exit();
}
if (verificar_datos("[0-9.]{1,25}", $precio)) {
    echo '<div class="notification is-danger is-light">
    <strong>¡Ocurrio un error inesperado!</strong><br>
    El precio no es valido
    </div>';
    exit();
}
if (verificar_datos("[0-9]{1,25}", $stock)) {
    echo '<div class="notification is-danger is-light">
    <strong>¡Ocurrio un error inesperado!</strong><br>
    El stock no es valido
    </div>';
    exit();
}
//verificando codigo:
    $check_codigo = conexion();
    $check_codigo = $check_codigo->query("SELECT producto_codigo FROM producto WHERE producto_codigo = '$codigo'");
    if ($check_codigo->rowCount() > 0) {
        echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El codigo de barras ya esta registrado
        </div>';
        exit();
    }
    $check_codigo = null; //cierro la conexion

//verificando nombre
$check_nombre = conexion();
    $check_nombre = $check_nombre->query("SELECT producto_nombre FROM producto WHERE producto_nombre = '$nombre'");
    if ($check_nombre->rowCount() > 0) {
        echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El nombre de producto ya esta registrado
        </div>';
        exit();
    }
    $check_nombre = null; //cierro la conexion

//verificando la categoria
$check_categoria = conexion();
    $check_categoria = $check_categoria->query("SELECT categoria_id FROM categoria WHERE categoria_id = '$categoria'");
    if ($check_categoria->rowCount() <= 0) {
        echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        La categoria que intenta ingresar no existe, registrela primero
        </div>';
        exit();
    }
    $check_categoria = null; //cierro la conexion

//directorio de imagenes:

$img_dir = "../img/producto/";

//comprobar si se selecciono una imagen:

if(isset($_FILES['producto_foto']['name']) && $_FILES['producto_foto']['size']>0){
    //verificando o creando directorio:
        if(!file_exists($img_dir)){
            if(!mkdir($img_dir,0777)){
                echo '<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo crear el directorio para guardar la imagen
                </div>';
                exit();    
            }
        }
        //verificar formato de la imagen:
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

        //condecer permisos de lectura y escritura en la carpeta:
        chmod($img_dir, 0777);

        $img_nombre=renombrar_fotos($nombre);
        $foto= $img_nombre.$img_ext;

        //moviendo imagen al directorio:
        if(!move_uploaded_file($_FILES['producto_foto']['tmp_name'],$img_dir.$foto)){
            echo '<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La imagen seleccionada no se ha podido mover al directorio de imagenes.
                </div>';
                exit();
        }
}else{
    $foto="";
};

//guardando producto:

//guardando los datos:

$guardar_producto = conexion();

/* $guardar_producto = $guardar_producto->query("INSERT INTO usuarios (usuario_nombre, usuario_apellido, usuario_usuario, usuario_clave, 
usuario_email) VALUES ('$nombre','$apellido','$usuario','$clave','$email');"); */

//evitando inyeccion sql
$guardar_producto = $guardar_producto->prepare("INSERT INTO producto (producto_codigo, producto_nombre, producto_precio, producto_stock, 
producto_foto, categoria_id, usuario_id) VALUES (:codigo,:nombre,:precio,:stock,:imagen, :categoria, :usuario)");

$marcadores = [
    ":codigo" => $codigo,
    ":nombre" => $nombre,
    ":precio" => $precio,
    ":stock" => $stock,
    ":imagen" => $foto,
    ":categoria" => $categoria,
    ":usuario" => $_SESSION['id']
];

$guardar_producto->execute($marcadores);

if ($guardar_producto->rowCount() == 1) {

    echo '<div class="notification is-success is-light">
            <strong>¡LISTO!</strong><br>
            Se ha registrado con exito el nuevo producto
            </div>';
    exit();
} else {
    //eliminamos la imagen ni no hemos podido guardar el producto:
    if(is_file($img_dir.$foto)){
        chmod($img_dir.$foto, 0777);
        unlink($img_dir.$foto);
    }
    echo '<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No se puedo registrar el nuevo producto
            </div>';
    exit();
}

$guardar_producto = null;