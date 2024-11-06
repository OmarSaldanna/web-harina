<?php 
	//Ejecutamos query para la tabla de Lotes Registrados
	$result_proveedor = query($conn, "SELECT * FROM domicilio;");


?>
<div class="col s12">
	<div class="row center-align">
		<h2>Domicilios</h2>
	</div>
	<!-- formulario -->
	    <div class="row">
      <form class="col s12" action="actions/domicilio.php" method="POST">
        <div class="row">
          <div class="input-field col s4 offset-s1">
            <textarea name="calle" id="textarea2" class="materialize-textarea" data-length="512"></textarea>
            <label for="textarea2">Calle</label>
          </div>
   
        <div class="row">
          <div class="input-field col s4 offset-s1">
            <textarea name="num_interior" id="textarea2" class="materialize-textarea" data-length="512"></textarea>
            <label for="textarea2">numero interior</label>
          </div>
        </div>
          <div class="row">
          <div class="input-field col s4 offset-s1">
            <textarea name="num_ext" id="textarea2" class="materialize-textarea" data-length="512"></textarea>
            <label for="textarea2">Numero Exterior</label>
          </div>
   
        <div class="row">
          <div class="input-field col s5 offset-s1">
            <textarea name="colonia" id="textarea2" class="materialize-textarea" data-length="512"></textarea>
            <label for="textarea2">colonia</label>
          </div>
        </div>
          <div class="row">
          <div class="input-field col s4 offset-s1">
            <textarea name="codigo_postal" id="textarea2" class="materialize-textarea" data-length="512"></textarea>
            <label for="textarea2">Codigo Postal</label>
          </div>
   
        <div class="row">
          <div class="input-field col s5 offset-s1">
            <textarea name="ciudad" id="textarea2" class="materialize-textarea" data-length="512"></textarea>
            <label for="textarea2">Ciudad</label>
          </div>
        </div>
          <div class="row">
          <div class="input-field col s4 offset-s1">
            <textarea name="estado" id="textarea2" class="materialize-textarea" data-length="512"></textarea>
            <label for="textarea2">Estado</label>
          </div>
   
        <div class="row">
          <div class="input-field col s5 offset-s1">
            <textarea name="pais" id="textarea2" class="materialize-textarea" data-length="512"></textarea>
            <label for="textarea2">País</label>
          </div>
        </div>
        <div class="row">
        	<div class="col s3 offset-s1">
        		<button class="btn waves-effect waves-light" type="submit" name="action">Añadir Domicilio
    				<i class="material-icons right">border_color</i>
  				</button>
        	</div>
      </form>
    </div>
	<!-- subtitulo -->
	<div class="row center-align">
		<h4>Domicilios Registrados</h4>
	</div>
	<!-- listado de usuarios -->
	<div class="row">
		<div class="col s12">
			
			<table class="striped">
				<thead>
					<tr>
						<th>Calle</th>
						<th>Numero Interior</th>
						<th>Numero Exterior</th>
						<th>Colonia</th>
						<th>Codigo Postal</th>
						<th>Estado</th>
						<th>Ciudad</th>
						<th>País</th>
					</tr>
				</thead>

				<tbody>
					<?php 
						// iterar las filas del select de usuarios
						while ($row = $result_proveedor->fetch_assoc()) {
							echo "<tr>";
							// Imprimir los datos que se mostrarán en la tabla
							echo "<td>".$row['calle']."</td>";
							echo "<td>".$row['num_int']."</td>";
							echo "<td>".$row['num_ext']."</td>";
							echo "<td>".$row['colonia']."</td>";
							echo "<td>".$row['codigo_postal']."</td>";
							echo "<td>".$row['ciudad']."</td>";
							echo "<td>".$row['estado']."</td>";
							echo "<td>".$row['pais']."</td>";
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
</script>