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

	$cnpj = "'".$_POST['cnpj']."'";
	$nota = "'".$_POST['nota']."'";
	$id_usuario = "'".$_POST['id_usuario']."'";

 	$data = new DateTime();
    $datahora = $data->format('Y-m-d H:i:s'); 
    $datahora = "'".$datahora."'";


	$sqlAvalia = "INSERT INTO avaliacaosimples (id_usuario, cnpj, nota, horario) VALUES ($id_usuario, $cnpj, $nota, $datahora)";
	$result = $conn->query($sqlAvalia);
	$response["mensagem"] = "Avaliação nota ".$_POST['nota']." realizada com sucesso.";

	$conn->close();
}
echo json_encode($response);
?>