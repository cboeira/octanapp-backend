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

	$sql = $conn->prepare("SELECT postofavorito.id_posto, a.nomeFantasia, a.bandeira from postofavorito inner join (select posto.nomeFantasia, posto.id_posto, posto.bandeira from estabelecimento inner join posto where estabelecimento.cnpj = posto.cnpj) as a where postofavorito.id_posto = a.id_posto and postofavorito.id_usuario = $id_usuario");
	$sql->execute();
	$sql->bind_result($id_posto, $nomeFantasia, $bandeira);

	while ($sql->fetch()) {
		$temp = array();
		$temp['id_posto'] = $id_posto;
		$temp['nomeFantasia'] = $nomeFantasia;
		$temp['bandeira'] = $bandeira;

		array_push($response, $temp);
	}
	echo json_encode($response);
	$conn->close();
}
?>