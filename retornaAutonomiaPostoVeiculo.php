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
	$id_veiculo = $_GET['id_veiculo'];

	$sqlCombustiveisPosto = $conn->prepare("SELECT A.nome, A.id_combustivel, A.preco, AVG(B.autonomia), COUNT(B.autonomia) from (SELECT nome, combustivel.id_combustivel, preco FROM combustivel INNER JOIN combustivelposto WHERE combustivel.id_combustivel = combustivelposto.id_combustivel AND id_posto = $id_posto) as A left join (SELECT autonomia, id_combustivel FROM avaliacao WHERE id_veiculo = $id_veiculo ) as B on A.id_combustivel = B.id_combustivel group by id_combustivel, preco");
	$sqlCombustiveisPosto->execute();
	$sqlCombustiveisPosto->bind_result($nome, $id_combustivel, $preco, $autonomia, $numeroAvaliacoes);

	while ($sqlCombustiveisPosto->fetch()) {
		$temp = array();
		$temp['nome'] = $nome;
		$temp['id_combustivel'] = $id_combustivel;
		$temp['preco'] = $preco;
		$temp['autonomia'] = (float)number_format($autonomia, 2, '.', '');
		if (is_null($autonomia)) {
			$temp['precokm'] = 0;
		} else {
			$temp['precokm'] = (float)number_format($preco/$temp['autonomia'], 2, '.', ''); 
		}
		$temp['numeroAvaliacoes'] = $numeroAvaliacoes;
		array_push($response, $temp);
	}

	echo json_encode($response);
	$conn->close();
}
?>