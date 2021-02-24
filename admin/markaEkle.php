<?php
  include_once 'conn.php';
  session_start();
  include 'oturum_acik_mi.php';

  if($_SERVER['REQUEST_METHOD'] == "POST"){
    // Marka adı yada Marka resmi set edilmemişse hata ver

    if( !isset($_POST['markaAd']) || !isset($_FILES['markaResim']) || $_FILES['markaResim']['name'] == NULL){
      echo ("<script> alert('Hata, tekrar deneyin'); </script>");
    }else{
      $markaAd = $_POST['markaAd'];

      $file = $_FILES['markaResim'];
      $fileName = $file['name'];
      $fileTmpName = $file['tmp_name'];
      $fileSize = $file['size'];
      $fileError = $file['error'];
      $fileType = $file['type'];

      // Bu isimde bir marka var mı?
      $object = new Dbh;
      $stmt = $object->connect()->query("SELECT * FROM markalar");
      $varMi = false;
      while($row = $stmt->fetch()){
        if(strtolower($markaAd) == strtolower($row['ad'])){
          $varMi = true;
          break;
        }
      }
      if($varMi){
        echo("<script> alert('Bu isimde bir marka zaten var!'); </script>");
      }else{
        // Resmi al ve Veri tabanına kaydet
        $parcala = explode('.', $fileName);
        $fileExt = strtolower(end($parcala));

        $fileNameNew = uniqid('', true).".".$fileExt;
        $fileDestination = 'images/'.$fileNameNew;
        move_uploaded_file($fileTmpName, $fileDestination);

        $stmt = $object->connect()->prepare("INSERT INTO markalar (ad, logo_resmi) VALUES ( ?, ?);");
        $stmt->execute([$markaAd, $fileDestination]);

        echo("<script> alert('Yeni marka eklendi.'); </script>");
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
    <title>Marka Ekle</title>
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
        let markaAd = document.getElementsByName('markaAd')[0];
        if(markaAd.value == ""){
          return;
        }
        let markaResim = document.getElementsByName('markaResim')[0];
        if (!markaResim.value) {
          return;
        }

        let file = markaResim.files[0];
        let fileName = file.name;
        let parcala = fileName.split('.');
        let extension = parcala[parcala.length -1];
        extension = extension.toLowerCase();

        if(extension != "png" && extension != "jpg" && extension != "jpeg"){
          // Uygun olmayan dosya formatı
          markaResim.style.borderColor = "#FF0000";
          alert('Uygun olmayan dosya formatı');
        }else if(markaAd.length > 100){
          alert("Marka adı 100 karakterden uzun olamaz");
        }else if(file.size > 5000000){
          // Dosya 5mbdan büyük
          alert("Dosya boyutu 5mb'dan büyük olamaz");
        }else{
          let form = document.getElementsByName('markaEkleForm')[0];
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
                        <h4 class="page-title">Marka Ekle</h4>
                    </div>
                    <div class="col-7 align-self-center">
                        <div class="d-flex align-items-center justify-content-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="index.php">Ana Sayfa</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Marka Ekle</li>
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
                              <form name="markaEkleForm" onsubmit="event.preventDefault(); validateForm();" enctype="multipart/form-data" method="POST">
                              <div class="form-group">
                                  <label>Marka Adı</label>
                                  <input name="markaAd" type="text" class="form-control" placeholder="Marka Adı" required="required" style="border-color: #C0C0C0; max-width: 200px; margin: 0 auto; min-width: 150px;">
                              </div>
                              <div class="form-group" style="width: 50%; max-width: 250px; margin: 0 auto;">
                                  <label>Marka Resmi ( jpg / jpeg / png)</label>
                                  <input name="markaResim" type="file" required="required" class="form-control" style="border-color: #C0C0C0; cursor: pointer;">
                              </div>
                              <button type="submit" class="btn" style="background-color: #C0C0C0; margin-top: 30px; padding-left: 30px; padding-right: 30px;"> Ekle </button>
                            </div>
                            </form>
                            <!-- ======== -->
                        </div>
                    </div>
                </div>
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
