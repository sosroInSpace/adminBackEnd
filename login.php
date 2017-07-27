<?php 
	session_start(); 	
	if(isset($_POST['Submit'])){
		$user_f = htmlspecialchars($_POST['user_f']);
		$pass_f = htmlspecialchars($_POST['pass_f']);
		include 'config.php';

		class TableRows extends RecursiveIteratorIterator { 
			function __construct($it) { 
				parent::__construct($it, self::LEAVES_ONLY); 
			}
		} 

		try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$stmt = $conn->prepare("SELECT * FROM admin WHERE `username` = '$user_f' AND `password` = '$pass_f'"); 
			$stmt->execute();

			// set the resulting array to associative
			$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
			
			$number_of_rows = $stmt->rowCount(); 
			if($number_of_rows == 0) {
				$msg="<span style='color:#e2a06b;'>Invalid login credentials. Try again.</span>";
			} else {		
				foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) { 
					//echo $v;
					//$user_e 	= $row["user_e"];

					$_SESSION['user_e']		= $user_f;
					$_SESSION['timeout'] 	= time();
					
					header("Location:admin.php");
					exit;	
				}
			}
		}
		catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
		$conn = null;
	}




/*session_start(); 
		
	if(isset($_POST['Submit'])){
		$Username = htmlspecialchars($_POST['username']);
		$Password = htmlspecialchars($_POST['password']);
		
		include 'config.php';
		$conn = new mysqli($servername, $username, $password, $dbname);
		if ($conn->connect_error) {	} 
		$sql = "SELECT * FROM admin WHERE `username` = '$Username; AND `password` = '$Password' ";
		$result = $conn->query($sql);
		if ($result->num_rows > 0) {
			while($row = $result->fetch_assoc()) {
				$user_e 	= $row["user_e"];

				$_SESSION['user_e']		= $user_e;
				$_SESSION['timeout'] 	= time();
				
				header("Location:index.php");
				exit;	
			}
		} else {
			$msg="<span style='color:#e2a06b;'>Invalid login credentials. Try again.</span>";
		}
		$conn->close();
	}	*/
?>
<!DOCTYPE html>
<html lang="en" style="background-color: #000;">
<head><link rel='stylesheet' type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"></head>
<style>
	html, body {
		background: #000;
		background-image: url(http://codex-themes.com/scalia/one-page/wp-content/uploads/2014/12/header-background.jpg);
		height:100%;
		width:100%;
		background-size:cover;
	}
	img.img-responsive.img-login {
		border-radius: 0%;
		width: auto;
		left: auto;
		position: relative;
		width: 50%;
		margin-left: 25%;
		background: #fff;
	}
		form.form-signin.animated.bounceInDown {
		    text-align: center;
		}
		
		form input {
		    margin-bottom: 26px;
		}
		input#inputEmail {
		    margin-top: 39px;
		    position: relative;
		}
		
		.home a:hover {
	    text-decoration: none;
	    color: crimson;
	    transition: .5s;
	}
	h3.form-signin-heading.loginHead {
		color: #eda214 !important;
		font-size: 35px !important;
		font-weight: 800 !important;
		margin-top: 0px;
		padding: 42px;
	}
	.col-md-12.loginHomeOne a {
		padding: 20px;
		color: #ffe200;
		font-weight: 600;
		font-size: 17px;
		left: 96%;
		position: relative;
	}
	.container.loginBackgroundChangeContain:hover {
		transform: scale(1.1);
		transition: 3s;
	}
	.container.loginBackgroundChangeContain {
		background: rgba(66, 61, 61, 0.1);
		top: 23%;
		position: relative;
		text-align: center;
		-webkit-box-shadow: 3px 4px 13px 0px rgba(0,0,0,0.75);
		-moz-box-shadow: 3px 4px 13px 0px rgba(0,0,0,0.75);
		width: ;
		width: ;
		width: 900px;
		border-radius: 15px;
		transition:3s;
		padding-bottom: 52px;
	}
	.container-fluid.loginHome {
		/* background: rgba(173, 43, 43, 0.48); */
		/* -webkit-box-shadow: -2px 3px 5px 0px rgba(0,0,0,0.25); */
		-moz-box-shadow: -2px 3px 5px 0px rgba(0,0,0,0.25);
		/* box-shadow: -2px 3px 5px 0px rgba(0,0,0,0.25); */
		-webkit-box-shadow: -2px 3px 23px 0px rgba(0,0,0,0.25);
		-moz-box-shadow: -2px 3px 23px 0px rgba(0,0,0,0.25);
		box-shadow: -2px 3px 23px 0px rgba(0,0,0,0.25);
	}
</style>

<body>
<div class='container-fluid loginHome'>
	<div class='row home'>
		<div class='col-md-12 loginHomeOne'><a href='oaktown.php'>home</a></div>
	</div>
</div>
	 <div class="container loginBackgroundChangeContain">
		<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<form class="form-signin animated bounceInDown" action="" method="POST">
					<h3 class="form-signin-heading loginHead" style="text-align:center;">OakTown Footbal Club Admin Login</h3>
					<label for="inputEmail" class="sr-only">Email</label>
					<input type="text" id="inputText" class="form-control" placeholder="user" name="user_f" required autofocus>
					<label for="inputPassword" class="sr-only">Password</label>
					<input type="password" id="inputPassword" class="form-control" placeholder="Password" name="pass_f" required>
					<button class="btn btn-lg btn-primary btn-block" type="submit" name="Submit">Sign In</button>
					<div class="loginErr">
					  <?php if(isset($msg)){ ?>
						<?php echo $msg; ?>
					  <?php } ?>
					</div>
				</form>
			</div>
		</div>
    </div> <!-- /container -->
</body>

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
</html>
