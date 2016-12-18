<?php
		include '../admin/connection/main_Connection.php';

		$admin = new adminSession();
		$admin -> sessionDestroy();
        header('location:index.php');
?>
