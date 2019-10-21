<?php 
header('Content-Type: application/json charset=utf-8');
error_reporting(0);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	
	include 'dbConnection.php';

	$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);

	mysqli_set_charset($conn, "utf8");

	if ($conn->connect_error) {
		die ("Falha de conexao: " . $conn->connect_error);
	}

	$sql = "SELECT DISTINCT marca FROM veiculo";

	$result = $conn->query($sql);

	while ($linha = mysqli_fetch_assoc($result)) {
		$tmp[] = $linha;

	}
	echo json_encode($tmp);
	$conn->close();
}
?>