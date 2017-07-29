<?php
	include "banco.php";
	$conn = conecta();
	$sql = "SELECT id, tip_ocorrencia tipo, date_format(momento_ocorrencia,'%d/%m/%Y %H:%i:%s') data
			  FROM ocorrencia
			 WHERE status = 'P'
			 ORDER BY momento_ocorrencia DESC";
	$ret = $conn->query($sql);
	foreach ($ret as $obj) {
?>
	<a href="#" class="w3-bar-item w3-button" onclick="detalharOcorrencia(<?=$obj["id"]?>);"><?=$obj["tipo"]?><div class="w3-right"><?=$obj["data"]?></div></a>
<?php }?>