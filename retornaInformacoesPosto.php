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

	$id_posto = $_GET['id_posto'];

	$sql = "SELECT razaoSociao, endereco, telefone, ramo, informacoes, bandeira, formaPagamento FROM estabelecimento INNER JOIN posto ON estabelecimento.cnpj = posto.cnpj and posto.id_posto = $id_posto ";
	$result = $conn->query($sql);

	$registro = mysqli_fetch_array($result);
	$response["razaoSocial"] = $registro['razaoSociao'];
	$response["endereco"] = $registro['endereco'];
	$response["telefone"] = $registro['telefone'];
	$response["ramo"] = $registro['ramo'];
	$response["informacoes"] = $registro['informacoes'];
	$response["bandeira"] = $registro['bandeira'];
	$response["formaPagamento"] = $registro['formaPagamento'];

	echo json_encode($response);
	$conn->close();
}
?>