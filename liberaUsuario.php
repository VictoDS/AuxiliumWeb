<?php 
	include "banco.php";
	try{
		$conn = conecta();
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$id = @$_GET["id"];
		$sql = "UPDATE usuario SET status = 'L' WHERE id = $id";
		$ret = $conn->query($sql);
		?>
		<div class="w3-panel w3-leftbar w3-border-green w3-pale-green">
			<p>Usuário liberado com sucesso!</p>
		</div>
		<?php
	} catch(PDOException $e) {
		?>
		<div class="w3-panel w3-leftbar w3-border-red w3-pale-red">
			<p>Não foi possível liberar o usuário!</p>
		</div>
		<?php
	}
	
?>