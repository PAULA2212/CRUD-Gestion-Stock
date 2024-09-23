<?php
require("./inc/session_start.php")
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <?php
    include("./inc/head.php");
    ?>
</head>

<body>
    <?php
    //si la variable no esta definida o esta vacia, vista es login
    if (!isset($_GET['vista']) || $_GET['vista'] == "") {
        $_GET['vista'] = "login";
    }

    //si existe el archivo y su valor no es login y no es 404 
    if (is_file("./vistas/" . $_GET['vista'] . ".php") && $_GET['vista'] != "login" && $_GET['vista'] != "404") {
        //forzar cierre de sesion
        if ((!isset($_SESSION['id'])) or (empty($_SESSION['id']))) {
            include("./vistas/cerrar_sesion.php");
            exit();
        }
        include("./inc/navbar.php");
        include("./vistas/" . $_GET['vista'] . ".php");
        include("./inc/script.php");
    } else {
        if ($_GET['vista'] == "login") {
            include("./vistas/login.php");
        } else {
            include("./vistas/404.php");
        }
    }



    ?>
</body>

</html>