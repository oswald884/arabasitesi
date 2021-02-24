<?php

  class Dbh{
    private $servername;
    private $username;
    private $password;
    private $dbname;
    private $charset;

    public function connect(){
      $this->servername = "localhost";
      $this->username = "root";
      $this->password = "";
      $this->dbname = "arabasitesi";
      $this->charset = "utf8mb4";

      try {
        $dsn = "mysql:host=".$this->servername.";dbname=".$this->dbname.";charset=".$this->charset;
        $pdo = new PDO($dsn, $this->username, $this->password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
      } catch (PDOException $e) {
        return false;
      }
      return false;
    }

    // ********************
    public function getRenkRow($id){
      try {
        $pdo = $this->connect();
        if (!$pdo) {
          return false;
        }

        $sql = "SELECT * FROM renkler WHERE id = ?;";
        $stmt = $pdo->prepare($sql);
        if (!$stmt) {
          return false;
        }

        $stmt->execute([$id]);
        return $stmt->fetch();
      } catch (\Exception $e) {
        return false;
      }
      return false;
    }

    public function getRowById($table, $id){
      try {
        $pdo = $this->connect();
        if (!$pdo) {
          return false;
        }

        $sql = "SELECT * FROM ".$table." WHERE id = ?;";
        $stmt = $pdo->prepare($sql);
        if (!$stmt) {
          return false;
        }

        $stmt->execute([$id]);
        return $stmt->fetch();
      } catch (\Exception $e) {
        return false;
      }
      return false;
    }
    // ********************

    public function selectTable($table){
      try {
        $pdo = $this->connect();
        if (!$pdo) {
          return false;
        }

        $sql = "SELECT * FROM ".$table.";";
        $stmt = $pdo->query($sql);
        if (!$stmt) {
          return false;
        }

        return $stmt;
      } catch (\Exception $e) {
        return false;
      }
      return false;
    }

    public function getMarkaIdByModelId($modelId){
      try {
        $pdo = $this->connect();
        if (!$pdo) {
          return false;
        }

        $sql = "SELECT marka_id FROM modeller WHERE id = ?;";
        $stmt = $pdo->prepare($sql);
        if (!$stmt) {
          return false;
        }
        $stmt->execute([$modelId]);
        if(!$stmt->rowCount()){
          return false;
        }
        return $stmt->fetch();
      } catch (\Exception $e) {
        return false;
      }
      return false;
    }

    public function insert($sql, $varArray){
      try {
        $pdo = $this->connect();
        if (!$pdo) {
          return false;
        }

        $stmt = $pdo->prepare($sql);
        if (!$stmt) {
          return false;
        }

        if(!$stmt->execute($varArray)){
          return false;
        }

        return true;
      } catch (\Exception $e) {
        return false;
      }
      return false;
    }

    public function select($sql, $varArray){
      try {
        $pdo = $this->connect();
        if (!$pdo) {
          return false;
        }

        if ($varArray != null) {
          $stmt = $pdo->prepare($sql);
          if (!$stmt) {
            return false;
          }

          $stmt->execute($varArray);
          if ($stmt->rowCount() == 0) {
            return false;
          }

          return $stmt;
        }else{
          $stmt = $pdo->query($sql);
          if (!$stmt || !$stmt->rowCount()) {
            return false;
          }

          return $stmt;
        }
      } catch (\Exception $e) {
        return false;
      }
      return false;
    }

    public function update($sql, $varArray){
      $pdo = $this->connect();
      if(!$pdo){
        return false;
      }

      $stmt = $pdo->prepare($sql);
      if (!$stmt) {
        return false;
      }

      if ($stmt->execute($varArray)) {
        return true;
      }else{
        return false;
      }

    }

    public function getMarkaAd($id){
      if ($id === false) {
        return "YOK";
      }
      $stmt = $this->connect()->prepare("SELECT * FROM markalar WHERE id = ?;");
      $stmt->execute([$id]);
      if(!$stmt->rowCount()){
        return "Bulunamadı";
      }else{
        $row = $stmt->fetch();
        return $row['ad'];
      }
    }

    public function getTipAd($id){
      $stmt = $this->connect()->prepare("SELECT * FROM tipler WHERE id = ?;");
      $stmt->execute([$id]);
      if(!$stmt->rowCount()){
        return "Bulunamadı";
      }else{
        $row = $stmt->fetch();
        return $row['tip_adi'];
      }
    }

    public function getYakitTipAd($id){
      $stmt = $this->connect()->prepare("SELECT * FROM yakit_tipleri WHERE id = ?;");
      $stmt->execute([$id]);
      if(!$stmt->rowCount()){
        return "Bulunamadı";
      }else{
        $row = $stmt->fetch();
        return $row['yakit_tip_adi'];
      }
    }

    public function getVitesTipAd($id){
      $stmt = $this->connect()->prepare("SELECT * FROM vites_tipleri WHERE id = ?;");
      $stmt->execute([$id]);
      if(!$stmt->rowCount()){
        return "Bulunamadı";
      }else{
        $row = $stmt->fetch();
        return $row['vites_tip_adi'];
      }
    }

    public function getModelRow($id){
      $stmt = $this->connect()->prepare("SELECT * FROM modeller WHERE id = ?;");
      $stmt->execute([$id]);
      if(!$stmt->rowCount()){
        return false;
      }else{
        $row = $stmt->fetch();
        return $row;
      }
    }

    public function selectIcerik($key){
      try {
        $pdo = $this->connect();
        if (!$pdo) {
          return false;
        }

        $sql = "SELECT * FROM icerikler WHERE icerik_key = ?;";
        $stmt = $pdo->prepare($sql);
        if (!$stmt) {
          return false;
        }

        $stmt->execute([$key]);
        if(!$stmt->rowCount()){
          return false;
        }
        return $stmt->fetch();
      } catch (\Exception $e) {
        return false;
      }
      return false;
    }
  }
?>
