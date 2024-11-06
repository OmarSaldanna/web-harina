<?php
	// ejecutamos las queries para leer los equipos y mediciones pasadas
	
	// esta query muestra los datos de equipo y ademas cambia el id_tipo por el tipo
	$result_equipo = query($conn, "SELECT equipo.id_equipo, equipo.modelo, tipo.tipo
		FROM equipo
		JOIN tipo ON equipo.id_tipo = tipo.id_tipo;");
	// esta es para la tabla, para unir las 3 tablas y mostrar lo que necesitamos
	$result_mediciones = query($conn, "SELECT m.fecha, e.modelo, t.tipo, m.id_lote, u.nombre AS usuario, m.tipo AS subtipo
		FROM medicion m
		JOIN equipo e ON m.id_equipo = e.id_equipo
		JOIN tipo t ON e.id_tipo = t.id_tipo
		JOIN usuario u ON m.id_usuario = u.id_usuario ORDER BY m.fecha DESC;");

	// verificamos si llego una bad request
	$b = isset($_GET['bad_request']) ? True : False;

?>

<div class="col s12">
	<!-- titulo -->
	<div class="row center-align">
		<h2>Mediciones de Laboratorio</h2>
	</div>
	<!-- formulario -->
	<div class="row">
		<form class="col s12" method="POST" action="actions/mediciones.php">
			<!-- inputs de nombre y correo -->
			<div class="row">
				<div class="input-field col s3">
					<input id="nombre" type="number" class="validate" name="id_lote" value="<?php echo $b ? $_GET['id_lote'] : ""; ?>">
					<label for="nombre">ID de Lote</label>
		        </div>
		        <div class="input-field col s4 offset-s1">
					<select name="equipo" id="select">
						<option value="" disabled selected>Selecciona el Equipo</option>
						<?php
							// iterar las filas del select de equipos
							while ($row = $result_equipo->fetch_assoc()) {
								// renderizar los equipos disponibles
								// el value es el id_equipo-id_tipo para eficientar la action
								echo "<option value=\"".$row['id_equipo'].'-'.$row['tipo']."\">".$row['modelo']."</option>";
    						}
						?>
					</select>
					<label>Equipo de Laboratorio</label>
				</div>
				<div class="input-field col s3 offset-s1">
					<!-- Switch -->
					  <div class="switch">
					    <label>
					      Alveógrafo
					      <input type="checkbox" name="switch" <?php if ($b) { echo $_GET['switch'] == "" ? "" : "checked"; } ?>>
					      <span class="lever"></span>
					      Farinógrafo
					    </label>
					  </div>
				</div>
		    </div>
		    <div class="row grey lighten-3 ">
				<!-- inputs de mediciones uno -->
			    <div class="row space">
			    	<!-- primer campo -->
			        <div class="input-field col s4 offset-s1">
						<input id="m1" type="number" class="validate" name="m1" value="<?php echo $b ? $_GET['m1'] : ""; ?>">
						<label id="label-m1" for="m1">Medida 1</label>
			        </div>
			        <div class="input-field col s4 offset-s2">
						<input id="m2" type="number" class="validate" name="m2" value="<?php echo $b ? $_GET['m2'] : ""; ?>">
						<label id="label-m2" for="m2">Medida 2</label>
			        </div>
				</div>
				<div class="row">
					 <div class="input-field col s4 offset-s1">
						<input id="m3" type="number" class="validate" name="m3" value="<?php echo $b ? $_GET['m3'] : ""; ?>">
						<label id="label-m3" for="m3">Medida 3</label>
			        </div>
			         <div class="input-field col s4 offset-s2">
						<input id="m4" type="number" class="validate" name="m4" value="<?php echo $b ? $_GET['m4'] : ""; ?>">
						<label id="label-m4" for="m4">Medida 4</label>
			        </div>
				</div>
				<!-- inputs de contraseña -->
			    <div class="row space-b">
			    	<!-- primer campo -->
			        <div class="input-field col s4 offset-s1">
						<input id="m5" type="number" class="validate" name="m5" value="<?php echo $b ? $_GET['m5'] : ""; ?>">
						<label id="label-m5" for="m5">Medida 5</label>
			        </div>
			        <div class="input-field col s4 offset-s2 container">
			        	<div class="row">
			        		<div class="col s6">
			        			<button class="waves-effect waves-light btn" type="submit">
									<i class="material-icons right">save</i>Guardar
								</button>
			        		</div>	
			        	
				        	<div class="col s6">
				        		<p>
									<label>
										<input name="checkbox" type="checkbox" class="filled-in" checked="checked"/>
										<span>Para Pedido?</span>
									</label>
								</p>
				        	</div>
				        </div>
			        </div>
				</div>
			</div>
		</form>
	</div>
	<div class="row center-align">
		<h4>Mediciones Pasadas</h4>
	</div>
	<!-- mediciones pasadas -->
	<!-- se va a mostrar la fecha, modelo de equipo, tipo, lote y el usuario que hizo la medicion -->
	<!-- listado de usuarios -->
	<div class="row">
		<div class="col s12">
			<table class="striped">
				<thead>
					<tr>
						<th>Fecha</th>
						<th>Modelo</th>
						<th>Tipo de Equipo</th>
						<th>Número de Lote</th>
						<th>Responsable</th>
						<th>Tipo de Medición</th>
					</tr>
				</thead>

				<tbody>
					<?php 
						// iterar las filas del select de usuarios
						while ($row = $result_mediciones->fetch_assoc()) {
							$t = $row['tipo'] == "A" ? "Alveógrafo" : "Farinógrafo";
							echo "<tr>";
							// imprimir el nombre y el correo
							echo "<td>".$row['fecha']."</td>";
							echo "<td>".$row['modelo']."</td>";
							echo "<td>".$t."</td>";
							echo "<td>".$row['id_lote']."</td>";
							echo "<td>".$row['usuario']."</td>";
							echo "<td>".$row['subtipo']."</td>";
							// y ya lo que falta de la tabla
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

	// labels de las medidas
	var medidas = [
		["Tenacidad a Extensibilidad (P/L)","Tenacidad (P)","Extensibilidad (L)","Índice de Elasticidad","Energía de Deformación"],
		["Absorción de agua","Tiempo de desarrollo","Estabilidad","Debilitamiento","Índice de calidad"]
	];

	// 0 para albeografo, 1 para farinografo
	function cambiar_medidas(state) {
		// iteramos por 5 por las 5 medidas
		for (let i = 1; i <= 5; i++) {
			// cambiar el contenido de los componentes
			document.getElementById("label-m"+i).innerHTML = medidas[state][i-1];
		}
	}

	// funcion de jquery para iniciar el select
	$(document).ready(function() {
		// antes ver si no hubo una bad_request
		<?php
			// si se va a actualizar
			if ($b) {
				// preseleccionar el equipo
				echo "$('select').val(\"".$_GET['equipo']."\");";
			}
		?>
		// inicializar el select
    	$('select').formSelect();
    	// incializar las medidas por default tambien consiferando la request
    	cambiar_medidas(<?php if ($b) { echo $_GET['switch'] == "Alveógrafo" ? "0" : "1"; } else { echo "0"; }?>);
  	});

	
	// por default el switch está en farinografo, entonces esas seran las labels iniciales
	// cuando el switch cambie
	$('.switch input[type="checkbox"]').on('change', function() {
	    if ($(this).is(':checked')) {
	        // console.log("Farinógrafo seleccionado");
	        cambiar_medidas(1);
	    } else {
	        // console.log("Alveógrafo seleccionado");
	        cambiar_medidas(0);
	    }
	});

</script>