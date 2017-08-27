<header class="w3-container w3-red"> 
	<span onclick="document.getElementById('modalDet').style.display='none'" class="w3-closebtn">X</i></span>
	<h4>Detalhes Ocorrência - Corpo de Bombeiros Militar</h4>
</header>
<div class="w3-container w3-border city">
	<?php
		include "banco.php";
		$conn = conecta();
		$id = @$_GET["id"];
		$sql = "SELECT u.fone, u.nome, u.cpf, o.lat, o.lng, o.tip_ocorrencia, o.descricao
				  FROM ocorrencia o inner join usuario u on (u.id = o.id_usuario)
				 WHERE o.id = '$id'";
		$ret = $conn->query($sql);
		foreach ($ret as $obj) {
	?>
	<table class="w3-table w3-striped">
		<tr>
			<td>Telefone</td>
			<td><?=$obj["fone"]?></td>
		</tr>
		<tr>
			<td>Solicitante</td>
			<td><?=$obj["nome"]?></td>
		</tr>
		<tr>
			<td>CPF</td>
			<td><?=$obj["cpf"]?></td>
		</tr>
		<!--<tr>
			<td>Cidade</td>
			<td><?//=$obj["cidade"]?></td>
		</tr>
		<tr>
			<td>Bairro</td>
			<td><?//=$obj["bairro"]?></td>
		</tr>
		<tr>-->
			<td>Endereço</td>
			<td id="endereco"></td>
		</tr>
		<!--<tr>
			<td>Número</td>
			<td><?//=$obj["numero"]?></td>
		</tr>-->
		<tr>
			<td>Tipo de Ocorrência</td>
			<td><?=$obj["tip_ocorrencia"]?></td>
		</tr>
		<tr>
			<td>Detalhes</td>
			<td><?=$obj["descricao"]?></td>
		</tr>
	</table>
</div>
<footer class="w3-container w3-white">
	<div id="ctrlSolicitacao">
		<p class="w3-third w3-padding w3-white"><button class="w3-btn-block w3-red" onclick="reprovaSol(<?=$id?>);">Reprovar</button></p>
		<p class="w3-third w3-padding w3-white"><button class="w3-btn-block w3-red">Enviar atendimento</button></p>
		<p class="w3-third w3-padding w3-white"><button class="w3-btn-block w3-red" onclick="encerraSol(<?=$id?>);">Ecerrar atendimento</button></p>
	</div>
</footer>
<script>buscaEndereco(<?=$obj["lat"]?>,<?=$obj["lng"]?>);</script>
<?php }?>