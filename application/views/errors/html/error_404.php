<?php
defined('BASEPATH') OR exit('No direct script access allowed');
header('Content-Type: application/json');

$data = null;
$responseError = true;
$responseCode = "404";
$responseDesc = null;

$response = array("error" => $responseError,"code" => $responseCode,"message" => $responseDesc, "data" => $data);

echo json_encode($response);
?>