<html>
<head>
   <title>CS160 Team 1</title>
      <link href="style.css" rel="stylesheet">
</head>
<body>
<div class="search">
   <form action="salary.php" method="get">
      Salary: <input type="text" name="salary" value="<?php echo $_GET["salary"] ?>"><br>
      <input type="submit" value="Search">
   </form>

</div>
   <div class="results">
<?php
   if(is_numeric($_GET["salary"])){
      $output = array();
      $cmd = "python test.py " . $_GET["salary"];
      exec($cmd, $output); //calling python from php, there are better ways to run python for web
      //var_dump($output);
      foreach ($output as $value)
         echo $value . " <br>";
   }


?>
   </div>

</body>
</html>
