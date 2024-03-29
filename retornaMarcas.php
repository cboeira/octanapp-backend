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

	$sql = $conn->prepare("SELECT DISTINCT marca FROM veiculo");
	$sql->execute();
	$sql->bind_result($marca);

	$teste = array();

	while ($sql->fetch()) {
		$temp = array();
		$temp['marca'] = $marca;
		array_push($response, $temp);
	}

	$teste['result'] = $response;

	echo json_encode($teste);

	$conn->close();
}

/*	$sql = "SELECT DISTINCT marca FROM veiculo";

	$result = $conn->query($sql);

	$temp = array();

	while ($linha = mysqli_fetch_assoc($result)) {
		$tmp[] = $linha;

	}
	echo json_encode($tmp);*/
?>

