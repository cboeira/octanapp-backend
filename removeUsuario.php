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

	$id_usuario = $_POST['id_usuario'];
	$response['veiculo_removido'] = "false";

	$sqlValidaVeiculoEmplado = "SELECT * FROM veiculoemplacado WHERE id_usuario = $id_usuario";
	$sqlRemoveVeiculoEmplacado = "DELETE FROM veiculoemplacado WHERE id_usuario = $id_usuario";
	$sqlRemoveUsuario = "DELETE FROM usuario WHERE id_usuario = $id_usuario";

	$resultValida = $conn->query($sqlValidaVeiculoEmplado);
	if ($resultValida->num_rows > 0) {
		$resultRemoveVeiculo = $conn->query($sqlRemoveVeiculoEmplacado);
		$response['veiculo_removido'] = "true";
	}
	$resultRemoveUsuario = $conn->query($sqlRemoveUsuario);
	$response['mensagem']="Usuário removido.";
	echo json_encode($response);
	$conn->close();
}
?>