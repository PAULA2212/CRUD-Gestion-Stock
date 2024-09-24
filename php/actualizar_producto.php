<?php

require_once "main.php";

    /*== Almacenando id ==*/
    $id=limpiar_cadena($_POST['producto_id']);

    /*== Verificando producto ==*/
	$check_producto=conexion();
	$check_producto=$check_producto->query("SELECT * FROM producto WHERE producto_id='$id'");

    if($check_producto->rowCount()<=0){
    	echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                EL producto no existe en el sistema
            </div>
        ';
        exit();
    }else{
    	$datos=$check_producto->fetch();
    }
    $check_producto=null;

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
    if($datos['producto_codigo'] != $codigo){
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
    }
    //verificando el nombre
    if($datos['producto_nombre'] != $nombre){
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
    }
    //verificando categoria

    if($datos['categoria_id'] != $categoria){
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
    }
    
    //actualizando los valores:

    $actualizar_producto = conexion();

    //evitando inyeccion sql
    $actualizar_producto = $actualizar_producto->prepare("UPDATE producto 
    SET producto_codigo = :codigo, 
        producto_nombre = :nombre, 
        producto_precio = :precio, 
        producto_stock = :stock, 
        categoria_id = :categoria 
    WHERE producto_id = :id");

    $marcadores = [
        ":codigo" => $codigo,
        ":nombre" => $nombre,
        ":precio" => $precio,
        ":stock" => $stock,
        ":categoria" => $categoria,
        ":id" => $id

    ];

    $actualizar_producto->execute($marcadores);

    if ($actualizar_producto->rowCount() == 1) {

        echo '<div class="notification is-success is-light">
                <strong>¡LISTO!</strong><br>
                Se ha actualizado con exito el nuevo producto
                </div>';
        exit();
    } else {
        echo '<div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo actualizar el producto.
                </div>';
        exit();
    }

    $actualizar_producto = null;

        
    