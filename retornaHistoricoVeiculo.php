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

	$placa = $_GET['placa'];

	$sql = $conn->prepare("SELECT a.kmtotal, a.litros, a.nota, a.horario, a.autonomia, a.nomeFantasia, a.bandeira, combustivel.nome, a.id_posto from (select avaliacao.id_combustivel, avaliacao.kmtotal, avaliacao.litros, avaliacao.nota, avaliacao.horario, avaliacao.autonomia, posto.nomeFantasia, posto.bandeira, posto.id_posto from avaliacao inner join posto where avaliacao.id_posto = posto.id_posto and avaliacao.placa = '$placa') as a inner join combustivel where a.id_combustivel = combustivel.id_combustivel order by a.horario");
	$sql->execute();
	$sql->bind_result($kmTotal, $litros, $nota, $horario, $autonomia, $nomeFantasia, $bandeira, $nomeCombustivel, $id_posto);

	while ($sql->fetch()) {
		$temp = array();
		$temp['kmTotal'] = $kmTotal;
		$temp['litros'] = $litros;
		$temp['nota'] = $nota;
		$temp['horario'] = $horario;
		$temp['autonomia'] = (float)number_format($autonomia, 2, '.', '');
		$temp['nomeFantasia'] = $nomeFantasia;
		$temp['bandeira'] = $bandeira;
		$temp['nomeCombustivel'] = $nomeCombustivel;	

		array_push($response, $temp);
	}
	echo json_encode($response);
	$conn->close();
}
?>