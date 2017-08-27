<?php 
	include "banco.php";
	$conn = conecta();
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Ocorrências - CBMSC</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/style.css">
		<script src="js/3_11jquery.js"></script>
		<link rel="icon" type="image/ico" href="img/logo-ico.ico">
	</head>
	<body>
		<!--Modal Controle Usuário-->
		<div id="modalCtrlUser" class="w3-modal">
			<div class="w3-modal-content w3-card-8 w3-animate-top">
				<header class="w3-container w3-red"> 
					<span onclick="document.getElementById('modalCtrlUser').style.display='none'" class="w3-closebtn">X</i></span>
					<h4>Controle de Usuário - Corpo de Bombeiros Militar</h4>
				</header>
				<div class="w3-bar w3-black">
					<button id="btInativos" class="w3-bar-item w3-button tablink w3-gray" onclick="trocaAba(event)"><span class="w3-badge w3-indigo">I</span> Inativos</button>
					<button id="btBloqueados" class="w3-bar-item w3-button tablink" onclick="trocaAba(event)"><span class="w3-badge w3-red">B</span> Bloqueados</button>
					<button id="btLiberados" class="w3-bar-item w3-button tablink" onclick="trocaAba(event)"><span class="w3-badge w3-green">L</span> Liberados</button>
					<button id="btTodos" class="w3-bar-item w3-button tablink" onclick="trocaAba(event)">Todos</button>
				</div>  
				<div class="w3-container w3-border">
					<hr>
					<div class="w3-row-padding">
						<div class="w3-third">
							<input id="nome" class="w3-input" type="text" placeholder="Nome">
						</div>
						<div class="w3-third">
							<input id="cpf" class="w3-input" type="number" placeholder="CPF">
						</div>
						<div class="w3-third">
							<button id="btBuscar" class="w3-btn-block w3-red">Buscar</button>
						</div>
					<hr>
					<div id="list" style="height:350px;width:100%;overflow:auto;"></div>
					</div>
				</div>
			</div>
		</div>
		<!--Modal Detalhes Ocorrência-->
		<div id="modalDet" class="w3-modal">
			<div class="w3-modal-content w3-card-8 w3-animate-top">
				<div id="detail" style="width:100%;"></div>
			</div>
		</div>
		<!--Barra Lateral-->
		<div class="w3-sidebar w3-right w3-bar-block w3-card-2 w3-animate-left" style="display:none" id="barraOcorrencias">
			<button class="w3-bar-item w3-button w3-large w3-red" onclick="listaHide()">&#9776; Esconder Ocorrências</button>
			<div id="ocorrPendentes"></div>
		</div>
		<!--Barra superior-->
		<div class="w3-red">
			<button class="w3-button w3-red w3-xxlarge" onclick="listaShow()">&#9776; Ocorrências</button>
			<a href="#" onclick="controleUsuario();"><button class="w3-button w3-red w3-large w3-right">Controle de<br>Usuários</button></a>
		</div>
		<!--Mapa-->
		<div id="mapa" style="width:100%;height:690px;"></div>
		<script>
			//Variáveis do Mapa
			var geocoder;
			var map;
			var marker;
			var abaAtual;
			abaAtual = 'I';
			
			//Função para exibir detalhes da ocorrência
			function detalharOcorrencia(idOcorencia){
				$.get("tab_detalhe_ocorrencia.php",
					{
						id: idOcorencia
					},
				function(data, status){
					$('#detail').html(data);
				});
				//marker1.setPosition(latlng1);
				document.getElementById('modalDet').style.display='block';
			}
			
			function controleUsuario() {
				$.get("tab_usr.php",{status: "I"},
				function(data, status){
					$('#list').html(data);
				});
				document.getElementById('modalCtrlUser').style.display='block';
				var i, x, tablinks;
				tablinks = document.getElementsByClassName("tablink");
				for (i = 0; i < tablinks.length; i++) {
					tablinks[i].className = tablinks[i].className.replace(" w3-gray", "");
				}
				document.getElementById('btInativos').className += " w3-gray";
			}
			
			function carregaLista(){
				$.get("list_ocorrencias.php",{},
				function(data, status){
					$('#ocorrPendentes').html(data);
				});
			}
			
			$(document).ready(function () {
				initialize();
				listaShow();
			});
			
			//Programação inicial do Mapa
			function initialize() {
				if(navigator.geolocation) {
					navigator.geolocation.getCurrentPosition(function(position){
						var latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
						var options = {
							zoom: 15,
							center: latlng,
							mapTypeId: google.maps.MapTypeId.ROADMAP,
							mapTypeControl: false,
							streetViewControl: false
						};
						map = new google.maps.Map(document.getElementById("mapa"), options);
						geocoder = new google.maps.Geocoder();
						
						marker = new google.maps.Marker({
							map: map,
							draggable: false,
							icon: 'img/logo-ico.ico'
						});
						marker.setPosition(latlng);
						
						<?php
							$sql = "SELECT id, lat, lng, tip_ocorrencia tipo
									  FROM ocorrencia
									 WHERE status = 'P'";
							$ret = $conn->query($sql);
							foreach ($ret as $obj) {
						?>
						var latlng<?=$obj["id"]?> = new google.maps.LatLng(<?=$obj["lat"]?>,<?=$obj["lng"]?>);
						marker<?=$obj["id"]?> = new google.maps.Marker({
							map: map,
							draggable: false,
						});
						marker<?=$obj["id"]?>.setPosition(latlng<?=$obj["id"]?>);
						
						google.maps.event.addListener(marker<?=$obj["id"]?>,'click',function() {
							var infowindow = new google.maps.InfoWindow({
								content:'<p class="w3-opacity"><?=$obj["tipo"]?><div class="w3-card-4" style="max-width:250px;"><button onclick="detalharOcorrencia(<?=$obj["id"]?>);" class="w3-btn-block w3-red">Detlhes</button></div></div></p>'
							});
							infowindow.open(map,marker<?=$obj["id"]?>);
						});
						<?php }?>
					}, 
					function(error){ // callback de erro - direciona para o quartel dos bombeiros em Criciúma
						var latlng = new google.maps.LatLng(-28.683661, -49.375112);
						var options = {
							zoom: 15,
							center: latlng,
							mapTypeId: google.maps.MapTypeId.ROADMAP,
							mapTypeControl: false,
							streetViewControl: false
						};
						<?php
							$sql = "SELECT id, lat, lng, tip_ocorrencia tipo
									  FROM ocorrencia
									 WHERE status = 'P'";
							$ret = $conn->query($sql);
							foreach ($ret as $obj) {
						?>
						var latlng<?=$obj["id"]?> = new google.maps.LatLng(<?=$obj["lat"]?>,<?=$obj["lng"]?>);
						marker<?=$obj["id"]?> = new google.maps.Marker({
							map: map,
							draggable: false,
						});
						marker<?=$obj["id"]?>.setPosition(latlng<?=$obj["id"]?>);
						
						google.maps.event.addListener(marker<?=$obj["id"]?>,'click',function() {
							var infowindow = new google.maps.InfoWindow({
								content:'<p class="w3-opacity"><?=$obj["tipo"]?><div class="w3-card-4" style="max-width:250px;"><button onclick="detalharOcorrencia(<?=$obj["id"]?>);" class="w3-btn-block w3-red">Detlhes</button></div></div></p>'
							});
							infowindow.open(map,marker<?=$obj["id"]?>);
						});
						<?php }?>
						console.log('Erro ao obter localização.', error);
					});
				} else {
					alert('Navegador não suporta Geolocalização!');
				}
			}
			function listaShow() {
				document.getElementById("barraOcorrencias").style.width = "25%";
				document.getElementById("barraOcorrencias").style.display = "block";
				carregaLista();
			}
			function listaHide() {
				document.getElementById("barraOcorrencias").style.display = "none";
			}
			function trocaAba(evt) {
				var i, x, tablinks;
				tablinks = document.getElementsByClassName("tablink");
				for (i = 0; i < tablinks.length; i++) {
					tablinks[i].className = tablinks[i].className.replace(" w3-gray", "");
				}
				evt.currentTarget.className += " w3-gray";
			}
			$("#btInativos").click(function(){
				abaAtual = 'I';
				$.get("tab_usr.php",
					{
						status: abaAtual
					},
				function(data, status){
					$('#list').html(data);
				});
			});
			$("#btBloqueados").click(function(){
				abaAtual = 'B';
				$.get("tab_usr.php",
					{
						status: abaAtual
					},
				function(data, status){
					$('#list').html(data);
				});
			});
			$("#btLiberados").click(function(){
				abaAtual = 'L';
				$.get("tab_usr.php",
					{
						status: abaAtual
					},
				function(data, status){
					$('#list').html(data);
				});
			});
			$("#btTodos").click(function(){
				abaAtual = 'T';
				$.get("tab_usr.php",
					{
						status: abaAtual
					},
				function(data, status){
					$('#list').html(data);
				});
			});
			$("#btBuscar").click(function(){
				$.get("tab_usr.php",
					{
						status: abaAtual,
						nome  : $("#nome").val(),
						cpf   : $("#cpf").val()
					},
				function(data, status){
					$('#list').html(data);
				});
			});
			function liberaAcesso(id, sql){
				$.get("liberaUsuario.php",
					{
						id  : id
					},
				function(data, status){
					$('#list').html(data);
				});
			}
			function reprovaSol(idOcorencia){
				$.get("statusSolicitacao.php",
					{
						id    : idOcorencia,
						status: 'R'
					},
				function(data, status){
					$('#ctrlSolicitacao').html(data);
				});
				initialize();
			}
			function encerraSol(idOcorencia){
				$.get("http://seleman.16mb.com/statusSolicitacao.php",
					{
						id  : idOcorencia,
						status: 'E'
					},
				function(data, status){
					$('#ctrlSolicitacao').html(data);
				});
				initialize();
			}
			function buscaEndereco(lat, lng){
				var latlng = lat+","+lng; 
				var url = "https://maps.googleapis.com/maps/api/geocode/json?latlng="+latlng; 
				//alert(url);
				$.getJSON(url, function (data) { 
					var adress = data.results[0].formatted_address; 
					//alert(adress);
					$('#endereco').html(adress);
					//endereco_campo.value = adress;
				});
			}
			var variavel = setInterval(function() {carregaLista();}, 1000);
		</script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCtys0DZkR6N6iNwQjQTLzA49iz-niYfEo"></script>
	</body>
</html>