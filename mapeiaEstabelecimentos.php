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

	$sql = $conn->prepare("SELECT coordenadas, razaoSociao, estabelecimento.cnpj, estabelecimento.ramo from estabelecimento left join posto on estabelecimento.cnpj = posto.cnpj where posto.cnpj is null ");
	$sql->execute();
	$sql->bind_result($coordenadas, $razaoSocial, $cnpj, $ramo);

	$teste = array();

	while ($sql->fetch()) {
		$temp = array();
		$temp['coordenadas'] = $coordenadas;
		$temp['razaoSocial'] = $razaoSocial;
		$temp['cnpj'] = $cnpj;
		$temp['ramo'] = $ramo;

		array_push($response, $temp);
	}

	$teste['result'] = $response;

	echo json_encode($teste);
	$conn->close();
}
?>