<?php
session_start();
if (isset($_SESSION['user_id'])) {
	header("Location: index.php");
	exit();
}
include_once 'conn.php';
	if (isset($_GET['login'])) {
		echo("<script> alert('Başarıyla kayıt oldunuz. Giriş yapabilirsiniz.'); </script>");
	}

	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		if (isset($_POST['email']) && isset($_POST['sifre'])) {
			// Emaile göre şifreyi getir
			$userRow = getir($_POST['email']);
			if (!$userRow) {
				echo("<script> alert('Hatalı Email veya şifre.'); </script>");
			}else{
				if (!password_verify($_POST['sifre'], $userRow['password'])) {
					echo("<script> alert('Hatalı Email veya şifre.'); </script>");
				}else{
					echo("<script> alert('Giriş başarılı.'); </script>");
					$_SESSION['user_id'] = $userRow['id'];
					$_SESSION['user_ad'] = $userRow['ad_soyad'];
					$_SESSION['user_email'] = $userRow['email'];
					$_SESSION['user_telefon'] = $userRow['telefon'];
					header("Location: index.php");
					exit();
				}
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

			$sql = "SELECT * FROM user_info WHERE email = ?;";
			$stmt = $pdo->prepare($sql);
			if (!$stmt) {
				return false;
			}

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
			<input type="email" required="required" name="email" class="login-element" placeholder="Email" /> </br>
			<input type="password" required="required" name="sifre" class="login-element2" placeholder="Şifre" /> </br>
			<input type="submit" name="sbmt_login" class="login-element-submit" value="Giriş yap"/> </br>
			<!-- Kayıt ol şifremi unuttum -->
			<table style="width: 60%; height: auto; margin: 0 auto; margin-top: 20px; padding: 0;">
				<tr style="width: 100%; height: auto; margin: 0; padding: 0;">
					<td style="width: 40%; height: auto; margin-top: 20px; padding: 0; text-align: center;">
						<a href="kayitOl.php" class="temp-a">Kayıt ol</a>
					</td>
					<td style="width: 60%; height: auto; margin-top: 20px; padding: 0; text-align: right;">
						<a href="forgotPassword.php" class="temp-a">Şifremi unuttum</a>
					</td>
				</tr>
			</table>
		</form>
	</div>
</body>
</html>
