<?php session_start(); ?>
<!DOCTYPE html>

<html lang="es" xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	
	<!-- NO CACHE -->
	<meta http-equiv="cache-control" content="max-age=0" />
	<meta http-equiv="cache-control" content="no-cache" />
	<meta http-equiv="expires" content="0" />
	<meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
	<meta http-equiv="pragma" content="no-cache" />		
    <title></title>
    <script src="js/script.js"></script>
    <link href="css/style.css" rel="stylesheet" type="text/css" />

</head>
<?php
$servername = "localhost";
$user = "root";
$password = "";
$dbname = "fvs_grupo1";
$conn = new mysqli($servername, $user,$password,
$dbname);
$usuario = $_SESSION['userid'];


// Check connection
if ($conn->connect_error) {
	die("Error: " . $conn->connect_error);
}

$conn->set_charset("utf8");
    
$sql = "SELECT responde.idPersona, pregunta.pregunta, respuesta.respuesta, depende.valorRespuesta, textoInformativo.texto 
FROM responde 
JOIN depende ON responde.idRespuesta=depende.id 
JOIN pregunta ON pregunta.id=depende.idPregunta 
JOIN respuesta ON respuesta.id=depende.idRespuesta 
LEFT JOIN textoInformativo ON depende.idTexto=textoInformativo.id 
WHERE responde.idPersona=$usuario";
$resultadoFinal=0;
$result = $conn->query($sql);
if ($result->num_rows > 0) {

while($row = $result->fetch_assoc()) {

	echo "<div class='capaRespuesta'>";
	echo " Pregunta: ".$row["pregunta"]."<br>";
	echo " Respuesta: ".$row["respuesta"]."<br>";
	echo " Valor: ".$row["valorRespuesta"]."<br>";
	echo " Texto: ".$row["texto"]."<br>";
	$resultadoFinal = $row["valorRespuesta"] + $resultadoFinal;   
	echo "</div>";
}
} else {
echo "0 results";
}

echo "<h1>Puntos Totales: ".$resultadoFinal."</h1>";

if($resultadoFinal<25){
    echo "<h1>Si todos fueramos como tu, ¡necesitariamos un planeta para sobrevivir! Enhorabuena</h1>";
}else if($resultadoFinal>=25 && $resultadoFinal<=50){
    echo "<h1>Si todos fueramos como tu, ¡necesitariamos de dos a tres planetas para sobrevivir! Es insostenibe</h1>";
}else if($resultadoFinal>=50){
    echo "<h1>Si todos fueramos como tu, ¡necesitariamos cinco o más planetas para sobrevivir! Es completamente insostenibe</h1>";
}

$conn->close(); 
?>

</html>