<?php
  include_once 'conn.php';
  $object = new Dbh;
  session_start();
  include 'oturum_acik_mi.php';

  if ($_SERVER['REQUEST_METHOD'] == "POST") {
    if(!isset($_POST['selectModel']) || !isset($_POST['selectRenk']) || !isset($_POST['yapimYili']) || !isset($_FILES['aracResim']) ){
      echo("<script> alert('Eksik bilgiler var tekrar deneyin.'); </script>");
    }else{
      $file = $_FILES['aracResim'];
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

      $model_id = $_POST['selectModel'];
      $renk_id = $_POST['selectRenk'];
      $yapim_yili = $_POST['yapimYili'];
      $satilik_mi = false;
      $kiralik_mi = false;
      $satis_fiyati = 0;
      $gunluk_fiyati = 0;
      if (isset($_POST['satilik_mi'])) {
        $satilik_mi = true;
        $satis_fiyati = $_POST['satisFiyati'];
      }
      if (isset($_POST['kiralik_mi'])) {
        $kiralik_mi = true;
        $gunluk_fiyati = $_POST['gunlukFiyati'];
      }

      $sql = "INSERT INTO stok (model_id, renk_id, yil, satilik_mi, kiralik_mi, satis_fiyati, gunluk_fiyati, resim) VALUES (?, ?, ?, ?, ?, ?, ?, ?);";
      $temp = $object->insert($sql, [$model_id, $renk_id, $yapim_yili, $satilik_mi, $kiralik_mi, $satis_fiyati, $gunluk_fiyati, $fileDestination]);
      if(!$temp){
        echo("<script> alert('Bir hata oldu tekrar deneyin.'); </script>");
      }else{
        echo("<script> alert('Araç stoğa eklendi.'); </script>");
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
    <title>Stoğa Araç Ekle</title>
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
  function modelYaz(){

    let hataElement = document.getElementById("hata");
    let hata = hataElement.value;

    let selectMarkaElement = document.getElementsByName("selectMarka")[0];

    let selectModelElement = document.getElementsByName("selectModel")[0];
    if (hata == 1) {
      return;
    }else if (selectMarkaElement.options[selectMarkaElement.selectedIndex].value == -1) {
      selectModelElement.innerHTML = '<option selected value="-3">Önce Marka seçin</option>';
      return;
    }else if(selectMarkaElement.options[selectMarkaElement.selectedIndex].value == -2){
      return;
    }
    else if (hata == 2) {
      selectModelElement.innerHTML = '<option selected value="-4">Model Bulunamadı</option>';
      return;
    }else{
      let secilen_marka_id = selectMarkaElement.options[selectMarkaElement.selectedIndex].value;
      let markaId = document.getElementsByName("marka_id");
      let modelId = document.getElementsByName("model_id");
      let modelAdi = document.getElementsByName("model_adi");

      selectModelElement.innerHTML = '';
      let string = "";
      for (var i = 0; i < markaId.length; i++) {
        if (secilen_marka_id == markaId[i].value) {

          string = string + '<option value="'+ escapeHtml(modelId[i].value) +'" >'+ escapeHtml(modelAdi[i].value) +'</option>';
        }
      }

      if (string == "") {
        selectModelElement.innerHTML = '<option selected value="-4">Model Bulunamadı</option>';
      }else{
        selectModelElement.innerHTML = string;
      }
    }
  }

  function validateForm(){
    let selectModelElement = document.getElementsByName("selectModel")[0];
    let model_id = selectModelElement.options[selectModelElement.selectedIndex].value;

    // Model kontrol
    if(model_id == -3){
      alert("Önce marka seçin");
      return;
    }else if(model_id == -4){
      alert("Model bulunamadı");
      return;
    }

    // Renk Kontrol
    let selectRenkElement = document.getElementsByName("selectRenk")[0];
    let renk_id = selectRenkElement.options[selectRenkElement.selectedIndex].value;

    if(renk_id == -1){
      alert("Renk seçin");
      return;
    }else if(renk_id == -2){
      alert("Renk bulunamadı");
      return;
    }

    let yapimYiliElement = document.getElementsByName("yapimYili")[0];
    let yapimYili = yapimYiliElement.value;

    if(yapimYili < 1900 || yapimYili > 2099){
      return;
    }

    // Araç Resmi kontrol
    let aracResimElement = document.getElementsByName("aracResim")[0];
    if(aracResimElement.value == ""){
      return;
    }

    let file = aracResimElement.files[0];
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

    // CheckBox lar kontrol
    let check1 = document.getElementsByName("satilik_mi")[0];
    if (check1.checked) {
      let satisFiyatiElement = document.getElementsByName("satisFiyati")[0];
      if (satisFiyatiElement.value == "") {
        alert("Satış fiyatını giriniz. ");
        return;
      }
    }

    // Veriler uygun submit
    let form = document.getElementById("stokEkleForm");
    form.submit();
  }

  function escapeHtml(str) {
    return str.replace(/&/g, "&").replace(/</g, "<").replace(/>/g, ">").replace(/"/g, '"').replace(/'/g, "'");
  }

  function show(sayi){
    if(sayi == 1) {
      let element = document.getElementsByName("satisFiyati")[0];
      if (element.style.visibility == "hidden") {
        element.style.visibility = "visible";
        element.required = true;
      }else{
        element.style.visibility = "hidden";
        element.required = false;
      }
    }else if(sayi == 2){
      let element = document.getElementsByName("gunlukFiyati")[0];
      if (element.style.visibility == "hidden") {
        element.style.visibility = "visible";
        element.required = true;
      }else{
        element.style.visibility = "hidden";
        element.required = false;
      }
    }
  }
</script>
</head>

<body>
    <?php

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
                        <h4 class="page-title">Araç Ekle</h4>
                    </div>
                    <div class="col-7 align-self-center">
                        <div class="d-flex align-items-center justify-content-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="index.php">Ana Sayfa</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Stoğa Araç Ekle</li>
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
                            <form id="stokEkleForm" onsubmit="event.preventDefault(); validateForm();" enctype="multipart/form-data" method="POST">
                            <!-- ======== -->
                              <div class="form-group">
                              <label>Marka</label></br>
                              <select onchange="modelYaz();" name="selectMarka" class="custom-select col-6" id="inlineFormCustomSelect" required="required">
                                  <option selected value="-1">Marka Seçin</option>
                                  <?php
                                    // Markaları getir
                                    if ($object !== false){
                                      $stmt = $object->selectTable("markalar");
                                      if (!$stmt) {
                                        echo('<option value="-2">Hiç marka bulunamadı</option>');
                                      }else{
                                        if($stmt->rowCount() == 0){
                                          echo('<option value="-2">Hiç marka yok</option>');
                                        }else{
                                          while($row = $stmt->fetch()){
                                            echo('
                                              <option value="'.escape($row['id']).'"> '.escape($row['ad']).' </option>
                                            ');
                                          }
                                        }
                                      }
                                    }

                                  ?>
                              </select>
                            </div>

                            <!-- ======== -->
                            <div class="form-group">
                              <label>Model</label></br>
                              <select name="selectModel" class="custom-select col-6" id="inlineFormCustomSelect" required="required">
                                  <option value="-3">Önce Marka seçin</option>
                              </select>
                            </div>

                            <div class="form-group">
                            <label>Renk</label></br>
                            <select name="selectRenk" class="custom-select col-6" id="inlineFormCustomSelect" required="required">
                                <option selected value="-1">Renk Seçin</option>
                                <?php
                                  // Renkleri getir
                                  if ($object !== false){
                                    $stmt = $object->selectTable("renkler");
                                    if (!$stmt) {
                                      echo('<option value="-2">Hiç renk bulunamadı</option>');
                                    }else{
                                      if($stmt->rowCount() == 0){
                                        echo('<option value="-2">Hiç renk yok</option>');
                                      }else{
                                        while($row = $stmt->fetch()){
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
                                <label>Yapım Yılı</label>
                                <input name="yapimYili" placeholder="Yapım Yılı" required="required" type="number" min="1900" max="2099" step="1" value="2016" class="form-control col-6" style="border-color: #C0C0C0; max-width: 200px; margin: 0 auto;">
                            </div>

                            <div class="form-group" style="width: 50%; max-width: 250px; margin: 0 auto;">
                                <label>Aracın Resmi ( jpg / jpeg / png)</label>
                                <input name="aracResim" type="file" required="required" class="form-control" style="border-color: #C0C0C0; cursor: pointer;">
                            </div><br>

                            <div class="custom-control custom-checkbox">
                                <input onclick="show(1);" name="satilik_mi" type="checkbox" class="custom-control-input" id="customCheck1">
                                <label class="custom-control-label" for="customCheck1">Satılık mı?</label>
                            </div>
                            <div class="form-group">
                                <input name="satisFiyati" placeholder="Satış Fiyatı" type="number" min="0" class="form-control col-6" style="border-color: #C0C0C0; max-width: 200px; margin: 0 auto; visibility: hidden;">
                            </div>

                            <div class="custom-control custom-checkbox">
                                <input onclick="show(2);" name="kiralik_mi" type="checkbox" class="custom-control-input" id="customCheck2">
                                <label class="custom-control-label" for="customCheck2">Kiralık mı?</label>
                            </div>
                            <div class="form-group">
                                <input name="gunlukFiyati" placeholder="Günlük Fiyatı" type="number" min="0" class="form-control col-6" style="border-color: #C0C0C0; max-width: 200px; margin: 0 auto; visibility: hidden;">
                            </div>



                            <div class="form-group" style="width: 50%; max-width: 250px; margin: 0 auto;">
                                <button type="submit" class="btn" style="background-color: #C0C0C0; margin-top: 30px; padding-left: 30px; padding-right: 30px;"> Ekle </button>
                            </div>
                            </form>
                            <!-- ======== -->
                            <section id="modeller">
                              <?php
                                // Modelleri getir

                                if ($object !== false){
                                  $stmt = $object->selectTable("modeller");
                                  if (!$stmt) {
                                    echo('<input type="hidden" id="hata" value="1">');
                                  }else{
                                    if(!$stmt->rowCount()){
                                      echo('<input type="hidden" id="hata" value="2">');
                                    }else{
                                      echo('<input type="hidden" id="hata" value="0">');
                                      while($row = $stmt->fetch()){
                                        echo('
                                          <section name="modelRow">
                                            <input type="hidden" name="marka_id" value="'.escape($row['marka_id']).'">
                                            <input type="hidden" name="model_id" value="'.escape($row['id']).'">
                                            <input type="hidden" name="model_adi" value="'.escape($row['model_adi']).'">
                                          </section>
                                        ');
                                      }
                                    }
                                  }
                                }else{
                                  echo('<input type="hidden" id="hata" value="1">');
                                }
                              ?>
                            </section>
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
