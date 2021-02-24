<?php
  include_once 'conn.php';
  $object = new Dbh;
  session_start();
  include 'oturum_acik_mi.php';

  if($_SERVER['REQUEST_METHOD'] == "POST"){
    // Marka Silinmek isteniyorsa
    if(isset($_POST['btnSil']) AND isset($_POST['marka_id'])){
      $stmt = $object->connect()->prepare("DELETE FROM markalar WHERE id = ?;");
      $stmt->execute([$_POST['marka_id']]);

      // *********************************************************
      // Markanın modelleri ile ilgili siparişleri sil
      $stmt = $object->connect()->prepare("DELETE FROM offlinesiparisler WHERE stok_id IN (SELECT id FROM stok WHERE model_id IN (SELECT id FROM modeller WHERE marka_id = ?));");
      $stmt->execute([$_POST['marka_id']]);

      $stmt = $object->connect()->prepare("DELETE FROM onlinesiparisler WHERE stok_id IN (SELECT id FROM stok WHERE model_id IN (SELECT id FROM modeller WHERE marka_id = ?));");
      $stmt->execute([$_POST['marka_id']]);
      //**********************************************************

      // Markayla ilgili araçları stoktan sil
      $stmt = $object->connect()->prepare("DELETE FROM stok WHERE model_id IN (SELECT id FROM modeller WHERE marka_id = ?);");
      $stmt->execute([$_POST['marka_id']]);

      // Markayla ilgili modelleri de sil
      $stmt = $object->connect()->prepare("DELETE FROM modeller WHERE marka_id = ?;");
      $stmt->execute([$_POST['marka_id']]);

      echo("<script> alert('Seçtiğiniz marka silindi.'); </script>");
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
    <title>Markalar</title>
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
                        <h4 class="page-title">Mevcut Markalar</h4>
                    </div>
                    <div class="col-7 align-self-center">
                        <div class="d-flex align-items-center justify-content-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="index.php">Ana Sayfa</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Markalar</li>
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
                                              <th scope="col">İşlemler</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                        <?php
                                          // Markaları getir
                                          $stmt = $object->connect()->query("SELECT * FROM markalar;");
                                          if($stmt->rowCount()){
                                            $i = 1;
                                            while ($row = $stmt->fetch()) {
                                              // Satırı ekrana yaz
                                              echo('
                                                <tr>
                                                    <th scope="row">'.$i.'</th>
                                                    <td>'.escape($row['id']).'</td>
                                                    <td> <img class="miniLogo" src="'.escape($row['logo_resmi']).'">'.escape(ucfirst($row['ad'])).'</td>
                                                    <td style="padding-left: 0;">
                                                        <ul style="list-style: none; margin: 0; max-width: 150px; min-width: 150px; padding: 0;">
                                                          <li style="float: left;">
                                                            <form action="markaDuzenle.php" method="POST">
                                                                <input type="hidden" name="marka_id" value = "'.$row['id'].'" />
                                                                <button type="submit" class="btn btn-primary" style="height: 40px; margin-left: 0px;">Düzenle</button>
                                                            </form>
                                                          </li>
                                                          <li style="float: right;">
                                                            <form method="POST">
                                                            <input type="hidden" name="marka_id" value="'.$row['id'].'">
                                                            <button type="submit" name="btnSil" class="btn btn-warning" style="height: 40px; line-height:20px; font-size: 24px;"><i class="mdi mdi-delete"></i></button>
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
                                                  <td colspan="4" style="text-align: center; font-size: 25px;">Hiç marka bulunamadı.</td>
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
