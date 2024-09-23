<?php
    require_once("main.php");


    $nombre = limpiar_cadena($_POST['categoria_nombre']);
    $ubicacion = limpiar_cadena($_POST['categoria_ubicacion']);

    if ($nombre == "") {
        echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        No has llenado todos los campos que son obligatorios
        </div>';
        exit();
    }

    if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{4,50}", $nombre)) {
        echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El nombre no es valido
        </div>';
        exit();
    }
    if($ubicacion!=""){
        if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{5,150}", $ubicacion)) {
            echo '<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            La ubicacion no coincide con el formato solicitado
            </div>';
            exit();
        }
    }

    //verificando el nombre de categoria:

        $check_nombre = conexion();
        $check_nombre = $check_nombre->query("SELECT categoria_nombre FROM categoria WHERE categoria_nombre = '$nombre'");
        if ($check_nombre->rowCount() > 0) {
            echo '<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El nombre de la categoria ya esta registrado
            </div>';
            exit();
        }
        $check_nombre = null; //cierro la conexion




 $guardar_categoria = conexion();
//evitando inyeccion sql
$guardar_categoria = $guardar_categoria->prepare("INSERT INTO categoria (categoria_nombre, categoria_ubicacion) VALUES (:nombre,:ubicacion)");

$marcadores = [
    ":nombre" => $nombre,
    ":ubicacion" => $ubicacion,

];

$guardar_categoria->execute($marcadores);

if ($guardar_categoria->rowCount() == 1) {
    echo '<div class="notification is-success is-light">
            <strong>¡LISTO!</strong><br>
            Se ha registrado con exito la nueva categoria
            </div>';
    exit();
} else {
    echo '<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No se pudo guardar la nueva categoria
            </div>';
    exit();
}

$guardar_categoria = null;