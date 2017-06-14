<?php
$server = "localhost";
$user = "root";
$pass = "";
$bd = "fvs_grupo1";

// Creamos la conexion
$conexion = mysqli_connect ( $server, $user, $pass, $bd ) or die ( "Ha sucedido un error inexperado en la conexion de la base de datos" );

// generamos la consulta
$sql = "SELECT  pregunta.id as idPregunta, pregunta.pregunta as pregunta,
		respuesta.id as idRespuesta,
respuesta.respuesta as respuesta, categoria.nombre as nombreCat, 
		tipo.tipo as tipoPregunta,
 pregunta.disponibilidad as disponible, 
		GROUP_CONCAT(dependencia.idDepende) as idRespuestaDepende, 
		depende.id as idDepende, pregunta.imagen as imagen
FROM depende
JOIN respuesta ON depende.idRespuesta = respuesta.id
JOIN pregunta ON depende.idPregunta = pregunta.id
JOIN pertenece ON pregunta.id = pertenece.idPregunta
JOIN categoria ON pertenece.idCategoria = categoria.id
LEFT JOIN dependencia ON dependencia.idPregunta = pregunta.id
JOIN tipo ON pregunta.tipo = tipo.id

GROUP BY pregunta.id, depende.id
ORDER BY pregunta.id, depende.id
";

//Group concat y agrupo por pregunta.id, depende.id

//JOIN dependencia ON dependencia.idPregunta = pregunta.id SE PONE EN DEPENDENCIA.IDDEPENDE
mysqli_set_charset ( $conexion, "utf8" ); // formato de datos utf8

if (! $result = mysqli_query ( $conexion, $sql ))
	die ();

$formulario = array (); // creamos un array

$preguntaAnterior = null;
while ( $row = mysqli_fetch_array ( $result ) ) {



	$idPregunta = $row ['idPregunta'];
	$pregunta = $row ['pregunta'];
	$idRespuesta = $row ['idRespuesta'];
	$respuesta = $row ['respuesta'];
	$categoria = $row ['nombreCat'];
	$tipo = $row ['tipoPregunta'];
	$disponible = $row['disponible'];
	$idDepende = $row['idDepende'];
	$idPreguntaDepende = $row['idRespuestaDepende'];
	$imagen = $row['imagen'];
	//$textoInfo = $row['textoInfo'];

	

	if ($idPregunta != $preguntaAnterior) {

			//echo "<br/>";
	//var_dump($row);
	//echo "<br/>";

		if (isset ( $arrRespuestas )) {

			$arrPregunta ['respuesta'] = $arrRespuestas;
			$formulario [] = $arrPregunta;
		}

		$arrRespuestas = array ();
		$arrDependencias = array();
		
		if(isset($idPreguntaDepende))
		$arrDependencias = explode(",", $idPreguntaDepende);
		//var_dump($prueba);
		//die();
		// echo "hols:".$idPreguntaDepende;
		// die();

		$arrPregunta = array (
				'idPregunta' => $idPregunta,
				'pregunta' => $pregunta,
				'categoria' => $categoria,
				'tipo' => $tipo,
				'disponible' => $disponible,
				'idRespuestaDepende' => $arrDependencias,
				'imagen' => $imagen
		);

			$arrRespuestas [] = array (
					'respuesta' => $respuesta,
					'idRespuesta' => $idDepende
					//'texto' => $textoInfo
					
					
			);
		$preguntaAnterior = $idPregunta;

	} else {

		$arrRespuestas [] = array (
				'respuesta' => $respuesta,
				'idRespuesta' => $idDepende
				//'texto' => $textoInfo
				
		);
		// echo "arrRespuestas: ".sizeof($arrRespuestas)."<br/>";
		// echo "dentroDePregunta: ".sizeof($arrPregunta['respuesta'])."<br/>";
	}
}

// Cuando terminamos el bucle metemos la última pregunta
$arrPregunta ['respuesta'] = $arrRespuestas;
$formulario [] = $arrPregunta;

// desconectamos la base de datos
$close = mysqli_close ( $conexion ) or die ( "Ha sucedido un error inexperado en la desconexion de la base de datos" );

// Creamos el JSON
// $json_string = json_encode ( $formulario, JSON_PRETTY_PRINT );
// echo "<pre>";
// echo $json_string;
// echo "</pre>";

?>
