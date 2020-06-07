<!--Navbar -->
<nav class="mb-1 navbar navbar-expand-lg navbar-dark danger-color lighten-1 mb-5">
    <li class="navbar-brand" href="#">Magazzino merci</li>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-555"
            aria-controls="navbarSupportedContent-555" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent-555">
        <ul class="navbar-nav mr-auto">

            <!-- Categorie -->
            <li class="nav-item <?php echo ($_SESSION['nav_bar_item'] == FORNITORI)? 'active': null ?>">
                <a class="nav-link" href="<?php echo URL . 'supplier' ?>">Fornitori</a>
            </li>

            <!-- Catalogo -->
            <li class="nav-item <?php echo ($_SESSION['nav_bar_item'] == CATALOGO)? 'active': null ?>">
                <a class="nav-link" href="<?php echo URL . 'catalog' ?>">Catalogo</a>
            </li>

        </ul>
        <ul class="navbar-nav ml-auto nav-flex-icons">
            <li class="nav-item avatar dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-55" data-toggle="dropdown"
                   aria-haspopup="true" aria-expanded="false"><?php echo strtolower($_SESSION['email']) ?>
                </a>
                <div class="dropdown-menu dropdown-menu-lg-right dropdown-secondary"
                     aria-labelledby="navbarDropdownMenuLink-55">
                    <a class="dropdown-item" href="<?php echo URL . 'user/logout' ?>">Logout</a>
                </div>
            </li>
        </ul>
    </div>
</nav>
<!--/.Navbar -->