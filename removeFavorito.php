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

	$id_usuario = "'".$_POST['id_usuario']."'";
	$id_posto = "'".$_POST['id_posto']."'";

	$sqlRemoveFavorito = "DELETE FROM postofavorito WHERE id_usuario = $id_usuario AND id_posto = $id_posto";
	$result = $conn->query($sqlRemoveFavorito);
	$response["mensagem"] = "Posto removido dos favoritos.";
	$conn->close();
}
echo json_encode($response);
?>