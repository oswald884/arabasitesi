<?php
  include_once 'conn.php';
  $object = new Dbh;
  session_start();
  include 'oturum_acik_mi.php';

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    if( !isset($_POST['selectMarka']) || !isset($_POST['modelAd']) || !isset($_POST['selectAracTipi']) || !isset($_POST['selectYakitTipi']) || !isset($_POST['selectVitesTipi']) || !isset($_POST['koltukSayisi']) || !isset($_FILES['modelResim'])){
      echo("<script> alert('Bilgiler eksik lütfen tekrar deneyin'); </script>");
    }else{
      // Resim ile ilgilen
      if ($_FILES['modelResim']['name'] == "") {
        // Resimde sıkıntı var birşey yapma
        echo("<script> alert('Resimde sorun çıktı tekrar deneyin.'); </script>");
      }
      else{
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

        // Veri tabanına modeli kaydet
        $stmt = $object->connect()->prepare("INSERT INTO modeller (marka_id, model_adi, tip_id, yakit_tip_id, vites_tip_id, koltuk_sayisi, model_resim) VALUES (?, ?, ?, ?, ?, ?, ?);");
        $stmt->execute( [$_POST['selectMarka'], $_POST['modelAd'], $_POST['selectAracTipi'], $_POST['selectYakitTipi'], $_POST['selectVitesTipi'], $_POST['koltukSayisi'], $fileDestination] );
        echo("<script> alert('Model Eklendi.'); </script>");
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
    <title>Model Ekle</title>
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
  function validateForm(){
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
    }

    // Model adı kontrol
    let modelAdElement = document.getElementsByName("modelAd")[0];
    let modelAd = modelAdElement.value;
    if(modelAd == ""){
      alert("Model adı boş bırakılamaz!");
      return;
    }else if(modelAd.length > 100){
      alert("Model adı 100 karakterden uzun olamaz!");
      return;
    }

    // Araç tipi kontrol
    let selectAracTipi = document.getElementsByName("selectAracTipi")[0];
    let aracTipi = selectAracTipi.options[selectAracTipi.selectedIndex].value;

    if(aracTipi == -1){
      alert("Lütfen araç tipi seçin.");
      return;
    }else if(aracTipi == -2){
      alert("Hiç araç tipi yok!");
      return;
    }

    // Yakıt tipi kontrol
    let selectYakitTipi = document.getElementsByName("selectYakitTipi")[0];
    let yakitTipi = selectYakitTipi.options[selectYakitTipi.selectedIndex].value;

    if(yakitTipi == -1){
      alert("Lütfen yakıt tipi seçin.");
      return;
    }else if(yakitTipi == -2){
      alert("Hiç yakıt tipi yok!");
      return;
    }

    // Vites tipi kontrol
    let selectVitesTipi = document.getElementsByName("selectVitesTipi")[0];
    let vitesTipi = selectVitesTipi.options[selectVitesTipi.selectedIndex].value;

    if(vitesTipi == -1){
      alert("Lütfen vites tipi seçin.");
      return;
    }else if(vitesTipi == -2){
      alert("Hiç vites tipi yok!");
      return;
    }

    /*  Gerek yok
    // Koltuk sayısı kontrol
    let koltukSayisiElement = document.getElementsByName("koltukSayisi")[0];
    let koltukSayisi = koltukSayisiElement.value;

    if(koltukSayisi < 1){
      alert("Koltuk sayısı 1'den küçük olamaz.");
      return;
    }else if(koltukSayisi > 100){
      alert("Koltuk sayısı 100'den büyük olamaz.");
      return;
    }*/

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

    // Uygun veriler formu gönder
    let form = document.getElementById("modelEkleForm");
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
                        <h4 class="page-title">Model Ekle</h4>
                    </div>
                    <div class="col-7 align-self-center">
                        <div class="d-flex align-items-center justify-content-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="index.php">Ana Sayfa</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Model Ekle</li>
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
                            <!-- ======== -->
                            <form id="modelEkleForm" onsubmit="event.preventDefault(); validateForm();" enctype="multipart/form-data" method="POST">
                              <div class="form-group">
                              <label>Marka</label></br>
                              <select name="selectMarka" class="custom-select col-6" id="inlineFormCustomSelect" required="required">
                                  <option selected value="-1">Marka Seçin</option>
                                  <?php
                                    // Markaları getir
                                    $stmt = $object->connect()->query("SELECT * FROM markalar;");
                                    if(!$stmt->rowCount()){
                                      echo('<option value="-2">Hiç marka yok</option>');
                                    }
                                    else{
                                      while($row = $stmt->fetch()){
                                        echo('
                                          <option value="'.escape($row['id']).'"> '.escape($row['ad']).' </option>
                                        ');
                                      }
                                    }
                                  ?>
                              </select>
                            </div>
                            <div class="form-group">
                                <label>Model Adı</label>
                                <input name="modelAd" type="text" class="form-control col-6" placeholder="Model Adı" required="required" style="border-color: #C0C0C0; max-width: 200px; margin: 0 auto;">
                            </div>
                            <div class="form-group">
                            <label>Araç tipi</label></br>
                            <select name="selectAracTipi" class="custom-select col-6" id="inlineFormCustomSelect" required="required">
                                <option selected value="-1">Araç Tipi Seçin</option>
                                <?php
                                  // Araç tiplerini getir
                                  $stmt = $object->connect()->query("SELECT * FROM tipler;");
                                  if(!$stmt->rowCount()){
                                    echo('<option value="-2">Hiç araç tipi yok</option>');
                                  }
                                  else{
                                    while($row = $stmt->fetch()){
                                      echo('
                                        <option value="'.escape($row['id']).'"> '.escape(ucfirst($row['tip_adi'])).' </option>
                                      ');
                                    }
                                  }
                                ?>
                            </select>
                            </div>
                            <div class="form-group">
                            <label>Yakıt tipi</label></br>
                            <select name="selectYakitTipi" class="custom-select col-6" id="inlineFormCustomSelect" required="required">
                                <option selected value="-1">Yakıt Tipi Seçin</option>
                                <?php
                                  // Yakıt tiplerini getir
                                  $stmt = $object->connect()->query("SELECT * FROM yakit_tipleri;");
                                  if(!$stmt->rowCount()){
                                    echo('<option value="-2">Hiç yakıt tipi yok</option>');
                                  }
                                  else{
                                    while($row = $stmt->fetch()){
                                      echo('
                                        <option value="'.escape($row['id']).'"> '.escape(ucfirst($row['yakit_tip_adi'])).' </option>
                                      ');
                                    }
                                  }
                                ?>
                            </select>
                            </div>
                            <div class="form-group">
                            <label>Vites tipi</label></br>
                            <select name="selectVitesTipi" class="custom-select col-6" id="inlineFormCustomSelect" required="required">
                                <option selected value="-1">Vites Tipi Seçin</option>
                                <?php
                                  // Vites tiplerini getir
                                  $stmt = $object->connect()->query("SELECT * FROM vites_tipleri;");
                                  if(!$stmt->rowCount()){
                                    echo('<option value="-2">Hiç vites tipi yok</option>');
                                  }
                                  else{
                                    while($row = $stmt->fetch()){
                                      echo('
                                        <option value="'.escape($row['id']).'"> '.escape(ucfirst($row['vites_tip_adi'])).' </option>
                                      ');
                                    }
                                  }
                                ?>
                            </select>
                            </div>
                            <div class="form-group">
                                <label>Koltuk Sayısı</label>
                                <input name="koltukSayisi" type="number" min="1" max="70" class="form-control col-6" placeholder="Koltuk Sayısı" required="required" style="border-color: #C0C0C0; max-width: 200px; margin: 0 auto;">
                            </div>
                            <div class="form-group" style="width: 50%; max-width: 250px; margin: 0 auto;">
                                <label>Model Resmi ( jpg / jpeg / png)</label>
                                <input name="modelResim" type="file" required="required" class="form-control" style="border-color: #C0C0C0; cursor: pointer;">
                            </div>
                            <div class="form-group" style="width: 50%; max-width: 250px; margin: 0 auto;">
                                <button type="submit" class="btn" style="background-color: #C0C0C0; margin-top: 30px; padding-left: 30px; padding-right: 30px;"> Ekle </button>
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
