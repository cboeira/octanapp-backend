<?php
header('Content-Type: application/json charset=utf-8');
error_reporting(0);

$response = array();
$response["erro"] = true;

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	
	include 'dbConnection.php';

	$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);

	mysqli_set_charset($conn, "utf8");

	if ($conn->connect_error) {
		die ("Falha de conexao: " . $conn->connect_error);
	}

	$id_usuario = $_GET['id_usuario'];

	$sqlVeiculoAtivo = "SELECT id_veiculo, placa, kmTotal, ano FROM veiculoemplacado WHERE id_usuario = $id_usuario and ativo = 1";
	$resultVeiculoAtivo = $conn->query($sqlVeiculoAtivo);

	if ($resultVeiculoAtivo->num_rows > 0) {
		$registro = mysqli_fetch_array($resultVeiculoAtivo);
		$id_veiculo = $registro["id_veiculo"];
		$response["id_veiculo"] = $registro["id_veiculo"];
		$response["placa"] = $registro["placa"];
		$response["kmTotal"] = $registro["kmTotal"];
		$response["ano"] = $registro["ano"];
		$sqlVeiculo = "SELECT marca, modelo FROM veiculo WHERE id_veiculo = $id_veiculo";
		$resultVeiculo = $conn->query($sqlVeiculo);
		$registro = mysqli_fetch_array($resultVeiculo);
		$response["marca"] = $registro["marca"];
		$response["modelo"] = $registro["modelo"];
		$response["erro"] = false;

	} else {
		$response["mensagem"] = "Nenhum veículo selecionado.";
	}
	echo json_encode($response);
	$conn->close();
}
?>