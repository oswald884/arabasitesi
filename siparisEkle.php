<?php
  session_start();
  include_once 'conn.php';
  $object = new Dbh;


  if (!isset($_POST['stok_id'])) {
    header("Location: kirala.php");
    exit();
  }else{
    // Sipariş talebini oluştur
    if ($object) {
      if (isset($_SESSION['user_id'])) { // Oturum açıksa online Siparişlere ekle
        $val = $object->insert("INSERT INTO onlineSiparisler (user_id, baslangicT, bitisT, stok_id) VALUES(?, ?, ?, ?); ", [$_SESSION['user_id'], $_POST['baslangicTarihi'], $_POST['bitisTarihi'], $_POST['stok_id']]);
      }else{
        // Oturum kapalıysa offline siparişlere ekle
        $val = $object->insert("INSERT INTO offlineSiparisler (ad_soyad, telefon, email, baslangicT, bitisT, stok_id) VALUES(?, ?, ?, ?, ?, ?); ", [$_POST['ad'], $_POST['telefon'], $_POST['email'], $_POST['baslangicTarihi'], $_POST['bitisTarihi'], $_POST['stok_id']]);
      }
      header("Location: index.php?suc");
      exit();
    }
  }

?>
