<?php
	// ejecutamos las queries para leer los usuarios y los roles
	$result_roles = query($conn, "SELECT * FROM rol;");
	$result_users = query($conn, "SELECT * FROM usuario;");
	// array de roles
	$roles_array = array();


	//////////////////////////////////////////////////////////////////
	/////////////////////// En caso Update ///////////////////////////
	//////////////////////////////////////////////////////////////////

	// booleano para saber si hubo una redireccion para hacer un update
	$update_mode = isset($_GET['user_id']);
	$update = "";
	// si si hubo redireccion hacer una query de los datos del usuario a actualizar
	if ($update_mode) {
		// hacer la query
		$user_update = query($conn, "SELECT * FROM usuario WHERE id_usuario=".$_GET['user_id'].";");
		// hacer el fetch de los datos
		$update = $user_update->fetch_assoc();
	}
?>

<style type="text/css">
	.margin-left { margin-left: 5%; }
</style>

<div class="col s12">
	<!-- titulo -->
	<div class="row center-align">
		<h2>Gestión de Usuarios</h2>
	</div>
	<!-- formulario -->
	<div class="row">
		<form class="col s12" method="POST" action="actions/usuarios.php<?php echo $update_mode ? "?user_update_id=".$_GET['user_id'] : "" ?>">
			<!-- inputs de nombre y correo -->
			<div class="row">
				<div class="input-field col s4 offset-s1">
					<input id="nombre" type="text" class="validate" name="name" value="<?php echo $update_mode ? $update['nombre'] : ""; ?>">
					<label for="nombre">Nombre</label>
		        </div>
		        <div class="input-field col s4 offset-s1">
					<input id="correo" type="text" class="validate" name="email" value="<?php echo $update_mode ? $update['correo'] : ""; ?>">
					<label for="correo">Correo</label>
		        </div>
		    </div>
			<!-- inputs de contraseña -->
		    <div class="row">
				<div class="input-field col s4 offset-s1">
					<input id="password" type="password" class="validate" name="password">
					<label for="password"><?php echo $update_mode ? "Nueva " : "" ?>Contraseña</label>
		        </div>
		        <div class="input-field col s4 offset-s1">
					<input id="repeat" type="password" class="validate" name="repeat">
					<label for="repeat">Confirmar Contraseña</label>
		        </div>
		    </div>
		    <!-- select del rol -->
		    <div class="row">
			    <div class="input-field col s4 offset-s1">
					<select name="role" id="select">
						<option value="" disabled selected>Selecciona el Rol</option>
						<?php
							// iterar las filas del select de roles
							while ($row = $result_roles->fetch_assoc()) {
								// renderizar los roles disponibles
								echo "<option value=\"".$row['id_rol']."\">".$row['rol']."</option>";
								// y agregar a los roles
								$roles_array[] = $row['rol'];
    						}
						?>
					</select>
					<label>Rol de Usuario</label>
				</div>
				<!-- botones de crear y guardar -->
				<div class="input-field col s4 offset-s1">
					<div class="buttons-row row">
						<!-- boton de crear -->
						<button class="<?php echo $update_mode ? "hide " : ""; ?>waves-effect waves-light btn" name="action" type="submit" value="create">
							<i class="material-icons right">create</i>Crear
						</button>
						<!-- boton de guardar cambios -->
						<button class="<?php echo $update_mode ? "" : "hide "; ?>margin-left waves-effect waves-light btn" name="action" value="update" type="submit">
							<i class="material-icons right">save</i>Guardar
						</button>
						<!-- boton de cancelar actualizacion, normalmente escondido -->
						<a href="dashboard.php" class="<?php echo $update_mode ? "" : "hide "; ?> margin-left waves-effect waves-light btn red">
							<i class="material-icons right">cancel</i>Cancelar
						</a>
					</div>
				</div>
			</div>
		</form>
	</div>
	<!-- subtitulo -->
	<div class="row center-align">
		<h4>Usuarios Disponibles</h4>
	</div>
	<!-- listado de usuarios -->
	<div class="row">
		<div class="col s12">
			
			<table class="striped">
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Correo</th>
						<th>Rol</th>
						<th>Acciones</th>
					</tr>
				</thead>

				<tbody>
					<?php 
						// iterar las filas del select de usuarios
						while ($row = $result_users->fetch_assoc()) {
							echo "<tr>";
							// imprimir el nombre y el correo
							echo "<td>".$row['nombre']."</td>";
							echo "<td>".$row['correo']."</td>";
							// imprimir el rol
							echo "<td>".$roles_array[$row['id_rol']-1]."</td>";
							// y los botones
							echo "<td>";
							// boton para editar NO ES DE TYPE SUBMIT
							echo "<a class=\"blue waves-effect waves-light btn\" href=\"?user_id=".$row["id_usuario"]."\"><i class=\"material-icons right\">settings</i>Editar</a>";
							// boton para eliminar
							echo "<a class=\"red margin-left waves-effect waves-light btn\" href=\"actions/usuarios.php?user_id=".$row["id_usuario"]."\"><i class=\"material-icons right\">delete</i>Eliminar</a>";
							// y ya lo que falta de la tabla
							echo "</td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	// PERDON, TUVE QUE USARLO
	// funcion de jquery para iniciar el select
	$(document).ready(function(){
		// esta linea preselecciona el select
		// $('select').val("2");
		<?php
			// si se va a actualizar
			if ($update_mode) {
				// preseleccionar el rol
				echo "$('select').val(\"".$update['id_rol']."\");";
			}
		?>
		// inicializar el select
    	$('select').formSelect();
  	});
</script>