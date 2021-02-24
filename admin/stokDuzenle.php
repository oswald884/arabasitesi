<?php
  include_once 'conn.php';
  $object = new Dbh;
  session_start();
  include 'oturum_acik_mi.php';

  if($_SERVER['REQUEST_METHOD'] == "POST" AND isset($_POST['stok_id'])){
    if(isset($_POST['selectRenk'])){
      $renk_id = $_POST['selectRenk'];
      $val = $object->update("UPDATE stok SET renk_id = ? WHERE id= ?;", [$renk_id, $_POST['stok_id']]);
      if ($val) {
        echo("<script> alert('Güncellendi'); </script>");
      }else{
        echo("<script> alert('Güncellenemedi'); </script>");
      }
    }else if(isset($_POST['k_yes'])){
      if (isset($_POST['kiralik_mi'])) {
        $val = $object->update("UPDATE stok SET kiralik_mi = ? WHERE id= ?;", [true, $_POST['stok_id']]);
        if ($val) {
          echo("<script> alert('Güncellendi'); </script>");
        }else{
          echo("<script> alert('Güncellenemedi'); </script>");
        }
      }
      else{
        $val = $object->update("UPDATE stok SET kiralik_mi = ? WHERE id= ?;", [false, $_POST['stok_id']]);
        if ($val) {
          echo("<script> alert('Güncellendi'); </script>");
        }else{
          echo("<script> alert('Güncellenemedi'); </script>");
        }
      }
    }else if(isset($_POST['s_yes'])){
      if (isset($_POST['satilik_mi'])) {
        $val = $object->update("UPDATE stok SET satilik_mi = ? WHERE id= ?;", [true, $_POST['stok_id']]);
        if ($val) {
          echo("<script> alert('Güncellendi'); </script>");
        }else{
          echo("<script> alert('Güncellenemedi'); </script>");
        }
      }
      else{
        $val = $object->update("UPDATE stok SET satilik_mi = ? WHERE id= ?;", [false, $_POST['stok_id']]);
        if ($val) {
          echo("<script> alert('Güncellendi'); </script>");
        }else{
          echo("<script> alert('Güncellenemedi'); </script>");
        }
      }
    }else if(isset($_POST['gunlukFiyati'])){
        $val = $object->update("UPDATE stok SET gunluk_fiyati = ? WHERE id= ?;", [$_POST['gunlukFiyati'], $_POST['stok_id']]);
        if ($val) {
          echo("<script> alert('Güncellendi'); </script>");
        }else{
          echo("<script> alert('Güncellenemedi'); </script>");
        }
    }else if(isset($_POST['satisFiyati'])){
        $val = $object->update("UPDATE stok SET satis_fiyati = ? WHERE id= ?;", [$_POST['satisFiyati'], $_POST['stok_id']]);
        if ($val) {
          echo("<script> alert('Güncellendi'); </script>");
        }else{
          echo("<script> alert('Güncellenemedi'); </script>");
        }
    }
    else if(isset($_POST['myCheck'])){
      if (isset($_POST['durum'])) {
        $val = $object->update("UPDATE stok SET durum = ? WHERE id= ?;", [true, $_POST['stok_id']]);
        if ($val) {
          echo("<script> alert('Güncellendi'); </script>");
        }else{
          echo("<script> alert('Güncellenemedi'); </script>");
        }
      }else{
        $val = $object->update("UPDATE stok SET durum = ? WHERE id= ?;", [false, $_POST['stok_id']]);
        if ($val) {
          echo("<script> alert('Güncellendi'); </script>");
        }else{
          echo("<script> alert('Güncellenemedi'); </script>");
        }
      }
    }
  }

  $row = null;
  if(isset($_POST['stok_id']) AND $object){
    $row = $object->getRowById("stok", $_POST['stok_id']);
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
    <title>Stok Düzenle</title>
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
  function validateRenk(){
    let selectRenkElement = document.getElementsByName("selectRenk")[0];
    let renk_id = selectRenkElement.options[selectRenkElement.selectedIndex].value;

    if(renk_id == -1){
      alert("Renk seçin");
      return;
    }else if(renk_id == -2){
      alert("Renk bulunamadı");
      return;
    }else{
      let form = document.getElementById("renkForm");
      form.submit();
    }
  }

  function submitKiralikMi(){
    let element = document.getElementsByName("stok_id")[0];
    if(element.value == ""){
      return;
    }

    let form = document.getElementById("kiralikMiForm");
    form.submit();
  }

  function submitSatilikMi(){
    let element = document.getElementsByName("stok_id")[0];
    if(element.value == ""){
      return;
    }

    let form = document.getElementById("satilikMiForm");
    form.submit();
  }

  function submitDurum(){
    let element = document.getElementsByName("stok_id")[0];
    if(element.value == ""){
      return;
    }

    let form = document.getElementById("durumForm");
    form.submit();
  }

  function validateGunlukFiyatForm(){
    let element = document.getElementsByName("stok_id")[0];
    if(element.value == ""){
      return;
    }

    let form = document.getElementById("gunlukFiyatForm");
    form.submit();
  }

  function validateSatisFiyatForm(){
    let element = document.getElementsByName("stok_id")[0];
    if(element.value == ""){
      return;
    }

    let form = document.getElementById("satisFiyatForm");
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
                        <h4 class="page-title">Stok Düzenle</h4>
                    </div>
                    <div class="col-7 align-self-center">
                        <div class="d-flex align-items-center justify-content-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="index.php">Ana Sayfa</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="stok.php">Stok</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Stok Düzenle</li>
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

                            <div class="form-group">
                              <label>Marka</label></br>
                              <label>
                                <?php
                                  if(isset($_POST['stok_id']) AND $object){
                                    $a = $object->getMarkaIdByModelId($row['model_id']);
                                    $marka_id = $a['marka_id'];
                                    echo(escape(ucfirst($object->getMarkaAd($marka_id))));
                                  }
                                ?>
                              </label>
                            </div>

                            <div class="form-group">
                              <label>Model</label></br>
                              <label>
                                <?php
                                  if(isset($_POST['stok_id']) AND $object){
                                    $row2 = $object->getRowById("modeller", $row['model_id']);
                                    if($row2){
                                      echo(escape(ucfirst($row2['model_adi'])));
                                    }
                                  }
                                ?>
                              </label>
                            </div>

                            <div class="form-group">
                              <label>Yılı</label></br>
                              <label>
                                <?php
                                  if(isset($_POST['stok_id']) AND $object){
                                    echo(escape($row['yil']));
                                  }
                                ?>
                              </label>
                            </div>

                            <form id="renkForm" method="POST" onsubmit="event.preventDefault(); validateRenk();">
                            <div class="form-group">
                            <label>Renk</label></br>
                            <select name="selectRenk" class="custom-select col-6" id="inlineFormCustomSelect" required="required">
                                <?php
                                  // Renkleri getir
                                  if ($object !== false && isset($_POST['stok_id'])){
                                    $stmt = $object->selectTable("renkler");
                                    if (!$stmt) {
                                      echo('<option value="-2">Hiç renk bulunamadı</option>');
                                    }else{
                                      if($stmt->rowCount() == 0){
                                        echo('<option value="-2">Hiç renk yok</option>');
                                      }else{
                                        while($row3 = $stmt->fetch()){
                                          if($row['renk_id'] == $row3['id']){
                                            echo('
                                              <option selected value="'.escape($row3['id']).'">'.escape(ucFirst($row3['renk_adi'])).' </option>
                                            ');
                                          }else{
                                            echo('
                                              <option value="'.escape($row3['id']).'">'.escape(ucFirst($row3['renk_adi'])).' </option>
                                            ');
                                          }
                                        }
                                      }
                                    }
                                  }
                                ?>
                            </select>
                            <button type="submit" name="marka_guncelle" class="btn" style="background-color: #C0C0C0; margin-left: 5px; padding-left: 30px; padding-right: 30px;"/>Güncelle</button>
                            <input type="hidden" name="stok_id" value="<?php if(isset($_POST['stok_id'])){echo(escape($_POST['stok_id']));}?>" >
                          </div>
                          </form>

                          <form id="kiralikMiForm" method="POST">
                            <div class="custom-control custom-checkbox">
                                <?php
                                  $kiralik_mi="";
                                  if($object AND isset($_POST['stok_id']) AND $row){
                                    if ($row['kiralik_mi'] == true) {
                                      $kiralik_mi = "checked";
                                    }
                                  }
                                ?>
                                <input onclick="submitKiralikMi();" <?php echo($kiralik_mi); ?> name="kiralik_mi" type="checkbox" class="custom-control-input" id="customCheck1">
                                <label class="custom-control-label" for="customCheck1">Kiralık mı?</label>
                            </div>
                            <input type="hidden" name="stok_id" value="<?php if(isset($_POST['stok_id'])){echo(escape($_POST['stok_id']));}?>" >
                            <input type="hidden" name="k_yes" value="-1" >
                          </form>

                          <form onsubmit="event.preventDefault(); validateGunlukFiyatForm();" id="gunlukFiyatForm" method="POST">
                          <div class="form-group">
                              <label>Günlük Fiyatı</label>
                              <input name="gunlukFiyati" required="required" placeholder="<?php if(isset($_POST['stok_id']) AND $object){ echo(escape($row['gunluk_fiyati']));} ?>" type="number" min="0" class="form-control col-6" style="border-color: #C0C0C0; max-width: 200px; margin: 0 auto;">
                              <br><button type="submit" name="gunluk_guncelle" class="btn" style="background-color: #C0C0C0; margin-left: 5px; padding-left: 30px; padding-right: 30px;"/>Güncelle</button>
                              <input type="hidden" name="stok_id" value="<?php if(isset($_POST['stok_id'])){echo(escape($_POST['stok_id']));}?>" >
                          </div>
                          </form>

                          <form id="satilikMiForm" method="POST">
                            <div class="custom-control custom-checkbox">
                                <?php
                                  $satilik_mi="";
                                  if($object AND isset($_POST['stok_id']) AND $row){
                                    if ($row['satilik_mi'] == true) {
                                      $satilik_mi = "checked";
                                    }
                                  }
                                ?>
                                <input onclick="submitSatilikMi();" <?php echo($satilik_mi); ?> name="satilik_mi" type="checkbox" class="custom-control-input" id="customCheck2">
                                <label class="custom-control-label" for="customCheck2">Satılık mı?</label>
                            </div>
                            <input type="hidden" name="stok_id" value="<?php if(isset($_POST['stok_id'])){echo(escape($_POST['stok_id']));}?>" >
                            <input type="hidden" name="s_yes" value="-1" >
                          </form>
                          <form onsubmit="event.preventDefault(); validateSatisFiyatForm();" id="satisFiyatForm" method="POST">
                          <div class="form-group">
                              <label>Satış Fiyatı</label>
                              <input name="satisFiyati" required="required" placeholder="<?php if(isset($_POST['stok_id']) AND $object){ echo(escape($row['satis_fiyati']));} ?>" type="number" min="0" class="form-control col-6" style="border-color: #C0C0C0; max-width: 200px; margin: 0 auto;">
                              <br><button type="submit" name="satis_guncelle" class="btn" style="background-color: #C0C0C0; margin-left: 5px; padding-left: 30px; padding-right: 30px;"/>Güncelle</button>
                              <input type="hidden" name="stok_id" value="<?php if(isset($_POST['stok_id'])){echo(escape($_POST['stok_id']));}?>" >
                          </div>
                          </form>

                          <form id="durumForm" method="POST">
                            <label>Durum</label>
                            <div class="custom-control custom-checkbox">
                                <?php
                                  $durum="";
                                  if($object AND isset($_POST['stok_id']) AND $row){
                                    if ($row['durum'] == true) {
                                      $durum = "checked";
                                    }
                                  }
                                ?>
                                <input onclick="submitDurum();" <?php echo($durum); ?> name="durum" type="checkbox" class="custom-control-input" id="customCheck3">
                                <label class="custom-control-label" for="customCheck3">
                                  <?php
                                  if($object AND isset($_POST['stok_id']) AND $row){
                                    if ($row['durum'] == true) {
                                      echo("Aktif");
                                    }else{
                                      echo("Pasif");
                                    }
                                  }
                                  ?>
                                </label>
                            </div>
                            <input type="hidden" name="stok_id" value="<?php if(isset($_POST['stok_id'])){echo(escape($_POST['stok_id']));}?>" >
                            <input type="hidden" name="myCheck" value="-1" >
                          </form>
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
