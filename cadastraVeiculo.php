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

	$marca = $_POST['marca'];
	$modelo = $_POST['modelo'];
	$placa = "'".$_POST['placa']."'";
	$kmTotal = $_POST['kmTotal'];
	$id_usuario = $_POST['id_usuario'];
	$ano = $_POST['ano'];

	$sqlValidaModelo = "SELECT id_veiculo FROM veiculo where marca like \"$marca\" and modelo like \"$modelo\"";

	$resultValidaModelo = $conn->query($sqlValidaModelo);

	if ($resultValidaModelo->num_rows > 0) {
		$registro = mysqli_fetch_array($resultValidaModelo);
		$id_veiculo = $registro["id_veiculo"];

		$sqlValidaPlaca = "SELECT * FROM veiculoemplacado where placa like $placa";
		$sqlInformaAtivo = "SELECT * FROM veiculoemplacado where id_usuario = $id_usuario ";
		$resultValidaPlaca = $conn->query($sqlValidaPlaca);
		$resultInformaAtivo = $conn->query($sqlInformaAtivo);

		if ($resultValidaPlaca->num_rows > 0) {
			$response["mensagem"] = "Placa já cadastrada.";
		} else {
			if ($resultInformaAtivo->num_rows > 0) {
				$ativo = 0;
			} else {
				$ativo = 1;
			}
			$sqlCadastra = "INSERT INTO veiculoemplacado (placa, kmTotal, id_veiculo, id_usuario, ano, ativo) VALUES ($placa, $kmTotal, $id_veiculo, $id_usuario, $ano, $ativo)";
			$resultCadastra = $conn->query($sqlCadastra);
			$response["erro"] = false;
			$response["mensagem"] = "Veiculo cadastrado com sucesso.";
		}
	} else {
		$response["mensagem"] = "Modelo não encontrado.";
	}



	echo json_encode($response);
	$conn->close();
}
?>