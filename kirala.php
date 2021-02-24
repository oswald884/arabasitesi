<?php
session_start();
  include_once 'conn.php';
  $object = new Dbh;

  /*
  <?php if($object){ $row = $object->selectIcerik(""); if($row){ echo($row['icerik']); }} ?>
  */
  $stmt = false;
  $a = false;
  $b = false;
  $c = false;
  $varArray = array();
  $index = 0;

  if (isset($_POST['selectRenk']) && $_POST['selectRenk'] != -2 && $_POST['selectRenk'] != -1) {
    $a = true;
  }
  if (isset($_POST['altFiyat']) && $_POST['altFiyat'] != "") {
    $b = true;
  }
  if (isset($_POST['ustFiyat']) && $_POST['ustFiyat'] != "") {
    $c = true;
  }




  if (isset($_POST['filtre'])){
    $sql = "SELECT * FROM stok WHERE kiralik_mi = true AND durum = true";
    if ($a) {
      $sql = $sql." AND renk_id = ?";
      $varArray[$index] = $_POST['selectRenk'];
      $index++;
    }
    if ($b) {
      $sql = $sql." AND gunluk_fiyati >= ?";
      $varArray[$index] = $_POST['altFiyat'];
      $index++;
    }
    if ($c) {
      $sql = $sql." AND gunluk_fiyati <= ?";
      $varArray[$index] = $_POST['ustFiyat'];
      $index++;
    }
    $stmt = $object->select($sql, $varArray);
  }
  else{
    // Eğer filtre yoksa hepsini getir
    $stmt = $object->select("SELECT * FROM stok WHERE kiralik_mi = true AND durum = true;", null);
  }
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>Araç Kirala</title>

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
    .grid-cell{
      border: 1px solid;
      border-color: gray;
      width: auto;
      height: 100%;
      padding-left: 3px;
      padding-right: 3px;
      padding-top: 0px;
    }
    .grid-cell img{
      width: 60%;
      max-height: 250px;
      margin: 0px auto;
    }
    .grid-cell h3{
      margin-top: 0;
    }
    .bg{
      background-color: red;
    }
    .m-auto{
      margin: 0 auto;
    }
    .cell-parent{
      margin-bottom: 10px;
      margin-top: 0px;
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
  </style>
</head>

<body>
  <?php
    include 'header.php';
   ?>
   <div class="my-container">
     <div class="row">
       <div class="col-md-3 text-center cell-parent">
         <div class="grid-cell" style="padding-top: 10px; height: auto;">
           <h3>Filtrele</h3>
           <form method="POST">
           <div class="form-group">
           <label>Renk</label></br>
           <select name="selectRenk" class="custom-select col-6" id="inlineFormCustomSelect" required="required">
               <option selected value="-1">Renk Seçin</option>
               <?php
                 // Renkleri getir
                 if ($object !== false){
                   $stmt2 = $object->selectTable("renkler");
                   if (!$stmt2) {
                     echo('<option value="-2">Hiç renk bulunamadı</option>');
                   }else{
                     if($stmt2->rowCount() == 0){
                       echo('<option value="-2">Hiç renk yok</option>');
                     }else{
                       while($row = $stmt2->fetch()){
                         echo('
                           <option value="'.escape($row['id']).'">'.escape(ucFirst($row['renk_adi'])).' </option>
                         ');
                       }
                     }
                   }
                 }

               ?>
           </select>
         </div>
         <div class="form-group">
             <label>Alt fiyat</label>
             <input name="altFiyat" placeholder="Min" type="number" min="0" step="1" class="form-control col-6" style="border-color: #C0C0C0; max-width: 200px; margin: 0 auto;">
         </div>
         <div class="form-group">
             <label>Üst fiyat</label>
             <input name="ustFiyat" placeholder="Max" type="number" min="0" step="1" class="form-control col-6" style="border-color: #C0C0C0; max-width: 200px; margin: 0 auto;">
         </div>
           <input type="hidden" name="filtre" value="1">
           <button type="submit" class="incele"> Filtereyi Uygula </button>
         </div>
       </form>
       </div>
       <div class="col-md-9">
         <!-- GRID BURADA BAŞLIYOR -->
           <div class="container">
             <div class="row">
               <?php
                if ($stmt) {
                  while($row = $stmt->fetch()){
                    $modelRow = $object->getModelRow($row['model_id']);
                    echo('
                    <div class="col-md-4 text-center cell-parent">
                      <div class="grid-cell">
                        <img src="'.escape("admin/".$row['resim']).'">
                        <h3>'.escape($modelRow['model_adi']).'</h3>
                        <h5>'.escape($object->getMarkaAd($modelRow['marka_id'])).'</h5>
                        <h5>'.escape($row['yil']).' Model</h5>
                        <h5> Günlük: '.escape($row['gunluk_fiyati']).' <span style="font-size: 18px;">tl</span></h5>
                        <form action="kiralaTamamla.php" method="POST">
                        <button class="incele">Kirala</button>
                        <input type="hidden" name="stok_id" value="'.escape($row['id']).'" >
                        </form>
                      </div>
                    </div>
                    ');

                  }
                }else{
                  echo('
                      <h2 class="text-center col-md-12"> Üzgünüz Hiç Araç Bulunamadı </h2>
                  ');
                }
               ?>

               <!--
               <div class="col-md-4 text-center cell-parent">
                 <div class="grid-cell">
                   <img src="images/banner1.jpg">
                   <h3>Model</h3>
                   <h5>Marka</h5>
                   <h5>Yıl</h5>
                   <h5>Fiyat</h5>
                   <button class="incele">İncele</button>
                 </div>
               </div>
              -->
           </div>
         </div>
         <!-- GRID BURADA BİTİYOR -->
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
