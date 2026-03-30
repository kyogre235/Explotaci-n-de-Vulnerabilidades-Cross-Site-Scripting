<?php
// Activamos reporte de errores para tu entorno Arch Linux
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Reflected XXS - Facultad de Ciencias</title>
<style>
:root { --primary: #0b7dda; --bg: #f8fafc; --text: #1e293b; --card: #ffffff; }
body { font-family: 'Segoe UI', sans-serif; background: var(--bg); color: var(--text); padding: 20px; line-height: 1.6; }
.container { max-width: 900px; margin: auto; }
.card { background: var(--card); padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 25px; border: 1px solid #e2e8f0; }
h1, h2, h3 { color: var(--primary); margin-top: 0; }

/* Estilo del buscador */
.search-box { display: flex; gap: 10px; margin: 20px 0; }
input[type="text"] { flex-grow: 1; padding: 12px; border-radius: 8px; border: 1px solid #cbd5e1; font-size: 1rem; }
button { background: var(--primary); color: white; border: none; padding: 10px 25px; border-radius: 8px; cursor: pointer; font-weight: 600; }

/* El "Eco" del buscador (Vulnerable) */
.result-output { background: #fff3cd; padding: 15px; border-radius: 8px; border-left: 5px solid #ffc107; font-style: italic; margin: 20px 0; }

.code-snippet { background: #f1f5f9; padding: 10px; border: 1px solid #cbd5e1; border-radius: 6px; font-family: monospace; display: block; margin: 10px 0; overflow-x: auto; }
.hidden-code { color: #FFFFFF; font-size: 0.1rem; } /* El código secreto que venía al final */
.nav-btn { background: var(--primary); color: white; padding: 10px 20px; border-radius: 6px; text-decoration: none; float: right; }
</style>
</head>
<body>

<div class="container">
<div class="card">
<h1> XXS Crossscripting </h1>
<p>Cross Site Scripting (XSS) es una vulnerabilidad de seguridad que permite a atacantes maliciosos inyectar su propio código malicioso en el sitio web legítimo de víctimas desprevenidas. Esto se puede utilizar para aprovechar las vulnerabilidades del lado de la víctima y causar consecuencias importantes.</p>
<p> Permite que un atacante se haga pasar por el usuario afectado, realice cualquier acción que el usuario sea capaz de hacer y obtenga acceso a cualquier información del usuario. Si el usuario afectado tiene acceso privilegiado a la aplicación, el atacante podría obtener el control total sobre toda la funcionalidad y los datos de la aplicación.</p>
</div>

<div class="card">
<h3> Clasificación de ataques XXS </h3>
<p> Existen varios tipo de ataques, dependiendo de donde viene el codigo malicioso. Los principales son:
<i>Reflected XSS, </i>
<i>Stored XSS</i> y
<i>DOM XSS</i>
</p>

<h3> Ejercicios de la practica </h3>
<p> Esta serie de paginas cuentan con una serie de ejercicios para que puedas llevar acabo una serie de ataques los cuales te permitan comprender como es que este ataque se lleva acabo. Deberás documentar cada uno de tus progresos y entregar un reporte al respecto. Finalmente investigarás como prevenir para que ya no suceda estos problemas de vulnerabilidad.</p>
</div>

<div class="card">
<h2> Reflected XSS </h2>
<p>En este cuadro de texto puedes buscar cualquier palabra que se te ocurra. Es un claro ejemplo de como funciona la vulnerabilidad Reflected XSS.</p>

<form class="search-box" action="reflectedXxs.php" method="GET">
<input type="text" placeholder="search..." name="search">
<button type="submit">Buscar</button>
</form>

<?php
if(isset($_GET["search"])) {
    echo "<div class='result-output'>No se encontraron resultados a la busqueda: ".$_GET["search"]."</div>";
}
?>

<p>Reflected cross-site scripting (or XSS) surge cuando una aplicación recibe datos en una solicitud HTTP e incluye esos datos dentro de la respuesta inmediata de una manera insegura. Veamos el ejemplo de esta página:</p>
<code class="code-snippet">Proyecto03/reflectedXxs.php?search=zapato</code>
<p>La aplicación se hace eco del término de búsqueda proporcionado en la respuesta a esta URL:</p>
<code class="code-snippet">No se encontraron resultados a la busqueda: zapato</code>
<p>Suponiendo que la aplicación no realice ningún otro procesamiento de los datos, un atacante puede construir un ataque como este:</p>
<code class="code-snippet">Proyecto03/reflectedXxs.php?search=&lt;script&gt;/*+codigo malicioso...+*/&lt;/script&gt;</code>
</div>

<div class="card">
<h1> XSS DOM </h1>
<p>El DOM o Document Object Model es una interfaz de programación de aplicaciones (API) que permite leer, acceder y modificar el frontend del código fuente de una aplicación web. El DOM representa archivos XML o HTML en una estructura de árbol, basada en la jerarquía de los objetos que componen la página web.</p>
<p>El XSS DOM o basado en DOM es aquel que se realiza inyectando comandos de JavaScript en el DOM de una página web. Una aplicación web vulnerable permite ejecutar código malicioso desde su frontend, debido a una falta de validación de los inputs de un usuario.</p>

<p>Por ejemplo, si llamas a la URL:</p>
<code class="code-snippet">http://localhost/Proyecto03/reflectedXxs.php?search=Seguridad</code>

<p>Un atacante podría usar:</p>
<code class="code-snippet">http://localhost/Proyecto03/reflectedXxs.php?search=%3c%73%63%72%69%70%74%3e%61%6c%65%72%74%28%22%4c%45%41%56%45%20%54%48%49%53%20%50%41%47%45%21%20%59%4f%55%20%41%52%45%20%42%45%49%4e%47%20%48%41%43%4b%45%44%21%22%29%3b%3c%2f%73%63%72%69%70%74%3e</code>

<p><small> Todo lo seguido en el search es simplemente scripts codificados en hexadecimal para dificil detección.</small></p>
</div>

<div class="card" style="border-left: 5px solid #ef4444;">
<h3>Ahora es tu turno. Con el ejemplo realiza las siguientes tareas:</h3>
<ul>
<li>Agrega de el mensaje "Esta página es vulnerable" a color rojo y con la letra en cursiva.</li>
<li>Crea una ventana de alert con el mensaje "hackeado".</li>
<li>Agrega y modifica la página con cambios en html y script de manera en que agregues color y estilo a la información dada previamente.</li>
<li>Agrega dos scripts y agregalos a la url de manera que el primero te redirigan a "Writing Secure Code" y el segundo muestre una alerta y un redireccionamiento a google.com.</li>
</ul>
</div>

<p class="hidden-code">%3C%73%63%72%69%70%74%3E%61%6C%65%72%74%28%22%4D%79%20 %6E%61%6D%65%20%69%73%20%58%58%53%22%29%3B%3C%2F%73%63%72%69%70%74%3E</p>

<div style="overflow: hidden; padding-bottom: 40px;">
<a href="storeXss.php" class="nav-btn">Siguiente: Stored XSS</a>
</div>
</div>

</body>
</html>
