<html>
<head>
</head>
<body>
	<form action="location.php" method="get">
		Location: <input type="text" name="location" value="<?php echo $_GET["location"] ?>"><br>
		<input type="submit" value="Search">
	</form>

<?php 
	
	if($_GET["location"]){ 
		$output = array();
		$cmd = "python location.py " . $_GET["location"];
		exec($cmd, $output); //calling python from php, there are better ways to run python for web
		//var_dump($output);
		foreach ($output as $value)
			echo $value . " <br>";
	}

	
?>

</body>
</html>
