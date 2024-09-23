<?php

require('main.php');

$nombre = limpiar_cadena($_POST['nombre']);
$apellido = limpiar_cadena($_POST['apellido']);
$usuario = limpiar_cadena($_POST['usuario']);
$email = limpiar_cadena($_POST['email']);
$clave_1 = limpiar_cadena($_POST['clave_1']);
$clave_2 = limpiar_cadena($_POST['clave_2']);

if ($nombre == "" or $apellido == "" or $usuario == "" or $clave_1 == "" or $clave_2 == "") {
    echo '<div class="notification is-danger is-light">
    <strong>¡Ocurrio un error inesperado!</strong><br>
    No has llenado todos los campos que son obligatorios
    </div>';
    exit();
}

if (verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $nombre)) {
    echo '<div class="notification is-danger is-light">
    <strong>¡Ocurrio un error inesperado!</strong><br>
    El nombre no es valido
    </div>';
    exit();
}

if (verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}", $apellido)) {
    echo '<div class="notification is-danger is-light">
    <strong>¡Ocurrio un error inesperado!</strong><br>
    El apellido no es valido
    </div>';
    exit();
}

if (verificar_datos("[a-zA-Z0-9]{4,20}", $usuario)) {
    echo '<div class="notification is-danger is-light">
    <strong>¡Ocurrio un error inesperado!</strong><br>
    El usuario no es valido
    </div>';
    exit();
}

if (verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave_1) || verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $clave_2)) {
    echo '<div class="notification is-danger is-light">
    <strong>¡Ocurrio un error inesperado!</strong><br>
    La clave no es valida
    </div>';
    exit();
}

//verificando el email
if ($email != "") {
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $check_email = conexion();
        $check_email = $check_email->query("SELECT usuario_email FROM usuario WHERE usuario_email = '$email'");
        if ($check_email->rowCount() > 0) {
            echo '<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El email ya esta registrado
            </div>';
            exit();
        }
        $check_email = null; //cierro la conexion
    } else {
        echo '<div class="notification is-danger is-light">
        <strong>¡Ocurrio un error inesperado!</strong><br>
        El email no es valido
        </div>';
        exit();
    }
}

//verificando el usuario:

        $check_usuario = conexion();
        $check_usuario = $check_usuario->query("SELECT usuario_usuario FROM usuario WHERE usuario_usuario = '$usuario'");
        if ($check_usuario->rowCount() > 0) {
            echo '<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            El usuario ya esta registrado
            </div>';
            exit();
        }
        $check_usuario = null; //cierro la conexion


//verificando las claves 

if ($clave_1 != $clave_2) {
    echo '<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            Las claves no coinciden
            </div>';
    exit();
} else {
    $clave = password_hash($clave_1, PASSWORD_BCRYPT, ["cost" => 10]);
}


//guardando los datos:

$guardar_usuario = conexion();

/* $guardar_usuario = $guardar_usuario->query("INSERT INTO usuarios (usuario_nombre, usuario_apellido, usuario_usuario, usuario_clave, 
usuario_email) VALUES ('$nombre','$apellido','$usuario','$clave','$email');"); */

//evitando inyeccion sql
$guardar_usuario = $guardar_usuario->prepare("INSERT INTO usuario (usuario_nombre, usuario_apellido, usuario_usuario, usuario_clave, 
usuario_email) VALUES (:nombre,:apellido,:usuario,:clave,:email)");

$marcadores = [
    ":nombre" => $nombre,
    ":apellido" => $apellido,
    ":usuario" => $usuario,
    ":clave" => $clave,
    ":email" => $email
];

$guardar_usuario->execute($marcadores);

if ($guardar_usuario->rowCount() == 1) {
    echo '<div class="notification is-success is-light">
            <strong>¡LISTO!</strong><br>
            Se ha registrado con exito el nuevo usuario
            </div>';
    exit();
} else {
    echo '<div class="notification is-danger is-light">
            <strong>¡Ocurrio un error inesperado!</strong><br>
            No se puedo registrar el nuevo usuario
            </div>';
    exit();
}

$guardar_usuario = null;
