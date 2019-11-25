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

	$cnpj = $_GET['cnpj'];

	$sqlNumeroAv = "SELECT * from avaliacaosimples where cnpj = '$cnpj'";
	$resultNumeroAv = $conn->query($sqlNumeroAv);

	$response["numeroAvaliacoes"] = $resultNumeroAv->num_rows;

	$sqlNotaMedia = "SELECT AVG(nota) from avaliacaosimples where cnpj = '$cnpj'";

	$resultNotaMedia = $conn->query($sqlNotaMedia);

	$registro = mysqli_fetch_array($resultNotaMedia);

	$notaMedia = $registro['AVG(nota)'];

	if ($notaMedia == null) {
		$response['notaMedia'] = 0;
	} else {
		$response['notaMedia'] = (float)number_format($notaMedia, 2, '.', '');;
	}

	$sqlInfoEstab = "SELECT razaoSociao, endereco, telefone, informacoes, ramo, coordenadas from estabelecimento where cnpj = '$cnpj'";
	$resultInfoEstab = $conn->query($sqlInfoEstab);
	$registro = mysqli_fetch_array($resultInfoEstab);
	$response["razaoSocial"] = $registro['razaoSociao'];
	$response["endereco"] = $registro['endereco'];
	$response["telefone"] = $registro['telefone'];
	$response["informacoes"] = $registro['informacoes'];
	$response["ramo"] = $registro['ramo'];
	$response["coordenadas"] = $registro['coordenadas'];


	echo json_encode($response);
	$conn->close();
}
?>