<?php
session_start();
include 'oturum_acik_mi.php';

include 'conn.php';
$object = new Dbh;


$tempo = false;
if (isset($_SESSION['admin_id'])) {
  $tempo = true;
}

if ($_SERVER['REQUEST_METHOD'] == "POST" && isset($_SESSION['admin_id']) && $object) {
  if (isset($_POST['adSoyad'])) {
    // Ad soyad güncelle
    $val = $object->update("UPDATE admin_info SET ad_soyad = ? WHERE id = ?;", [$_POST['adSoyad'], $_SESSION['admin_id']]);
    if($val){
      echo("<script> alert('Güncellendi.'); </script>");
      $_SESSION['admin_adsoyad'] = $_POST['adSoyad'];
    }else{
      echo("<script> alert('Güncellenemedi tekrar deneyin.'); </script>");
    }
  }
  else if (isset($_POST['email'])) {
    // Email güncelle
    $val = $object->update("UPDATE admin_info SET email = ? WHERE id = ?;", [$_POST['email'], $_SESSION['admin_id']]);
    if($val){
      $_SESSION['admin_email'] = $_POST['email'];
      echo("<script> alert('Güncellendi.'); </script>");
    }else{
      echo("<script> alert('Güncellenemedi tekrar deneyin.'); </script>");
    }
  }
  else if (isset($_POST['adres'])) {
    // Adres güncelle
    $val = $object->update("UPDATE admin_info SET adres = ? WHERE id = ?;", [$_POST['adres'], $_SESSION['admin_id']]);
    if($val){
      $_SESSION['admin_adres'] = $_POST['adres'];
      echo("<script> alert('Güncellendi.'); </script>");
    }else{
      echo("<script> alert('Güncellenemedi tekrar deneyin.'); </script>");
    }
  }
  else if (isset($_POST['yeniSifre']) && isset($_POST['eskiSifre'])) {
    $adminRow = $object->getRowById("admin_info", $_SESSION['admin_id']);
    $password = $adminRow['password'];
    if (password_verify($_POST['eskiSifre'], $password)) {
      // Şifre doğru güncelle
      $hashed = password_hash($_POST['yeniSifre'], PASSWORD_DEFAULT);
      $val = $object->update("UPDATE admin_info SET password = ? WHERE id = ?;", [$hashed, $_SESSION['admin_id']]);
      if($val){
        echo("<script> alert('Güncellendi.'); </script>");
      }else{
        echo("<script> alert('Güncellenemedi tekrar deneyin.'); </script>");
      }
    }else{
      // Şifre yanlış
      echo("<script> alert('Yanlış şifre.'); </script>");
    }
  }else if (isset($_POST['telefon'])) {
    // Email güncelle
    $val = $object->update("UPDATE admin_info SET telefon = ? WHERE id = ?;", [$_POST['telefon'], $_SESSION['admin_id']]);
    if($val){
      $_SESSION['admin_telefon'] = $_POST['telefon'];
      echo("<script> alert('Güncellendi.'); </script>");
    }else{
      echo("<script> alert('Güncellenemedi tekrar deneyin.'); </script>");
    }
  }else if (isset($_FILES['profilResim'])) {
    // Resmi güncelle
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

    $val = $object->update("UPDATE admin_info SET resim = ? WHERE id = ?;", [$fileDestination, $_SESSION['admin_id']]);
    if($val){
      $_SESSION['admin_resim'] = $fileDestination;
      echo("<script> alert('Güncellendi.'); </script>");
    }else{
      echo("<script> alert('Güncellenemedi tekrar deneyin.'); </script>");
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
    <title>Profil</title>
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<style>
  input[type=text], input[type=password], input[type=email]{
    border-color: #333;
  }

  .mauto{
    margin: 0 auto;
  }
</style>
<script>
  function validateAdSoyad(){
    let adSoyadElement = document.getElementsByName("adSoyad")[0];
    let adSoyad = adSoyadElement.value;
    if (adSoyad == "") {
      return;
    }else if(adSoyad.length > 200){
      alert("Ad soyad 200 karakterden uzun olamaz.");
      return;
    }

    let form = document.getElementById("adSoyadForm");
    form.submit();
  }

  function validateEmail(){
    let emailElement = document.getElementsByName("email")[0];
    let email = emailElement.value;
    if (email == "") {
      return;
    }else if(email.length > 200){
      alert("Email 200 karakterden uzun olamaz.");
      return;
    }

    let form = document.getElementById("emailForm");
    form.submit();
  }

  function validateAdres(){
    let adresElement = document.getElementsByName("adres")[0];
    let adres = adresElement.value;
    if (adres == "") {
      return;
    }else if(adres.length > 500){
      alert("Adres 500 karakterden uzun olamaz.");
      return;
    }

    let form = document.getElementById("adresForm");
    form.submit();
  }

  function validateSifre(){
    let eskiSifreElement = document.getElementsByName("eskiSifre")[0];
    let eskiSifre = eskiSifreElement.value;
    let yeniSifreElement = document.getElementsByName("yeniSifre")[0];
    let yeniSifre = yeniSifreElement.value;

    if (eskiSifre == "") {
      return;
    }else if (yeniSifre == "") {
      return;
    }else if(yeniSifre.length < 6 || yeniSifre.length > 12){
      alert("Yeni şifreniz 6 karakter ile 12 karakter arasında olmalıdır.");
      return;
    }

    let form = document.getElementById("sifreForm");
    form.submit();
  }

  function validateTelefon(){
    var numbers = /^[0-9]+$/;
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

    let form = document.getElementById("telefonForm");
    form.submit();
  }

  function validateProfilResim(){
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

    let form = document.getElementById("profilResimForm");
    form.submit();
  }
</script>
</head>

<body>
    <?php
      include 'preloader.php';
    ?>

    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.css -->
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
                        <h4 class="page-title"></h4>
                    </div>
                    <div class="col-7 align-self-center">
                        <div class="d-flex align-items-center justify-content-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="index.php">Ana Sayfa</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Profil</li>
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
                <!-- Row -->
                <div class="row">
                    <!-- Column -->
                    <div class="col-lg-4 col-xlg-3 col-md-5">
                        <div class="card">
                            <div class="card-body">
                                <center class="m-t-30"> <img src="<?php if($tempo){ echo(escape($_SESSION['admin_resim'])); }?>" class="rounded-circle" width="150" />
                                    <h4 class="card-title m-t-10"><?php if($tempo){ echo(escape(ucfirst($_SESSION['admin_adsoyad']))); }?></h4>
                                    <h6 class="card-subtitle"><?php if($tempo){ echo(escape(ucfirst($_SESSION['admin_pozisyon']))); }?></h6>
                                    <div class="row text-center justify-content-md-center">

                                    </div>
                                </center>
                            </div>
                            <div>
                                <hr> </div>
                            <div class="card-body"> <small class="text-muted">Email adresi</small>
                                <h6><?php if($tempo){ echo(escape($_SESSION['admin_email'])); }?></h6> <small class="text-muted p-t-30 db">Telefon</small>
                                <h6><?php if($tempo){ echo(escape($_SESSION['admin_telefon'])); }?></h6> <small class="text-muted p-t-30 db">Adres</small>
                                <h6><?php if($tempo){ echo(escape($_SESSION['admin_adres'])); }?></h6>
                                <div class="map-box">
                                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d470029.1604841957!2d72.29955005258641!3d23.019996818380896!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e848aba5bd449%3A0x4fcedd11614f6516!2sAhmedabad%2C+Gujarat!5e0!3m2!1sen!2sin!4v1493204785508" width="100%" height="150" frameborder="0" style="border:0" allowfullscreen></iframe>
                                </div> <small class="text-muted p-t-30 db"><br>Sosyal Medya</small>
                                <br/>
                                <button style="margin-top: 5px;" class="btn btn-circle btn-secondary"><i class="mdi mdi-facebook"></i></button>
                                <button style="margin-top: 5px;" class="btn btn-circle btn-secondary"><i class="mdi mdi-twitter"></i></button>
                                <button style="margin-top: 5px;" class="btn btn-circle btn-secondary"><i class="mdi mdi-youtube-play"></i></button>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                    <!-- Column -->
                    <div class="col-lg-8 col-xlg-9 col-md-7">
                        <div class="card">
                            <div class="card-body" style="text-align: center;">
                              <form id="adSoyadForm" method="post" onsubmit="event.preventDefault(); validateAdSoyad();">
                                    <div class="form-group">
                                        <label class="col-md-12">Ad Soyad</label>
                                        <div class="col-md-6 mauto">
                                            <input name="adSoyad" type="text" required="required" placeholder="<?php if($tempo){ echo(escape(ucfirst($_SESSION['admin_adsoyad']))); }?>" class="form-control form-control-line">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button type="submit" name="btn_adSoyad" class="btn btn-success">Güncelle</button>
                                        </div>
                                    </div>
                              </form>
                              <form id="emailForm" method="post" onsubmit="event.preventDefault(); validateEmail();">
                                    <div class="form-group">
                                        <label for="example-email" class="col-md-12">Email</label>
                                        <div class="col-md-6 mauto">
                                            <input type="email" name="email" required="required" placeholder="<?php if($tempo){ echo(escape($_SESSION['admin_email'])); }?>" class="form-control form-control-line">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button type="submit" name="btn_email" class="btn btn-success">Güncelle</button>
                                        </div>
                                    </div>
                              </form>
                              <form id="adresForm" method="post" onsubmit="event.preventDefault(); validateAdres();">
                                    <div class="form-group">
                                        <label for="example-email" class="col-md-12">Adres</label>
                                        <div class="col-md-6 mauto">
                                            <input type="text" name="adres" required="required" placeholder="<?php if($tempo){ echo(escape($_SESSION['admin_adres'])); }?>" class="form-control form-control-line">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button type="submit" name="btn_adres" class="btn btn-success">Güncelle</button>
                                        </div>
                                    </div>
                              </form>
                              <form id="sifreForm" method="post" onsubmit="event.preventDefault(); validateSifre();">
                                    <div class="form-group">
                                        <label class="col-md-12">Şuanki Şifre</label>
                                        <div class="col-md-6 mauto">
                                            <input name="eskiSifre" required="required" type="password" placeholder="Şuanki Şifre" class="form-control form-control-line">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-md-12">Yeni Şifre</label>
                                        <div class="col-md-6 mauto">
                                            <input name="yeniSifre" required="required" type="password" placeholder="Yeni Şifre" class="form-control form-control-line">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button type="submit" name="btn_sifre" class="btn btn-success">Güncelle</button>
                                        </div>
                                    </div>
                              </form>
                              <form id="telefonForm" method="post" onsubmit="event.preventDefault(); validateTelefon();">
                                    <div class="form-group">
                                        <label class="col-md-12">Telefon</label>
                                        <div class="col-md-6 mauto">
                                            <input name="telefon" required="required" type="text" placeholder="<?php if($tempo){ echo(escape($_SESSION['admin_telefon'])); }?>" class="form-control form-control-line">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button type="submit" name="btn_telefon" class="btn btn-success">Güncelle</button>
                                        </div>
                                    </div>
                              </form>
                              <form id="profilResimForm" method="post" enctype="multipart/form-data">
                                <div class="form-group" style="width: 50%; max-width: 250px; margin: 0 auto;">
                                    <label>Profil Resmi ( jpg / jpeg / png)</label>
                                    <input onchange="validateProfilResim();" name="profilResim" type="file" required="required" class="form-control" style="border-color: #C0C0C0; cursor: pointer;">
                                </div>
                              </form>
                            </div>
                        </div>
                    </div>
                    <!-- Column -->
                </div>
                <!-- Row -->
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
<?php
  function escape($string){
    return htmlspecialchars($string, ENT_QUOTES);
  }
?>
