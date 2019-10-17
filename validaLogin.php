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

	$email = "'".$_POST['email']."'";
	$senha = "'".$_POST['senha']."'";

	$sql = "SELECT * FROM usuario WHERE email = $email AND senha = $senha";

	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
		$registro = mysqli_fetch_array($result);
		$response["id_usuario"] = $registro['id_usuario'];
		$response["name"] = $registro['name'];
		$response["email"] = $registro['email'];
		$response["senha"] = $registro['senha'];
		$response["data_nasc"] = $registro['data_nasc'];
		$response["genero"] = $registro['genero'];
		$response["erro"] = false;
	} else {
		$response["mensagem"] = "Usuario ou senha invalidos.";
	}

	$conn->close();
}
echo json_encode($response);
?>