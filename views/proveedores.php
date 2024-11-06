<?php 
	//Ejecutamos query para la tabla de Lotes Registrados
	$result_proveedor = query($conn, "SELECT * FROM proveedor;");


?>
<div class="col s12">
	<div class="row center-align">
		<h2>Gestión de proveedores</h2>
	</div>
	<!-- formulario -->
	    <div class="row">
      <form class="col s12" action="actions/proveedores.php" method="POST">
        <div class="row">
          <div class="input-field col s5 offset-s1">
            <textarea name="nombre" id="textarea2" class="materialize-textarea" data-length="512"></textarea>
            <label for="textarea2">Nombre</label>
          </div>
          <div class="input-field col s4 offset-s1">
    		<input name="fecha" type="text" class="datepicker" id="datepicker">
    		<label for="datepicker">Fecha de inicio de relación</label>
		</div>
        </div>
        <div class="row">
          <div class="input-field col s10 offset-s1">
            <textarea name="correo" id="textarea2" class="materialize-textarea" data-length="512"></textarea>
            <label for="textarea2">Correo</label>
          </div>
        </div>
        <div class="row">
          <div class="input-field col s5 offset-s1">
            <textarea name="sitio_web" id="textarea2" class="materialize-textarea" data-length="512"></textarea>
            <label for="textarea2">Sitio Web</label>
        </div>

        <!-- Selección de Estados -->
		<div class="row">
		    <div class="input-field col s4 offset-s1">
		        <!-- Elemento Select -->
		        <select name="estado" id="estado">
		            <option value="" disabled selected>Selecciona un estado</option>
		            <option value="Aguascalientes">Aguascalientes</option>
					<option value="Baja California">Baja California</option>
				    <option value="Baja California Sur">Baja California Sur</option>
				    <option value="Campeche">Campeche</option>
				    <option value="Chiapas">Chiapas</option>
				    <option value="Chihuahua">Chihuahua</option>
				    <option value="Ciudad de México">Ciudad de México</option>
				    <option value="Coahuila">Coahuila</option>
				    <option value="Colima">Colima</option>
				    <option value="Durango">Durango</option>
				    <option value="Estado de México">Estado de México</option>
				    <option value="Guanajuato">Guanajuato</option>
				    <option value="Guerrero">Guerrero</option>
				    <option value="Hidalgo">Hidalgo</option>
				    <option value="Jalisco">Jalisco</option>
				    <option value="Michoacán">Michoacán</option>
				    <option value="Morelos">Morelos</option>
				    <option value="Nayarit">Nayarit</option>
				    <option value="Nuevo León">Nuevo León</option>
				    <option value="Oaxaca">Oaxaca</option>
				    <option value="Puebla">Puebla</option>
				    <option value="Querétaro">Querétaro</option>
				    <option value="Quintana Roo">Quintana Roo</option>
				    <option value="San Luis Potosí">San Luis Potosí</option>
				    <option value="Sinaloa">Sinaloa</option>
				    <option value="Sonora">Sonora</option>
				    <option value="Tabasco">Tabasco</option>
				    <option value="Tamaulipas">Tamaulipas</option>
				    <option value="Tlaxcala">Tlaxcala</option>
				    <option value="Veracruz">Veracruz</option>
				    <option value="Yucatán">Yucatán</option>
				    <option value="Zacatecas">Zacatecas</option>
		        </select>
		        <label for="estado">Estado</label>
		    </div>
		</div>

        <div class="row">
        	<div class="col s3 offset-s1">
        		<button class="btn waves-effect waves-light" type="submit" name="action">Añadir
    				<i class="material-icons right">border_color</i>
  				</button>
        	</div>
        </div>
      </form>
    </div>
	<!-- subtitulo -->
	<div class="row center-align">
		<h4>Proveedores Registrados</h4>
	</div>
	<!-- listado de usuarios -->
	<div class="row">
		<div class="col s12">
			
			<table class="striped">
				<thead>
					<tr>
						<th>Nombre</th>
						<th>Correo</th>
						<th>Sitio Web</th>
						<th>Fecha de inicio de relación</th>
						<th>Estado</th>
					</tr>
				</thead>

				<tbody>
					<?php 
						// iterar las filas del select de usuarios
						while ($row = $result_proveedor->fetch_assoc()) {
							echo "<tr>";
							// Imprimir los datos que se mostrarán en la tabla
							echo "<td>".$row['nombre']."</td>";
							echo "<td>".$row['correo']."</td>";
							echo "<td>".$row['sitio_web']."</td>";
							echo "<td>".$row['f_inicio_relacion']."</td>";
							echo "<td>".$row['estado']."</td>";
							// El resto de la tabla
							echo "</td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<!-- Initialize Date Picker y Select -->
<script>
	document.addEventListener('DOMContentLoaded', function() {
	  var elems = document.querySelectorAll('select');
	  var instances = M.FormSelect.init(elems);
	});

  document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.datepicker');
    var instances = M.Datepicker.init(elems, {"format":"yyyy-mm-dd"});
    var textArea = document.querySelector('#textarea2');
    var textAreaCounter = document.querySelector('#textarea2 + .character-counter');
    M.textareaAutoResize(textArea);
    M.CharacterCounter.init(textArea);
  });
</script>