<?php
header('Content-Type: application/json');
include '../../Aretha.php';
Aretha::sessionStart();

$response = array("expired" => false);

if (!Aretha::sessionGranted()) {
    $response['expired'] = true;
} else {
	$response['expired'] = false;
}
echo json_encode($response);
?>