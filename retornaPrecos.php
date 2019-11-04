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

	$sql = $conn->prepare("SELECT nome, preco FROM combustivel INNER JOIN combustivelposto WHERE combustivel.id_combustivel = combustivelposto.id_combustivel AND id_posto = $id_posto ");
	$sql->execute();
	$sql->bind_result($nome, $preco);

	while ($sql->fetch()) {
		$temp = array();
		$temp['nome'] = $nome;
		$temp['preco'] = $preco;
		array_push($response, $temp);
	}


	echo json_encode($response);
	$conn->close();
}
?>