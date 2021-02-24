<?php
  if (isset($_POST['logout'])) {

    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
  }
?>
<!-- NAVIGATION BAR STARTS HERE -->
<section id="nav-bar">
  <nav class="navbar navbar-expand-lg navbar-light">
    <a class="navbar-brand" href="#"><img src="images/logo.png" /></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item active">
          <?php

            if (isset($_SESSION['user_id'])) {
              echo('<a class="nav-link">'.htmlspecialchars( $_SESSION['user_ad'], ENT_QUOTES).'</a>');
              echo('<a class="nav-link"><form action="index.php" method="POST"><button type="submit" name="logout">Oturumu Kapat</button></form></a>');
            }else{
              echo('<a class="nav-link" href="login.php">Giriş Yap</a>');
            }

          ?>
        </li>
        <li class="nav-item active">
          <a class="nav-link" href="index.php">Ana Sayfa</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="kirala.php">Kirala</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="satinAl.php">Satın Al</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php#price">Fiyatlandırma</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="index.php#contact">İletişim</a>
        </li>
      </ul>
    </div>
  </nav>
</section>
<!-- NAVIGATION BAR ENDS HERE -->
