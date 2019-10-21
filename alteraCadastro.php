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
	$altera_nome = $_POST['altera_nome'];
	$altera_email = $_POST['altera_email'];
	$altera_data = $_POST['altera_data'];
	$altera_genero = $_POST['altera_genero'];

	if ($altera_nome == "true") {
		$sqlAlteraNome = "UPDATE usuario SET name = '".$_POST['name']."' WHERE id_usuario = $id_usuario";
		$resultAlteraNome = $conn->query($sqlAlteraNome);
		$response['nome_alterado'] = "true";
	} else {
		$response['nome_alterado'] = "false";
	}
	if ($altera_email == "true") {
		$sqlAlteraEmail = "UPDATE usuario SET email = '".$_POST['email']."' WHERE id_usuario = $id_usuario";
		$resultAlteraEmail = $conn->query($sqlAlteraEmail);
		$response['email_alterado'] = "true";
	} else {
		$response['email_alterado'] = "false";

	}
	if ($altera_data == "true") {
		$sqlAlteraData = "UPDATE usuario SET data_nasc = '".$_POST['data_nasc']."' WHERE id_usuario = $id_usuario";
		$resultAlteraData = $conn->query($sqlAlteraData);
		$response['data_alterada'] = "true";
	} else {
		$response['data_alterada'] = "false";
	}
	if ($altera_genero == "true") {
		$sqlAlteraGenero = "UPDATE usuario SET genero = '".$_POST['genero']."' WHERE id_usuario = $id_usuario";
		$resultAlteraGenero = $conn->query($sqlAlteraGenero);
		$response['genero_alterado'] = "true";
	} else {
		$response['genero_alterado'] = "false";
	}
	echo json_encode($response);
	$conn->close();
}
?>