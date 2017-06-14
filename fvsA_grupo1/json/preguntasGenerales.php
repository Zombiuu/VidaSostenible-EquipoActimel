<?php
$server = "localhost";
$user = "root";
$pass = "";
$bd = "fvs_grupo1";


// Creamos la conexion
$conexion = mysqli_connect ( $server, $user, $pass, $bd ) or die ( "Ha sucedido un error inexperado en la conexion de la base de datos" );

mysqli_set_charset ( $conexion, "utf8" ); // formato de datos utf8

	$arrayGeneral =  array(
		"edad"  => "¿Qué edad tienes?",
		"paises" => "¿De que país eres?",
		"ccaa" => "¿De que comunidad autonoma eres?",
		"tipoCasa"  => "¿Cómo es la casa en la que vives?(Si tienes varias casas, tu domicilio principal)",
		"espacioCasas" => "Indica el espacio que dispones en metros cuadrados",
		"numPersonas" => "¿Cuantas personas viven en tu hogar?",
		"ingresos"  => "Nivel de ingresos anual",
		"conocimientos" => "Profesión, formación y area de conocimiento",
		"estudios" => "¿Nivel de estudios?"
	
    );
	
	$formulario2 = array();

	foreach ($arrayGeneral as $key => $valor) {
		# code...
		$arrayPregunta = array ();
		$pregunta = $valor;
		$arrayRespuestas = array ();
		$query = "SELECT * FROM $key";

		$result = mysqli_query ( $conexion, $query );
		
		if(isset($result) && $result){
			while ( $row = mysqli_fetch_array ( $result ) ) {
				$texto = $row ['texto'];
				$id = $row ['id'];
				$arrayRespuestas [] = array (
					'respuesta' => $texto,
					'idRespuesta' => $id,
					'texto' => ''	
				);
			}
			
			$formulario2[] = array (
				'idPregunta' => $key,
				'pregunta' => $pregunta,
				'categoria' => 'General',
				'disponible' => 1,
				'tipo' => 'select',
				'idRespuestaDepende' => [],
				'imagen' => null,
				'respuesta' => $arrayRespuestas
			);
			
		}else{
			echo $conexion->error;
			
		}

	
	}

// desconectamos la base de datos
$close = mysqli_close ( $conexion ) or die ( "Ha sucedido un error inexperado en la desconexion de la base de datos" );

// Creamos el JSON
// $json_string = json_encode ( $formulario2, JSON_PRETTY_PRINT );
//echo "ANTES DE ESCRIBIR EL JSON";
// echo "<pre>";
// echo $json_string;
// echo "</pre>";
//echo "DESPUES DE ESCRIBIR EL JSON";

?>
