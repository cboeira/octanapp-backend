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
	$id_usuario = $_GET['id_usuario'];

	$sqlNumeroAv = "SELECT nota, cnpj, avaliacao.id_posto from avaliacao inner join posto where 
					avaliacao.id_posto = posto.id_posto and posto.id_posto = $id_posto 
					union all
					SELECT nota, avaliacaosimples.cnpj, posto.id_posto from avaliacaosimples inner join posto where avaliacaosimples.cnpj = posto.cnpj and posto.id_posto = $id_posto ";
	$resultNumeroAv = $conn->query($sqlNumeroAv);

	$response["numeroAvaliacoes"] = $resultNumeroAv->num_rows;

	$sqlNotaMedia = "SELECT AVG(nota) from (
					select nota, cnpj, avaliacao.id_posto from avaliacao inner join posto where avaliacao.id_posto = posto.id_posto and posto.id_posto = $id_posto union all
					select nota, avaliacaosimples.cnpj, posto.id_posto from avaliacaosimples inner join posto where avaliacaosimples.cnpj = posto.cnpj and posto.id_posto = $id_posto) as mediaNota";

	$resultNotaMedia = $conn->query($sqlNotaMedia);

	$registro = mysqli_fetch_array($resultNotaMedia);

	$notaMedia = $registro['AVG(nota)'];

	if ($notaMedia == null) {
		$response['notaMedia'] = 0;
	} else {
		$response['notaMedia'] = (float)number_format($notaMedia, 2, '.', '');;
	}

	$sqlInfoPosto = "SELECT id_posto, nomeFantasia, bandeira, cnpj FROM posto WHERE id_posto = $id_posto";
	$resultInfoPosto = $conn->query($sqlInfoPosto);
	$registro = mysqli_fetch_array($resultInfoPosto);
	$response["nomeFantasia"] = $registro['nomeFantasia'];
	$response["bandeira"] = $registro['bandeira'];
	$response["id_posto"] = $registro['id_posto'];
	$cnpj = $registro['cnpj'];

	$sqlCoordenadas = "SELECT coordenadas FROM estabelecimento WHERE cnpj = $cnpj";
	$resultCoord = $conn->query($sqlCoordenadas);
	$registro = mysqli_fetch_array($resultCoord);
	$response["coordenadas"] = $registro['coordenadas'];

	$sqlFavorito = "SELECT * from postofavorito WHERE id_posto = $id_posto AND id_usuario = $id_usuario";
	$result = $conn->query($sqlFavorito);
	if ($result->num_rows > 0) {
		$response["favorito"] = true;
	} else {
		$response["favorito"] = false;
	}
	


	echo json_encode($response);
	$conn->close();
}
?>