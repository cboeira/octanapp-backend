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

	$id_posto = "'".$_POST['id_posto']."'";
	$nota = "'".$_POST['nota']."'";
	$id_usuario = "'".$_POST['id_usuario']."'";
	$nome_combustivel = "'".$_POST['nome_combustivel']."'";
	$litragem = $_POST['litragem'];
	$novaKmTotal = $_POST['kmTotal'];

	$sqlVerificaVeiculoAtivo = "SELECT * from veiculoemplacado WHERE id_usuario = $id_usuario AND ativo = 1";
	$vAtivo = $conn->query($sqlVerificaVeiculoAtivo);
	if ($vAtivo->num_rows == 0) {
		$response["erro"] = true;
		$response["mensagem"] = "Não é possível realizar a avaliação sem um veículo ativo.";
	} else {
		$sqlRetornaIdCombustivel = $conn->prepare("SELECT id_combustivel FROM combustivel WHERE nome LIKE $nome_combustivel");
		$sqlRetornaIdCombustivel->execute();
		$sqlRetornaIdCombustivel->bind_result($result);

		while ($sqlRetornaIdCombustivel->fetch()) {
			$id_combustivel = $result;
		}

 		$data = new DateTime();
    	$datahora = $data->format('Y-m-d H:i:s'); 
    	$datahora = "'".$datahora."'";

    	$sqlDadosVeiculo = $conn->prepare("SELECT kmTotal, id_veiculo, placa FROM veiculoemplacado WHERE id_usuario = $id_usuario AND ativo = 1");
    	$sqlDadosVeiculo->execute();
    	$sqlDadosVeiculo->bind_result($resultKmAtual, $resultId_veiculo, $resultPlaca);

   		while ($sqlDadosVeiculo->fetch()) {
			$kmAtual = $resultKmAtual;
			$id_veiculo = $resultId_veiculo;
			$placa = "'".$resultPlaca."'";
		}

		$kmAbastecimento = $novaKmTotal - $kmAtual;
		$autonomia = $kmAbastecimento/$litragem;

		if ($novaKmTotal == $kmAtual || $novaKmTotal < $kmAtual) {
			$response["erro"] = true;
			$response["mensagem"] = "Quilometragem declarada igual ou menor à anterior.";
		} else if ($novaKmTotal > $kmAtual+1000) {
			$response["erro"] = true;
			$response["mensagem"] = "Quilometragem muito alta.";
		} else { 
			$sqlAvalia = $conn->prepare("INSERT INTO avaliacao (id_posto, placa, id_combustivel, id_usuario, kmTotal, litros, nota, horario, autonomia, id_veiculo) VALUES ($id_posto, $placa, $id_combustivel, $id_usuario, $novaKmTotal, $litragem, $nota, $datahora, $autonomia, $id_veiculo)");
			$sqlAvalia->execute();
			$response["erro"] = false;
			$response["mensagem"] = "Avaliação realizada com sucesso.";

			$sqlAtualizaKmTotal = $conn->prepare("UPDATE veiculoemplacado SET kmTotal = $novaKmTotal WHERE id_usuario = $id_usuario AND ativo = 1");
			$sqlAtualizaKmTotal->execute();
		}
	}
	$conn->close();
}
echo json_encode($response);
?>