<?php
	function conecta(){
		// Remoto - Logosystem
		$conn = new PDO("mysql:host=externo.logosystem.com.br;dbname=auxilium","gustavo","gustavo@tcc");
		// Retorna conexão
		return $conn;
	}
?>