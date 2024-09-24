<div class="container is-fluid mb-6">
    <h1 class="title">Productos</h1>
    <h2 class="subtitle">Lista de productos</h2>
</div>

<div class="container pb-6 pt-6">
    <?php
    require_once('./php/main.php');

    //eliminar producto:
    if (isset($_GET['producto_id_del'])) {
        require_once("./php/eliminar_producto.php");
    }

    //pasar la pagina de forma dinamica
    if (!isset($_GET['page'])) {
        $pagina = 1;
    } else {
        $pagina = (int) $_GET['page'];
        if ($pagina <= 1) {
            $pagina = 1;
        }
    };
    $categoria_id = (isset($_GET['categoria_id'])) ? $_GET['categoria_id'] : 0 ;
    $pagina = limpiar_cadena($pagina);
    $url = "index.php?vista=producto_lista&page=";
    $registros = 15;
    $busqueda = "";
    require_once('./php/producto_listar.php');

    ?>


</div>