<?php 
	include "banco.php";
	$conn = conecta();
?>
<!--<html>
	<head>
		<link rel="stylesheet" href="css/style.css">
	</head>
	<body bgcolor="white">-->
		<div class="w3-container">
			<?php
				$sql = "SELECT id, nome, cpf, date_format(dt_nascimento,'%d/%m/%Y') dt_nascimento, fone, endereco, bairro, cidade, uf, status
						  FROM usuario";
				$status = @$_GET['status'];
				$nome   = @$_GET['nome'];
				$cpf    = @$_GET['cpf'];
				if ($status != "T"){
					$sql .= " WHERE status = '$status'";
				}else{
					$sql .= " WHERE status = status";
				}
				if ($nome != ""){
					$sql .= " AND nome LIKE replace(' $nome ',' ','%')";
				}
				if ($cpf != ""){
					$sql .= " AND cpf = '$cpf'";
				}
				$ret = $conn->query($sql);
				foreach ($ret as $obj) {
			?>
			<button onclick="expandir('usr<?=$obj['id']?>')" class="w3-button w3-block w3-black w3-left-align"><?=$obj['nome']?>
				<?php
				switch ($obj['status']) {
					case "B":
					echo '<span class="w3-badge w3-right w3-red">B</span>';
					break;
					case "I":
					echo '<span class="w3-badge w3-right w3-indigo">I</span>';
					break;
					case "L":
					echo '<span class="w3-badge w3-right w3-green">L</span>';
					break;
					default:
					echo '';
					}
				?>
			</button>
			<div id="usr<?=$obj["id"]?>" class="w3-hide w3-container">
				<table class="w3-table">
					<tr>
						<td colspan="2">Nome: <?=$obj['nome']?></td>
						<td>CPF: <?=$obj['cpf']?></td>
					</tr>
					<tr>
						<td>Nascimento:<?=$obj['dt_nascimento']?></td>
						<td colspan="2">Telefone: <?=$obj['fone']?></td>
					</tr>
					<tr>
						<td colspan="3">Endereço: <?=$obj['endereco']?></td>
					</tr>
					<tr>
						<td>Bairro: <?=$obj['bairro']?></td>
						<td>Cidade: <?=$obj['cidade']?></td>
						<td>UF: <?=$obj['uf']?></td>
					</tr>
				</table>
				<div id="lib<?=$obj["id"]?>">
					<button id="btLib<?=$obj["id"]?>" class="w3-button w3-right w3-green <?=$obj['status']=='L'?"w3-disabled":""?>">Liberar Uso</button>
					<script>
						$("#btLib<?=$obj["id"]?>").click(function(){
							$.get("liberaUsuario.php",
								{
									id : <?=$obj["id"]?>
								},
							function(data, status){
								$('#lib<?=$obj["id"]?>').html(data);
							});
						})
					</script>
				</div>
			</div>
			<?php }?>
		</div>
		<script>
			function expandir(id) {
				var x = document.getElementById(id);
				if (x.className.indexOf("w3-show") == -1) {
					x.className += " w3-show";
					x.previousElementSibling.className = 
					x.previousElementSibling.className.replace("w3-black", "w3-red");
				} else { 
					x.className = x.className.replace(" w3-show", "");
					x.previousElementSibling.className = 
					x.previousElementSibling.className.replace("w3-red", "w3-black");
				}
			}
		</script>
	<!--</body>
</html>-->