<?php
  include_once 'conn.php';
  $object = new Dbh;
  session_start();
  include 'oturum_acik_mi.php';

  if ($_SERVER['REQUEST_METHOD'] == "POST" && $object) {
    if (!isset($_POST['adSoyad']) || !isset($_POST['pozisyon']) || !isset($_POST['email']) || !isset($_POST['sifre']) || !isset($_POST['telefon']) || !isset($_POST['adres']) || !isset($_FILES['profilResim'])) {
      echo("<script> alert('Eksik bilgiler var tekrar deneyin.'); </script>");
    }else{

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
          $sql = "SELECT email FROM admin_info WHERE email = ?;";
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
        $adSoyad = $_POST['adSoyad'];
        $pozisyon = $_POST['pozisyon'];
        $sifre = $_POST['sifre'];
        $telefon = $_POST['telefon'];
        $adres = $_POST['adres'];

        $file = $_FILES['profilResim'];
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];
        $fileType = $file['type'];

        // Resmi kopyala
        $parcala = explode('.', $fileName);
        $fileExt = strtolower(end($parcala));

        $fileNameNew = uniqid('', true).".".$fileExt;
        $fileDestination = 'images/'.$fileNameNew;
        move_uploaded_file($fileTmpName, $fileDestination);

        // Şifreyi hashle
        $password = password_hash($sifre, PASSWORD_DEFAULT);

        $varArray = [$adSoyad, $pozisyon, $email, $password, $telefon, $adres, $fileDestination];
        $val = $object->insert("INSERT INTO admin_info (ad_soyad, pozisyon, email, password, telefon, adres, resim) VALUES (?, ?, ?, ?, ? ,? ,?); ", $varArray);
        if (!$val) {
          echo("<script> alert('Eklenemedi tekrar deneyin.'); </script>");
        }else{
          echo("<script> alert('Yeni Admin Eklendi.'); </script>");
        }
      }
    }
  }
?>

<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title>Admin Ekle</title>
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<script>
  function validateForm(){
    // Ad Soyad kontrol
    let adSoyadElement = document.getElementsByName("adSoyad")[0];
    let adSoyad = adSoyadElement.value;
    if (adSoyad == ""){
      return;
    }else if(adSoyad.length > 200){
      alert("Ad soyad 200 karakterden uzun olamaz.");
      return;
    }

    // Pozisyon Kontrol
    let pozisyonElement = document.getElementsByName("pozisyon")[0];
    let pozisyon = pozisyonElement.value;
    if (pozisyon == "") {
      return;
    }else if(pozisyon.length >200){
      alert("Pozisyon 200 karakterden uzun olamaz.");
      return;
    }

    // Email Kontrol
    let emailElement = document.getElementsByName("email")[0];
    let email = emailElement.value;
    if (email == "") {
      return;
    }else if(email.length > 200){
      alert("Email 200 karakterden uzun olamaz.");
      return;
    }

    // Şifre Kontrol
    let sifreElement = document.getElementsByName("sifre")[0];
    let sifre = sifreElement.value;
    if (sifre == "") {
      return;
    }else if(sifre.length < 6 || sifre.length > 12){
      alert("En az 6 en fazla 12 karakterli bir şifre seçin.");
    }

    var numbers = /^[0-9]+$/;
    // Telefon Kontrol
    let telefonElement = document.getElementsByName("telefon")[0];
    let telefon = telefonElement.value;
    if (telefon == "") {
      return;
    }else if(telefon.length > 15){
      alert("Telefon 15 karakterden uzun olamaz.");
      return;
    }else if(!telefon.match(numbers)){
        alert('Telefon numarası sadece rakamları içerebilir.');
        return;
    }

    // Adres Kontrol
    let adresElement = document.getElementsByName("adres")[0];
    let adres = adresElement.value;
    if (adres == "") {
      return;
    }else if(adres.length > 500){
      alert("Adres 500 karakterden uzun olamaz.");
      return;
    }

    // Resim Kontrol
    let profilResimElement = document.getElementsByName("profilResim")[0];
    if(profilResimElement.value == ""){
      return;
    }

    let file = profilResimElement.files[0];
    let fileName = file.name;
    let parcala = fileName.split('.');
    let extension = parcala[parcala.length -1];
    extension = extension.toLowerCase();

    if(extension != "png" && extension != "jpg" && extension != "jpeg"){
      // Uygun olmayan dosya formatı
      profilResimElement.style.borderColor = "#FF0000";
      alert('Uygun olmayan dosya formatı');
      return;
    }else if(file.size > 5000000){
      // Dosya 5mbdan büyük
      alert("Dosya boyutu 5mb'dan büyük olamaz");
      return;
    }

    let form = document.getElementById("adminEkleForm");
    form.submit();
  }
