<?php
session_start();
include 'oturum_acik_mi.php';
include_once 'conn.php';
$object = new Dbh;

if (isset($_POST['siparis_reddet'])) {

  if ($_POST['oturum'] == 1) { // Online siparişlerden silinecek ise
    // update de sql dışarıdan alındığı için delete de çalıştırabiliyoruz
    $object->update("DELETE FROM onlineSiparisler WHERE id = ?;", [$_POST['siparis_id']]);
  }else{ // Offline siparişlerden silincekse
    // update de sql dışarıdan alındığı için delete de çalıştırabiliyoruz
    $object->update("DELETE FROM offlineSiparisler WHERE id = ?;", [$_POST['siparis_id']]);
  }
}else if(isset($_POST['siparis_onayla'])){

  if ($_POST['oturum'] == 1) { // Online siparişlerden onaylandıysa
    // Siparişin durumunu onaylandı yap
    $object->update("UPDATE onlineSiparisler SET durum = 1 WHERE id = ?;", [$_POST['siparis_id']]);
    $siparisRow = $object->getRowById("onlinesiparisler", $_POST['siparis_id']);
    $object->update("UPDATE stok SET durum = 0 WHERE id = ?;", [$siparisRow['stok_id']]);
  }else{ // Offline siparişlerden onaylandıysa
    // Siparişin durumunu onaylandı yap
    $object->update("UPDATE offlineSiparisler SET durum = 1 WHERE id = ?;", [$_POST['siparis_id']]);
    $siparisRow = $object->getRowById("offlinesiparisler", $_POST['siparis_id']);
    $object->update("UPDATE stok SET durum = 0 WHERE id = ?;", [$siparisRow['stok_id']]);
  }
}else if(isset($_POST['talep_satildi']) AND isset($_POST['talep_id'])){
  // Aracı satılanlar tablosuna aktar ve stoktan sil
  if ($_POST['oturum'] == 1) {
    $talepRow = $object->getRowById("onlinesatinalim", $_POST['talep_id']);
    $object->update("DELETE FROM onlinesatinalim WHERE id = ?;", [$_POST['talep_id']]);
  }else{
    $talepRow = $object->getRowById("offlinesatinalim", $_POST['talep_id']);
    $object->update("DELETE FROM offlinesatinalim WHERE id = ?;", [$_POST['talep_id']]);
  }

  $stokRow = $object->getRowById("stok", $talepRow['stok_id']);
  $object->update("INSERT INTO satilanlar (model_id, renk_id, yil, satis_fiyati) VALUES (?, ?, ?, ?);", [$stokRow['model_id'], $stokRow['renk_id'], $stokRow['yil'], $stokRow['satis_fiyati']]);

  // Stoktan sil
  $object->update("DELETE FROM stok WHERE id = ?", [$stokRow['id']]);
  // Arabayla ilgili siparişleri sil
  $object->update("DELETE FROM offlinesiparisler WHERE stok_id = ?;", [$stokRow["id"]]);
  $object->update("DELETE FROM onlinesiparisler WHERE stok_id = ?;", [$stokRow["id"]]);
  $object->update("DELETE FROM offlinesatinalim WHERE stok_id = ?;", [$stokRow["id"]]);
  $object->update("DELETE FROM onlinesatinalim WHERE stok_id = ?;", [$stokRow["id"]]);
}else if(isset($_POST['talep_sil'])){
  // Talebi sil
  if ($_POST['oturum'] == 1) {
    // online dan sil
    // update de sql dışarıdan alındığı için delete de çalıştırabiliyoruz
    $object->update("DELETE FROM onlinesatinalim WHERE id = ?;", [$_POST['talep_id']]);
  }else{
    // offline dan sil
    // update de sql dışarıdan alındığı için delete de çalıştırabiliyoruz
    $object->update("DELETE FROM offlinesatinalim WHERE id = ?;", [$_POST['talep_id']]);
  }
}

