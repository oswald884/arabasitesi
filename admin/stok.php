<?php
  include_once 'conn.php';
  $object = new Dbh;
  session_start();
  include 'oturum_acik_mi.php';

  if($_SERVER['REQUEST_METHOD'] == "POST"){
    // Stoktaki araç silinmek isteniyorsa
    if(isset($_POST['btnSil']) AND isset($_POST['stok_id'])){
      $stmt = $object->connect()->prepare("DELETE FROM stok WHERE id = ?;");
      $stmt->execute([$_POST['stok_id']]);

      // Araçla ilgili siparişleri de sil
      $stmt = $object->connect()->prepare("DELETE FROM offlinesiparisler WHERE stok_id = ?;");
      $stmt->execute([$_POST['stok_id']]);

      // Araçla ilgili siparişleri de sil
      $stmt = $object->connect()->prepare("DELETE FROM onlinesiparisler WHERE stok_id = ?;");
      $stmt->execute([$_POST['stok_id']]);

      echo("<script> alert('Seçtiğiniz araç silindi.'); </script>");
    }else if(isset($_POST['btnSil'])){
      echo("<script> alert('Bir hata oldu tekrar deneyin.'); </script>");
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
    <title>Stok</title>
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
      .miniLogo{
        width: 40px;
        height: 40px;
        margin-right: 10px;
      }
      tr th, tr td{
        line-height: 40px;
        text-align: center;
      }
    </style>
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
                        <h4 class="page-title">Stoktakiler</h4>
                    </div>
                    <div class="col-7 align-self-center">
                        <div class="d-flex align-items-center justify-content-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="index.php">Ana Sayfa</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Stok</li>
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
                        <div class="card">
                            <div class="card-body">
                              <div class="table-responsive">
                                  <table class="table">
                                      <thead>
                                          <tr>
                                              <th scope="col">#</th>
                                              <th scope="col">ID</th>
                                              <th scope="col">Marka</th>
                                              <th scope="col">Model</th>
                                              <th scope="col">Renk</th>
                                              <th scope="col">Yıl</th>
                                              <th scope="col">Satılık mı?</th>
                                              <th scope="col">Kiralık mı?</th>
                                              <th scope="col">Durum</th>
                                              <th scope="col">İşlemler</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                        <?php
                                          if ($object) {
                                            $stmt = $object->selectTable("stok");
                                            if(!$stmt || !$stmt->rowCount()){
                                              // Bulunamadı veya birşey yok
                                              echo('
                                                <tr>
                                                    <td colspan="10" style="text-align: center; font-size: 25px;">Hiçbir şey bulunamadı.</td>
                                                </tr>
                                              ');
                                            }else{
                                              $i = 1;
                                              while ($row = $stmt->fetch()) {
                                                // Satırı ekrana yaz
                                                $temp = $object->getMarkaIdByModelId($row['model_id']);
                                                $marka_id = $temp['marka_id'];

                                                $modelRow = $object->getModelRow($row['model_id']);

                                                $renkRow = $object->getRenkRow($row['renk_id']);

                                                echo('
                                                  <tr>
                                                      <th scope="row">'.$i.'</th>
                                                      <td>'.escape($row['id']).'</td>
                                                      <td>'.escape($object->getMarkaAd($marka_id)).'</td>
                                                      <td>'.escape(ucfirst($modelRow['model_adi'])).'</td>
                                                      <td style="line-height: 40px;"><img style="width: 30px; height: 30px; background-color: '.escape($renkRow['rgb']).'"> '.escape(ucfirst($renkRow['renk_adi'])).'</td>
                                                      <td>'.escape($row['yil']).'</td>
                                                      <td>'.getEvetHayır($row['satilik_mi']).'</td>
                                                      <td>'.getEvetHayır($row['kiralik_mi']).'</td>
                                                      <td>'.getDurum($row['durum']).'</td>
                                                      <td>
                                                          <ul style="list-style: none; max-width: 200px; min-width: 175px;">
                                                            <li style="float: left;">
                                                              <form action="stokDuzenle.php" method="POST">
                                                                  <input type="hidden" name="stok_id" value = "'.$row['id'].'" />
                                                                  <button type="submit" class="btn btn-primary" style="height: 40px;">Düzenle</button>
                                                              </form>
                                                            </li>
                                                            <li style="float: right;">
                                                              <form method="POST">
                                                              <input type="hidden" name="stok_id" value="'.$row['id'].'">
                                                              <button type="submit" name="btnSil" class="btn btn-warning" style="height: 40px;">Sil</button>
                                                              </form>
                                                            </li>
                                                          </ul>
                                                      </td>
                                                  </tr>
                                                ');
                                                $i++;
                                              }
                                            }
                                          }
                                          // Stoktakileri getir

                                        ?>
                                      </tbody>
                                  </table>
                              </div>
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

  function getEvetHayır($boolean){
    if($boolean == true){
      return "Evet";
    }else{
      return "Hayır";
    }
  }

  function getDurum($boolean){
    if($boolean == true){
      return "Aktif";
    }else{
      return "Pasif";
    }
  }
?>
