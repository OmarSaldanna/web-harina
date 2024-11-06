<?php
	// ejecutamos las queries para leer los usuarios y los roles
	$result_roles = query($conn, "SELECT * FROM rol;");
	
	//Query para leer los pedidos
	$result_pedido = query($conn, "SELECT * FROM pedido;");
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
		$pedido_update = query($conn, "SELECT * FROM pedido WHERE id_pedido=".$_GET['user_id'].";");
		// hacer el fetch de los datos
		$update = $pedido_update->fetch_assoc();
	}

	// 
?>

<style type="text/css">
	.margin-left { margin-left: 5%; }
</style>

<div class="col s12">
	<!-- titulo -->
	<div class="row center-align">
		<h2>Gestión de Pedidos</h2>
	</div>
	<!-- formulario -->
	<div class="row">
		<form class="col s12" method="POST" action="actions/pedidos.php<?php echo $update_mode ? "?user_update_id=".$_GET['user_id'] : "" ?>">
			<!-- inputs de todos los parametros de pedidos -->
			<div class="row">
				<div class="input-field col s4 offset-s1">
					<input id="cantidad_s" type="text" class="validate" name="c_solicitada" value="<?php echo $update_mode ? $update['cantidad_s'] : ""; ?>">
					<label for="cantidad_s">Cantidad Solicitada</label>
		        </div>
		        <div class="input-field col s4 offset-s1">
					<input id="cantidad_e" type="text" class="validate" name="c_entregada" value="<?php echo $update_mode ? $update['cantidad_e'] : ""; ?>">
					<label for="cantidad_e">Cantidad Entregada</label>
		        </div>
		    </div>
	       <div class="row">
	       	<!-- Opción de facturación sí o no -->
		       <div class="input-field col s4 offset-s1">
					<label>
						<!-- Hice que fuera booleana la checkbox, si se selecciona manda 1 y si no 0 -->
				        <input name="factura" id="indeterminate-checkbox" type="checkbox"<?php echo $update_mode && $update['factura'] == "1" ? "checked" : ""; ?>/>
				        <span>Factura</span>
				      </label>
		        </div>

		        <!-- Date picker para la fecha del pedido -->
				<div class="input-field col s4 offset-s1">
				    <input id="datepicker" type="text" class="datepicker" name="f_pedido" 
				           value="<?php echo $update_mode ? $update['f_pedido'] : ""; ?>">
				    <label for="f_pedido">Fecha de Recepción del Pedido</label>
				</div>
			</div>

		    <!-- Para introducir el lote -->
			<div class="row">
				<div class="input-field col s4 offset-s1">
					<input id="id_lote" type="text" class="validate" name="id_lote" value="<?php echo $update_mode ? $update['id_lote'] : ""; ?>">
					<label for="id_lote">Lote</label>
		        </div>

		        <!-- Date picker para la fecha del pedido -->
				<div class="input-field col s4 offset-s1">
				    <input id="datepicker" type="text" class="datepicker" name="f_pedido" 
				           value="<?php echo $update_mode ? $update['f_pedido'] : ""; ?>" readonly>
				    <label for="f_pedido">Fecha de Entrega del Pedido</label>
				</div>
			</div>

		    <!-- Introducir el id de cliente de pedido -->
			<div class="row">
				<div class="input-field col s4 offset-s1">
					<input id="id_cliente" type="text" class="validate" name="id_cliente" value="<?php echo $update_mode ? $update['id_cliente'] : ""; ?>">
					<label for="id_cliente">ID Cliente</label>
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
		<h4>Pedidos Hechos</h4>
	</div>
	<!-- listado de usuarios -->
	<div class="row">
		<div class="col s12">
			
			<table class="striped">
				<thead>
					<tr>
						<th>Cantidad Solicitada</th>
						<th>Cantidad Entregada</th>
						<th>Factura</th>
						<th>Fecha de Pedido</th>
						<th>Fecha de Entrega</th>
						<th>ID Cliente</th>
						<th>ID Lote</th>
					</tr>
				</thead>

				<tbody>
					<?php 
						// iterar las filas del select de usuarios
						while ($row = $result_pedido->fetch_assoc()) {
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

  	// //Esto es para la checkbox de la factura
  	// document.addEventListener('DOMContentLoaded', function() {
    // var checkbox = document.getElementById('indeterminate-checkbox');
    // var hiddenInput = document.getElementById('factura');

    // // Actualiza el valor del campo oculto basado en si el checkbox está marcado o no
    // checkbox.onchange = function() {
    //     hiddenInput.value = this.checked ? "1" : "0";
    // 	};
	// });



  	//Inicialización del select para facturación
  	document.addEventListener('DOMContentLoaded', function() {
	  var elems = document.querySelectorAll('select');
	  var instances = M.FormSelect.init(elems);
	});


  	//Inicialización del date picker
  	document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.datepicker');
    var instances = M.Datepicker.init(elems, {"format":"yyyy-mm-dd"});
    var textArea = document.querySelector('#textarea2');
    var textAreaCounter = document.querySelector('#textarea2 + .character-counter');
    M.textareaAutoResize(textArea);
    M.CharacterCounter.init(textArea);
  });
</script>