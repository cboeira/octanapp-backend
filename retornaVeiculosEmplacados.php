<?php
header('Content-Type: application/json charset=utf-8');
error_reporting(0);

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

	include 'dbConnection.php';

	$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);

	mysqli_set_charset($conn, "utf8");

	if ($conn->connect_error) {
		die ("Falha de conexao: " . $conn->connect_error);
	}

	$id_usuario = $_GET['id_usuario'];

	$sql = $conn->prepare("SELECT placa, kmTotal, ano, veiculo.id_veiculo, modelo, marca, ativo FROM veiculoemplacado INNER JOIN veiculo ON veiculoemplacado.id_veiculo = veiculo.id_veiculo and veiculoemplacado.id_usuario = $id_usuario ");
	$sql->execute();
	$sql->bind_result($placa, $kmTotal, $ano, $id_veiculo, $modelo, $marca, $ativo);

	while ($sql->fetch()) {
		$temp = array();
		$temp['placa'] = $placa;
		$temp['kmTotal'] = $kmTotal;
		$temp['ano'] = $ano;
		$temp['id_veiculo'] = $id_veiculo;
		$temp['modelo'] = $modelo;
		$temp['marca'] = $marca;
		$temp['ativo'] = $ativo;

		array_push($response, $temp);
	}
	echo json_encode($response);
	$conn->close();
}
?>