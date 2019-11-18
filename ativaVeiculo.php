<?php 
header('Content-Type: application/json charset=utf-8');
error_reporting(0);

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	include 'dbConnection.php';

	$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);

	mysqli_set_charset($conn, "utf8");

	if ($conn->connect_error) {
		die ("Falha de conexao: " . $conn->connect_error);
	}

	$id_usuario = "'".$_POST['id_usuario']."'";
	$placa = "'".$_POST['placa']."'";

	$sqlRemoveAtivo = "UPDATE veiculoemplacado SET ativo = 0 WHERE id_usuario = $id_usuario AND ativo = 1";
	$result = $conn->query($sqlRemoveAtivo);

	$sqlAtivaVeiculo = "UPDATE veiculoemplacado SET ativo = 1 WHERE id_usuario = $id_usuario AND placa = $placa";
	$result = $conn->query($sqlAtivaVeiculo);
	$response["mensagem"] = "Veículo placa ".$_POST['placa']." foi ativo com sucesso";
	$conn->close();
}
echo json_encode($response);
?>