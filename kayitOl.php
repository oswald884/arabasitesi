<?php
	if (!session_status() == PHP_SESSION_ACTIVE) {
		sesion_start();
	}
	if (isset($_SESSION['user_id'])) {
		header("Location: index.php");
		exit();
	}

	include_once 'conn.php';
	$object = new Dbh;

	if ($_SERVER['REQUEST_METHOD'] == "POST") {
		if (isset($_POST['adSoyad']) && isset($_POST['email']) && isset($_POST['telefon']) && isset($_POST['sifre'])) {
			// Email kullanılmış mı
      $email = $_POST['email'];
      $hata = 0;
      $kullanilmis = false;
      try {
        $pdo = $object->connect();
        if (!$pdo) {
          // hata
          $hata = 1;
        }else{
          $sql = "SELECT email FROM user_info WHERE email = ?;";
          $stmt = $pdo->prepare($sql);
          if (!$stmt) {
            // hata
            $hata = 1;
          }else{
            if(!$stmt->execute([$email])){
              // hata
              $hata = 1;
            }else{
              if (!$stmt->rowCount()) {
                // kullanılmamış
              }else{
                // kullanılmış
                $kullanilmis = true;
              }
            }
          }
        }
      } catch (\Exception $e) {
      }

			if($hata == 1){
        echo("<script> alert('Bir hata oluştu tekrar deneyin.'); </script>");
      }else if($kullanilmis == true){
        echo("<script> alert('Bu e-posta kullanılmış.'); </script>");
      }else{
				// Şifreyi hashle
				$password = password_hash($_POST['sifre'], PASSWORD_DEFAULT);
				$varArray = [ $_POST['adSoyad'], $_POST['email'], $_POST['telefon'], $password];
				$val = $object->insert("INSERT INTO user_info (ad_soyad, email, telefon, password) VALUES (?, ?, ?, ?); ", $varArray);
        if (!$val) {
          echo("<script> alert('Bir hata oluştu tekrar deneyin.'); </script>");
        }else{
					header("Location: login.php?login");
					exit();
        }
			}
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Kayıt Ol</title>

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
			input{
				margin-bottom: 10px;
			}
	</style>
	<script type="text/javascript">
			function validateForm(){
				// ad soyad kontrol

				let adSoyadElement = document.getElementsByName("adSoyad")[0];
				let adSoyad = adSoyadElement.value;
				if (adSoyad.length > 200) {
					alert("Ad soyad 200 karakterden uzun olamaz.");
					return;
				}

				// Email kontrol
				let emailElement = document.getElementsByName("email")[0];
				let email = emailElement.value;
				if (email.length > 200) {
					alert("Email 200 karakterden uzun olamaz.");
					return;
				}

				// Telefon kontrol
				let telefonElement = document.getElementsByName("telefon")[0];
				let telefon = telefonElement.value;
				if (telefon.length > 200) {
					alert("Telefon 200 karakterden uzun olamaz.");
					return;
				}

				// Şifre kontrol
				let sifreElement = document.getElementsByName("sifre")[0];
				let sifre = sifreElement.value;
				if (sifre.length < 6 || sifre.length > 12) {
					alert("Şifre 6 ila 12 karakter arasında olmalıdır.");
					return;
				}

				let form = document.getElementById("kayitForm");
				form.submit();
			}
	</script>
</head>
<body>
	<div class="login-form-div">
		<form id="kayitForm" onsubmit="event.preventDefault(); validateForm();" method="POST">
			<input type="text" required="required" name="adSoyad" class="login-element" placeholder="Ad Soyad" />
			<input type="email"required="required" name="email" class="login-element" placeholder="Email" />
			<input type="number" required="required" min="0" name="telefon" class="login-element" placeholder="Telefon" />
			<input type="password" required="required" name="sifre" class="login-element" placeholder="Şifre" /><br>
			<input type="submit" name="sbmt_sign" class="login-element-submit" value="Kayıt ol"/>
		</form>
	</div>
</body>
</html>
