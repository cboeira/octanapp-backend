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

	$id_usuario = $_POST['id_usuario'];
	$senha = "'".$_POST['senha_nova']."'";

	$sqlAlteraSenha = "UPDATE usuario SET senha = $senha WHERE id_usuario = $id_usuario";
	$result = $conn->query($sqlAlteraSenha);
	$response['mensagem'] = "Senha alterada com sucesso.";

	echo json_encode($response);
	$conn->close();

}
?>