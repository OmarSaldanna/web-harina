<!-- cdn de axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<!-- contenido de la pagina -->
<div class="col s12">
	<!-- titulo -->
	<div class="row center-align">
		<h2>Emisión de Certificados</h2>
	</div>
	<!-- formulario para emision de certificados -->
	<div class="row">
		<div class="col s10 offset-s1">
			
			<div class="row">
				<div class="input-field col s3">
					<input id="id_lote" type="number" class="validate">
					<label for="id_lote">Id de Lote</label>
				</div>
				<div class="input-field col s3 offset-s1">
					<input id="tipo_medicion" type="text" class="validate">
					<label for="tipo_medicion">Tipo de Medición</label>
				</div>
				<div class="input-field col s3 offset-s1">
					<input id="id_cliente" type="number" class="validate">
					<label for="id_cliente">Id de Cliente</label>
				</div>
			</div>
			<div class="row">
				<div class="input-field col s3">
					<input id="cantidad" type="number" class="validate">
					<label for="cantidad">Cantidad de Harina (Kg)</label>
				</div>
				<div class="input-field col s4 offset-s5">
					<button class="margin-left waves-effect waves-light btn" onclick="crear_certificado()">
						<i class="material-icons right">save</i>Emitir Certificado
					</button>
				</div>
			</div>
		</div>
	</div>

	<div id="pdf-container" class="row hide">
		<!-- certificate -->
		<embed id="pdf" src="" width="1000" height="550" type="application/pdf"></embed>
	</div>
</div>

<script type="text/javascript">
	function crear_certificado() {
		// seleccionar el valor del input
		var id_lote = document.getElementById("id_lote").value;
		var tipo_medicion = document.getElementById("tipo_medicion").value;
		var cantidad = document.getElementById("cantidad").value;
		var id_cliente = document.getElementById("id_cliente").value;
		// el row del pdf
		var element = document.getElementById("pdf-container");
		// el pdf
		var embed = document.getElementById("pdf");
		// verificar que el id_lote no sea nulo
		if (id_lote == "") {
			M.toast({html: 'No se aceptan campos vacíos', classes: 'red'});
			return 0;
		}
		// hacer la request a la api de certificados
		axios.post('http://127.0.0.1:3142/certificados', { params: { id_lote: id_lote, tipo_medicion: tipo_medicion, cantidad: cantidad, id_cliente: id_cliente }
		}).then(function(response) {
		    // Imprimir la respuesta "answer" en la consola
		    let ans = response.data.answer;
		    // si hubo un error
		    if (ans == "error") { M.toast({html: 'Número de lote incorrecto', classes: 'red'}); }
		    // si se proceso correctamente
		    else {
		    	// mandar una alerta 
		    	M.toast({html: 'Certificado emitido correctamente', classes: 'green rounded'});
		    	// colocar el pdf en el iframe
		    	embed.src = ans;
		    	// y mostrarlo
				element.classList.remove("hide");
		    }
		})
		.catch(function(error) {
			// si hubo un error en la api
			// console.log(error);
			// mandar un error
		    M.toast({html: 'Ups! algo salió mal', classes: 'green rounded'});

		});
	}
</script>



