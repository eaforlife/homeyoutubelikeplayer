<?php
session_start();
if(isset($_SESSION['result'])) {
	unset($_SESSION['result']);
}
if(isset($_SESSION['user_name']) && isset($_SESSION['user_level'])) {
	$rng = rand(10000,99999);
	$_SESSION['user_name'] = "anonymous".$rng;
	unset($_SESSION['user_level']);
}
/* NECESSARY HEADER */

$usn = $pwd = $email = "";
$usn_err = $pwd_err = $email_err = "";
$modal_error = $modal_loc = "";

/* LOGIN */
if($_SERVER["REQUEST_METHOD"] == "POST") {
	if(isset($_POST['type'])) {
		$usn = $pwd = $email = $usn_err = $pwd_err = $email_err = $modal_error = $modal_loc = "";
		$modal_loc = $_POST['type'];
		if($_POST['type'] == 'login') {
			if(empty($_POST['usn'])) {
				$usn_err = "Invalid Username<br/>";
			} else {
				$usn = inputClean($_POST['usn']);
			}
			if(empty($_POST['pwd'])) {
				$pwd_err = "Invalid Password<br/>";
			} else {
				$pwd = inputClean($_POST['pwd']);
			}
				
			if($usn_err != "" || $pwd_err != "") {
				$modal_error = "Fields are required";
				$modal_loc = "login";
			} else {
				$query = "SELECT username, password, account FROM login";
				$myCon = new mysqli('localhost', 'root', '', 'ea_data');
				if($myCon->connect_error) {
					$modal_error .= "Connection failed: " . $myCon->connect_error . "<br />";
				}
				$result = $myCon->query($query);
				$found = 0;
				if($result->num_rows > 0) {
					while($output = $result->fetch_assoc()) {
						if(strtolower($output['username']) == strtolower($usn) && $output['password'] == $pwd) {
							$found = 1;
							$_SESSION['user_name'] = $output['username'];
							$_SESSION['user_level'] = $output['account'];
							break;
						}
					}
				}
				$myCon->close();
	
				if($found == 0) {
					$modal_error .= "Invalid Credentials<br/>";
					$modal_loc = "login";
					//$pwd_err = $usn_err = "has-warning has-feedback";
				}
			}
		}
		if($_POST['type'] == 'signup') {
			if(empty($_POST['new-usn'])) {
				$usn_err = "Invalid Username<br/>";
			} else {
				$usn = inputClean($_POST['new-usn']);
			}
			if(empty($_POST['new-pwd'])) {
				$pwd_err = "Invalid Password<br/>";
			} else {
				$pwd = inputClean($_POST['new-pwd']);
			}
			if(empty($_POST['new-email'])) {
				$email_err = "Invalid E-Mail<br/>";
			} else {
				$email = inputClean($_POST['new-email']);
			}
	
			if($usn_err != "" || $pwd_err != "" || $email_err != "") {
				$modal_error .= "Fields are required";
				$modal_loc = "signup";
			} else {
				$query = "SELECT username, email FROM login";
				$myCon = new mysqli('localhost', 'root', '', 'ea_data');
				if($myCon->connect_error) {
					$modal_error .= "Connection failed: " . $myCon->connect_error . "<br />";
				}
				$result = $myCon->query($query);
				$found = 1;
	
				if($result->num_rows > 0) {
					while($output = $result->fetch_assoc()) {
						if(strtolower($output['username']) == strtolower($usn)) {
							$usn_err = "Username already exists!<br />";
							$modal_error .= "Username already exists!<br />";
							$found = 1;
							break;
						}
						if(strtolower($output['email']) == strtolower($email)) {
							$email_err = "Email already exists!<br />";
							$modal_error .= "Email already exists!<br />";
							$found = 1;
							break;
						}
						$found = 0;
					}
				} else {
					$found = 0;
				}
				if($found == 1) {
					$modal_error .= "Something didn't go right..<br />";
					$modal_loc = "signup";
					$myCon->close();
				} else {
					$query_insert = $myCon->prepare("INSERT INTO login (username, password, email, account) VALUES (?,?,?,?)");
					$query_insert->bind_param("sssi", $n_usn, $n_pwd, $n_email, $n_level);
					$n_usn = $usn;
					$n_pwd = $pwd;
					$n_email = $email;
					$n_level = 2;
					$query_insert->execute();
					$query_insert->close();
					$myCon->close();
				}
			}
		}
	}
	
}
header('Refresh: 5;url=./');
function inputClean($data) {
	$data = trim($data);
	$data = stripslashes($data);
	$data = htmlspecialchars($data);
	return $data;
}
?>
<!DOCTYPE html>
<html>
<head><title>Please Wait...</title>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet">
	<link href="css/font-awesome.min.css" rel="stylesheet">
	<style>
		#container {
			margin:0 auto;
			width: 500px;
			height: 350px;
			margin-top: 150px;
			top: 0;
		}
	</style>
</head>
<body>
<div id="container">
	<?php if($modal_loc == "login" && empty($modal_error)): ?>
	<h3 class="text-center">Successfully Logged In</h3>
	<p class="text-center">You will now be redirected to the home page shortly</p>
	<div class="progress">
		<div class="progress-bar progress-bar-striped bg-success active" role="progressbar" aria-valuenow="100" style="height:60px;width:100%">
			Please wait...
		</div>
	</div>
	<?php elseif($modal_loc == "signup" && empty($modal_error)): ?>
	<h3 class="text-center">Successfully Created Account!</h3>
	<p class="text-center">You will now be redirected to the home page shortly</p>
	<div class="progress">
		<div class="progress-bar progress-bar-striped bg-success active" role="progressbar" aria-valuenow="100" style="height:60px;width:100%">
			Please wait...
		</div>
	</div>
	<?php elseif(!empty($modal_error)): ?>
	<h3 class="text-center">Something went wrong</h3>
	<p class="text-center">You will now be redirected to the home page in 10 seconds, you may check the logs for more info.</p>
	<div class="progress">
		<div class="progress-bar progress-bar-striped bg-warning active" role="progressbar" aria-valuenow="100" style="height:60px;width:100%">
			Please wait...
		</div>
	</div>
	<p><small><a href="#" id="debug-toggle"><span id="down" class="glyphicon glyphicon-chevron-down" aria-hidden="true"></span> Show Logs</a></small></p>
	<div class="collapse" id="debug">
		<pre class="text-nowrap">
			<?php echo $modal_error . "<br/>" . $usn_err . $pwd_err . $email_err ?>
		</pre>
	</div>
	<?php endif; ?>
	<?php if(empty($modal_loc)): ?>
		<h3 class="text-center">Page not found</h3>
		<p class="text-center">You will now be redirected to the home page shortly.</p>
		<div class="progress">
			<div class="progress-bar progress-bar-striped bg-danger active" role="progressbar" aria-valuenow="100" style="height:100px;width:100%">
				Please wait...
			</div>
		</div>
	<?php endif; ?>
	
	
</div>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.min.js"></script>
<script>
	$(document).ready(function() {
		$("#debug").collapse('hide');

		$("#debug-toggle").click(function() {
			$("#debug").collapse('toggle');
		});

		$("#debug").on("shown.bs.collapse", function() {
			// change arrow
			$("#down").attr("class","glyphicon glyphicon-chevron-right");
		});
		$("#debug").on("hidden.bs.collapse", function() {
			// change arrow
			$("#down").attr("class","glyphicon glyphicon-chevron-down");
		});
		
	});
</script>
</body>
</html>



