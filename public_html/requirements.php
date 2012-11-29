<html>
<head>
</head>
<body>
	<form action="requirements.php" method="get">
		Salary: <input type="text" name="salary" value="<?php echo $_GET["salary"] ?>"><br>
		<input type="submit" value="Search">
	</form>

<?php 
	
	if(is_numeric($_GET["salary"])){ 
		$output = array();
		$cmd = "python requirements.py " . $_GET["salary"];
		exec($cmd, $output); //calling python from php, there are better ways to run python for web
		//var_dump($output);
		foreach ($output as $value)
			echo $value . " <br>";
	}

	
?>

</body>
</html>
