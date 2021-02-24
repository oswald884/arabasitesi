<?php
  include_once 'conn.php';
  $object = new Dbh;
  session_start();
  include 'oturum_acik_mi.php';

  $islem = false;
  if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['model_id'])){
      $islem = true;
    }

    // Marka değiştirilmek isteniyorsa
    if($islem AND isset($_POST['selectMarka'])){
      $stmt = $object->connect()->prepare("UPDATE modeller SET marka_id = ? WHERE id = ?;");
      $stmt->execute([$_POST['selectMarka'], $_POST['model_id']]);
      echo("<script> alert('Marka güncellendi.') </script>");
    }
    // Model adı değiştirilmek isteniyorsa
    else if($islem AND isset($_POST['modelAd'])){
      $stmt = $object->connect()->prepare("UPDATE modeller SET model_adi = ? WHERE id = ?;");
      $stmt->execute([$_POST['modelAd'], $_POST['model_id']]);
      echo("<script> alert('Model adı güncellendi.') </script>");
    }// Araç tipi değiştirilmek isteniyorsa
    else if($islem AND isset($_POST['selectAracTipi'])){
      $stmt = $object->connect()->prepare("UPDATE modeller SET tip_id = ? WHERE id = ?;");
      $stmt->execute([$_POST['selectAracTipi'], $_POST['model_id']]);
      echo("<script> alert('Araç Tipi güncellendi.') </script>");
    }// Yakıt tipi değiştirilmek isteniyorsa
    else if($islem AND isset($_POST['selectYakitTipi'])){
      $stmt = $object->connect()->prepare("UPDATE modeller SET yakit_tip_id = ? WHERE id = ?;");
      $stmt->execute([$_POST['selectYakitTipi'], $_POST['model_id']]);
      echo("<script> alert('Yakıt Tipi güncellendi.') </script>");
    }// Vites tipi değiştirilmek isteniyorsa
    else if($islem AND isset($_POST['selectVitesTipi'])){
      $stmt = $object->connect()->prepare("UPDATE modeller SET vites_tip_id = ? WHERE id = ?;");
      $stmt->execute([$_POST['selectVitesTipi'], $_POST['model_id']]);
      echo("<script> alert('Vites Tipi güncellendi.') </script>");
    }// Koltuk Sayısı değiştirilmek isteniyorsa
    else if($islem AND isset($_POST['koltukSayisi'])){
      $stmt = $object->connect()->prepare("UPDATE modeller SET koltuk_sayisi = ? WHERE id = ?;");
      $stmt->execute([$_POST['koltukSayisi'], $_POST['model_id']]);
      echo("<script> alert('Koltuk Sayısı güncellendi.') </script>");
    }// Model Resmi değiştirilmek isteniyorsa
    else if($islem AND isset($_FILES['modelResim'])){
      // Önceki resmi sil
      $tempRow = $object->getModelRow($_POST['model_id']);
      $eskiResim = $tempRow['model_resim'];
      try {
        unlink($eskiResim);
      } catch (Exception $e) {

      }

      // Yeni resmi kopyala
      $file = $_FILES['modelResim'];
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

      $stmt = $object->connect()->prepare("UPDATE modeller SET model_resim = ? WHERE id = ?;");
      $stmt->execute([$fileDestination, $_POST['model_id']]);
      echo("<script> alert('Model Resmi güncellendi.') </script>");
    }

    $modelRow = null;
    if($islem == true){
      $modelRow = $object->getModelRow($_POST['model_id']);
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
    <title>Model Düzenle</title>
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
<style>
  select{
    border-color: #C0C0C0 !important;
  }
</style>
<script>
  function validateModelResim(){
    let model_id = document.getElementsByName("model_id")[0];
    if(model_id.value == ""){
      return;
    }

    // Model Resmi kontrol
    let modelResimElement = document.getElementsByName("modelResim")[0];
    if(modelResimElement.value == ""){
      return;
    }

    let file = modelResimElement.files[0];
    let fileName = file.name;
    let parcala = fileName.split('.');
    let extension = parcala[parcala.length -1];
    extension = extension.toLowerCase();

    if(extension != "png" && extension != "jpg" && extension != "jpeg"){
      // Uygun olmayan dosya formatı
      modelResimElement.style.borderColor = "#FF0000";
      alert('Uygun olmayan dosya formatı');
      return;
    }else if(file.size > 5000000){
      // Dosya 5mbdan büyük
      alert("Dosya boyutu 5mb'dan büyük olamaz");
      return;
    }

    // Uygun resim formu gönder
    let form = document.getElementById("modelResimForm");
    form.submit();
  }

  function validateMarka(){
    // Geçerli bir marka seçildi mi?
    let selectMarka = document.getElementsByName("selectMarka")[0];
    let value = selectMarka.options[selectMarka.selectedIndex].value;

    if(value == -1){
      // Seçilmediyse
      alert("Lütfen marka seçin.");
      return;
    }else if(value == -2){
      // Veri tabanında marka yoksa
      alert("Hiç marka yok!");
      return;
    }else if(value == -3){
      alert("Marka zaten aynı");
    }
    else{
      let form = document.getElementById("markaForm");
      form.submit();
    }
  }

  function validateModelAd(){
    // Model adı kontrol
    let modelAdElement = document.getElementsByName("modelAd")[0];
    let modelAd = modelAdElement.value;
    if(modelAd == ""){
      alert("Model adı boş bırakılamaz!");
      return;
    }else if(modelAd.length > 100){
      alert("Model adı 100 karakterden uzun olamaz!");
      return;
    }else{
      let form = document.getElementById("modelAdForm");
      form.submit();
    }
  }

  function validateAracTipi(){
    // Araç tipi kontrol
    let selectAracTipi = document.getElementsByName("selectAracTipi")[0];
    let aracTipi = selectAracTipi.options[selectAracTipi.selectedIndex].value;

    if(aracTipi == -1){
      alert("Lütfen araç tipi seçin.");
      return;
    }else if(aracTipi == -2){
      alert("Hiç araç tipi yok!");
      return;
    }else if(aracTipi == -3){
      alert("Araç tipi zaten aynı");
    }
    else{
      let form = document.getElementById("aracTipiForm");
      form.submit();
    }
  }

  function validateYakitTipi(){
    // Yakıt tipi kontrol
    let selectYakitTipi = document.getElementsByName("selectYakitTipi")[0];
    let yakitTipi = selectYakitTipi.options[selectYakitTipi.selectedIndex].value;

    if(yakitTipi == -1){
      alert("Lütfen yakıt tipi seçin.");
      return;
    }else if(yakitTipi == -2){
      alert("Hiç yakıt tipi yok!");
      return;
    }else if(yakitTipi == -3){
      alert("Yakıt tipi zaten aynı");
    }
    else{
      let form = document.getElementById("yakitTipiForm");
      form.submit();
    }
  }

  function validateVitesTipi(){
    // Vites tipi kontrol
    let selectVitesTipi = document.getElementsByName("selectVitesTipi")[0];
    let vitesTipi = selectVitesTipi.options[selectVitesTipi.selectedIndex].value;

    if(vitesTipi == -1){
      alert("Lütfen vites tipi seçin.");
      return;
    }else if(vitesTipi == -2){
      alert("Hiç vites tipi yok!");
      return;
    }else if(vitesTipi == -3){
      alert("Vites tipi zaten aynı");
    }
    else{
      let form = document.getElementById("vitesTipiForm");
      form.submit();
    }
  }

  function validateKoltukSayisi(){
    let model_id = document.getElementsByName("model_id")[0];
    if(model_id.value == ""){
      return;
    }else{
      let form = document.getElementById("koltukSayisiForm");
      form.submit();
    }
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
                        <h4 class="page-title">Model Düzenle</h4>
                    </div>
                    <div class="col-7 align-self-center">
                        <div class="d-flex align-items-center justify-content-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="index.php">Ana Sayfa</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="modeller.php">Modeller</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Model Düzenle</li>
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

                            <!-- MARKA GUNCELLE ================= -->
                            <form id="markaForm" onsubmit="event.preventDefault(); validateMarka();" method="POST">
                              <input type="hidden" name="model_id" value="<?php if($islem){echo($_POST['model_id']);} ?>" >
                              <div class="form-group">
                              <label>Marka</label></br>
                              <select name="selectMarka" class="custom-select col-6" id="inlineFormCustomSelect" required="required">
                                  <?php
                                    if($modelRow != null){
                                      // Markaları getir
                                      $stmt = $object->connect()->query("SELECT * FROM markalar;");
                                      if(!$stmt->rowCount()){
                                        echo('<option value="-2">Hiç marka yok</option>');
                                      }
                                      else{
                                        while($row = $stmt->fetch()){
                                          echo('
                                            <option
                                          ');
                                          if($modelRow['marka_id'] == $row['id']){
                                            echo(' selected value="-3">');
                                          }
                                          else{
                                            echo ('value="'.escape($row['id']).'">');
                                          }
                                          echo(escape($row['ad']).' </option>');
                                        }
                                      }
                                    }
                                  ?>
                              </select>
                              <button type="submit" name="marka_guncelle" class="btn" style="background-color: #C0C0C0; margin-left: 5px; padding-left: 30px; padding-right: 30px;"/>Güncelle</button>
                              </div>
                            </form>
                            <!-- ================= -->

                            <!-- MODEL ADI GUNCELLE ================= -->
                            <form id="modelAdForm" onsubmit="event.preventDefault(); validateModelAd();" method="POST">
                              <input type="hidden" name="model_id" value="<?php if($islem){echo($_POST['model_id']);} ?>" >
                              <div class="form-group">
                              <label>Model Adı</label></br>
                              <input name="modelAd" type="text" class="form-control col-6" placeholder="<?php if($islem){echo(escape(ucfirst($modelRow['model_adi'])));} ?>" required="required" style="border-color: #C0C0C0; max-width: 200px; margin: 0 auto;">
                              <button type="submit" name="model_ad_guncelle" class="btn" style="background-color: #C0C0C0; margin-bottom: 10px; margin-top: 10px; margin-left: 5px; padding-left: 30px; padding-right: 30px;"/>Güncelle</button>
                            </div>
                            </form>
                            <!-- ================= -->

                            <!-- ARAÇ TİPİ GUNCELLE ================= -->
                            <form id="aracTipiForm" onsubmit="event.preventDefault(); validateAracTipi();" method="POST">
                              <input type="hidden" name="model_id" value="<?php if($islem){echo($_POST['model_id']);} ?>" >
                              <div class="form-group">
                              <label>Araç Tipi</label></br>
                              <select name="selectAracTipi" class="custom-select col-6" id="inlineFormCustomSelect" required="required">
                                  <?php
                                    if($modelRow != null){
                                      // Araç tiplerini getir
                                      $stmt = $object->connect()->query("SELECT * FROM tipler;");
                                      if(!$stmt->rowCount()){
                                        echo('<option value="-2">Hiç araç tipi yok</option>');
                                      }
                                      else{
                                        while($row = $stmt->fetch()){
                                          echo('
                                            <option
                                          ');
                                          if($modelRow['tip_id'] == $row['id']){
                                            echo(' selected value="-3">');
                                          }
                                          else{
                                            echo ('value="'.escape($row['id']).'">');
                                          }
                                          echo(escape(ucfirst($row['tip_adi'])).' </option>');
                                        }
                                      }
                                    }
                                  ?>
                              </select>
                              <button type="submit" name="aracTipi_guncelle" class="btn" style="background-color: #C0C0C0; margin-left: 5px; padding-left: 30px; padding-right: 30px;"/>Güncelle</button>
                              </div>
                            </form>
                            <!-- ================= -->

                            <!-- ARAÇ TİPİ GUNCELLE ================= -->
                            <form id="yakitTipiForm" onsubmit="event.preventDefault(); validateYakitTipi();" method="POST">
                              <input type="hidden" name="model_id" value="<?php if($islem){echo($_POST['model_id']);} ?>" >
                              <div class="form-group">
                              <label>Yakıt Tipi</label></br>
                              <select name="selectYakitTipi" class="custom-select col-6" id="inlineFormCustomSelect" required="required">
                                  <?php
                                    if($modelRow != null){
                                      // Yakıt tiplerini getir
                                      $stmt = $object->connect()->query("SELECT * FROM yakit_tipleri;");
                                      if(!$stmt->rowCount()){
                                        echo('<option value="-2">Hiç yakıt tipi yok</option>');
                                      }
                                      else{
                                        while($row = $stmt->fetch()){
                                          echo('
                                            <option
                                          ');
                                          if($modelRow['yakit_tip_id'] == $row['id']){
                                            echo(' selected value="-3">');
                                          }
                                          else{
                                            echo ('value="'.escape($row['id']).'">');
                                          }
                                          echo(escape(ucfirst($row['yakit_tip_adi'])).' </option>');
                                        }
                                      }
                                    }
                                  ?>
                              </select>
                              <button type="submit" name="yakitTipi_guncelle" class="btn" style="background-color: #C0C0C0; margin-left: 5px; padding-left: 30px; padding-right: 30px;"/>Güncelle</button>
                              </div>
                            </form>
                            <!-- ================= -->

                            <!-- VİTES TİPİ GUNCELLE ================= -->
                            <form id="vitesTipiForm" onsubmit="event.preventDefault(); validateVitesTipi();" method="POST">
                              <input type="hidden" name="model_id" value="<?php if($islem){echo($_POST['model_id']);} ?>" >
                              <div class="form-group">
                              <label>Vites Tipi</label></br>
                              <select name="selectVitesTipi" class="custom-select col-6" id="inlineFormCustomSelect" required="required">
                                  <?php
                                    if($modelRow != null){
                                      // Yakıt tiplerini getir
                                      $stmt = $object->connect()->query("SELECT * FROM vites_tipleri;");
                                      if(!$stmt->rowCount()){
                                        echo('<option value="-2">Hiç yakıt tipi yok</option>');
                                      }
                                      else{
                                        while($row = $stmt->fetch()){
                                          echo('
                                            <option
                                          ');
                                          if($modelRow['vites_tip_id'] == $row['id']){
                                            echo(' selected value="-3">');
                                          }
                                          else{
                                            echo ('value="'.escape($row['id']).'">');
                                          }
                                          echo(escape(ucfirst($row['vites_tip_adi'])).' </option>');
                                        }
                                      }
                                    }
                                  ?>
                              </select>
                              <button type="submit" name="vitesTipi_guncelle" class="btn" style="background-color: #C0C0C0; margin-left: 5px; padding-left: 30px; padding-right: 30px;"/>Güncelle</button>
                              </div>
                            </form>
                            <!-- ================= -->

                            <!-- KOLTUK SAYISI GUNCELLE ================= -->
                            <form id="koltukSayisiForm" onsubmit="event.preventDefault(); validateKoltukSayisi();" method="POST">
                              <input type="hidden" name="model_id" value="<?php if($islem){echo($_POST['model_id']);} ?>" >
                              <div class="form-group">
                              <label>Koltuk Sayısı</label>
                              <input name="koltukSayisi" type="number" min="1" max="100" class="form-control col-6" placeholder="<?php if($islem){echo(escape(ucfirst($modelRow['koltuk_sayisi'])));} ?>" required="required" style="text-align: center; border-color: #C0C0C0; max-width: 200px; margin: 0 auto;">
                              <button type="submit" name="koltuk_sayisi_guncelle" class="btn" style="background-color: #C0C0C0; margin-bottom: 10px; margin-top: 10px; margin-left: 5px; padding-left: 30px; padding-right: 30px;"/>Güncelle</button>
                            </div>
                            </form>
                            <!-- ================= -->


                            <!-- MODEL RESMİNİ GÜNCELLE ======== -->
                            <form id="modelResimForm" onsubmit="event.preventDefault(); validateModelResim();" enctype="multipart/form-data" method="POST">
                            <input type="hidden" name="model_id" value="<?php if($islem){echo($_POST['model_id']);} ?>" >
                            <div class="form-group" style="width: 50%; max-width: 250px; margin: 0 auto;">
                                <label>Model Resmi ( jpg / jpeg / png)</label>
                                <?php
                                  if($islem){
                                    echo('<img src="'.$modelRow['model_resim'].'" style="width: 100%; max-height: 500px; margin-bottom: 20px;" />');
                                  }
                                ?>
                                <input name="modelResim" type="file" required="required" class="form-control" style="border-color: #C0C0C0; cursor: pointer;">
                            </div>
                            <div class="form-group" style="width: 50%; max-width: 250px; margin: 0 auto;">
                                <button type="submit" name="model_resim_guncelle" class="btn" style="background-color: #C0C0C0; margin-bottom: 10px; margin-top: 10px; margin-left: 5px; padding-left: 30px; padding-right: 30px;"/>Güncelle</button>
                            </div>
                            </form>
                            <!-- ======== -->
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

  function modelVarmi(){
    echo "<script> alert('Var'); </script>";
  }
?>
