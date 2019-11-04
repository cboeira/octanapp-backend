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

	$sql = $conn->prepare("SELECT coordenadas, posto.nomeFantasia, posto.id_posto from estabelecimento inner join posto where estabelecimento.cnpj = posto.cnpj ");
	$sql->execute();
	$sql->bind_result($coordenadas, $nomeFantasia, $id_posto);

	while ($sql->fetch()) {
		$temp = array();
		$temp['coordenadas'] = $coordenadas;
		$temp['nomeFantasia'] = $nomeFantasia;
		$temp['id_posto'] = $id_posto;

		array_push($response, $temp);
	}
	echo json_encode($response);
	$conn->close();
}
?>