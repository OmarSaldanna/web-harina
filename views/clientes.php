<?php
	// id_pedido, nombre, correo, f_registro, rfc, id_parametro, id_domicilio_fiscal, id_domicilio de entrega, certificado, numero
	// ejecutamos las queries para leer los usuarios y los roles
	$result_cliente = query($conn, "SELECT c.*, df.calle AS calle_fiscal, df.num_int AS num_int_fiscal, df.num_ext AS num_ext_fiscal, df.colonia AS colonia_fiscal, df.codigo_postal AS cp_fiscal, df.ciudad AS ciudad_fiscal, df.estado AS estado_fiscal, df.pais AS pais_fiscal, de.calle AS calle_entrega, de.num_int AS num_int_entrega, de.num_ext AS num_ext_entrega, de.colonia AS colonia_entrega, de.codigo_postal AS cp_entrega, de.ciudad AS ciudad_entrega, de.estado AS estado_entrega, de.pais AS pais_entrega  FROM cliente c JOIN domicilio df ON c.id_domicilio_fiscal = df.id_domicilio JOIN domicilio de ON c.id_domicilio_entrega = de.id_domicilio;");
	$result_domicilio = query($conn, "SELECT id_domicilio, calle, num_ext FROM domicilio;");
	$result_domicilio_2 = query($conn, "SELECT id_domicilio, calle, num_ext FROM domicilio;");

	// recieves a db row to pass this to the modal content
	function parse_address ($row, $sub) { // sub es "entrega" o "fiscal"
		$content = "";
		// parametros de la db
		$params = array("calle", "num_int", "num_ext", "colonia", "cp", "ciudad", "estado", "pais");
		// para formar una lista
		$labels = array("<br><strong>Calle:</strong>", "<br><strong>Número Interior:</strong>", "<br><strong>Número Exterior:</strong>", "<br><strong>Colonia:</strong>", "<br><strong>CP:</strong>", "<br><strong>Ciudad:</strong>", "<br><strong>Estado:</strong>", "<br><strong>Pais:</strong>");
		for ($p = 0; $p<8; $p++) {
			$content = $content.$labels[$p]." ".$row[$params[$p]."_".$sub];
		}
		return $content;
	}

	//////////////////////////////////////////////////////////////////
	/////////////////////// En caso Update ///////////////////////////
	//////////////////////////////////////////////////////////////////



	// booleano para saber si hubo una redireccion para hacer un update
	$update_mode = isset($_GET['cliente_id']);
	$update = "";
	// si si hubo redireccion hacer una query de los datos del cliente a actualizar
	if ($update_mode) {
		// hacer la query
		$cliente_update = query($conn, "SELECT * FROM cliente WHERE id_cliente=".$_GET['cliente_id'].";");
		// hacer el fetch de los datos
		$update = $cliente_update->fetch_assoc();
	}
?>

<style type="text/css">
	.margin-left { margin-left: 5%; }
</style>

