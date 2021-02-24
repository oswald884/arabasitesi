<?php
  session_start();
  include 'oturum_acik_mi.php';

  include_once 'conn.php';
  $object = new Dbh;

  if ($_SERVER['REQUEST_METHOD'] == "POST" && $object) {
    if (isset($_FILES['slider1Resim']) && $object) {
      $file = $_FILES['slider1Resim'];
      $fileName = $file['name'];
      $fileTmpName = $file['tmp_name'];
      $fileSize = $file['size'];
      $fileError = $file['error'];
      $fileType = $file['type'];

      // Resmi kopyala
      $parcala = explode('.', $fileName);
      $fileExt = strtolower(end($parcala));

      $fileNameNew = uniqid('', true).".".$fileExt;
      $fileDestination = '../images/'.$fileNameNew;
      move_uploaded_file($fileTmpName, $fileDestination);

      $val = $object->update("UPDATE icerikler SET icerik = ? WHERE icerik_key = ?;", ["images/".$fileNameNew, "slider_resim1"]);
      if($val){
        echo("<script> alert('Güncellendi.'); </script>");
      }else{
        echo("<script> alert('Güncellenemedi tekrar deneyin.'); </script>");
      }
    }else if(isset($_POST['slider1Baslik'])){
      $val = $object->update("UPDATE icerikler SET icerik = ? WHERE icerik_key = ?;", [$_POST['slider1Baslik'], "slider_baslik1"]);
      if($val){
        echo("<script> alert('Güncellendi.'); </script>");
      }else{
        echo("<script> alert('Güncellenemedi tekrar deneyin.'); </script>");
      }
    }else if (isset($_FILES['slider2Resim']) && $object) {
      $file = $_FILES['slider2Resim'];
      $fileName = $file['name'];
      $fileTmpName = $file['tmp_name'];
      $fileSize = $file['size'];
      $fileError = $file['error'];
      $fileType = $file['type'];

      // Resmi kopyala
      $parcala = explode('.', $fileName);
      $fileExt = strtolower(end($parcala));

      $fileNameNew = uniqid('', true).".".$fileExt;
      $fileDestination = '../images/'.$fileNameNew;
      move_uploaded_file($fileTmpName, $fileDestination);

      $val = $object->update("UPDATE icerikler SET icerik = ? WHERE icerik_key = ?;", ["images/".$fileNameNew, "slider_resim2"]);
      if($val){
        echo("<script> alert('Güncellendi.'); </script>");
      }else{
        echo("<script> alert('Güncellenemedi tekrar deneyin.'); </script>");
      }
    }else if(isset($_POST['slider2Baslik'])){
      $val = $object->update("UPDATE icerikler SET icerik = ? WHERE icerik_key = ?;", [$_POST['slider2Baslik'], "slider_baslik2"]);
      if($val){
        echo("<script> alert('Güncellendi.'); </script>");
      }else{
        echo("<script> alert('Güncellenemedi tekrar deneyin.'); </script>");
      }
    }
    else if (isset($_FILES['slider3Resim']) && $object) {
      $file = $_FILES['slider3Resim'];
      $fileName = $file['name'];
      $fileTmpName = $file['tmp_name'];
      $fileSize = $file['size'];
      $fileError = $file['error'];
      $fileType = $file['type'];

      // Resmi kopyala
      $parcala = explode('.', $fileName);
      $fileExt = strtolower(end($parcala));

      $fileNameNew = uniqid('', true).".".$fileExt;
      $fileDestination = '../images/'.$fileNameNew;
      move_uploaded_file($fileTmpName, $fileDestination);

      $val = $object->update("UPDATE icerikler SET icerik = ? WHERE icerik_key = ?;", ["images/".$fileNameNew, "slider_resim3"]);
      if($val){
        echo("<script> alert('Güncellendi.'); </script>");
      }else{
        echo("<script> alert('Güncellenemedi tekrar deneyin.'); </script>");
      }
    }else if(isset($_POST['slider3Baslik'])){
      $val = $object->update("UPDATE icerikler SET icerik = ? WHERE icerik_key = ?;", [$_POST['slider3Baslik'], "slider_baslik3"]);
      if($val){
        echo("<script> alert('Güncellendi.'); </script>");
      }else{
        echo("<script> alert('Güncellenemedi tekrar deneyin.'); </script>");
      }
    }
    else if(isset($_POST['aboutBaslik'])){
      $val = $object->update("UPDATE icerikler SET icerik = ? WHERE icerik_key = ?;", [$_POST['aboutBaslik'], "about_baslik"]);
      if($val){
        echo("<script> alert('Güncellendi.'); </script>");
      }else{
        echo("<script> alert('Güncellenemedi tekrar deneyin.'); </script>");
      }
    }
    else if(isset($_POST['aboutParagraf'])){
      $val = $object->update("UPDATE icerikler SET icerik = ? WHERE icerik_key = ?;", [$_POST['aboutParagraf'], "about_paragraf"]);
      if($val){
        echo("<script> alert('Güncellendi.'); </script>");
      }else{
        echo("<script> alert('Güncellenemedi tekrar deneyin.'); </script>");
      }
    }
    else if(isset($_POST['hizmet1Baslik'])){
      $val = $object->update("UPDATE icerikler SET icerik = ? WHERE icerik_key = ?;", [$_POST['hizmet1Baslik'], "services_baslik1"]);
      if($val){
        echo("<script> alert('Güncellendi.'); </script>");
      }else{
        echo("<script> alert('Güncellenemedi tekrar deneyin.'); </script>");
      }
    }
    else if(isset($_POST['hizmet2Baslik'])){
      $val = $object->update("UPDATE icerikler SET icerik = ? WHERE icerik_key = ?;", [$_POST['hizmet2Baslik'], "services_baslik2"]);
      if($val){
        echo("<script> alert('Güncellendi.'); </script>");
      }else{
        echo("<script> alert('Güncellenemedi tekrar deneyin.'); </script>");
      }
    }
    else if(isset($_POST['hizmet3Baslik'])){
      $val = $object->update("UPDATE icerikler SET icerik = ? WHERE icerik_key = ?;", [$_POST['hizmet3Baslik'], "services_baslik3"]);
      if($val){
        echo("<script> alert('Güncellendi.'); </script>");
      }else{
        echo("<script> alert('Güncellenemedi tekrar deneyin.'); </script>");
      }
    }
    else if(isset($_POST['hizmet4Baslik'])){
      $val = $object->update("UPDATE icerikler SET icerik = ? WHERE icerik_key = ?;", [$_POST['hizmet4Baslik'], "services_baslik4"]);
      if($val){
        echo("<script> alert('Güncellendi.'); </script>");
      }else{
        echo("<script> alert('Güncellenemedi tekrar deneyin.'); </script>");
      }
    }
    else if(isset($_POST['hizmet1Paragraf'])){
      $val = $object->update("UPDATE icerikler SET icerik = ? WHERE icerik_key = ?;", [$_POST['hizmet1Paragraf'], "services_paragraf1"]);
      if($val){
        echo("<script> alert('Güncellendi.'); </script>");
      }else{
        echo("<script> alert('Güncellenemedi tekrar deneyin.'); </script>");
      }
    }
    else if(isset($_POST['hizmet2Paragraf'])){
      $val = $object->update("UPDATE icerikler SET icerik = ? WHERE icerik_key = ?;", [$_POST['hizmet2Paragraf'], "services_paragraf2"]);
      if($val){
        echo("<script> alert('Güncellendi.'); </script>");
      }else{
        echo("<script> alert('Güncellenemedi tekrar deneyin.'); </script>");
      }
    }
    else if(isset($_POST['hizmet3Paragraf'])){
      $val = $object->update("UPDATE icerikler SET icerik = ? WHERE icerik_key = ?;", [$_POST['hizmet3Paragraf'], "services_paragraf3"]);
      if($val){
        echo("<script> alert('Güncellendi.'); </script>");
      }else{
        echo("<script> alert('Güncellenemedi tekrar deneyin.'); </script>");
      }
    }
    else if(isset($_POST['hizmet4Paragraf'])){
      $val = $object->update("UPDATE icerikler SET icerik = ? WHERE icerik_key = ?;", [$_POST['hizmet4Paragraf'], "services_paragraf4"]);
      if($val){
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
    <title>İçeriği Düzenle</title>
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<style media="screen">
  .middle-img{
    width: 100%;
    max-height: 250px;
  }
  .border-black{
    border-color: #000;
  }
  .divider{
    padding: 3px 0;
    width: 60%;
    margin: 20px auto;
    background-color: #000;
    border-radius: 5px;
  }
  .divider-2{
    padding: 3px 0;
    width: 80%;
    margin: 20px auto;
    background-color: gray;
    border-radius: 5px;
  }
</style>
<script>
  function validateSlider1Resim(){
    let sliderResimElement = document.getElementsByName("slider1Resim")[0];
    if(sliderResimElement.value == ""){
      return;
    }

    let file = sliderResimElement.files[0];
    let fileName = file.name;
    let parcala = fileName.split('.');
    let extension = parcala[parcala.length -1];
    extension = extension.toLowerCase();

    if(extension != "png" && extension != "jpg" && extension != "jpeg"){
      // Uygun olmayan dosya formatı
      sliderResimElement.style.borderColor = "#FF0000";
      alert('Uygun olmayan dosya formatı');
      return;
    }else if(file.size > 5000000){
      // Dosya 5mbdan büyük
      alert("Dosya boyutu 5mb'dan büyük olamaz");
      return;
    }

    let form = document.getElementById("slider1ResimForm");
    form.submit();
  }

  function validateSlider1Baslik(){
    let baslikElement = document.getElementsByName("slider1Baslik")[0];
    let baslik = baslikElement.value;

    if (baslik.length > 50) {
      alert('Başlık 50 karakterden uzun olamaz.');
      return;
    }

    let form = document.getElementById("slider1BaslikForm");
    form.submit();
  }

  function validateSlider2Resim(){
    let sliderResimElement = document.getElementsByName("slider2Resim")[0];
    if(sliderResimElement.value == ""){
      return;
    }

    let file = sliderResimElement.files[0];
    let fileName = file.name;
    let parcala = fileName.split('.');
    let extension = parcala[parcala.length -1];
    extension = extension.toLowerCase();

    if(extension != "png" && extension != "jpg" && extension != "jpeg"){
      // Uygun olmayan dosya formatı
      sliderResimElement.style.borderColor = "#FF0000";
      alert('Uygun olmayan dosya formatı');
      return;
    }else if(file.size > 5000000){
      // Dosya 5mbdan büyük
      alert("Dosya boyutu 5mb'dan büyük olamaz");
      return;
    }

    let form = document.getElementById("slider2ResimForm");
    form.submit();
  }

  function validateSlider2Baslik(){
    let baslikElement = document.getElementsByName("slider2Baslik")[0];
    let baslik = baslikElement.value;

    if (baslik.length > 50) {
      alert('Başlık 50 karakterden uzun olamaz.');
      return;
    }

    let form = document.getElementById("slider2BaslikForm");
    form.submit();
  }

  function validateSlider3Resim(){
    let sliderResimElement = document.getElementsByName("slider3Resim")[0];
    if(sliderResimElement.value == ""){
      return;
    }

    let file = sliderResimElement.files[0];
    let fileName = file.name;
    let parcala = fileName.split('.');
    let extension = parcala[parcala.length -1];
    extension = extension.toLowerCase();

    if(extension != "png" && extension != "jpg" && extension != "jpeg"){
      // Uygun olmayan dosya formatı
      sliderResimElement.style.borderColor = "#FF0000";
      alert('Uygun olmayan dosya formatı');
      return;
    }else if(file.size > 5000000){
      // Dosya 5mbdan büyük
      alert("Dosya boyutu 5mb'dan büyük olamaz");
      return;
    }

    let form = document.getElementById("slider3ResimForm");
    form.submit();
  }

  function validateSlider3Baslik(){
    let baslikElement = document.getElementsByName("slider3Baslik")[0];
    let baslik = baslikElement.value;

    if (baslik.length > 50) {
      alert('Başlık 50 karakterden uzun olamaz.');
      return;
    }

    let form = document.getElementById("slider3BaslikForm");
    form.submit();
  }

  function validateAboutBaslik(){
    let baslikElement = document.getElementsByName("aboutBaslik")[0];
    let baslik = baslikElement.value;

    if (baslik.length > 50) {
      alert('Başlık 50 karakterden uzun olamaz.');
      return;
    }

    let form = document.getElementById("aboutBaslikForm");
    form.submit();
  }

  function validateAboutParagraf(){
    let baslikElement = document.getElementsByName("aboutParagraf")[0];
    let baslik = baslikElement.value;

    let form = document.getElementById("aboutParagrafForm");
    form.submit();
  }

  function validateHizmet1Baslik(){
    let baslikElement = document.getElementsByName("hizmet1Baslik")[0];
    let baslik = baslikElement.value;

    if (baslik.length > 50) {
      alert('Başlık 50 karakterden uzun olamaz.');
      return;
    }

    let form = document.getElementById("hizmet1BaslikForm");
    form.submit();
  }

  function validateHizmet2Baslik(){
    let baslikElement = document.getElementsByName("hizmet2Baslik")[0];
    let baslik = baslikElement.value;

    if (baslik.length > 50) {
      alert('Başlık 50 karakterden uzun olamaz.');
      return;
    }

    let form = document.getElementById("hizmet2BaslikForm");
    form.submit();
  }

  function validateHizmet3Baslik(){
    let baslikElement = document.getElementsByName("hizmet3Baslik")[0];
    let baslik = baslikElement.value;

    if (baslik.length > 50) {
      alert('Başlık 50 karakterden uzun olamaz.');
      return;
    }

    let form = document.getElementById("hizmet3BaslikForm");
    form.submit();
  }

  function validateHizmet4Baslik(){
    let baslikElement = document.getElementsByName("hizmet4Baslik")[0];
    let baslik = baslikElement.value;

    if (baslik.length > 50) {
      alert('Başlık 50 karakterden uzun olamaz.');
      return;
    }

    let form = document.getElementById("hizmet4BaslikForm");
    form.submit();
  }

  function validateHizmet1Paragraf(){
    let baslikElement = document.getElementsByName("hizmet1Paragraf")[0];
    let baslik = baslikElement.value;

    let form = document.getElementById("hizmet1ParagrafForm");
    form.submit();
  }

  function validateHizmet2Paragraf(){
    let baslikElement = document.getElementsByName("hizmet2Paragraf")[0];
    let baslik = baslikElement.value;

    let form = document.getElementById("hizmet2ParagrafForm");
    form.submit();
  }

  function validateHizmet3Paragraf(){
    let baslikElement = document.getElementsByName("hizmet3Paragraf")[0];
    let baslik = baslikElement.value;

    let form = document.getElementById("hizmet3ParagrafForm");
    form.submit();
  }

  function validateHizmet4Paragraf(){
    let baslikElement = document.getElementsByName("hizmet4Paragraf")[0];
    let baslik = baslikElement.value;

    let form = document.getElementById("hizmet4ParagrafForm");
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
                        <h4 class="page-title">İçerik Düzenle</h4>
                    </div>
                    <div class="col-7 align-self-center">
                        <div class="d-flex align-items-center justify-content-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="index.php">Ana Sayfa</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">İçerik Düzenle</li>
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
                        <div class="card text-center">
                            <div class="card-body">
                              <div style="height: 600px; width: 100%; overflow: scroll;">
                              <iframe src="../index.php" style="width: 100%; height: 4850px; pointer-events: none;"></iframe>
                            </div><br>
                            <div class="divider-2"></div>
                              <!-- ============= SLIDER1 ================= -->
                              <!-- SLIDER 1 RESİM GÜNCELLEME -->
                              <form id="slider1ResimForm" method="post" enctype="multipart/form-data">
                                <div class="form-group" style="width: 50%; max-width: 250px; margin: 0 auto;">
                                  <img src="<?php $row = $object->selectIcerik("slider_resim1"); if($row){ echo("../".escape($row['icerik'])); } ?>" style="margin: 0 auto;" class="middle-img">
                                </div><br>
                                <div class="form-group" style="width: 50%; max-width: 250px; margin: 0 auto;">
                                    <label>Slider 1 Resmi ( jpg / jpeg / png)</label>
                                    <input onchange="validateSlider1Resim();" name="slider1Resim" type="file" required="required" class="form-control" style="border-color: #C0C0C0; cursor: pointer;">
                                </div>
                              </form><br>
                              <form id="slider1BaslikForm" method="post" onsubmit="event.preventDefault(); validateSlider1Baslik();">
                                    <div class="form-group" style="width: 50%; max-width: 600px; margin: 0 auto;">
                                        <label class="col-md-12">Slider 1 Başlık</label>
                                        <div class="col-md-6" style="margin: 0 auto;">
                                            <input name="slider1Baslik" type="text" required="required" placeholder="<?php $row = $object->selectIcerik("slider_baslik1"); if($row){ echo(escape($row['icerik'])); } ?>" class="form-control form-control-line border-black">
                                        </div>
                                    </div><br>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button type="submit" name="btn_slider1Baslik" class="btn btn-success">Güncelle</button>
                                        </div>
                                    </div>
                              </form>
                              <!-- ============= SLIDER1 ================= -->
                              <div class="divider"></div>
                              <!-- ============= SLIDER2 ================= -->
                              <!-- SLIDER 2 RESİM GÜNCELLEME -->
                              <form id="slider2ResimForm" method="post" enctype="multipart/form-data">
                                <div class="form-group" style="width: 50%; max-width: 250px; margin: 0 auto;">
                                  <img src="<?php $row = $object->selectIcerik("slider_resim2"); if($row){ echo("../".escape($row['icerik'])); } ?>" style="margin: 0 auto;" class="middle-img">
                                </div><br>
                                <div class="form-group" style="width: 50%; max-width: 250px; margin: 0 auto;">
                                    <label>Slider 2 Resmi ( jpg / jpeg / png)</label>
                                    <input onchange="validateSlider2Resim();" name="slider2Resim" type="file" required="required" class="form-control" style="border-color: #C0C0C0; cursor: pointer;">
                                </div>
                              </form><br>
                              <form id="slider2BaslikForm" method="post" onsubmit="event.preventDefault(); validateSlider2Baslik();">
                                    <div class="form-group" style="width: 50%; max-width: 600px; margin: 0 auto;">
                                        <label class="col-md-12">Slider 2 Başlık</label>
                                        <div class="col-md-6" style="margin: 0 auto;">
                                            <input name="slider2Baslik" type="text" required="required" placeholder="<?php $row = $object->selectIcerik("slider_baslik2"); if($row){ echo(escape($row['icerik'])); } ?>" class="form-control form-control-line border-black">
                                        </div>
                                    </div><br>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button type="submit" name="btn_slider2Baslik" class="btn btn-success">Güncelle</button>
                                        </div>
                                    </div>
                              </form>
                              <!-- ============= SLIDER2 ================= -->
                              <div class="divider"></div>
                              <!-- ============= SLIDER3 ================= -->
                              <!-- SLIDER 3 RESİM GÜNCELLEME -->
                              <form id="slider3ResimForm" method="post" enctype="multipart/form-data">
                                <div class="form-group" style="width: 50%; max-width: 250px; margin: 0 auto;">
                                  <img src="<?php $row = $object->selectIcerik("slider_resim3"); if($row){ echo("../".escape($row['icerik'])); } ?>" style="margin: 0 auto;" class="middle-img">
                                </div><br>
                                <div class="form-group" style="width: 50%; max-width: 250px; margin: 0 auto;">
                                    <label>Slider 3 Resmi ( jpg / jpeg / png)</label>
                                    <input onchange="validateSlider3Resim();" name="slider3Resim" type="file" required="required" class="form-control" style="border-color: #C0C0C0; cursor: pointer;">
                                </div>
                              </form><br>
                              <form id="slider3BaslikForm" method="post" onsubmit="event.preventDefault(); validateSlider3Baslik();">
                                    <div class="form-group" style="width: 50%; max-width: 600px; margin: 0 auto;">
                                        <label class="col-md-12">Slider 3 Başlık</label>
                                        <div class="col-md-6" style="margin: 0 auto;">
                                            <input name="slider3Baslik" type="text" required="required" placeholder="<?php $row = $object->selectIcerik("slider_baslik3"); if($row){ echo(escape($row['icerik'])); } ?>" class="form-control form-control-line border-black">
                                        </div>
                                    </div><br>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button type="submit" name="btn_slider3Baslik" class="btn btn-success">Güncelle</button>
                                        </div>
                                    </div>
                              </form>
                              <!-- ============= SLIDER3 ================= -->
                              <div class="divider-2"></div>
                              <!-- ============= HAKKIMIZDA KISMI ==================== -->
                              <form id="aboutBaslikForm" method="post" onsubmit="event.preventDefault(); validateAboutBaslik();">
                                    <div class="form-group" style="width: 50%; max-width: 600px; margin: 0 auto;">
                                        <label class="col-md-12">Hakkımızda kısmı Başlık</label>
                                        <div class="col-md-6" style="margin: 0 auto;">
                                            <input name="aboutBaslik" type="text" required="required" placeholder="<?php $row = $object->selectIcerik("about_baslik"); if($row){ echo(escape($row['icerik'])); } ?>" class="form-control form-control-line border-black">
                                        </div>
                                    </div><br>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button type="submit" name="btn_aboutBaslik" class="btn btn-success">Güncelle</button>
                                        </div>
                                    </div>
                              </form>
                              <form id="aboutParagrafForm" method="post" onsubmit="event.preventDefault(); validateAboutParagraf();">
                                    <div class="form-group" style="width: 50%; max-width: 600px; margin: 0 auto;">
                                        <label class="col-md-12">Hakkımızda paragraf</label>
                                        <div class="col-md-6" style="margin: 0 auto;">
                                            <textarea name="aboutParagraf" cols="40" rows="6" required="required" placeholder="<?php $row = $object->selectIcerik("about_paragraf"); if($row){ echo(escape($row['icerik'])); } ?>" class="form-control form-control-line border-black"></textarea>
                                        </div>
                                    </div><br>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button type="submit" name="btn_aboutParagraf" class="btn btn-success">Güncelle</button>
                                        </div>
                                    </div>
                              </form>
                              <!-- ============= HAKKIMIZDA KISMI ==================== -->
                              <div class="divider-2"></div>
                              <!-- ============= HİZMETLERİMİZ KISMI ==================== -->
                                  <!-- ============= HİZMET1 ==================== -->
                                  <form id="hizmet1BaslikForm" method="post" onsubmit="event.preventDefault(); validateHizmet1Baslik();">
                                        <div class="form-group" style="width: 50%; max-width: 600px; margin: 0 auto;">
                                            <label class="col-md-12">Hizmet 1 Başlık</label>
                                            <div class="col-md-6" style="margin: 0 auto;">
                                                <input name="hizmet1Baslik" type="text" required="required" placeholder="<?php $row = $object->selectIcerik("services_baslik1"); if($row){ echo(escape($row['icerik'])); } ?>" class="form-control form-control-line border-black">
                                            </div>
                                        </div><br>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button type="submit" name="btn_hizmet1Baslik" class="btn btn-success">Güncelle</button>
                                            </div>
                                        </div>
                                  </form>
                                  <form id="hizmet1ParagrafForm" method="post" onsubmit="event.preventDefault(); validateHizmet1Paragraf();">
                                        <div class="form-group" style="width: 50%; max-width: 600px; margin: 0 auto;">
                                            <label class="col-md-12">Hizmet 1 Paragraf</label>
                                            <div class="col-md-6" style="margin: 0 auto;">
                                                <textarea name="hizmet1Paragraf" cols="40" rows="6" required="required" placeholder="<?php $row = $object->selectIcerik("services_paragraf1"); if($row){ echo(escape($row['icerik'])); } ?>" class="form-control form-control-line border-black"></textarea>
                                            </div>
                                        </div><br>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button type="submit" name="btn_hizmet1Paragraf" class="btn btn-success">Güncelle</button>
                                            </div>
                                        </div>
                                  </form>
                                  <!-- ============= HİZMET1 ==================== -->
                                  <div class="divider"></div>
                                  <!-- ============= HİZMET2 ==================== -->
                                  <form id="hizmet2BaslikForm" method="post" onsubmit="event.preventDefault(); validateHizmet2Baslik();">
                                        <div class="form-group" style="width: 50%; max-width: 600px; margin: 0 auto;">
                                            <label class="col-md-12">Hizmet 2 Başlık</label>
                                            <div class="col-md-6" style="margin: 0 auto;">
                                                <input name="hizmet2Baslik" type="text" required="required" placeholder="<?php $row = $object->selectIcerik("services_baslik2"); if($row){ echo(escape($row['icerik'])); } ?>" class="form-control form-control-line border-black">
                                            </div>
                                        </div><br>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button type="submit" name="btn_hizmet2Baslik" class="btn btn-success">Güncelle</button>
                                            </div>
                                        </div>
                                  </form>
                                  <form id="hizmet2ParagrafForm" method="post" onsubmit="event.preventDefault(); validateHizmet2Paragraf();">
                                        <div class="form-group" style="width: 50%; max-width: 600px; margin: 0 auto;">
                                            <label class="col-md-12">Hizmet 2 Paragraf</label>
                                            <div class="col-md-6" style="margin: 0 auto;">
                                                <textarea name="hizmet2Paragraf" cols="40" rows="6" required="required" placeholder="<?php $row = $object->selectIcerik("services_paragraf2"); if($row){ echo(escape($row['icerik'])); } ?>" class="form-control form-control-line border-black"></textarea>
                                            </div>
                                        </div><br>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button type="submit" name="btn_hizmet2Paragraf" class="btn btn-success">Güncelle</button>
                                            </div>
                                        </div>
                                  </form>
                                  <!-- ============= HİZMET2 ==================== -->
                                  <div class="divider"></div>
                                  <!-- ============= HİZMET3 ==================== -->
                                  <form id="hizmet3BaslikForm" method="post" onsubmit="event.preventDefault(); validateHizmet3Baslik();">
                                        <div class="form-group" style="width: 50%; max-width: 600px; margin: 0 auto;">
                                            <label class="col-md-12">Hizmet 3 Başlık</label>
                                            <div class="col-md-6" style="margin: 0 auto;">
                                                <input name="hizmet3Baslik" type="text" required="required" placeholder="<?php $row = $object->selectIcerik("services_baslik3"); if($row){ echo(escape($row['icerik'])); } ?>" class="form-control form-control-line border-black">
                                            </div>
                                        </div><br>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button type="submit" name="btn_hizmet3Baslik" class="btn btn-success">Güncelle</button>
                                            </div>
                                        </div>
                                  </form>
                                  <form id="hizmet3ParagrafForm" method="post" onsubmit="event.preventDefault(); validateHizmet3Paragraf();">
                                        <div class="form-group" style="width: 50%; max-width: 600px; margin: 0 auto;">
                                            <label class="col-md-12">Hizmet 3 Paragraf</label>
                                            <div class="col-md-6" style="margin: 0 auto;">
                                                <textarea name="hizmet3Paragraf" cols="40" rows="6" required="required" placeholder="<?php $row = $object->selectIcerik("services_paragraf3"); if($row){ echo(escape($row['icerik'])); } ?>" class="form-control form-control-line border-black"></textarea>
                                            </div>
                                        </div><br>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button type="submit" name="btn_hizmet3Paragraf" class="btn btn-success">Güncelle</button>
                                            </div>
                                        </div>
                                  </form>
                                  <!-- ============= HİZMET3 ==================== -->
                                  <div class="divider"></div>
                                  <!-- ============= HİZMET4 ==================== -->
                                  <form id="hizmet4BaslikForm" method="post" onsubmit="event.preventDefault(); validateHizmet4Baslik();">
                                        <div class="form-group" style="width: 50%; max-width: 600px; margin: 0 auto;">
                                            <label class="col-md-12">Hizmet 4 Başlık</label>
                                            <div class="col-md-6" style="margin: 0 auto;">
                                                <input name="hizmet4Baslik" type="text" required="required" placeholder="<?php $row = $object->selectIcerik("services_baslik4"); if($row){ echo(escape($row['icerik'])); } ?>" class="form-control form-control-line border-black">
                                            </div>
                                        </div><br>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button type="submit" name="btn_hizmet4Baslik" class="btn btn-success">Güncelle</button>
                                            </div>
                                        </div>
                                  </form>
                                  <form id="hizmet4ParagrafForm" method="post" onsubmit="event.preventDefault(); validateHizmet4Paragraf();">
                                        <div class="form-group" style="width: 50%; max-width: 600px; margin: 0 auto;">
                                            <label class="col-md-12">Hizmet 4 Paragraf</label>
                                            <div class="col-md-6" style="margin: 0 auto;">
                                                <textarea name="hizmet4Paragraf" cols="40" rows="6" required="required" placeholder="<?php $row = $object->selectIcerik("services_paragraf4"); if($row){ echo(escape($row['icerik'])); } ?>" class="form-control form-control-line border-black"></textarea>
                                            </div>
                                        </div><br>
                                        <div class="form-group">
                                            <div class="col-sm-12">
                                                <button type="submit" name="btn_hizmet4Paragraf" class="btn btn-success">Güncelle</button>
                                            </div>
                                        </div>
                                  </form>
                                  <!-- ============= HİZMET4 ==================== -->
                              <!-- ============= HİZMETLERİMİZ KISMI ==================== -->
                              <div class="divider-2">
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

<?php
  function escape($string){
    return htmlspecialchars($string, ENT_QUOTES);
  }
?>
