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

	$sql = $conn->prepare("SELECT nome, combustivel.id_combustivel FROM combustivel INNER JOIN combustivelposto WHERE combustivel.id_combustivel = combustivelposto.id_combustivel AND id_posto = $id_posto ");
	$sql->execute();
	$sql->bind_result($nome, $id_combustivel);

	$teste = array();

	while ($sql->fetch()) {
		$temp = array();
		$temp['nome'] = $nome;
		$temp['id_combustivel'] = $id_combustivel;
		array_push($response, $temp);
	}

	$teste['result'] = $response;

	echo json_encode($teste);
	$conn->close();
}
?>