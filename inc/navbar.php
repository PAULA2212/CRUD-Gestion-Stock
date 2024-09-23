<nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-item" href="index.php?vista=home">
            <img src="./img/new-php-logo.png" width="100" height="28" />
        </a>

        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navbarBasicExample" class="navbar-menu">
        <div class="navbar-start">

            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                    Usuarios
                </a>

                <div class="navbar-dropdown">
                    <a class="navbar-item" href="./index.php?vista=nuevo_usuario">
                        Nuevo
                    </a>
                    <a class="navbar-item" href="./index.php?vista=lista_usuarios">
                        Lista
                    </a>
                    <a class="navbar-item" href="./index.php?vista=buscar_usuario">
                        Buscar
                    </a>
                </div>
            </div>
            <div class="navbar-item has-dropdown is-hoverable">
                <a class="navbar-link">
                    Categorias
                </a>

                <div class="navbar-dropdown">
                    <a class="navbar-item" href="./index.php?vista=categoria_nueva">
                        Nuevo
                    </a>
                    <a class="navbar-item" href="./index.php?vista=categoria_lista">
                        Lista
                    </a>
                    <a class="navbar-item" href="./index.php?vista=categoria_buscar">
                        Buscar
                    </a>
                </div>
            </div>
            <div class="navbar-start">
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link">
                        Productos
                    </a>

                    <div class="navbar-dropdown">
                        <a class="navbar-item">
                            Nuevo
                        </a>
                        <a class="navbar-item">
                            Lista
                        </a>
                        <a class="navbar-item">
                            Buscar
                        </a>
                        <a class="navbar-item">
                            Por categoria
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="navbar-end">
            <div class="navbar-item">
                <div class="buttons">
                    <a class="button is-primary is-rounded" href="index.php?vista=actualizar_usuario&user_id_up=<?php echo $_SESSION['id'];?>">
                        <strong>Mi cuenta</strong>
                    </a>
                    <a class="button is-light" href="./index.php?vista=cerrar_sesion">
                        Salir
                    </a>
                </div>
            </div>
        </div>
    </div>

</nav>