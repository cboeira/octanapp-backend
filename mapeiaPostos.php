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

	$sql = $conn->prepare("SELECT coordenadas, posto.nomeFantasia, posto.id_posto, posto.bandeira from estabelecimento inner join posto where estabelecimento.cnpj = posto.cnpj ");
	$sql->execute();
	$sql->bind_result($coordenadas, $nomeFantasia, $id_posto, $bandeira);

	$teste = array();

	while ($sql->fetch()) {
		$temp = array();
		$temp['coordenadas'] = $coordenadas;
		$temp['nomeFantasia'] = $nomeFantasia;
		$temp['id_posto'] = $id_posto;
		$temp['bandeira'] = $bandeira;

		array_push($response, $temp);
	}

	$teste['result'] = $response;

	echo json_encode($teste);

	$conn->close();
}
?>