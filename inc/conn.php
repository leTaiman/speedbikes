<?php 
$dbhost="127.0.0.1";  // localhost
$dbport =""; 
$dbname="speedbikes";

$usuario="usuarioweb";
$contrasenia="HOLApayaso_2019";

$strCnx = "mysql:dbname=$dbname;host=$dbhost";  // ;charset=utf8

$db ="";

try {
	$db = new PDO($strCnx, $usuario, $contrasenia);
	
	$db->setAttribute(PDO::ATTR_CASE,PDO::CASE_LOWER); # para referenciar en minuscula el nombre de las columnas
	$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC); # Relizar el FETCh ASSOC por defecto para ahorrar memoria
	
} catch (PDOException $e) {
    print "Error: no se pudo ingresar a la base de datos  " . $e->getMessage() . "<br/>";   # cambiar por un error personalizado 
    die();
}
?>