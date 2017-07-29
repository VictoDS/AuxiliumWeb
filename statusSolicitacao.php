<?php 
	include "banco.php";
	try{
		$conn = conecta();
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$id = @$_GET["id"];
		$status = @$_GET["status"];
		if ($status == 'R'){
			$msgSucesso = "Ocorrência reprovada com sucesso!";
			$msgErro    = "Não foi possível reprovar a ocorrência!";
		}else if ($status == 'E'){
			$msgSucesso = "Ocorrência encerrada com sucesso!";
			$msgErro    = "Não foi possível encerrar a ocorrência!";
		}else{
			$msgErro = "Operação não indentificada!";
		}
		$sql = "UPDATE ocorrencia SET status = '$status' WHERE id = $id";
		$ret = $conn->query($sql);
		if ($status == 'R') {
			$sql = "SELECT u.id
					FROM ocorrencia o INNER JOIN usuario u ON (u.id = o.id_usuario)
					WHERE o.id = $id";
			$ret = $conn->query($sql);
			foreach($ret as $obj){
				$idUsuario = $obj["id"];
			};
			$sql = "UPDATE usuario SET status = 'B' WHERE id = $idUsuario";
			$ret = $conn->query($sql);
			$msgSucesso .= " Usuário bloqueado.";
		};
		echo '<div class="w3-panel w3-leftbar w3-border-green w3-pale-green"><p>'.$msgSucesso.'</p></div>';
	}catch(PDOException $e) {
		echo '<div class="w3-panel w3-leftbar w3-border-red w3-pale-red"><p>'.$msgErro.'</p></div>';
	}
?>