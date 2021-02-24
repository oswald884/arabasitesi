<?php
session_start();
  if (!isset($_POST['stok_id'])) {
    header('Location: index.php');
    exit();
  }

  include_once 'conn.php';
  $object = new Dbh;

  $row;
  $hata = true;
  if ($object) {
    $sql="SELECT * FROM stok WHERE id = ?;";
    $stmt = $object->select($sql, [$_POST['stok_id']]);
    if ($stmt) {
      $hata = false;
      $row = $stmt->fetch();
    }
    if ($row['durum'] == false) {
      header("Location: index.php");
      exit();
    }
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>Ana Sayfa</title>

  <!-- CSS -->
  <link rel="stylesheet" type="text/css" href="./css/main.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />

  <!-- JS, Popper.js, and jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

  <!-- JAVASCRIPT -->
  <script type="text/javascript" src="js/main.js"></script>
  <style media="screen">
    .bg{
      background-color: red;
    }
    .m-auto{
      margin: 20px auto;
    }
    .container{
      width: 100%;
    }
    .p-0{
      padding: 0;
    }
    .my-container{
      width: 90%;
      margin: 30px auto;
    }
    .incele{
      padding: 10px 30px;
      border-radius: 5px;
      background-color: orange;
      margin-bottom: 10px;
      border: none;
      color: #fff;
    }
    .foto{
      width:90%;
      max-height:400px;
      margin-top:0px;
      margin-bottom:0px;
    }
    .p-clas{
      padding: 30px 10px;
    }

    h2{
      font-weight: normal;
    }

    .bbottom{
      padding-top: 5px;
      border-bottom: 1px solid;
      border-color: gray;
      margin: 0 auto;
    }

  </style>
  <script>
    function validateForm(){
      // Ad soyad kontrol
      let adSoyadElement = document.getElementsByName("ad")[0];
      let adSoyad = adSoyadElement.value;
      if (adSoyad.length > 200) {
        alert("Ad soyad 200 karakterden uzun olamaz.");
        return;
      }

      // telefon
      let telefonElement = document.getElementsByName("telefon")[0];
      let telefon = telefonElement.value;
      if (telefon.length > 15) {
        alert("Telefon 15 karakterden uzun olamaz.");
        return;
      }
      // Email kontrol
      let emailElement = document.getElementsByName("email")[0];
      let email = emailElement.value;
      if (email.length > 200) {
        alert("Email 200 karakterden uzun olamaz.");
        return;
      }

      // Şuanki tarih
      var today = new Date();
      var gun = today.getDate();
      var ay = today.getMonth();
      var yil = today.getFullYear();

      // Başlangıç tarihi kontrol
      let baslangicTarihi = new Date(document.getElementsByName("baslangicTarihi")[0].value);
      let bgun = baslangicTarihi.getDate();
      let bay = baslangicTarihi.getMonth();
      let byil = baslangicTarihi.getFullYear();

      if (byil > yil + 1 || byil < yil) { // En fazla 1 sene öteye kadar tolere ediyoruz. 30 Aralık 2019 da 1 Ocak 2020 e sipariş verilebilsin diye
        alert("Lütfen geçerli bir başlangıç tarihi seçin.");
        return;
      }else if(byil == yil && bay < ay){ // Geçmişte bir tarih seçildiyse
        alert("Lütfen geçerli bir başlangıç tarihi seçin.");
        return;
      }else if (byil == yil && bay == ay && bgun < gun) { // Geçmişte bir tarih seçildiyse
        alert("Lütfen geçerli bir başlangıç tarihi seçin.");
        return;
      }

      // Bitiş tarihi kontrol
      let bitisTarihi = new Date(document.getElementsByName("bitisTarihi")[0].value);
      let bitgun = bitisTarihi.getDate();
      let bitay = bitisTarihi.getMonth();
      let bityil = bitisTarihi.getFullYear();

      if (bityil < byil) {
        alert("Bitiş tarihi başlangıç tarihinden önce olamaz.");
        return;
      }else if(bityil == byil && bitay < bay){
        alert("Bitiş tarihi başlangıç tarihinden önce olamaz.");
        return;
      }else if(bityil == byil && bitay == bay && bitgun < bgun){
        alert("Bitiş tarihi başlangıç tarihinden önce olamaz.");
        return;
      }

      let form = document.getElementById("siparisForm");
      form.submit();
    }
  </script>
</head>

<body>
  <?php
    include 'header.php';
    $devam = false;
    if (isset($_SESSION['user_id'])) {
      $devam = true;
    }
   ?>
   <div class="my-container">
     <div class="row">
       <div class="col-md-4 text-center m-auto p-clas" style="border: 1px solid; border-color: gray;">
         <div class="form-group text-center col-md-6 m-auto">
           <label>Araç Bilgileri</label><br><br>
         </div>
         <div class="form-group text-center col-md-12 m-auto">
           <img class="foto" src="<?php if(!$hata){ echo(escape("admin/".$row['resim']));} ?>" alt=""><br><br>
         </div>

         <?php
          $modelRow;
          if (!$hata) {
            $modelRow = $object->getModelRow($row['model_id']);
          }
         ?>
         <div class="form-group text-center col-md-12 m-auto">
           <h2>Marka: <?php if(!$hata){echo(escape(ucfirst($object->getMarkaAd($modelRow['marka_id']))));} ?></h2>
         </div>
        <div class="form-group text-center col-md-12 m-auto">
          <h2>Model: <?php if(!$hata){echo(escape(ucfirst($modelRow['model_adi'])));} ?></h2>
        </div>
        <?php
          if (!$hata) {
            $renkRow = $object->getRenkRow($row['renk_id']);
          }
        ?>
        <div class="form-group text-center col-md-12 m-auto">
          <h2>Renk: <?php if(!$hata){echo(escape(ucfirst($renkRow['renk_adi'])));} ?> <img style="background-color: <?php if(!$hata){echo(ucfirst(escape($renkRow['rgb']))); } ?>; width: 30px; height: 30px;"></h2>
        </div>
        <div class="form-group text-center col-md-12 m-auto">
          <h2>Günlük Fiyatı: <?php if(!$hata){echo(escape(ucfirst($row['gunluk_fiyati'])));} ?> tl</h2>
        </div>
       </div>

       <!-- Siparişi tamamla -->
       <div class="col-md-6 text-center m-auto" style="border: 1px solid; border-color: gray; padding: 20px 0; margin-top: 0;">
         <div class="form-group text-center col-md-6 m-auto">
           <label> Siparişinizi Tamamlayın</label>
         </div>
         <form id="siparisForm" action="siparisEkle.php" method="POST" onsubmit="event.preventDefault(); validateForm();">
         <div class="form-group text-center col-md-6 m-auto">
           <label> Ad Soyad </label>
           <input class="form-control" required="required" type="text" name="ad" value="<?php if(!$hata && $devam){echo(escape(ucfirst($_SESSION['user_ad'])));} ?>" placeholder="Ad Soyad">
         </div>
         <div class="form-group text-center col-md-6 m-auto" style="padding-top: 20px;">
           <label> Telefon </label>
           <input class="form-control" required="required" type="number" min="0" name="telefon" value="<?php if(!$hata && $devam){echo(escape(ucfirst($_SESSION['user_telefon'])));} ?>" placeholder="Telefon">
         </div>
         <div class="form-group text-center col-md-6 m-auto" style="padding-top: 20px;">
           <label> Email </label>
           <input class="form-control" required="required" type="email" name="email" value="<?php if(!$hata && $devam){echo(escape($_SESSION['user_email']));} ?>" placeholder="Email">
         </div>
         <div class="form-group text-center col-md-6 m-auto" style="padding-top: 20px;">
           <label> Başlangıç Tarihi </label>
           <input class="form-control" required="required" type="date" name="baslangicTarihi" placeholder="Email" style="text-align: center;">
         </div>
         <div class="form-group text-center col-md-6 m-auto" style="padding-top: 20px;">
           <label> Bitiş Tarihi </label>
           <input class="form-control" required="required" type="date" name="bitisTarihi" placeholder="Bitiş Tarihi" style="text-align: center;">
         </div>
         <div class="form-group text-center col-md-6 m-auto" style="padding-top: 20px;">
           <input type="submit" class="btn btn-primary" name="kirala" value="Sipariş Ver" style="text-align: center;">
         </div>
         <input type="hidden" name="stok_id" value="<?php if(isset($_POST['stok_id'])){echo(escape($_POST['stok_id']));} ?>" >
         </form>
       </div>
    </div>

 </div>
 <div class="row" style="margin-bottom: 35px;">
   <div class="col-md-6 m-auto text-center" style="margin-top: 35px; border: 1px solid; border-color: gray; padding-top: 30px; padding-bottom: 30px;">

     <div class="form-group text-center col-md-12 m-auto">
       <label style="font-size: 20px;"> Detaylı Özellikler </label>
     </div>
     <?php
        // Marka satırını getir
        $markaRow;
        if (!$hata) {
          $markaRow = $object->getRowById("markalar", $modelRow['marka_id']);
        }
     ?>
     <div class="form-group text-center col-md-8 m-auto bbottom">
       <label> Marka: <img src="<?php if(!$hata){echo(escape("admin/".$markaRow['logo_resmi']));} ?>" style="width: 30px; height: 30px;"> <?php if(!$hata){echo(escape(ucfirst($markaRow['ad'])));} ?> </label>
     </div>
     <div class="form-group text-center col-md-8 m-auto bbottom">
       <label> Model: <img src="<?php if(!$hata){echo(escape("admin/".$modelRow['model_resim']));} ?>" style="width: 30px; height: 30px;"> <?php if(!$hata){echo(escape(ucfirst($modelRow['model_adi'])));} ?> </label>
     </div>
     <?php
      if (!$hata) {
        $tipRow = $object->getRowById("tipler", $modelRow['tip_id']);
      }
     ?>
     <div class="form-group text-center col-md-8 m-auto bbottom">
       <label>Araç Tipi: <?php if(!$hata){echo(escape(ucfirst($tipRow['tip_adi'])));} ?></label>
     </div>

     <?php
      if (!$hata) {
        $yakitTipRow = $object->getRowById("yakit_tipleri", $modelRow['yakit_tip_id']);
      }
     ?>
     <div class="form-group text-center col-md-8 m-auto bbottom">
       <label>Yakıt Tipi: <?php if(!$hata){echo(escape(ucfirst($yakitTipRow['yakit_tip_adi'])));} ?></label>
     </div>

     <?php
      if (!$hata) {
        $vitesTipRow = $object->getRowById("vites_tipleri", $modelRow['vites_tip_id']);
      }
     ?>
     <div class="form-group text-center col-md-8 m-auto bbottom">
       <label>Vites Tipi: <?php if(!$hata){echo(escape(ucfirst($vitesTipRow['vites_tip_adi'])));} ?></label>
     </div>

     <div class="form-group text-center col-md-8 m-auto bbottom">
       <label>Koltuk Sayısı: <?php if(!$hata){echo(escape($modelRow['koltuk_sayisi']));} ?></label>
     </div>

     <div class="form-group text-center col-md-8 m-auto bbottom">
       <label> Renk: <img style="width: 30px; height: 30px; background-color: <?php if(!$hata){echo(escape($renkRow['rgb']));} ?>;"> <?php if(!$hata){echo(escape(ucfirst($renkRow['renk_adi'])));} ?> </label>
     </div>

     <div class="form-group text-center col-md-8 m-auto">
       <label>Yıl: <?php if(!$hata){echo(escape($row['yil']));} ?></label>
     </div>
   </div>
 </div>


  <?php
    include 'footer.php';
  ?>

  <!-- FOR SMOOTH SCROLL -->
  <script src="js/smooth-scroll.js"></script>
  <script>
	var scroll = new SmoothScroll('a[href*="#"]');
  </script>
</body>

</html>
<?php
  function escape($string){
    return htmlspecialchars($string, ENT_QUOTES);
  }
?>
