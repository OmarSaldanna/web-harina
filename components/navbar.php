<div class="navbar-fixed no-shadow">
  <!-- en la nav se pone el color del rol -->
  <nav class="<?php echo $role['color']; ?>">
    <!-- aqui igual -->
    <div class="nav-wrapper <?php echo $role['color']; ?>">
      <a href="#" class="brand-logo">Harinas Elizondo</a>
      <ul id="nav-mobile" class="right hide-on-med-and-down">
        <!-- renderizar los botones de las vistas -->
        <?php 
          foreach ($role_views as $view) {
            $active_setting = "";
            // ajuste para activar el boton de la vista actual
            if ($current_view == $view) {
              $active_setting = "active";
            }
            // renderizar el boton como el de abajo
            // <li><a href="sass.html">Sass</a></li>
            echo "<li class=\"$active_setting\"><a class=\"nav-button\" href=\"dashboard.php?view=$view\">$view</a></li>\n";
          }
        ?>
        <!-- boton de cerrar sesion -->
        <li><a href="actions/close_session.php" class="waves-effect waves-light btn white black-text">Cerrar Sesión<i class="material-icons right">exit_to_app</i></a></li>
      </ul>
    </div>
  </nav>
</div>