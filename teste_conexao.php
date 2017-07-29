<?php
	include "banco.php";
	$conn = conecta();
	$sql  = "UPDATE usuario SET status = 'L' WHERE id = 1";
	$obj  = $conn->query($sql);
	foreach($obj as $row) {
        print_r($row);
    }
?>