<div class="col s12">
	<!-- titulo -->
	<div class="row center-align">
		<h2>Gestión de Clientes</h2>
	</div>
	<!-- formulario -->
	<div class="row">
		<form class="col s12" method="POST" action="actions/clientes.php<?php echo $update_mode ? "?cliente_update_id=".$_GET['cliente_id'] : "" ?>">
			<!-- inputs de marca y modelo -->
			<div class="row">
				<div class="input-field col s4 offset-s1">
					<input id="nombre" type="text" class="validate" name="nombre" value="<?php echo $update_mode ? $update['nombre'] : ""; ?>">
					<label for="nombre">Nombre</label>
		        </div>
		        <div class="input-field col s4 offset-s1">
					<input id="correo" type="text" class="validate" name="correo" value="<?php echo $update_mode ? $update['correo'] : ""; ?>">
					<label for="correo">Correo</label>
		        </div>
		    </div>
		    <!-- inputs de numero de serie y garantia-->
			<div class="row">
				<div class="input-field col s4 offset-s1">
					<input id="rfc" type="text" class="validate" name="rfc" value="<?php echo $update_mode ? $update['rfc'] : ""; ?>">
					<label for="rfc">RFC</label>
		        </div>
		        <div class="input-field col s4 offset-s1">
					<input id="contacto" type="number" class="validate" name="contacto" value="<?php echo $update_mode ? $update['contacto'] : ""; ?>">
					<label for="contacto">Teléfono</label>
		        </div>
		    </div>
		    <!-- selects de domicilios -->
		    <div class="row">
		    	<!-- domicilio fiscal -->
			    <div class="input-field col s4 offset-s1">
					<select name="id_domicilio_fiscal" id="select-domicilio-fiscal">
						<option value="" disabled selected>Selecciona el Domicilio</option>
						<?php
							// iterar las filas del select de domicilios
							while ($row = $result_domicilio->fetch_assoc()) {
								// no se porque, pero tuvo que separarse en dos lineas
								echo "<option value=\"".$row['id_domicilio']."\">";
								echo $row['calle']." #".$row['num_ext']."</option>";
    						}
						?>
					</select>
					<label>Domicilio Fiscal</label>
				</div>
				<!-- domicilio de entrega -->
				<div class="input-field col s4 offset-s1">
					<select name="id_domicilio_entrega" id="select-domicilio-entrega">
						<option value="" disabled selected>Selecciona el Domicilio</option>
						<?php
							// iterar las filas del select de domicilios
							while ($row = $result_domicilio_2->fetch_assoc()) {
								// no se porque, pero tuvo que separarse en dos lineas
								echo "<option value=\"".$row['id_domicilio']."\">";
								echo $row['calle']." #".$row['num_ext']."</option>";
    						}
						?>
					</select>
					<label>Domicilio de Entrega</label>
				</div>
			</div>
			<div class="row">
				<!-- los pickers de las fechas de adquisición y garantía -->
				<div class="input-field col s4 offset-s1">
		    		<input name="f_registro" type="text" class="datepicker" id="datepicker-registro">
		    		<label for="datepicker-adquisicion">Fecha de Registro</label>
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
						<a href="dashboard.php?view=clientes" class=" margin-left waves-effect waves-light btn red" <?php echo $update_mode ? "" : "disabled"; ?>>
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
						<th>Nombre</th>
						<th>Correo</th>
						<th>Número</th>
						<th>RFC</th>
						<th>Dom Fiscal</th>
						<th>Dom Entrega</th>
						<th>Acciones</th>
					</tr>
				</thead>

				<tbody>
					<?php 
						// iterar las filas del select de usuarios
						while ($row = $result_cliente->fetch_assoc()) {
							echo "<tr>";
							// imprimir el nombre y el correo
							echo "<td>".$row['nombre']."</td>";
							echo "<td>".$row['correo']."</td>";
							echo "<td>".$row['contacto']."</td>";
							echo "<td>".$row['rfc']."</td>";
							// boton de domicilio fiscal
							echo "<td>";
							echo "<button onclick=\"show_modal('Domicilio Fiscal','".parse_address($row, "fiscal")."')\" class=\"waves-effect waves-light btn\"><i class=\"material-icons right\">more_horiz</i></button>";
							echo "</td>";
							// boton de domicilio de entrega
							echo "<td>";
							echo "<button onclick=\"show_modal('Domicilio Entrega','".parse_address($row, "entrega")."')\" class=\"waves-effect waves-light btn\"><i class=\"material-icons right\">more_horiz</i></button>";
							echo "</td>";
							// y los botones de acciones
							echo "<td>";
							// boton para editar NO ES DE TYPE SUBMIT
							echo "<a class=\"blue waves-effect waves-light btn\" href=\"?view=clientes&cliente_id=".$row["id_cliente"]."\"><i class=\"material-icons right\">settings</i></a>";
							// boton para eliminar
							echo "<a class=\"red margin-left waves-effect waves-light btn\" href=\"actions/clientes.php?cliente_delete_id=".$row["id_cliente"]."\"><i class=\"material-icons right\">delete</i></a>";
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


<!-- Modal Structure -->
<div id="modal" class="modal">
	<div class="modal-content">
		<h4 id="modal-h4">Domicilio</h4>
		<p id="modal-p"></p>
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

	// funcion para hacer un filling del modal y mostrarlo
	function show_modal (title, content) {
		// seleccionar los componentes del modal
		var modal_p = document.getElementById('modal-p');
		var modal_h4 = document.getElementById('modal-h4');
		// cambiar los htmls
		modal_p.innerHTML = content;
		modal_h4.innerHTML = title;
		// y mostrar el modal
		let modal = M.Modal.getInstance(document.getElementById('modal'));
		modal.open();
	}

	// PERDON, TUVE QUE USARLO
	// funcion de jquery para iniciar el select
	$(document).ready(function(){
		// inicalizar el modal
		M.Modal.init(document.getElementById('modal'), {});
		// var modal = M.Modal.getInstance(document.querySelectorAll('.modal'))
		<?php
			// inicializar los datepickers
			echo "var elems = document.querySelectorAll('.datepicker');\n";
    		echo "var instances = M.Datepicker.init(elems, {\"format\":\"yyyy-mm-dd\"});\n";

			// si va a haber actualizacion
			if ($update_mode) {
				// preseleccionar los valores de los selects
				echo "preselect_select('select#select-domicilio-fiscal','".$update['id_domicilio_fiscal']."');\n";
				echo "preselect_select('select#select-domicilio-entrega','".$update['id_domicilio_entrega']."');\n";
				// preseleccionar los valores de los datepickers
				echo "preselect_picker('#datepicker-registro','".$update['f_registro']."');\n";
			}
			// inicializar los selects
			echo "$('select#select-domicilio-fiscal').formSelect();$('select#select-domicilio-entrega').formSelect();\n";
			// inicializar los datepickers
			echo "var elems = document.querySelectorAll('.datepicker');\n";
    		echo "var instances = M.Datepicker.init(elems, {\"format\":\"yyyy-mm-dd\"});\n";

		?>
  	});
</script>