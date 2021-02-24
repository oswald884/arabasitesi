<?php
	include_once 'conn.php';
	$object = new Dbh;
	session_start();

	include 'oturum_acik.php';

	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		if (isset($_POST['email']) AND isset($_POST['password'])) {
			// Emaile göre şifreyi getir
			$email = $_POST['email'];
			$adminRow = getir($email);
			if (!$adminRow) {
				echo("<script> alert('Hatalı Email veya Şifre'); </script>");
			}
			else if (!password_verify($_POST['password'], $adminRow['password'])) {
				echo("<script> alert('Hatalı Email veya Şifre'); </script>");
			}else{
				$_SESSION['admin_id'] = $adminRow['id'];
				$_SESSION['admin_adsoyad'] = $adminRow['ad_soyad'];
				$_SESSION['admin_pozisyon'] = $adminRow['pozisyon'];
				$_SESSION['admin_email'] = $adminRow['email'];
				$_SESSION['admin_telefon'] = $adminRow['telefon'];
				$_SESSION['admin_adres'] = $adminRow['adres'];
				$_SESSION['admin_resim'] = $adminRow['resim'];

				header("Location: index.php");
				exit();
			}
		}
	}

	function getir($email){
		try {
			$object = new Dbh;
			$pdo = $object->connect();
			if (!$pdo) {
				return false;
			}

			$sql = "SELECT * FROM admin_info WHERE email = ?;";
			$stmt = $pdo->prepare($sql);
			if (!$stmt) {
				return false;
			}

			$email = $_POST['email'];
			$stmt->execute([$email]);
			if (!$stmt->rowCount()) {
				return false;
			}

			return $stmt->fetch();

		} catch (\Exception $e) {

		}

	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Giriş Yap</title>

	<style>

		/* Login Form */
		body{
			margin: 0;
			padding: 0;
			background-color: #AEABAB;
		}


		.login-form-div{
			width: 30%;
			height: auto;
			padding-top: 40px;
			padding-bottom: 40px;
			margin: 0 auto;
			margin-top: 200px;
			margin-bottom: 200px;
			background-color: white;
			text-align: center;
			border-radius: 15px;
			min-width: 400px;
		}

		/* Login Form Elements */
		.login-element{
			width: 50%;
			height: 40px;
			padding: 5px;
			text-align: center;
			color: black;
			border-radius: 25px;
			border: 1px solid;
			border-color: gray;
			font-size: 15px;

		}
		.login-element2{
			width: 50%;
			height: 40px;
			padding: 5px;
			text-align: center;
			color: black;
			border-radius: 25px;
			border: 1px solid;
			border-color: gray;
			margin-top: 30px;
			font-size: 15px;
		}

		/* To Change Placeholder's text color */
		.login-element::placeholder,.login-element2::placeholder { /* Chrome, Firefox, Opera, Safari 10.1+ */
  			color: black;
  			opacity: 1; /* Firefox */
		}

		.login-element:-ms-input-placeholder, .login-element2:-ms-input-placeholder { /* Internet Explorer 10-11 */
  			color: black;
		}

		.login-element::-ms-input-placeholder, .login-element2::-ms-input-placeholder { /* Microsoft Edge */
  			color: black;
		}

		/* Login Button */
		.login-element-submit{
			width: 25%;
			height: 50px;
			margin-top: 25px;
			color: white;
			font-size: 18px;
			border-radius: 15px;
			border: none;
			background-color: #696969;
			cursor: pointer;
			transition: 0.3s;
		}
		.login-element-submit:hover{
			background-color: #34eb4f;
		}


		/* To get rid of outline */
		.login-element:focus{
			outline: none !important;
		}
		.login-element2:focus{
			outline: none !important;
		}
		.login-element-submit:focus{
			outline: none !important;
		}

    	/* Error div */
    	.error-div{
    		padding: 0;
    		margin-bottom: 15px;
    		width: 100%;
    		height: auto;
    		text-align: center;
    		color: red;
    		font-size: 18px;
    	}

    	/* a tags */
    	.temp-a{
    		color: black;
    		font-size: 17px;
    	}
    	.temp-a:hover{
    		color: red;
    	}
	</style>
</head>
<body>
	<div class="login-form-div">
		<form method="POST">
			<input type="email" name="email" class="login-element" placeholder="Email" required="required"/> </br>
			<input type="password" name="password" class="login-element2" placeholder="Şifre" required="required" /> </br>
			<input type="submit" name="sbmt_login" class="login-element-submit" value="Giriş yap"/> </br>
		</form>
	</div>
</body>
</html>

<?php

?>
