<?php 
header('Content-Type: application/json charset=utf-8');
error_reporting(0);

$response = array();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	include 'dbConnection.php';

	$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);

	mysqli_set_charset($conn, "utf8");

	if ($conn->connect_error) {
		die ("Falha de conexao: " . $conn->connect_error);
	}

	$marca = $_POST['marca'];

	$sql = $conn->prepare("SELECT id_veiculo, modelo FROM veiculo where marca like \"$marca%\"");
	$sql->execute();
	$sql->bind_result($id_veiculo, $modelo);

	$teste = array();

	while ($sql->fetch()) {
		$temp = array();
		$temp['modelo'] = $modelo;
		$temp['id_veiculo'] = $id_veiculo;
		array_push($response, $temp);
	}

	$teste['result'] = $response;

	echo json_encode($teste);

	echo json_encode($tmp);
	$conn->close();
}
?>