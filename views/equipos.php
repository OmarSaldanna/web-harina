<?php
	// ejecutamos las queries para leer los usuarios y los roles
	$result_equipo = query($conn, "SELECT equipo.id_equipo, equipo.des_larga, equipo.des_corta, equipo.marca, equipo.modelo, equipo.serie, 
		proveedor.nombre AS proveedor, tipo.tipo AS tipo, equipo.f_adquisicion, equipo.garantia_tipo, 
		equipo.estado, equipo.ubicacion, equipo.garantia_vigencia
		FROM equipo
		JOIN proveedor ON equipo.id_proveedor = proveedor.id_proveedor
		JOIN tipo ON equipo.id_tipo = tipo.id_tipo;");
	$result_proveedor = query($conn, "SELECT * FROM proveedor;");
	$result_tipo = query($conn, "SELECT * FROM tipo;");

	//////////////////////////////////////////////////////////////////
	/////////////////////// En caso Update ///////////////////////////
	//////////////////////////////////////////////////////////////////

	// booleano para saber si hubo una redireccion para hacer un update
	$update_mode = isset($_GET['equipo_id']);
	$update = "";
	// si si hubo redireccion hacer una query de los datos del equipo a actualizar
	if ($update_mode) {
		// hacer la query
		$equipo_update = query($conn, "SELECT * FROM equipo WHERE id_equipo=".$_GET['equipo_id'].";");
		// hacer el fetch de los datos
		$update = $equipo_update->fetch_assoc();
	}
?>

<style type="text/css">
	.margin-left { margin-left: 5%; }
</style>

<div class="col s12">
	<!-- titulo -->
	<div class="row center-align">
		<h2>Gestión de Equipos</h2>
	</div>
	<!-- formulario -->
	<div class="row">
		<form class="col s12" method="POST" action="actions/equipos.php<?php echo $update_mode ? "?equipo_update_id=".$_GET['equipo_id'] : "" ?>">
			<!-- inputs de marca y modelo -->
			<div class="row">
				<div class="input-field col s4 offset-s1">
					<input id="marca" type="text" class="validate" name="marca" value="<?php echo $update_mode ? $update['marca'] : ""; ?>">
					<label for="marca">Marca del Equipo</label>
		        </div>
		        <div class="input-field col s4 offset-s1">
					<input id="modelo" type="text" class="validate" name="modelo" value="<?php echo $update_mode ? $update['modelo'] : ""; ?>">
					<label for="modelo">Modelo</label>
		        </div>
		    </div>
		    <!-- inputs de numero de serie y garantia-->
			<div class="row">
				<div class="input-field col s4 offset-s1">
					<input id="serie" type="text" class="validate" name="serie" value="<?php echo $update_mode ? $update['serie'] : ""; ?>">
					<label for="serie">Numero de Serie</label>
		        </div>
		        <div class="input-field col s4 offset-s1">
					<input id="garantia" type="text" class="validate" name="garantia_tipo" value="<?php echo $update_mode ? $update['garantia_tipo'] : ""; ?>">
					<label for="garantia">Tipo de Garantía</label>
		        </div>
		    </div>
		    <!-- inputs de estado y ubicacion-->
			<div class="row">
				<div class="input-field col s4 offset-s1">
					<input id="estado" type="text" class="validate" name="estado" value="<?php echo $update_mode ? $update['estado'] : ""; ?>">
					<label for="estado">Estado del Equipo</label>
		        </div>
		        <div class="input-field col s4 offset-s1">
					<input id="ubicacion" type="text" class="validate" name="ubicacion" value="<?php echo $update_mode ? $update['ubicacion'] : ""; ?>">
					<label for="ubicacion">Ubicación del Equipo</label>
		        </div>
		    </div>
		    <!-- inputs de descripcion -->
			<div class="row">
				<div class="input-field col s4 offset-s1">
					<textarea id="textarea-corta" class="materialize-textarea" name="des_corta"></textarea>
					<label for="textarea-corta">Descripción Corta</label>
		        </div>
		        <div class="input-field col s4 offset-s1">
					<textarea id="textarea-larga" class="materialize-textarea" name="des_larga"></textarea>
					<label for="textarea-larga">Descripción Larga</label>
		        </div>
		    </div>
		    <div class="row">
		    	<!-- select del tipo -->
			    <div class="input-field col s4 offset-s1">
					<select name="id_tipo" id="select-tipo">
						<option value="" disabled selected>Selecciona el Tipo</option>
						<?php
							// iterar las filas del select de tipos
							while ($row = $result_tipo->fetch_assoc()) {
								// renderizar los tipos disponibles
								// no se porque, pero tuvo que separarse en dos lineas
								echo "<option value=\"".$row['id_tipo']."\">";
								echo $row['tipo'] == 'A' ? "Albeógrafo" : "Farinógrafo"."</option>";
    						}
						?>
					</select>
					<label>Tipo de Equipo</label>
				</div>
				<!-- select del proveedor -->
				<div class="input-field col s4 offset-s1">
					<select name="id_proveedor" id="select-proveedor">
						<option value="" disabled selected>Selecciona el Proveedor</option>
						<?php
							// iterar las filas del select de proveedores
							while ($row = $result_proveedor->fetch_assoc()) {
								// renderizar los proveedores disponibles
								echo "<option value=\"".$row['id_proveedor']."\">".$row['nombre'].".</option>";
    						}
						?>
					</select>
					<label>Proveedor del Equipo</label>
				</div>
			</div>
			<div class="row">
				<!-- los pickers de las fechas de adquisición y garantía -->
				<div class="input-field col s4 offset-s1">
		    		<input name="f_adquisicion" type="text" class="datepicker" id="datepicker-adquisicion">
		    		<label for="datepicker-adquisicion">Fecha de Adquisición</label>
				</div>
				<div class="input-field col s4 offset-s1">
		    		<input name="garantia_vigencia" type="text" class="datepicker" id="datepicker-garantia">
		    		<label for="datepicker-garantia">Vigencia de la Garantía</label>
				</div>
			</div>
			<div class="row">
				<!-- botones de crear y guardar -->
				<div class="input-field col s10 offset-s1">
					<div class="row center-align">
						<!-- boton de crear -->
						<button class="waves-effect waves-light btn" name="action" type="submit" value="create" <?php echo $update_mode ? "disabled" : ""; ?>>
							<i class="material-icons right">create</i>Crear
						</button>
						<!-- boton de guardar cambios -->
						<button class="margin-left waves-effect waves-light btn" name="action" value="update" type="submit" <?php echo $update_mode ? "" : "disabled"; ?>>
							<i class="material-icons right">save</i>Guardar
						</button>
						<!-- boton de cancelar actualizacion, normalmente escondido -->
						<a href="dashboard.php?view=equipos" class=" margin-left waves-effect waves-light btn red" <?php echo $update_mode ? "" : "disabled"; ?>>
							<i class="material-icons right">cancel</i>Cancelar
						</a>
					</div>
				</div>
			</div>
		</form>
	</div>
	<!-- subtitulo -->
	<div class="row center-align">
		<h4>Equipos Disponibles</h4>
	</div>
	<!-- listado de usuarios -->
	<div class="row">
		<div class="col s12">
			<table class="striped">
				<thead>
					<tr>
						<th>Modelo</th>
						<th>Estado</th>
						<th>Ubicación</th>
						<th>Proveedor</th>
						<th>Garantía</th>
						<th>Tipo</th>
						<th>Adquisición</th>
						<th>Vigencia Garantía</th>
						<th>Acciones</th>
					</tr>
				</thead>

				<tbody>
					<?php 
						// iterar las filas del select de usuarios
						while ($row = $result_equipo->fetch_assoc()) {
							echo "<tr>";
							// imprimir el nombre y el correo
							echo "<td>".$row['modelo']."</td>";
							echo "<td>".$row['estado']."</td>";
							echo "<td>".$row['ubicacion']."</td>";
							echo "<td>".$row['proveedor']."</td>";
							echo "<td>".$row['garantia_tipo']."</td>";
							echo "<td>".$row['tipo']."</td>";
							echo "<td>".$row['f_adquisicion']."</td>";
							echo "<td>".$row['garantia_vigencia']."</td>";
							// y los botones
							echo "<td>";
							// boton para editar NO ES DE TYPE SUBMIT
							echo "<a class=\"blue waves-effect waves-light btn\" href=\"?view=equipos&equipo_id=".$row["id_equipo"]."\"><i class=\"material-icons right\">settings</i></a>";
							// boton para eliminar
							echo "<a class=\"red margin-left waves-effect waves-light btn\" href=\"actions/equipos.php?equipo_delete_id=".$row["id_equipo"]."\"><i class=\"material-icons right\">delete</i></a>";
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

	// funcion para preseleccionar los selects
	// https://stackoverflow.com/questions/57177597/materlialize-css-select-componenent-setting-default-value-with-javascript
	function preselect_select (identifier, value) {
		var elem = document.querySelector(identifier);
		var instances = M.FormSelect.init(elem);
		document.querySelector(identifier+' option[value="'+value+'"]').setAttribute('selected', 'selected');
		M.FormSelect.init(elem);
	}

	// funcion para preseleccionar fechas en los datepickers
	function preselect_picker (identifier, value) {
		var elem = document.querySelector(identifier);
		elem.value = value;
	}

	// PERDON, TUVE QUE USARLO
	// funcion de jquery para iniciar el select
	$(document).ready(function(){
		<?php
			// inicializar los datepickers
			echo "var elems = document.querySelectorAll('.datepicker');\n";
    		echo "var instances = M.Datepicker.init(elems, {\"format\":\"yyyy-mm-dd\"});\n";

			// si va a haber actualizacion
			if ($update_mode) {
				// preseleccionar los valores de los selects
				echo "preselect_select('select#select-tipo','".$update['id_tipo']."');\n";
				echo "preselect_select('select#select-proveedor','".$update['id_proveedor']."');\n";
				// preseleccionar los valores de los datepickers
				echo "preselect_picker('#datepicker-adquisicion','".$update['f_adquisicion']."');\n";
				echo "preselect_picker('#datepicker-garantia','".$update['garantia_vigencia']."');\n";
				// pre llenar los textareas
				echo "$('#textarea-corta').val('".$update['des_corta']."');";
				echo "$('#textarea-larga').val('".$update['des_larga']."');";
			}
			// inicializar los selects
			echo "$('select#select-proveedor').formSelect();$('select#select-tipo').formSelect();\n";
			// inicializar los datepickers
			echo "var elems = document.querySelectorAll('.datepicker');\n";
    		echo "var instances = M.Datepicker.init(elems, {\"format\":\"yyyy-mm-dd\"});\n";
    		// inicializar los textareas
    		echo "M.textareaAutoResize($('#textarea-corta'));";
    		echo "M.textareaAutoResize($('#textarea-larga'));";

		?>
  	});
</script>