<html>
	<head>
		<title>Teste Requisição</title>
	</head>
	<body>
		<button onclick="encerraSol(1);">Testar</button><br>
		<div id="ctrlSolicitacao"></div>
		<script>
			function encerraSol(idOcorencia){
				$.get("http://seleman.16mb.com/statusSolicitacao.php",
					{
						id  : idOcorencia,
						status: 'E'
					},
				function(data, status){
					$('#ctrlSolicitacao').html(data);
				});
			}
		</script>
	</body>
</html>