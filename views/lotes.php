<?php 
	//Ejecutamos query para la tabla de Lotes Registrados
	$result_lote = query($conn, "SELECT * FROM lote;");

 ?>

<div class="col s12">
	<!-- titulo -->
	<div class="row center-align">
		<h2>Registro de Lotes
		</h2>
	</div>
	<!-- Nombre del formulario -->
	<div></div>
	<!-- formulario -->
	    <div class="row">
      <form class="col s12" action="actions/lotes.php" method="POST">
        <div class="row">
          <div class="input-field col s4 offset-s1">
            <input name="cantidad" id="input_text" type="number" data-length="10">
            <label for="input_text">Cantidad</label>
          </div>
          <div class="input-field col s4 offset-s1">
    		<input name="fecha" type="text" class="datepicker" id="datepicker">
    		<label for="datepicker">Fecha</label>
		</div>
        </div>
        <div class="row">
          <div class="input-field col s9 offset-s1">
            <textarea name="notas" id="textarea2" class="materialize-textarea" data-length="512"></textarea>
            <label for="textarea2">Notas</label>
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
		<h4>Lotes registrados</h4>
	</div>
	<!-- listado de usuarios -->
	<div class="row">
		<div class="col s12">
			
			<table class="striped">
				<thead>
					<tr>
						<th>Fecha de Producción</th>
						<th>Fecha de Caducidad</th>
						<th>Cantidad</th>
						<th>Notas</th>
					</tr>
				</thead>

				<tbody>
					<?php 
						// iterar las filas del select de usuarios
						while ($row = $result_lote->fetch_assoc()) {
							echo "<tr>";
							// imprimir el fecha de producción, caducidad, y cantidad
							echo "<td>".$row['f_produccion']."</td>";
							echo "<td>".$row['f_caducidad']."</td>";
							echo "<td>".$row['cantidad']."</td>";
							echo "<td>".substr($row['notas'], 0, 40)."...</td>";
							// El resto de la tabla
							echo "</td>";
							echo "</tr>";
						}
					?>
				</tbody>
			</table>
		</div>
	</div> 

<!-- Initialize Date Picker -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var elems = document.querySelectorAll('.datepicker');
    var instances = M.Datepicker.init(elems, {"format":"yyyy-mm-dd"});
    var textArea = document.querySelector('#textarea2');
    var textAreaCounter = document.querySelector('#textarea2 + .character-counter');
    M.textareaAutoResize(textArea);
    M.CharacterCounter.init(textArea);
  });
</script>