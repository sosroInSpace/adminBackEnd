<?php 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {

	include 'config.php';
	date_default_timezone_set('Bangkok/Thailand');
	
	$date = date('Y-m-d H:i:s');
	
	$reserveNew  		=htmlspecialchars($_POST["reserveName"]);
	
	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "INSERT INTO teams (`dateTime`, `reserveTeam` ) VALUES ('$date', '$reserveNew')";
		// use exec() because no results are returned
		$conn->exec($sql);
		header("Location: http://e0712763mathewbowyer.myitoc.com.au/oakTown/admin.php");?>
			
		?>
	<?php	die();
		}
	catch(PDOException $e)
		{
		echo $sql . "<br>" . $e->getMessage();
		}

	$conn = null;
}
?>