<?php
// Incluyo la libreria del tito google
require_once __DIR__.'/tito_google/vendor/autoload.php';
// http://localhost/salamarkesa.com/supermail/enviar-cositas.php

// Nombre pa mandar el tema
$nombremenda='Menda D. Enviador';

// Gmail del nota que manda
$dequien='canverlanix@gmail.com';

// A quién queremos mandarle
$paquien='canverlanix@gmail.com';

// Clave OAuth 2.0
$codigo='JfgtanLUkJR3JuSAVsIRtJT_';

// Asunto del mensaje
$asuntazo='Plato de queso fresco';

// Contenido del mensaje en HTML
$contenido='Te regalo un <b>queso</b> francés.';

// Creamos una conexión con la clase Google_Client
$client = new Google_Client();

// Nos identificamos, con los datos guardados en el JSON de clavesitas
$client->setAuthConfigFile('clavesitas.json');

// Dale caña
$client->refreshToken($codigo);

// Iniciamos un GMAIL service
$service = new Google_Service_Gmail($client);
echo '<h2>Envío mail a '.$paquien.'</h2>';
echo '<h3>Desde cuenta '.$dequien.'</h3>';

// Limpiamos los caracteres graciosos primero
$subject = mb_encode_mimeheader($asuntazo, 'UTF-8');
$nombremendaguay = mb_encode_mimeheader($nombremenda, 'UTF-8');
$msg = "To: $paquien\n";
$msg .= "From: $nombremendaguay <$dequien>\n";
$msg .= "Subject: $subject\n";
$msg .= "MIME-Version: 1.0\n";
$msg .= "Content-Type: multipart/mixed;\n";
$boundary = uniqid("_Part_".time(), true); 
$boundary2 = uniqid("_Part2_".time(), true);
$msg .= " boundary=\"$boundary\"\n";
$msg .= "\n";
$msg .= "--$boundary\n";
$msg .= "Content-Type: multipart/alternative;\n";
$msg .= " boundary=\"$boundary2\"\n";
$msg .= "\n";
$msg .= "--$boundary2\n";

// Parte de texto plano
$msg .= "Content-Type: text/plain; charset=utf-8\n";
$msg .= "Content-Transfer-Encoding: 7bit\n";
$msg .= "\n";
$msg .= strip_tags($contenido); //remove any HTML tags
$msg .= "\n";

// Parte HTML
$msg .= "--$boundary2\n";
$msg .= "Content-Type: text/html; charset=utf-8\n";
$msg .= "Content-Transfer-Encoding: 7bit\n";
$msg .= "\n";
$msg .= $contenido;
$msg .= "\n";
$msg .= "--$boundary2--\n";

// Parte de archivo adjunto
$msg .= "\n";
$msg .= "--$boundary\n";
$msg .= "Content-Transfer-Encoding: base64\n";
$msg .= "Content-Type: {image/jpg}; name=una-imagen-adjunta.jpg;\n";
$msg .= "Content-Disposition: attachment; filename=una-imagen-adjunta.jpg;\n";
$msg .= "\n";
$msg .= base64_encode(file_get_contents('https://www.salamarkesa.com/wp-content/uploads/2017/03/superman-1367737_1280-min.jpg'));
$msg .= "\n--$boundary";

// Cerrar mensaje
$msg .= "--\n";
echo '<pre>'.htmlentities($msg).'</pre>';

// Intentamos mandar el tema
try {

// Codificamos el mensaje en base64 url safe
$mime = rtrim(strtr(base64_encode($msg), '+/', '-_'), '=');
$msg = new Google_Service_Gmail_Message();
$msg->setRaw($mime);
$objSentMsg = $service->users_messages->send("me", $msg);
echo '<h4>Resultado:</h4>';
echo '<pre>';
var_dump($objSentMsg);
echo '</pre>';
} catch (Exception $e) {
echo '<h4>Fallo gordo:</h4>';
print($e->getMessage());
}