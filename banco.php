<?php
	function conecta(){
		// Remoto - Logosystem
		$mysql = mysql_connect("externo.logosystem.com.br","gustavo","gustavo@tcc");
		mysql_select_db("auxilium",$mysql);
		mysql_set_charset("utf8",$mysql);
		// Local
		/*$mysql = mysql_connect("127.0.0.1","root","root");
		mysql_select_db("auxilium",$mysql);
		mysql_set_charset("utf8",$mysql);*/
		// Retorna conexão
		return $conn;
	}
?>