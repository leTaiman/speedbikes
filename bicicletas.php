<!DOCTYPE html>
<html lang="es">
<?php
require_once 'inc/conn.php';
require_once('inc/session.php');
include("encabezado.php");
include("pie.php");

generar_tit($titulo);
//generar_menu($menu_ppal,1);


#datos bicicletas

if (!perfil_valido(2)) {
	header("location:index.php");
}


$sql = "SELECT bicicletas.*, marcas.*, detalle. *
			FROM bicicletas
			INNER JOIN marcas ON marcas.id_marca=bicicletas.id_marca
            INNER JOIN detalle ON detalle.id_detalle=bicicletas.id_detalle
            INNER JOIN rodados ON rodados.medida=bicicletas.id_rodado			
			ORDER BY id_bicicleta ";
    $rs = $db->query($sql);
	
	$lista="";
	
	if (!$rs) {
		print_r($db->errorInfo());  #CUIDADO - mensajes de error en desarrollo  - en producción se emite mensaje amigable y que no muestre información del sistema
	} else {
		
		foreach($rs as $fila) {
			
			if (is_null($fila['nombre_marca'])) {
				$marca="__sin cargo__ -";
				$fecingr="";
				// $agregarTrabajo=" <a href='personas_cargo.php?tipo=A&id_bicicleta={$fila['id_bicicleta']}'> Agregar Trabajo</a> ";
			} else {
				$marca=utf8_encode($fila['nombre_marca']);
				$fecingr="(".date('d-m-Y',strtotime($fila['fecha_fabricacion'])).") -";
				// $agregarTrabajo=" <a href='personas_cargo.php?tipo=M&id_bicicleta={$fila['id_bicicleta']}'> Modificar Bicicleta</a> ";
			}
			
			$nombre=utf8_encode($fila['nombre_marca']).", ". utf8_encode($fila['nombre_bicicleta']);
			
			$lista.="<li>".
					"  <strong>$marca ----  </strong> $nombre ---- ". 
					"  $fecingr ____ ".
					"  <a href='abm_bicicleta.php?tipo=M&id_bicicleta={$fila['id_bicicleta']}'>Modificar</a> | ".
					"  <a href='#' onclick='javascript:borrar({$fila['id_bicicleta']});'>Baja</a>  ".
					// "  $agregarTrabajo ".
					"</li>";
		}		
	}

	$rs=null;
    $db=null;

?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <title>Bicicletas</title>

    <!-- <meta name="description" content="breve descripcion del sitio">
    <meta name="keywords" content="palabraclave1,palabraclave2,palabraclave3">
    <meta name="robots" content="index,nofollow"> -->

    <link rel="shortcut icon" href="images/Logo-Speed-Bikes.png" type="image/x-icon" />

    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/estilos.css">
    <link rel="stylesheet" href="css/all.min.css">

    <!-- jQuery first, then Popper.js (incluido en .blunde.min.js), then Bootstrap JS -->
    <script src="js/jquery-3.4.1.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>

    <script type="text/javascript">
    function ajax_conn(params, url) {
        var ajax_url;

        /*
        	Obtene una instancia del objeto XMLHttpRequest con el que JavaScript puede comunicarse con el servidor 
        	de forma asíncrona intercambiando datos entre el cliente y el servidor sin interferir en el comportamiento actual de la página. 
        */
        if (window.XMLHttpRequest) {
            conn = new XMLHttpRequest();
        } else if (window.ActiveXObject) { // ie 6
            conn = new ActiveXObject("Microsoft.XMLHTTP");
        }


        conn.onreadystatechange = respuesta;
        /*
        	Preparar la funcion de respuesta
        	cuando exista un cambio de estado se llama a la funcion respuesta (para que maneja la respuesta recibida)
        	la URL solicitada podría devolver HTML, JavaSript, CSS, texto plano, imágenes, XML, JSON, etc.
        */

        ajax_url = url + '?' + params;

        conn.open("GET", ajax_url, true);
        /*
        método XMLHttpRequest.open. 
        - método: el método HTTP a utilizar en la solicitud Ajax. GET o POST.
        url: dirección URL que se va a solicitar, la URL a la que se va enviar la solicitud.
        async: true (asíncrono) o false (síncrono).  -- asíncronico: no se espera la respuesta del servidor - sincronico: se espera la repuesta del servidor
        */

        conn.send(); // Enviamos la solicitud - por metodo GET

        /* Metodo POST  
        conn.open('POST', url);
        			// Si se utiliza el método POST, la solicitud necesita ser enviada como si fuera un formulario
        conn.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        conn.send(parametros);
        */

    }



    function respuesta() {
        /*
        	El valor de readyState 4 indica que la solicitud Ajax ha concluido 
        	y la respuesta desde el servidor está disponible en XMLHttpRequest.responseText
        */
        if (conn.readyState == 4) { // estado de conexión es completo - response.success
            if (conn.status == 200) { // estado devuelto por el HTTP fue "OK" 
                // conn.responseText - repuesta del servidor
                if (conn.responseText == 1) {
                    location.reload(); // se borro un empleado - se refresca la pag
                } else {
                    alert("La bicicleta no se puede borrar");
                }
            }
        }
    }

    function borrar(id) {
        var errores = 0;

        // validar ID

        // armar parametros a enviar - forma param1=valo1&param2=valor2 ...
        params = "id_bicicleta=" + id;
        // archivo,  al que se le solcita una tarea  (URL que se va a solicitar via Ajax)
        url = "personas_borrar.php";

        if (errores == 0) {
            if (confirm('¿Está seguro que quiere borrar la bicicleta?')) {
                ajax_conn(params, url);
            }
        }
    }
    </script>

</head>

<body>
    <div class="container">
        <header>
            <?php echo crear_barra(0); ?>
            <div id="encab">
                <?=$titulo?>
            </div>
        </header>

        <main id="cuerpo">

            <a href="abm_bicicleta.php?tipo=A">&raquo;Ingresar una bicicleta</a>

            <h3>Listado de Bicicletas</h3>

            <ul>
                <?=$lista ?>
            </ul>

        </main>


        <footer>
            <?=pie()?>
        </footer>

</body>

</html>