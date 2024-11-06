<!-- vamos a usar igual la variable $role para renderizar los datos -->

<div class="sidebar col s2 <?php echo $role['color']; ?>">
	<div class="row">
		<img class="img-sidebar" src="assets/icon.png">
	</div>
	<div class="row">
		<div class="col s12 center-align">
			<h4 class="white-text title"><?php echo $role['rol']; ?></h4>
		</div>
	</div>
	<div class="row">
		<div class="col s10 offset-s1 center-align">
			<h6 class="white-text"><?php echo $_SESSION['nombre']; ?></h6>
		</div>
	</div>
	<div class="row">
		<div class="col s10 offset-s1 center-align">
			<h6 class="white-text"><?php echo $_SESSION['correo']; ?></h6>
		</div>
	</div>
</div>