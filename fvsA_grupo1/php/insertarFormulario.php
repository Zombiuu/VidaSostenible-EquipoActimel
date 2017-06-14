
<?php session_start(); ?>
<?php
// var_dump($_POST);

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "fvs_grupo1";


$pais = $_POST['selectpaises'];
$ccaa= $_POST['selectccaa'];
$edad= $_POST['selectedad'];
$tipoCasa= $_POST['selecttipoCasa'];
$espacioCasa= $_POST['selectespacioCasas'];
$numPersonas= $_POST['selectnumPersonas'];
$ingresos = $_POST['selectingresos'];
$conocimiento = $_POST['selectconocimientos'];
$estudios = $_POST['selectestudios'];

//INSERT INTO `responde` (`id`, `idPersona`, `idRespuesta`) VALUES (NULL, '1', '3');
//INSERT INTO `persona` (`id`, `pais`, `ccaa`, `edad`, `tipoCasa`, `m2Casa`, `numPersonas`, `ingresos`, `conocimiento`, `estudios`, `sexo`) VALUES (NULL, '73', '5', '3', '1', '1', '2', '3', '3', '4', 'femenino');
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

// PREGUNTAS GENERALES
 $sql = "INSERT INTO `persona` (`pais`, `ccaa`, `edad`, `tipoCasa`, `m2Casa`, `numPersonas`, `ingresos`, `conocimiento`, `estudios`, `sexo`) VALUES ($pais, $ccaa, $edad, $tipoCasa, $espacioCasa, $numPersonas, $ingresos, $conocimiento , $estudios, 'femenino')";

// echo "<br/>";
// echo $sql;
// echo "<br/>";




if ($conn->query($sql) === TRUE) {
    
    $lastId = $conn->insert_id;

    // echo "Usu insertado ".$lastaId;
            // PREGUNTAS CON HUELLA ECOLOGICA
            foreach($_POST as $inputName => $respuesta){
                 // SI $inputName contiene "pregunta" 
                if (is_numeric($inputName)) {
                    $sql = "INSERT INTO `responde` (`idPersona`, `idRespuesta`) VALUES ($lastId, $respuesta)";
                        // echo "<br/>";
                        // echo $sql;
                        // echo "<br/>";

                    if ($conn->query($sql) === TRUE) {
                           // echo "New record created successfully";
                        } //else {
                           // echo "Error: " . $sql . "<br>" . $conn->error;
                       // }  
    }

}

} //else {
//     echo "Error: " . $sql . "<br>" . $conn->error;
// }


$conn->close();
?>
<?php
header('Location: ../resultado/index.php');
$_SESSION['userid'] = $lastId;
?>
