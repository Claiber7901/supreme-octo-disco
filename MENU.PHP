<!--Menu de navegacion-->
<header data-bs-theme="dark">
  <div class="collapse text-bg-dark" id="navbarHeader">
    <div class="container">
      <div class="row">
        <div class="col-sm-8 col-md-7 py-4">
        <h4>Acerca de nosotros</h4>
          <p class="text-body-secondary">Somos tienda líder en tecnología ubicada en el corazón de San Salvador. Fundada en 2010, TechShop ha crecido rápidamente hasta convertirse en el destino preferido para los entusiastas de la tecnología en el país. Ofrecemos una amplia gama de productos, desde las últimas laptops y smartphones hasta accesorios de gaming y componentes para PC. Nuestro compromiso con la calidad y el servicio al cliente nos distingue, garantizando que siempre encuentres lo mejor en tecnología a precios competitivos.</p>
        </div>
        <div class="col-sm-4 offset-md-1 py-4">
          <h4>Contact</h4>
          <ul class="list-unstyled">
            <li><a href="https://www.facebook.com/profile.php?id=61562957910790" class="text-white">Follow on Twitter</a></li>
            <li><a href="https://x.com/techshop?s=21" class="text-white">Like on Facebook</a></li>
            <li><a href="mailto:proyectoporgra@gmail.com" class="text-white">Email me</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
  <div class="navbar navbar-dark bg-dark shadow-sm">
    <div class="container d-flex align-items-center">
      <a href="TechShopIndex.php" class="navbar-brand d-flex align-items-center"> 
        <strong>TechShop</strong>
      </a>
      <ul class="navbar-nav d-flex flex-row ms-3">
        <li class="nav-item">
            <a href="index.php" class="nav-link active">Catálogo</a>
        </li>

        <!--
        <li class="nav-item ms-3">
            <a href="#" class="nav-link ">Contacto</a>
        </li>-->
      </ul>
      <div class="ms-auto d-flex align-items-center">
    <a href="checkout.php" class="btn btn-primary me-2">
        <i class="bi bi-cart"></i>
        <span id="num_cart" class="badge bg_secondary"><?php echo $num_cart ?></span>
    </a>

    <!-- SESION ACTUAL DE USUARIO -->
    <?php if (isset($_SESSION['user_id'])) { ?>
        <div class="dropdown">
            <button class="btn btn-success dropdown-toggle" id="btn_session" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person"></i> <?php echo $_SESSION['user_name']; ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="miCuenta.php">Mi cuenta</a></li>
                <li><a class="dropdown-item" href="compras.php">Mis compras</a></li>
                <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
            </ul>
        </div>
    <?php } else { ?>
        <a href="login.php" class="btn btn-success me-2"><i class="bi bi-person"></i> Ingresar</a>
        <a href="registro.php" class="btn btn-success"><i class="bi bi-person-vcard"></i> Registrarse</a>
    <?php } ?>
</div>

      
      <button class="navbar-toggler ms-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
    </div>
  </div>
</header>