if (isset($_POST['okundu'])) {
  // Mesajı okundu olarak işaretle
  $object->update("UPDATE mesajlar SET okundu_mu = 1 WHERE id = ?;", [$_POST['mesaj_id']]);
  header("Location: index.php#mesajlar");
  exit();
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
    <title>Ana Sayfa</title>
    <!-- Custom CSS -->
    <link href="assets/libs/chartist/dist/chartist.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="dist/css/style.min.css" rel="stylesheet">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body>
    <?php
      include 'preloader.php';
    ?>
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
                        <h4 class="page-title">Ana Sayfa</h4>
                    </div>
                    <div class="col-7 align-self-center">
                        <div class="d-flex align-items-center justify-content-end">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item">
                                        <a href="index.php">Ana Sayfa</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Durum Özeti</li>
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
                <!-- Email campaign chart -->
                <!-- ============================================================== -->

                <!-- ============================================================== -->
                <!-- Email campaign chart -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Ravenue - page-view-bounce rate -->
                <!-- ============================================================== -->
                <div class="row">
                    <!-- column -->
                    <div class="col-12" style="max-height: 400px;">
                        <div class="card">
                            <div class="card-body">
                                <h4 class="card-title">Bekleyen Siparişler</h4>
                            </div>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th class="border-top-0">Ad Soyad</th>
                                            <th class="border-top-0">Telefon</th>
                                            <th class="border-top-0">Email</th>
                                            <th class="border-top-0">Başlangıç Tarihi</th>
                                            <th class="border-top-0">Bitiş Tarihi</th>
                                            <th class="border-top-0">Araç</th>
                                            <th class="border-top-0">Sipariş Zamanı</th>
                                            <th class="border-top-0">Durum</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                          if ($object) {

                                            // ==========================================================
                                            // Offline siparişleri getir
                                            $stmt = $object->selectTable("offlineSiparisler");
                                            if ($stmt && $stmt->rowCount() >0) {
                                                while ($row = $stmt->fetch()) {

                                                  if ($row['durum'] != 1) { // Sipariş bekleyen bir siparişse Satırı ekrana yaz
                                                    // İlgili aracın bilgilerini getir
                                                    $stokRow = $object->getRowById("stok", $row['stok_id']);
                                                    $renkRow = $object->getRowById("renkler", $stokRow['renk_id']);
                                                    $modelRow = $object->getRowById("modeller", $stokRow['model_id']);

                                                    echo ('
                                                      <tr>
                                                        <td class="txt-oflo">'.escape(ucfirst($row['ad_soyad'])).'</td>
                                                        <td class="txt-oflo">'.escape(ucfirst($row['telefon'])).'</td>
                                                        <td class="txt-oflo">'.escape(ucfirst($row['email'])).'</td>
                                                        <td class="txt-oflo">'.escape(ucfirst($row['baslangicT'])).'</td>
                                                        <td class="txt-oflo">'.escape(ucfirst($row['bitisT'])).'</td>
                                                        <td class="txt-oflo" title="'.escape("Günlük: ".$stokRow['gunluk_fiyati']." tl").'">'.escape(ucfirst($stokRow['yil']." Model ".$renkRow['renk_adi']." ".$modelRow['model_adi'])).'</td>
                                                        <td class="txt-oflo">'.escape(ucfirst($row['tarih_saat'])).'</td>
                                                        <td>
                                                          <form method="POST">
                                                            <button name="siparis_onayla" class="btn btn-success">Onayla</button> <button name="siparis_reddet" class="btn btn-danger">Reddet</button>
                                                            <input type="hidden" name="siparis_id" value="'.$row['id'].'" >
                                                            <input type="hidden" name="oturum" value="0" >
                                                          </form>
                                                        </td>
                                                      </tr>
                                                    ');
                                                  }
                                                }
                                              }


                                            // ==========================================================
                                            // ONLINE SİPARİŞLERİ GETİR
                                            $stmt = $object->selectTable("onlineSiparisler");
                                            if ($stmt) {
                                                while ($row = $stmt->fetch()) {

                                                  if ($row['durum'] != 1) { // Sipariş bekleyen bir siparişse Satırı ekrana yaz

                                                    // Müşteri bilgileri
                                                    $musteriRow = $object->getRowById("user_info", $row['user_id']);
                                                    // İlgili aracın bilgilerini getir
                                                    $stokRow = $object->getRowById("stok", $row['stok_id']);
                                                    $renkRow = $object->getRowById("renkler", $stokRow['renk_id']);
                                                    $modelRow = $object->getRowById("modeller", $stokRow['model_id']);

                                                    echo ('
                                                      <tr>
                                                        <td class="txt-oflo">'.escape(ucfirst($musteriRow['ad_soyad'])).'</td>
                                                        <td class="txt-oflo">'.escape(ucfirst($musteriRow['telefon'])).'</td>
                                                        <td class="txt-oflo">'.escape(ucfirst($musteriRow['email'])).'</td>
                                                        <td class="txt-oflo">'.escape(ucfirst($row['baslangicT'])).'</td>
                                                        <td class="txt-oflo">'.escape(ucfirst($row['bitisT'])).'</td>
                                                        <td class="txt-oflo" title="'.escape("Günlük: ".$stokRow['gunluk_fiyati']." tl").'">'.escape(ucfirst($stokRow['yil']." Model ".$renkRow['renk_adi']." ".$modelRow['model_adi'])).'</td>
                                                        <td class="txt-oflo">'.escape(ucfirst($row['tarih_saat'])).'</td>
                                                        <td>
                                                          <form method="POST">
                                                            <button name="siparis_onayla" class="btn btn-success">Onayla</button> <button name="siparis_reddet" class="btn btn-danger">Reddet</button>
                                                            <input type="hidden" name="siparis_id" value="'.$row['id'].'" >
                                                            <input type="hidden" name="oturum" value="1" >
                                                          </form>
                                                        </td>
                                                      </tr>
                                                    ');
                                                  }
                                                }
                                              }

                                          }


                                        ?>

                                        <!--
                                        <tr>

                                            <td class="txt-oflo">Admin Sayfası</td>
                                            <td><span class="label label-success label-rounded">Satış</span> </td>
                                            <td class="txt-oflo">April 18, 2017</td>
                                            <td><span class="font-medium">$24</span></td>
                                        </tr>
                                        <tr>

                                            <td class="txt-oflo">Digital Agency PSD</td>
                                            <td><span class="label label-danger label-rounded">Vergi</span> </td>
                                            <td class="txt-oflo">April 23, 2017</td>
                                            <td><span class="font-medium">-$14</span></td>
                                        </tr>
                                      -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- column -->
                <div class="col-12" style="max-height: 400px;">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Onaylanan Siparişler</h4>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="border-top-0">Ad Soyad</th>
                                        <th class="border-top-0">Telefon</th>
                                        <th class="border-top-0">Email</th>
                                        <th class="border-top-0">Başlangıç Tarihi</th>
                                        <th class="border-top-0">Bitiş Tarihi</th>
                                        <th class="border-top-0">Araç</th>
                                        <th class="border-top-0">Sipariş Zamanı</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                      if ($object) {

                                        // ==========================================================
                                        // Offline siparişleri getir
                                        $stmt = $object->select("SELECT * FROM offlineSiparisler WHERE durum = 1 ORDER BY tarih_saat ASC;", null);
                                        if ($stmt) {
                                            while ($row = $stmt->fetch()) {

                                              // İlgili aracın bilgilerini getir
                                              $stokRow = $object->getRowById("stok", $row['stok_id']);
                                              $renkRow = $object->getRowById("renkler", $stokRow['renk_id']);
                                              $modelRow = $object->getRowById("modeller", $stokRow['model_id']);

                                              echo ('
                                                <tr>
                                                  <td class="txt-oflo">'.escape(ucfirst($row['ad_soyad'])).'</td>
                                                  <td class="txt-oflo">'.escape(ucfirst($row['telefon'])).'</td>
                                                  <td class="txt-oflo">'.escape($row['email']).'</td>
                                                  <td class="txt-oflo">'.escape(ucfirst($row['baslangicT'])).'</td>
                                                  <td class="txt-oflo">'.escape(ucfirst($row['bitisT'])).'</td>
                                                  <td class="txt-oflo" title="'.escape("Günlük: ".$stokRow['gunluk_fiyati']." tl").'">'.escape(ucfirst($stokRow['yil']." Model ".$renkRow['renk_adi']." ".$modelRow['model_adi'])).'</td>
                                                  <td class="txt-oflo">'.escape(ucfirst($row['tarih_saat'])).'</td>
                                                </tr>
                                              ');

                                            }
                                          }


                                        // ==========================================================
                                        // ONLINE SİPARİŞLERİ GETİR
                                        $stmt = $object->select("SELECT * FROM onlineSiparisler WHERE durum = 1 ORDER BY tarih_saat ASC;", null);
                                        if ($stmt) {
                                            while ($row = $stmt->fetch()) {

                                              // Müşteri bilgileri
                                              $musteriRow = $object->getRowById("user_info", $row['user_id']);
                                              // İlgili aracın bilgilerini getir
                                              $stokRow = $object->getRowById("stok", $row['stok_id']);
                                              $renkRow = $object->getRowById("renkler", $stokRow['renk_id']);
                                              $modelRow = $object->getRowById("modeller", $stokRow['model_id']);

                                              echo ('
                                                <tr>
                                                  <td class="txt-oflo">'.escape(ucfirst($musteriRow['ad_soyad'])).'</td>
                                                  <td class="txt-oflo">'.escape(ucfirst($musteriRow['telefon'])).'</td>
                                                  <td class="txt-oflo">'.escape($musteriRow['email']).'</td>
                                                  <td class="txt-oflo">'.escape(ucfirst($row['baslangicT'])).'</td>
                                                  <td class="txt-oflo">'.escape(ucfirst($row['bitisT'])).'</td>
                                                  <td class="txt-oflo" title="'.escape("Günlük: ".$stokRow['gunluk_fiyati']." tl").'">'.escape(ucfirst($stokRow['yil']." Model ".$renkRow['renk_adi']." ".$modelRow['model_adi'])).'</td>
                                                  <td class="txt-oflo">'.escape(ucfirst($row['tarih_saat'])).'</td>
                                                </tr>
                                              ');

                                            }
                                          }

                                      }


                                    ?>

                                    <!--
                                    <tr>

                                        <td class="txt-oflo">Admin Sayfası</td>
                                        <td><span class="label label-success label-rounded">Satış</span> </td>
                                        <td class="txt-oflo">April 18, 2017</td>
                                        <td><span class="font-medium">$24</span></td>
                                    </tr>
                                    <tr>

                                        <td class="txt-oflo">Digital Agency PSD</td>
                                        <td><span class="label label-danger label-rounded">Vergi</span> </td>
                                        <td class="txt-oflo">April 23, 2017</td>
                                        <td><span class="font-medium">-$14</span></td>
                                    </tr>
                                  -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- column -->
                <div class="col-12" style="max-height: 400px;">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Satın Alım Talepleri</h4>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th class="border-top-0">Ad Soyad</th>
                                        <th class="border-top-0">Telefon</th>
                                        <th class="border-top-0">Email</th>
                                        <th class="border-top-0">Araç</th>
                                        <th class="border-top-0">Sipariş Zamanı</th>
                                        <th class="border-top-0">İşlemler</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                      if ($object) {

                                        // ==========================================================
                                        // Offline siparişleri getir
                                        $stmt = $object->select("SELECT * FROM offlinesatinalim WHERE durum = 0 ORDER BY tarih_saat ASC;", null);
                                        if ($stmt) {
                                            while ($row = $stmt->fetch()) {

                                              // İlgili aracın bilgilerini getir
                                              $stokRow = $object->getRowById("stok", $row['stok_id']);
                                              $renkRow = $object->getRowById("renkler", $stokRow['renk_id']);
                                              $modelRow = $object->getRowById("modeller", $stokRow['model_id']);

                                              echo ('
                                                <tr>
                                                  <td class="txt-oflo">'.escape(ucfirst($row['ad_soyad'])).'</td>
                                                  <td class="txt-oflo">'.escape(ucfirst($row['telefon'])).'</td>
                                                  <td class="txt-oflo">'.escape($row['email']).'</td>
                                                  <td class="txt-oflo" title="'.escape("Fiyat: ".$stokRow['satis_fiyati']." tl").'">'.escape(ucfirst($stokRow['yil']." Model ".$renkRow['renk_adi']." ".$modelRow['model_adi'])).'</td>
                                                  <td class="txt-oflo">'.escape(ucfirst($row['tarih_saat'])).'</td>
                                                  <td>
                                                    <form method="POST">
                                                      <button name="talep_satildi" class="btn btn-success">Satıldı</button>
                                                      <button name="talep_sil" class="btn btn-danger">Talebi Sil</button>
                                                      <input type="hidden" name="talep_id" value="'.$row['id'].'" >
                                                      <input type="hidden" name="oturum" value="0" >
                                                    </form>
                                                  </td>
                                                </tr>
                                              ');

                                            }
                                          }


                                        // ==========================================================
                                        // ONLINE SİPARİŞLERİ GETİR
                                        $stmt = $object->select("SELECT * FROM onlinesatinalim WHERE durum = 0 ORDER BY tarih_saat ASC;", null);
                                        if ($stmt) {
                                            while ($row = $stmt->fetch()) {

                                              // Müşteri bilgileri
                                              $musteriRow = $object->getRowById("user_info", $row['user_id']);
                                              // İlgili aracın bilgilerini getir
                                              $stokRow = $object->getRowById("stok", $row['stok_id']);
                                              $renkRow = $object->getRowById("renkler", $stokRow['renk_id']);
                                              $modelRow = $object->getRowById("modeller", $stokRow['model_id']);

                                              echo ('
                                                <tr>
                                                  <td class="txt-oflo">'.escape(ucfirst($musteriRow['ad_soyad'])).'</td>
                                                  <td class="txt-oflo">'.escape(ucfirst($musteriRow['telefon'])).'</td>
                                                  <td class="txt-oflo">'.escape($musteriRow['email']).'</td>
                                                  <td class="txt-oflo" title="'.escape("Fiyat: ".$stokRow['satis_fiyati']." tl").'">'.escape(ucfirst($stokRow['yil']." Model ".$renkRow['renk_adi']." ".$modelRow['model_adi'])).'</td>
                                                  <td class="txt-oflo">'.escape(ucfirst($row['tarih_saat'])).'</td>
                                                  <td>
                                                    <form method="POST">
                                                      <button name="talep_satildi" class="btn btn-success">Satıldı</button>
                                                      <button name="talep_sil" class="btn btn-danger">Talebi Sil</button>
                                                      <input type="hidden" name="talep_id" value="'.$row['id'].'" >
                                                      <input type="hidden" name="oturum" value="1" >
                                                    </form>
                                                  </td>
                                                </tr>
                                              ');

                                            }
                                          }

                                      }


                                    ?>

                                    <!--
                                    <tr>

                                        <td class="txt-oflo">Admin Sayfası</td>
                                        <td><span class="label label-success label-rounded">Satış</span> </td>
                                        <td class="txt-oflo">April 18, 2017</td>
                                        <td><span class="font-medium">$24</span></td>
                                    </tr>
                                    <tr>

                                        <td class="txt-oflo">Digital Agency PSD</td>
                                        <td><span class="label label-danger label-rounded">Vergi</span> </td>
                                        <td class="txt-oflo">April 23, 2017</td>
                                        <td><span class="font-medium">-$14</span></td>
                                    </tr>
                                  -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

                <!-- ============================================================== -->
                <!-- Ravenue - page-view-bounce rate -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Recent comment and chats -->
                <!-- ============================================================== -->

                <div class="row"  id="mesajlar">
                    <!-- column -->
                    <div class="col-md-12" style="margin-left: 10%;">
                        <div class="card" style="width: 80%;">
                            <div class="card-body">
                                <h4 class="card-title">Okunmamış Mesajlar</h4>
                            </div>
                            <div class="comment-widgets" style="height:430px;">
                                <?php
                                  if ($object) {
                                    // Okunmamış mesajları getir
                                    $stmt = $object->select("SELECT * FROM mesajlar WHERE okundu_mu = 0 ORDER BY tarih_saat DESC;", NULL);
                                    if ($stmt) {
                                      while ($row = $stmt->fetch()) {
                                        echo('
                                          <!-- Mesaj Satırı -->
                                          <div class="d-flex flex-row comment-row m-t-0">
                                              <div class="comment-text w-100">
                                                  <h6 class="font-medium">Ad Soyad: '.escape(ucfirst($row['ad'])).'</h6>
                                                  <h6 class="font-small">Email: '.escape($row['email']).' &nbsp;&nbsp;&nbsp;&nbsp; Telefon: '.escape($row['telefon']).'</h6>
                                                  <span class="m-b-15 d-block">Mesaj : '.escape($row['mesaj']).' </span>
                                                  <div class="comment-footer">
                                                      <span class="text-muted float-right">Şu Tarihte: '.$row['tarih_saat'].'</span>
                                                      <span class="action-icons">
                                                        <form method="POST">
                                                          <button name="okundu" class="btn btn-warning">Okundu olarak işaretle</button>
                                                          <input name="mesaj_id" type="hidden" value="'.escape($row['id']).'">
                                                        </form>
                                                      </span>
                                                  </div>
                                              </div>
                                          </div>
                                          <!-- Mesaj Satırı -->
                                        ');
                                      }
                                    }
                                  }
                                ?>

                            </div>
                        </div>
                    </div>

                <!-- ============================================================== -->
                <!-- Recent comment and chats -->
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
    <!--This page JavaScript -->
    <!--chartis chart-->
    <script src="assets/libs/chartist/dist/chartist.min.js"></script>
    <script src="assets/libs/chartist-plugin-tooltips/dist/chartist-plugin-tooltip.min.js"></script>
    <script src="dist/js/pages/dashboards/dashboard1.js"></script>
</body>

</html>

<?php
  function escape($string){
    return htmlspecialchars($string, ENT_QUOTES);
  }
?>
