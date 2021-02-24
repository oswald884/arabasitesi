<?php
  include_once 'conn.php';
  $object = new Dbh;
  session_start();
  include 'oturum_acik_mi.php';

  if($_SERVER['REQUEST_METHOD'] == "POST"){
    // Model Silinmek isteniyorsa
    if(isset($_POST['btnSil']) AND isset($_POST['model_id'])){
      $stmt = $object->connect()->prepare("DELETE FROM modeller WHERE id = ?;");
      $stmt->execute([$_POST['model_id']]);

      // Modelle ilgili siparişleri de sil
      $stmt = $object->connect()->prepare("DELETE FROM offlinesiparisler WHERE stok_id IN (SELECT stok_id FROM modeller WHERE id = ?);");
      $stmt->execute([$_POST['stok_id']]);

      // Modelle ilgili siparişleri de sil
      $stmt = $object->connect()->prepare("DELETE FROM onlinesiparisler WHERE stok_id IN (SELECT stok_id FROM modeller WHERE id = ?);");
      $stmt->execute([$_POST['stok_id']]);

      // Bu modeldeki araçları da stoktan sil
      $stmt = $object->connect()->prepare("DELETE FROM stok WHERE model_id =?;");
      $stmt->execute([$_POST['model_id']]);

      echo("<script> alert('Seçtiğiniz model silindi.'); </script>");
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
    <title>Modeller</title>
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
                        <h4 class="page-title">Mevcut Modeller</h4>
                    </div>
                    <div class="col-7 align-self-center">
                        <div class="d-flex align-items-center justify-content-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="index.php">Ana Sayfa</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Modeller</li>
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
                                              <th scope="col">Araç Tipi</th>
                                              <th scope="col">Yakıt Tipi</th>
                                              <th scope="col">Vites Tipi</th>
                                              <th scope="col">Koltuk Sayısı</th>
                                              <th scope="col">İşlemler</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                        <?php
                                          // Modelleri getir
                                          $stmt = $object->connect()->query("SELECT * FROM modeller;");
                                          if($stmt->rowCount()){
                                            $i = 1;
                                            while ($row = $stmt->fetch()) {
                                              // Satırı ekrana yaz
                                              echo('
                                                <tr>
                                                    <th scope="row">'.$i.'</th>
                                                    <td>'.escape($row['id']).'</td>
                                                    <td>'.escape($object->getMarkaAd($row['marka_id'])).'</td>
                                                    <td> <img class="miniLogo" src="'.escape($row['model_resim']).'">'.escape(ucfirst($row['model_adi'])).'</td>
                                                    <td>'.escape(ucfirst($object->getTipAd($row['tip_id']))).'</td>
                                                    <td>'.escape(ucfirst($object->getYakitTipAd($row['yakit_tip_id']))).'</td>
                                                    <td>'.escape(ucfirst($object->getVitesTipAd($row['vites_tip_id']))).'</td>
                                                    <td>'.escape($row['koltuk_sayisi']).'</td>
                                                    <td>
                                                        <ul style="list-style: none; max-width: 200px; min-width: 175px;">
                                                          <li style="float: left;">
                                                            <form action="modelDuzenle.php" method="POST">
                                                                <input type="hidden" name="model_id" value = "'.$row['id'].'" />
                                                                <button type="submit" class="btn btn-primary" style="height: 40px;">Düzenle</button>
                                                            </form>
                                                          </li>
                                                          <li style="float: right;">
                                                            <form method="POST">
                                                            <input type="hidden" name="model_id" value="'.$row['id'].'">
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
                                          else{
                                            echo('
                                              <tr>
                                                  <td colspan="9" style="text-align: center; font-size: 25px;">Hiç model bulunamadı.</td>
                                              </tr>
                                            ');
                                          }
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
?>
