<?php
	require "secure\dbconnect.php";
?>
<!DOCTYPE HTML>  
<html>
<head>
    <?php require "util.php"?>
    <title>Login</title>
</head>
<?php 
	require 'header.php';
?>
<div class="container p-5 my-5">
<form method = "post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">User name</label>
    <input type = "text" id = "uname" name = "uname" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" required>
	<div id="emailHelp" class="form-text">Your user name is uniqe as you.</div>
  </div>
  <div class="mb-3">
    <label for="exampleInputPassword1" class="form-label">Password</label>
    <input type="password" id = "pass" name = "pass" class="form-control" id="exampleInputPassword1" required>
  </div>
  <button type="submit" name = "login" value = "submit" class="btn btn-primary">Submit</button>
</form>
</div>
<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST"){
		$uname = test_input($_POST['uname']);
		$pass = test_input($_POST['pass']);
		
		$sql = "SELECT user_id, password, type, current_timestamp() as timestamp, wrong_attempt FROM `user` WHERE user_name = ?";
		
		$stmt = mysqli_prepare($conn, $sql);
		mysqli_stmt_bind_param($stmt, 's', $uname);
		mysqli_stmt_execute($stmt);
		$result = mysqli_stmt_get_result($stmt);
		
		if (mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_row($result);
			echo $row[0];
			echo $row[3];
			$wrong_attempt = $row[4];
			if (openssl_digest($pass, 'sha512') == $row[1] && $wrong_attempt < 4){
				session_start();
				$_SESSION = array();
				$_SESSION['usrid'] = $row[0];
				$_SESSION['utype'] = $row[2];
				session_write_close();
				echo 'Logged in!';
				header('location:index.php');
			}elseif($wrong_attempt == 2){
				echo 'Caution: Your account will be locked in one more wrong attempt.';
			}
			else{
				$wrong_attempt = $wrong_attempt+1;
				echo "Incorrect username or password!";
				$sql = "UPDATE `user` SET wrong_attempt = ? WHERE user_name = ?";
				$stmt = mysqli_prepare($conn, $sql);
				mysqli_stmt_bind_param($stmt, 'is', $wrong_attempt, $uname);
				mysqli_stmt_execute($stmt);
			}
		}else{
			echo 'Not correct user name or password.';
		}
		
	}
	require 'footer.php';
?>