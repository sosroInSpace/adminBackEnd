<?php
	
	session_start(); 
	if(($_SESSION['user_e'] != 'user') || ((time() - $_SESSION['timeout']) > 3600 ) ){
		header("location:login.php");
		exit;
	}
	if(isset($_GET['date'])) {
		$dateSel = htmlspecialchars($_GET['date']);
		$dateSelSQL = " WHERE `dateTime` LIKE '". $dateSel ."%' ";
	}		
	include "cdn.php";	
	
	require_once 'config.php';

		class TableRows extends RecursiveIteratorIterator { 
			function __construct($it) { 
				parent::__construct($it, self::LEAVES_ONLY); 
			}
		} 

		try {
			$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		 	$stmt = $conn->prepare("SELECT `reserveTeam` FROM `teams` ORDER BY `id` DESC "); 
			$stmt->execute();

			// set the resulting array to associative
			$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
			
			$number_of_rows = $stmt->rowCount(); 
			if($number_of_rows == 0) {

			} else {
				$counter = 0; 
			}
		} catch(PDOException $e) {
			echo "Error: " . $e->getMessage();
		}
?>	

<style>
html, body {
    /* background: #000 !important; */
    overflow-x: hidden;
    /* font-size: 14px !important; */
    background-image: url(http://codex-themes.com/scalia/one-page/wp-content/uploads/2014/12/header-background.jpg);
    background-size: cover;
    /* background-repeat: repeat; */
}
.col-xs-12.no-padd-md.no-padd-mob.headRow {
    background: #fff;
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#a90329', endColorstr='#6d0019',GradientType=0 );
    background: rgb(96,108,136);
    background: -moz-linear-gradient(top, rgba(96,108,136,1) 0%, rgba(63,76,107,1) 100%);
    background: -webkit-linear-gradient(top, rgba(96,108,136,1) 0%,rgba(63,76,107,1) 100%);
    background: linear-gradient(to bottom, rgba(96,108,136,1) 0%,rgba(63, 76, 107, 0.83) 100%);
    filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#606c88', endColorstr='#3f4c6b',GradientType=0 );
    border-bottom: 1px solid rgba(255, 0, 0, 0.13);
	position:fixed;
	z-index: 999999999999999;
}

</style>
<body class=" ">
    <div class="main-wrapper admin ">
	
		<div class="  ">
            <div class="container-fluid no-padd">
                <div class="row">
					<div class="headBanner">
						<div class="col-xs-12 no-padd-md no-padd-mob headRow">
							<header class="right-menu">
								<a class="logo">
									<img src="images/oakTownLogo.png" class="img-responsive" />
									<span class='oneStep'>Administration</span>									
								</a>
								<span class='sO'><a href="logout.php"><i class="ion-log-out"></i> Sign Out</a></span>
								<span class='sO1'><a href="oaktown.php"><i class="ion-log-out"></i> Home</a></span>
							</header>
						</div>
					</div>
                </div>
            </div>
        </div>
		
		<div class="container-fluid">
			<div class="row">
			<div class='col-md-12'>
					<form action="adminProcess.php" method="POST" class='addTeam'>
							<label style='color:#fff'> Add a Reserve Team </label>
								<input type="text" name="reserveName" class='teamNew'><br>
								<input type="submit" value="Submit" class='submitThis'>
					</form>
					<a href='#' class='insertNewRow'>Add a new game</a>
					<a href='#' class='teamEdits'>Edit teams</a>
					<a href='#' class='deleteEdits'>Delete a game</a>
				</div>
				<div class='container adminFilter'>
							<div class='row'>
								<div class='col-md-12 text-center absoluteAdmin'>
									<h1> Select date to view scores for specific fixtures</h1> <?php
					try {
						$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
						$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
						$stmt = $conn->prepare("SELECT * FROM teams ORDER BY `dateTime` DESC "); 
						$stmt->execute();
						$result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
						
						$number_of_rows = $stmt->rowCount(); 
						if($number_of_rows == 0) {} else {
							$counter = 0;
							$dayArr = array();
							foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) { 
								$counter++;
								if ($counter % 8 == 2) {
									$first10 = substr($v, 0, 10);
									$dayArr[] = $first10;
								} 
							} 				
						}
					}
					catch(PDOException $e) {
						echo "Error: " . $e->getMessage();
					}
					$conn = null; 
					$dayArr = array_unique($dayArr); 
					echo "<select name='fixtures' id='fixtures'>
							<option value='' disabled selected>Select Date</option>";
					foreach($dayArr as $x_value) {
						echo "<option value='$x_value'>$x_value</option>";
					} ?>
									</select>		  
								</div>
			</div>						
				<div class='col-md-12 deleteForm'>
					<form action="delete.php" method="POST" class='deleteTeam'>
							<label style='color:#fff'> enter row id for deletion</label>
							<label><button class='crossOffDelete'>x</button></label>
								<input type="number" name="deleteRow" class='teamNew'><br>
								<input type="submit" value="Submit" class='submitThis'>
					</form>
				</div>
				
				<div class='col-md-12 editEditForm'>
				<label><button class='crossOffEditDelete'>x</button></label>
					<form action="edit.php" method="POST" class='editingTeam' enctype="multipart/form-data">
						
								<label>Enter Row Id </label>
									<input type="number" name="identify" placeholder="enter row identifier" class='teamNew' id="f1"><br>
								<label>Enter Game Date </label>
									<input type="text" name="dateTimeLocal" placeholder="date" class='teamNew datepicker' id="f2" ><br>
								<label>Change Home Team</label>
									<input type="text" name="homeTeamChange" placeholder="enter name" class='teamNew' id="f3"><br>
								<label>Change Away Team</label>
									<input type="text" name="awayTeamChange" placeholder="enter name" class='teamNew' id="f4"><br>
								<label>Change/add Reserve Team</label>
									<input type="text" name="reserveTeamChange" placeholder="enter name" class='teamNew' id="f5"><br>
								<label>Enter/add Home Team Logo</label>
									<input class="logo" type="file" name="homeTeamLogo" placeholder="enter name" class='teamNew' id="f11"><br>
								<label>Enter/add Away Team Logo</label>
									<input class="logo" type="file" name="awayTeamLogo" placeholder="enter name" class='teamNew' id="f11"><br>
								<label>Change Score (use format "2 - 0"</label>
									<input type="text" name="scoreChange" placeholder="enter new score" class='teamNew' id="f6"><br>
								
								<input type="submit" value="Submit" class='submitThis' onclick='formValidateFunction()'>
					</form>
					
				</div>
				
				<div class='col-md-12 addNewRowForm'>
				<label><button class='crossOffEditDelete'>x</button></label>
					<form action="newGame.php" method="POST" class='editingTeam' enctype="multipart/form-data">
						
							
								<label>Enter Game Date </label>
									<input type="text" name="dateTimeLocal" placeholder="date" class='teamNew datepicker' id="f8" ><br>
								<label>Enter Home Team</label>
									<input type="text" name="homeTeamChange" placeholder="enter name" class='teamNew' id="f9"><br>
								<label>Enter Away Team</label>
									<input type="text" name="awayTeamChange" placeholder="enter name" class='teamNew' id="f10"><br>
								<label>Enter/add Reserve Team</label>
									<input type="text" name="reserveTeamChange" placeholder="enter name" class='teamNew' id="f11"><br>
								<label>Enter/add Home Team Logo</label>
									<input class="logo" type="file" name="homeTeamLogo" placeholder="enter name" class='teamNew' id="f11"><br>
								<label>Enter/add Away Team Logo</label>
									<input class="logo" type="file" name="awayTeamLogo" placeholder="enter name" class='teamNew' id="f11"><br>
								<label>Enter Score (use format "2 - 0"</label>
									<input type="text" name="scoreChange" placeholder="enter new score" class='teamNew' id="f12"><br>
								
								<input type="submit" value="Submit" class='submitThis'>
					</form>
					
				</div>
			<div class='container-fluid errorModal'>
				
				<div class='errorModalur'>
				<span>x</span>
					<h1>ERROR !!!!!!!</h1>
					<p>You have left a field empty - please fill it in - if the input field doesn't need to be changed please enter the same information already displayed in the game table</p>
					
				</div>
				
			</div>
			<div class='container-fluid errorModalTwo'>
				
				<div class='errorModalur'>
				<span id='errorTwo'>x</span>
					<h1>ERROR !!!!!!!</h1>
					<p>You are missing some information.</p>
					
				</div>
				
			</div>
			
			
			
			</div>
		</div>
		<div class='instructions'>
				<h1 style='font-weight:600'>Instructions</h1>
				
				<ul>
					
					<li><span style='font-weight:600'>Add a Reserve Team</span> - Enter the name of a reserve team you'd like to store for future reference and submit.</li>
					<li><span style='font-weight:600'>Select date by fixture</span> - Select a fixture date to view all game data specific to the date selected. </li>
					<li><span style='font-weight:600'>Add a new game</span> - Enter all information known to create an additional future game. </li>
					<li><span style='font-weight:600'>Edit Teams</span> - Enter edit information in reference to id number of row you want to change. </li>
					<li><span style='font-weight:600'>Delete game</span> - Enter id number of game row you would like to delete. </li>
				</ul>
			
			</div>
	<?php if(isset($_GET['date'])) { ?>
											<tr> <td colspan='4'><a class='viewAllAdmin' href='admin.php'>View All Dates</a></td> </tr>
										<?php } ?>
	<?php
echo "<table style='border: solid 1px black;' class='teamsTable'>";
 echo "<tr><th>Id</th><th>Date</th><th>Home Team</th><th>Away Team</th><th>Reserve Team</th><th>Home Team Logo</th><th>Away Team Logo</th><th>Score ( Home vs Away )</th></tr>";
			
		
		
/**/

	include 'config.php';

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT id, dateTime, homeTeam, awayTeam,reserveTeam, homeLogo, awayLogo, score FROM teams " . $dateSelSQL ); 
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC); 

	$counterImg =0;
	
	
				foreach(new TableRows(new RecursiveArrayIterator($stmt->fetchAll())) as $k=>$v) { 
					$counter++;
					if ($counter % 8 == 1 || $counter == 1) {
						echo "<tr><td>".$v."</td>";
					}  else if ($counter % 8 == 0) {
						echo "<td>".$v."</td></tr>";
					} else if ($counter % 8 == 6 || $counter % 8 == 7  ){
						if(trim($v) == '') {
							echo "<td></td>";
						} else {
							echo "<td><img class='tableImage' src='uploads/".$v."'/></td>";
						}	
					}
					else {
						echo "<td>".$v."</td>";
					}
				}
    
}
catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
echo "</table>";
?> 

	<div class='loader'>
		<img src='images/loading.gif'>
	<div>
	<script>
	$( function() {
		$( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
	});
	$('.col-md-12.addNewRowForm .crossOffEditDelete').click(function(){
		$('.addNewRowForm').fadeOut(20);
		
		
	});
	$('.errorTwo').click(function(){
		$(this).fadeOut(20);
		$('container-fluid errorModalTwo').css('display','none');
		$('.errorModalur').fadeOut(20);
		$('.loader').fadeOut	(20);

		
	});
	

	$("#fixtures").on('change', function(){
		var fixture  = $(this).val();
		window.location = "?date="+fixture;	
	});

			
	$('.insertNewRow').click(function(){
		$('.addNewRowForm').fadeIn(200);
		$('.instructions').fadeOut(0);
		$('.col-md-12.editEditForm').fadeOut(0);
		
			
	})
	$('.errorModalur span').click(function(){
		$('.container-fluid.errorModal').fadeOut(20);
		$('.loader').fadeOut(20);
		
	});
	
	
	$('button.crossOffEditDelete').click(function(){
		$('.col-md-12.editForm').fadeOut(20);
		$('.col-md-12.editEditForm').fadeOut(20);
		$('.instructions').fadeIn(200);
	});
	$('a.teamEdits').click(function(){
		$('.col-md-12.editForm').fadeIn(200);
			$('.col-md-12.editEditForm').fadeIn(200)
			$('.instructions').fadeOut(0);
			$('.col-md-12.addNewRowForm').fadeOut(0);
			$('.deleteForm').fadeOut(0);
			
			
	})
	$('.crossOffDelete').click(function(){
		$('.deleteForm').fadeOut(20);
		$('.instructions').fadeIn(200);
	})

	$('.crossOffDelete .deleteEdits').click(function(){
		$('.deleteForm').fadeOut(20);
		$('.instructions').fadeOut(200);
	})
	$('.deleteEdits').click(function(){
		$('.deleteForm').fadeIn(20);
		$('.instructions').fadeOut(0);
		$('.col-md-12.editEditForm').fadeOut(0);
		$('.col-md-12.addNewRowForm').fadeOut(0);
		
	})

	
	$('.submitThis').click(function(){
			$('.loader').fadeIn(20);
		})
		$('.removeThis').click(function(){
			$('.loader').fadeIn(20);
		})
		$('.closeEdit').click(function(){
			$('.editTeam').fadeOut();
				
		});
		$('.updateThis').click(function(){
			$('.editTeam').slideDown(100);
		});
	
					
			
						function formValidateFunction(){ 
							var f1 = $('#f1').val();
							var f2 = $('#f2').val();
							var f3 = $('#f3').val();
							var f4 = $('#f4').val();
							var f5 = $('#f5').val();
							var f6 = $('#f6').val();
							
						if (f1 == "" || f2 == "" || f3 == "" || f4 == "" || f5 == "" || f6 == "") {
							
							$('.container-fluid.errorModal').css('display','block');
							event.preventDefault();
							return false;
						}
						else {
							return true;
						}
				}	
				function formValidateFunctionTwo(){ 
							
							var f8 = $('#f8').val();
							var f9 = $('#f9').val();
							var f10 = $('#f10').val();
							var f11 = $('#f11').val();
							var f12 = $('#f12').val();
							
						if (f8 == "" || f9 == "" || f6 == "" || f7 == "" || f8 == "") {
							
							$('.container-fluid.errorModalTwo').css('display','block');
							event.preventDefault();
							return false;
						}
						else {
							return true;
						}
				}	



	

		
	
	
	</script>
	</body>
</html>
