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

	$sqlNumeroAv = "SELECT nota, cnpj, avaliacao.id_posto from avaliacao inner join posto where 
					avaliacao.id_posto = posto.id_posto and posto.id_posto =1 
					union all
					SELECT nota, avaliacaosimples.cnpj, posto.id_posto from avaliacaosimples inner join posto where avaliacaosimples.cnpj = posto.cnpj and posto.id_posto = $id_posto ";
	$resultNumeroAv = $conn->query($sqlNumeroAv);

	$response["numeroAvaliacoes"] = $resultNumeroAv->num_rows;

	$sqlNotaMedia = "SELECT AVG(nota) from (
					select nota, cnpj, avaliacao.id_posto from avaliacao inner join posto where avaliacao.id_posto = posto.id_posto and posto.id_posto = $id_posto union all
					select nota, avaliacaosimples.cnpj, posto.id_posto from avaliacaosimples inner join posto where avaliacaosimples.cnpj = posto.cnpj and posto.id_posto = $id_posto) as mediaNota";

	$resultNotaMedia = $conn->query($sqlNotaMedia);

	$registro = mysqli_fetch_array($resultNotaMedia);
	$response["notaMedia"] = $registro['AVG(nota)'];


	echo json_encode($response);
	$conn->close();
}
?>