</script>
</head>

<body>
    <?php
      include 'preloader.php';
    ?>

    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper" data-navbarbg="skin6" data-theme="light" data-layout="vertical" data-sidebartype="full" data-boxed-layout="full">
        <?php
          include 'topbar-header.php';
          include 'sidebar.php';
        ?>

        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="page-breadcrumb">
                <div class="row">
                    <div class="col-5 align-self-center">
                        <h4 class="page-title">Admin Ekle</h4>
                    </div>
                    <div class="col-7 align-self-center">
                        <div class="d-flex align-items-center justify-content-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="index.php">Ana Sayfa</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Admin Ekle</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
            <!-- ============================================================== -->
            <!-- End Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Start Page Content -->
                <!-- ============================================================== -->
                <div class="row">
                    <div class="col-12">
                        <div class="card"  style="width: 50%; min-width: 400px; margin: 0 auto;">
                            <div class="card-body" style="text-align: center;">
                              <form id="adminEkleForm" onsubmit="event.preventDefault(); validateForm();" enctype="multipart/form-data" method="post">

                              <div class="form-group">
                                  <label>Ad Soyad</label>
                                  <input name="adSoyad" type="text" class="form-control col-6" placeholder="Ad Soyad" required="required" style="border-color: #C0C0C0; max-width: 200px; margin: 0 auto;">
                              </div>

                              <div class="form-group">
                                  <label>Pozisyon</label>
                                  <input name="pozisyon" type="text" class="form-control col-6" placeholder="Pozisyon" required="required" style="border-color: #C0C0C0; max-width: 200px; margin: 0 auto;">
                              </div>

                              <div class="form-group">
                                  <label>Email</label>
                                  <input name="email" type="email" class="form-control col-6" placeholder="Email" required="required" style="border-color: #C0C0C0; max-width: 200px; margin: 0 auto;">
                              </div>

                              <div class="form-group">
                                  <label>Şifre</label>
                                  <input name="sifre" type="password" class="form-control col-6" placeholder="Şifre" required="required" style="border-color: #C0C0C0; max-width: 200px; margin: 0 auto;">
                              </div>

                              <div class="form-group">
                                  <label>Telefon</label>
                                  <input name="telefon" type="tel" class="form-control col-6" placeholder="Telefon" required="required" style="border-color: #C0C0C0; max-width: 200px; margin: 0 auto;">
                              </div>

                              <div class="form-group">
                                  <label>Adres</label>
                                  <input name="adres" type="text" class="form-control col-6" placeholder="Adres" required="required" style="border-color: #C0C0C0; max-width: 200px; margin: 0 auto;">
                              </div>

                              <div class="form-group" style="width: 50%; max-width: 250px; margin: 0 auto;">
                                  <label>Profil Resmi ( jpg / jpeg / png)</label>
                                  <input name="profilResim" type="file" required="required" class="form-control" style="border-color: #C0C0C0; cursor: pointer;">
                              </div>

                              <div class="form-group" style="width: 50%; max-width: 250px; margin: 0 auto;">
                                  <button type="submit" class="btn" style="background-color: #C0C0C0; margin-top: 30px; padding-left: 30px; padding-right: 30px;"> Ekle </button>
                              </div>
                              </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End PAge Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
            <?php
              include 'footer.php';
            ?>
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <script src="assets/libs/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="assets/libs/popper.js/dist/umd/popper.min.js"></script>
    <script src="assets/libs/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="assets/extra-libs/sparkline/sparkline.js"></script>
    <!--Wave Effects -->
    <script src="dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="dist/js/sidebarmenu.js"></script>
    <!--Custom JavaScript -->
    <script src="dist/js/custom.min.js"></script>
</body>

</html>
