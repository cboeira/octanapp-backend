<?php 
header('Content-Type: application/json charset=utf-8');
error_reporting(0);

$response = array();
$response["erro"] = true;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
	include 'dbConnection.php';

	$conn = new mysqli($HostName, $HostUser, $HostPass, $DatabaseName);

	mysqli_set_charset($conn, "utf8");

	if ($conn->connect_error) {
		die ("Falha de conexao: " . $conn->connect_error);
	}
	$response["erro"] = false;

	$name = "'".$_POST['name']."'";
	$email = "'".$_POST['email']."'";
	$senha = "'".$_POST['senha']."'";
	$data_nasc = "'".$_POST['data_nasc']."'";
	$genero = "'".$_POST['genero']."'";

	/*print_r($name);
	print_r($email);
	print_r($senha);
	print_r($data_nasc);
	print_r($genero);*/

	$sqlConsultaEmail = "SELECT * FROM usuario WHERE email = $email";

	$result = $conn->query($sqlConsultaEmail);

	if ($result->num_rows > 0) {
		$response["erro"] = true;
		$response["mensagem"] = "E-mail já cadastrado.";
	} else {
		$sqlCadastra = "INSERT INTO usuario (name, email, senha, data_nasc, genero) VALUES ($name, $email, $senha, $data_nasc, $genero)";
		$result = $conn->query($sqlCadastra);
		$response["mensagem"] = "Cadastro efetuado com sucesso.";
	}

	$conn->close();
}
echo json_encode($response);
?>