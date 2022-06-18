<?php
/**
 * <pre>
 * SFApi 2.0
 * index file
 * Last Updated: $Date: 2021-02-17
 * </pre>
 *
 * @author 		Łukasz G.
 * @package		SFApi
 * @version		2.0
 *
 */

namespace SFBOT;

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

//session_start();

$clientip = $_SERVER['REMOTE_ADDR'];

require_once("functions.php");
require_once("class/crypto.php");
require_once("class/socket.php");
require_once("class/response.php");
require_once("class/client.php");

$resp = new response();

if(!isset($_GET['oko']) or !isset($_GET['oko']) or json_decode($_GET['oko']) === null){
	$resp->message = "data is empty or json damaged";
	exit(json_encode($resp));
}

$oko = json_decode($_GET['oko']);

if(!isset($oko->act)){
	$resp->message = "missing act param";
	exit(json_encode($resp));
}

$client = new client();
$client->init($oko);



exit(json_encode($resp));
?>