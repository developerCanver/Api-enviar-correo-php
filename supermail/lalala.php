<?php

/// localhost/salamarkesa.com/supermail/lalala.php


// Incluyo la libreria del tito google
require_once __DIR__.'/tito_google/vendor/autoload.php';
//require_once  '/path/to/your-project/vendor/autoload.php' ;

// Creamos una conexión con la clase Google_Client
$client = new Google_Client();

// Nos identificamos, con los datos guardados en el JSON de clavesitas
$client->setAuthConfigFile('clavesitas.json');

// Establecemos estos dos parámetros que sirven para que la APP pueda conseguir permisos de actuar por su cuenta sin que el usuario esté presente
$client->setAccessType('offline');
$client->setApprovalPrompt('force');

// Solicitamos los permisos que necesitamos que el usuario nos ceda
// LISTADO --> https://developers.google.com/identity/protocols/googlescopes
$client->addScope('https://mail.google.com/');

// Donde vamos pa hacer el control?
//$client->setRedirectUri('https://www.salamarkesa.com/supergmail/lalala.php');
$client->setRedirectUri('http://localhost/salamarkesa.com/supermail/lalala.php');

// Ejecutamos el control, o bien se ha identificado o bien tiene que hacerlo
if(!isset($_GET['code'])){

//Aún no lo ha hecho, lo redirigimos pa que le salga la ventanita de permisos esa molona
$auth_url = $client->createAuthUrl();
header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));

}else{

//Ya lo ha hecho, lo autenticamos pa sacar su mail del id del calendario primario
$client->authenticate($_GET['code']);
$merengue = $client->getAccessToken();

// Guardamos la clave refrescadora
$codigo=$merengue['refresh_token'];
echo $codigo;

// Lo normal es guardarla en una base de datos, pero ahora solo copiala para pegarla en el otro archivo.
}