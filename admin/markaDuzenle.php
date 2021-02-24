<?php

  include_once 'conn.php';
  $object = new Dbh;
  session_start();
  include 'oturum_acik_mi.php';

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['marka_id'])){
      if (isset($_POST['markaAd'])){
        // Resmi güncellemek istiyor mu?
        if(isset($_FILES['markaResim']) AND $_FILES['markaResim']['name'] != ""){
          // Resmi ve ismi güncelle ******* İstersen eski resmi burada sil
          $file = $_FILES['markaResim'];
          $fileName = $file['name'];
          $fileTmpName = $file['tmp_name'];
          $fileSize = $file['size'];
          $fileError = $file['error'];
          $fileType = $file['type'];

          $parcala = explode('.', $fileName);
          $fileExt = strtolower(end($parcala));

          $fileNameNew = uniqid('', true).".".$fileExt;
          $fileDestination = 'images/'.$fileNameNew;
          move_uploaded_file($fileTmpName, $fileDestination);

          $stmt = $object->connect()->prepare("UPDATE markalar SET ad = ?, logo_resmi = ? WHERE id = ?;");
          $stmt->execute([$_POST['markaAd'], $fileDestination, $_POST['marka_id']]);
        }else{
          // Sadece ismi güncelle
          $stmt = $object->connect()->prepare("UPDATE markalar SET ad = ? WHERE id = ?;");
          $stmt->execute([$_POST['markaAd'], $_POST['marka_id']]);
        }
        echo("<script> alert('Güncellendi'); </script>");
      }
    }
  }

  $row = null;
  if (isset($_POST['marka_id'])) {
    // Marka bilgilerini getir
    $stmt = $object->connect()->prepare("SELECT * FROM markalar WHERE id = ?;");
    $stmt->execute([ $_POST['marka_id'] ]);
    if($stmt->rowCount()){
      $row = $stmt->fetch();
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
    <title>Marka Düzenle</title>
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
  let marka_id = document.getElementsByName("marka_id")[0];
  if(marka_id.value == ""){
    alert('Seçili marka yok!');
    return;
  }

  let markaAd = document.getElementsByName('markaAd')[0];
  if(markaAd.value == ""){
    return;
  }

  if(markaAd.length > 100){
    alert("Marka adı 100 karakterden uzun olamaz");
    return;
  }

  // Eğer resimde seçilmişse
  let dizi = document.getElementsByName('markaResim');
  if(dizi.length > 0){
    let markaResim = dizi[0];
    if(markaResim.value != ""){
      let file = markaResim.files[0];
      let fileName = file.name;
      let parcala = fileName.split('.');
      let extension = parcala[parcala.length -1];
      extension = extension.toLowerCase();

      if(extension != "png" && extension != "jpg" && extension != "jpeg"){
        // Uygun olmayan dosya formatı
        markaResim.style.borderColor = "#FF0000";
        alert('Uygun olmayan dosya formatı');
        return;
      }else if(markaAd.length > 100){
        alert("Marka adı 100 karakterden uzun olamaz");
        return;
      }else if(markaResim.value && file.size > 5000000){
        // Dosya çok 10mbdan büyük
        alert("Dosya boyutu 5mb'dan büyük olamaz");
        return;
      }
    }
  }

  // Formu submitle
  let form = document.getElementsByName("markaGuncelleForm")[0];
  form.submit();
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
                        <h4 class="page-title">Marka Düzenle</h4>
                    </div>
                    <div class="col-7 align-self-center">
                        <div class="d-flex align-items-center justify-content-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="index.php">Ana Sayfa</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="markalar.php">Markalar</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Marka Düzenle</li>
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
                            <form name="markaGuncelleForm" onsubmit="event.preventDefault(); validateForm();" enctype="multipart/form-data" method="POST">
                            <input type="hidden" name="marka_id" value="<?php if(isset($_POST['marka_id'])){echo($_POST['marka_id']);} ?>"
                            <div class="form-group">
                                <label>Marka Adı</label>
                                <input name="markaAd" value="<?php if($row != null){echo($row['ad']);} ?>" type="text" class="form-control" placeholder="Marka Adı" required="required" style="border-color: #C0C0C0; max-width: 200px; margin: 0 auto; min-width: 150px;">
                            </div>
                            <div class="form-group" style="width: 50%; max-width: 250px; margin: 0 auto; text-align: center;">
                                <label>Marka Resmi ( jpg / jpeg / png)</label>
                                <img src="<?php if($row != null){echo($row['logo_resmi']);} ?>" style="width: 100%; max-height: 200px; max-width: 350px; margin: 0 auto; margin-top: 10px; margin-bottom: 20px;"/>
                                <input name="markaResim" type="file" class="form-control" style="border-color: #C0C0C0; cursor: pointer;">
                            </div>
                            <div style="width: 100%; text-align: center;">
                              <button type="submit" class="btn" style="background-color: #C0C0C0; margin-top: 30px; margin-bottom: 20px; padding-left: 30px; padding-right: 30px;"> Güncelle </button>
                            </div>

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
