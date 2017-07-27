<?php 

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST)) {

	include 'config.php';
	date_default_timezone_set('Bangkok/Thailand');
	
	
	$id = $_POST["identify"];
	$date 			= $_POST["dateTimeLocal"];
	$homeTeamChange = $_POST["homeTeamChange"];
	$awayTeamChange = $_POST["awayTeamChange"];
	$reserveTeamChange = $_POST["reserveTeamChange"];
	$scoreChange = $_POST["scoreChange"];
	
	$target_dir = "uploads/";
	$tempN = explode(".", $_FILES["homeTeamLogo"]["name"]);
	$newfilename = preg_replace('/\s+/', '', $tempN[0]) . round(microtime(true)) . '.' . end($tempN);
	$target_file = $target_dir . $newfilename;
	$uploadOk = 1;
	$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);

	if(strtolower($imageFileType) != "jpg" && strtolower($imageFileType) != "png" && strtolower($imageFileType) != "jpeg") {
		$uploadOk = 0;
	}
	if ($uploadOk == 0) {
		echo "Sorry, there was an error uploading your file.";
	} else {		
		if (move_uploaded_file($_FILES["homeTeamLogo"]["tmp_name"], $target_file)) { } else { errorFunc(); }
	}
	
	$tempN1 = explode(".", $_FILES["awayTeamLogo"]["name"]);
	$newfilename1 = preg_replace('/\s+/', '', $tempN1[0]) . round(microtime(true)) . '.' . end($tempN1);
	$target_file1 = $target_dir . $newfilename1;
	$uploadOk = 1;
	$imageFileType1 = pathinfo($target_file1,PATHINFO_EXTENSION);

	if(strtolower($imageFileType1) != "jpg" && strtolower($imageFileType1) != "png" && strtolower($imageFileType1) != "jpeg") {
		$uploadOk = 0;
	}
	if ($uploadOk == 0) {
		echo "Sorry, there was an error uploading your file.";
	} else {		
		if (move_uploaded_file($_FILES["awayTeamLogo"]["tmp_name"], $target_file1)) { } else { errorFunc(); }
	}
		
	function errorFunc() { ?>
		<script>window.location.replace("http://e0712763mathewbowyer.myitoc.com.au/oakTown/admin.php#error");</script>
		<?php 	header("Location: http://e0712763mathewbowyer.myitoc.com.au/oakTown/admin.php#error");
		die();
	}
	
	try {
		$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
		// set the PDO error mode to exception
		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$sql = "UPDATE `teams` SET `dateTime`='$date', `homeTeam`='$homeTeamChange',`awayTeam`='$awayTeamChange',`reserveTeam`='$reserveTeamChange', `homeLogo`='$newfilename', `awayLogo`='$newfilename1', `score`='$scoreChange' WHERE `id`=$id";

		// use exec() because no results are returned
		$conn->exec($sql);
		
		header("Location: http://e0712763mathewbowyer.myitoc.com.au/oakTown/admin.php")
		
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