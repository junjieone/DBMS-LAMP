<?php
	$h = 'localhost';
	$u = 'root';
	$p = '19920804wjj.';
	$dbname = 'mocksystem';
	$db = mysqli_connect($h,$u,$p,$dbname);
	if (mysqli_connect_errno()) {
		echo "Connect failed" . mysqli_connect_error();
		exit();
	}
	
?>
