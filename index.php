<?php
  session_start();
  include_once 'conn.php';
  $object = new Dbh;

  /*
  <?php if($object){ $row = $object->selectIcerik(""); if($row){ echo($row['icerik']); }} ?>
  */

  if ($_SERVER['REQUEST_METHOD'] == "POST" AND $object) {
    if (isset($_POST['mesaj']) AND isset($_POST['gonder']) AND $_POST['gonder'] == 1) {
      $val = $object->insert("INSERT INTO mesajlar (ad, telefon, email, mesaj) VALUES (?, ?, ?, ?);", [$_POST['ad'],$_POST['telefon'], $_POST['email'], $_POST['mesaj']]);
      if ($val) {
        echo("<script> alert('Gönderildi'); </script>");
        header("Location: index.php#contact");
        exit();
      }
      else{
        echo("<script> alert('Gönderilemedi'); </script>");
      }
    }
  }

?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title>Ana Sayfa</title>

  <!-- CSS -->
  <link rel="stylesheet" type="text/css" href="./css/main.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" />

  <!-- JS, Popper.js, and jQuery -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>

  <!-- JAVASCRIPT -->
  <script type="text/javascript" src="js/main.js"></script>
  <script>
      function validateContactForm(){
        //Ad kontrol
        let adElement = document.getElementsByName('ad')[0];
        let ad = adElement.value;
        if (ad.length > 200) {
          alert("Ad '200' karakterden uzun olamaz!");
          return;
        }

        //telefon kontrolü
        let telefonElement = document.getElementsByName('telefon')[0];
        let telefon = telefonElement.value;
        if (telefon.length > 15) {
          alert("Telefon '15' karakterden uzun olamaz!");
          return;
        }

        //email kontrolü
        let emailElement = document.getElementsByName('email')[0];
        let email = emailElement.value;
        if (email.length > 150) {
          alert("Email '150' karakterden uzun olamaz!");
          return;
        }

        let gonderElement = document.getElementsByName("gonder")[0];
        gonderElement.value = 1;

        let form = document.getElementById('contactForm');
        form.submit();
      }
  </script>
</head>

<body>
  <?php
    include 'header.php';
   ?>

  <!-- SLIDER STARTS HERE -->
  <div id="slider">
    <div id="headerSlider" class="carousel slide" data-ride="carousel">
      <ol class="carousel-indicators">
        <li data-target="#headerSlider" data-slide-to="0" class="active"></li>
        <li data-target="#headerSlider" data-slide-to="1"></li>
        <li data-target="#headerSlider" data-slide-to="2"></li>
      </ol>
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="<?php $row = $object->selectIcerik("slider_resim1"); if($row){ echo(escape($row['icerik'])); } ?>" class="d-block w-100" style="margin: 0 auto;" alt="banner1">
          <div class="image-box__overlay"></div>
          <div class="carousel-caption">
            <h5><?php $row = $object->selectIcerik("slider_baslik1"); if($row){ echo(escape($row['icerik'])); } ?></h5>
          </div>
        </div>
        <div class="carousel-item">
          <img src="<?php $row = $object->selectIcerik("slider_resim2"); if($row){ echo(escape($row['icerik'])); } ?>" class="d-block w-100" alt="banner2">
          <div class="image-box__overlay"></div>
          <div class="carousel-caption">
            <h5><?php $row = $object->selectIcerik("slider_baslik2"); if($row){ echo(escape($row['icerik'])); } ?></h5>
          </div>
        </div>
        <div class="carousel-item">
          <img src="<?php $row = $object->selectIcerik("slider_resim3"); if($row){ echo(escape($row['icerik'])); } ?>" class="d-block w-100" alt="banner3">
          <div class="image-box__overlay"></div>
          <div class="carousel-caption">
            <h5><?php $row = $object->selectIcerik("slider_baslik3"); if($row){ echo(escape($row['icerik'])); } ?></h5>
          </div>
        </div>
      </div>
      <a class="carousel-control-prev" href="#headerSlider" role="button" data-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="sr-only">Önceki</span>
      </a>
      <a class="carousel-control-next" href="#headerSlider" role="button" data-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="sr-only">Sonraki</span>
      </a>
    </div>
  </div>
  <!-- SLIDER ENDS HERE -->

  <!-- ABOUT SECTION STARTS HERE -->
  <section id="about">
    <div class="container">
      <div class="row">
        <div class="col-md-12" style="text-align: center;">
          <h2><?php $row = $object->selectIcerik("about_baslik"); if($row){ echo(escape($row['icerik'])); } ?></h2>
          <div class="about-content">
            <?php $row = $object->selectIcerik("about_paragraf"); if($row){ echo(escape($row['icerik'])); } ?>
          </div>
          <a href="#services"> <button type="button" name="readMore" class="btn btn-primary"> Daha fazla >></button></a>
        </div>

      </div>
    </div>
  </section>
  <!-- ABOUT SECTION ENDS HERE -->

  <!-- SERVICES SECTION STARTS HERE -->
  <section id="services">
    <div class="container">
      <h1>  <?php if($object){ $row = $object->selectIcerik("services_baslik"); if($row){ echo(escape($row['icerik'])); }} ?></h1>
      <div class="row services">
        <div class="col-md-3 text-center">
          <div class="icon">
            <i class="fa fa-desktop"></i>
          </div>
          <h3>  <?php if($object){ $row = $object->selectIcerik("services_baslik1"); if($row){ echo(escape($row['icerik'])); }} ?></h3>
          <p>  <?php if($object){ $row = $object->selectIcerik("services_paragraf1"); if($row){ echo(escape($row['icerik'])); }} ?></p>
        </div>
        <div class="col-md-3 text-center">
          <div class="icon">
            <i class="fa fa-line-chart"></i>
          </div>
          <h3><?php if($object){ $row = $object->selectIcerik("services_baslik2"); if($row){ echo(escape($row['icerik'])); }} ?></h3>
          <p><?php if($object){ $row = $object->selectIcerik("services_paragraf2"); if($row){ echo(escape($row['icerik'])); }} ?></p>
        </div>
        <div class="col-md-3 text-center">
          <div class="icon">
            <i class="fa fa-line-chart"></i>
          </div>
          <h3><?php if($object){ $row = $object->selectIcerik("services_baslik3"); if($row){ echo(escape($row['icerik'])); }} ?></h3>
          <p><?php if($object){ $row = $object->selectIcerik("services_paragraf3"); if($row){ echo(escape($row['icerik'])); }} ?></p>
        </div>
        <div class="col-md-3 text-center">
          <div class="icon">
            <i class="fa fa-desktop"></i>
          </div>
          <h3><?php if($object){ $row = $object->selectIcerik("services_baslik4"); if($row){ echo(escape($row['icerik'])); }} ?></h3>
          <p><?php if($object){ $row = $object->selectIcerik("services_paragraf4"); if($row){ echo(escape($row['icerik'])); }} ?></p>
        </div>
      </div>
    </div>
  </section>
  <!-- SERVICES SECTION ENDS HERE -->

  <!-- TEAM MEMBERS SECTION STARTS HERE -->
  <section id="team">
    <div class="container">
      <h1><?php if($object){ $row = $object->selectIcerik("team_baslik"); if($row){ echo(escape($row['icerik'])); }} ?></h1>
      <div class="row">
        <div id="person" class="col-md-3 profile-pic text-center">
          <div class="img-box">
            <img src="<?php if($object){ $row = $object->selectIcerik("team3_resim"); if($row){ echo(escape($row['icerik'])); }} ?>" alt="member1" class="img-responsive">
            <ul>
              <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                  <li><a href="#"><i class="fa fa-facebook"></i></a></li>
            </ul>
          </div>
          <h2><?php if($object){ $row = $object->selectIcerik("team3_baslik"); if($row){ echo(escape($row['icerik'])); }} ?></h2>
          <h3><?php if($object){ $row = $object->selectIcerik("team3_pozisyon"); if($row){ echo(escape($row['icerik'])); }} ?></h3>
          <p><?php if($object){ $row = $object->selectIcerik("team3_paragraf"); if($row){ echo(escape($row['icerik'])); }} ?></p>
        </div>

        <div id="person" class="col-md-3 profile-pic text-center">
          <div class="img-box">
            <img src="<?php if($object){ $row = $object->selectIcerik("team2_resim"); if($row){ echo(escape($row['icerik'])); }} ?>" alt="member1" class="img-responsive">
            <ul>
              <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                  <li><a href="#"><i class="fa fa-facebook"></i></a></li>
            </ul>
          </div>
          <h2><?php if($object){ $row = $object->selectIcerik("team2_baslik"); if($row){ echo(escape($row['icerik'])); }} ?></h2>
          <h3><?php if($object){ $row = $object->selectIcerik("team2_pozisyon"); if($row){ echo(escape($row['icerik'])); }} ?></h3>
          <p><?php if($object){ $row = $object->selectIcerik("team2_paragraf"); if($row){ echo(escape($row['icerik'])); }} ?></p>
        </div>

        <div id="person" class="col-md-3 profile-pic text-center">
          <div class="img-box">
            <img src="<?php if($object){ $row = $object->selectIcerik("team1_resim"); if($row){ echo(escape($row['icerik'])); }} ?>" alt="member1" class="img-responsive">
            <ul>
              <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                  <li><a href="#"><i class="fa fa-facebook"></i></a></li>
            </ul>
          </div>
          <h2><?php if($object){ $row = $object->selectIcerik("team1_baslik"); if($row){ echo(escape($row['icerik'])); }} ?></h2>
          <h3><?php if($object){ $row = $object->selectIcerik("team1_pozisyon"); if($row){ echo(escape($row['icerik'])); }} ?></h3>
          <p><?php if($object){ $row = $object->selectIcerik("team1_paragraf"); if($row){ echo(escape($row['icerik'])); }} ?></p>
        </div>

        <div id="person" class="col-md-3 profile-pic text-center">
          <div class="img-box">
            <img src="<?php if($object){ $row = $object->selectIcerik("team4_resim"); if($row){ echo(escape($row['icerik'])); }} ?>" alt="member1" class="img-responsive">
            <ul>
              <li><a href="#"><i class="fa fa-linkedin"></i></a></li>
                <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                  <li><a href="#"><i class="fa fa-facebook"></i></a></li>
            </ul>
          </div>
          <h2><?php if($object){ $row = $object->selectIcerik("team4_baslik"); if($row){ echo(escape($row['icerik'])); }} ?></h2>
          <h3><?php if($object){ $row = $object->selectIcerik("team4_pozisyon"); if($row){ echo(escape($row['icerik'])); }} ?></h3>
          <p><?php if($object){ $row = $object->selectIcerik("team4_paragraf"); if($row){ echo(escape($row['icerik'])); }} ?></p>
        </div>
      </div>
    </div>
  </section>
  <!-- TEAM MEMBERS SECTION ENDS HERE -->

  <!-- PROMO SECTION STARTS HERE -->
  <section id="promo">
    <div class="container">
      <p><?php if($object){ $row = $object->selectIcerik("promo_paragraf"); if($row){ echo(escape($row['icerik'])); }} ?></p>
      <a href="#contact" id="btn_middle" class="btn btn-primary">Bize Ulaşın</a>
    </div>
  </section>
  <!-- PROMO SECTION ENDS HERE -->

  <!-- PRICING SECTION STARTS HERE -->
  <section id="price">
    <div class="container">
      <h1><?php if($object){ $row = $object->selectIcerik("pricing_baslik"); if($row){ echo(escape($row['icerik'])); }} ?></h1>
      <div class="row">
        <div class="col-md-3">
          <div class="single-price">
            <div class="price-head">
              <h2><?php if($object){ $row = $object->selectIcerik("pricing1_baslik"); if($row){ echo(escape($row['icerik'])); }} ?></h2>
              <p><?php if($object){ $row = $object->selectIcerik("pricing1_fiyat"); if($row){ echo(escape($row['icerik'])); }} ?>₺<span> den başlayan fiyatlar</span></p>
              </div>
              <div class="price-content">
                <ul>
                  <li><i class="fa fa-check-circle"></i>
                    <?php if($object){ $row = $object->selectIcerik("pricing1_li1"); if($row){ echo(escape($row['icerik'])); }} ?></li>
                  <li><i class="fa fa-check-circle"></i>
                    <?php if($object){ $row = $object->selectIcerik("pricing1_li2"); if($row){ echo(escape($row['icerik'])); }} ?></li>
                  <li><i class="fa fa-check-circle"></i>
                    <?php if($object){ $row = $object->selectIcerik("pricing1_li3"); if($row){ echo(escape($row['icerik'])); }} ?></li>
                  <li><i class="fa fa-times-circle"></i>
                    <?php if($object){ $row = $object->selectIcerik("pricing1_li4"); if($row){ echo(escape($row['icerik'])); }} ?></li>
                  <li><i class="fa fa-times-circle"></i>
                    <?php if($object){ $row = $object->selectIcerik("pricing1_li5"); if($row){ echo(escape($row['icerik'])); }} ?></li>
                </ul>
              </div>
              <div class="price-button">
                <a class="buy-btn" href="#">KİRALA</a>
              </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="single-price">
            <div class="price-head">
              <h2><?php if($object){ $row = $object->selectIcerik("pricing2_baslik"); if($row){ echo(escape($row['icerik'])); }} ?></h2>
              <p><?php if($object){ $row = $object->selectIcerik("pricing2_fiyat"); if($row){ echo(escape($row['icerik'])); }} ?>₺ <span>den başlayan fiyatlar</span></p>
              </div>
              <div class="price-content">
                <ul>
                  <li><i class="fa fa-check-circle"></i>
                    <?php if($object){ $row = $object->selectIcerik("pricing2_li1"); if($row){ echo(escape($row['icerik'])); }} ?></li>
                  <li><i class="fa fa-check-circle"></i>
                    <?php if($object){ $row = $object->selectIcerik("pricing2_li2"); if($row){ echo(escape($row['icerik'])); }} ?></li>
                  <li><i class="fa fa-check-circle"></i>
                    <?php if($object){ $row = $object->selectIcerik("pricing2_li3"); if($row){ echo(escape($row['icerik'])); }} ?></li>
                    <li><i class="fa fa-times-circle"></i>
                      <?php if($object){ $row = $object->selectIcerik("pricing2_li4"); if($row){ echo(escape($row['icerik'])); }} ?></li>
                    <li><i class="fa fa-check-circle"></i>
                      <?php if($object){ $row = $object->selectIcerik("pricing2_li5"); if($row){ echo(escape($row['icerik'])); }} ?></li>
                </ul>
              </div>
              <div class="price-button">
                <a class="buy-btn" href="#">KİRALA</a>
              </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="single-price">
            <div class="price-head">
              <h2><?php if($object){ $row = $object->selectIcerik("pricing3_baslik"); if($row){ echo(escape($row['icerik'])); }} ?></h2>
              <p><?php if($object){ $row = $object->selectIcerik("pricing3_fiyat"); if($row){ echo(escape($row['icerik'])); }} ?>₺ <span>den başlayan fiyatlar</span></p>
              </div>
              <div class="price-content">
                <ul>
                  <li><i class="fa fa-check-circle"></i>
                    <?php if($object){ $row = $object->selectIcerik("pricing3_li1"); if($row){ echo(escape($row['icerik'])); }} ?></li>
                  <li><i class="fa fa-check-circle"></i>
                    <?php if($object){ $row = $object->selectIcerik("pricing3_li2"); if($row){ echo(escape($row['icerik'])); }} ?></li>
                  <li><i class="fa fa-check-circle"></i>
                  <?php if($object){ $row = $object->selectIcerik("pricing3_li3"); if($row){ echo(escape($row['icerik'])); }} ?></li>
                    <li><i class="fa fa-check-circle"></i>
                      <?php if($object){ $row = $object->selectIcerik("pricing3_li4"); if($row){ echo(escape($row['icerik'])); }} ?></li>
                    <li><i class="fa fa-check-circle"></i>
                      <?php if($object){ $row = $object->selectIcerik("pricing3_li5"); if($row){ echo(escape($row['icerik'])); }} ?></li>
                </ul>
              </div>
              <div class="price-button">
                <a class="buy-btn" href="#">KİRALA</a>
              </div>
          </div>
        </div>

        <div class="col-md-3">
          <div class="single-price">
            <div class="price-head">
              <h2><?php if($object){ $row = $object->selectIcerik("pricing4_baslik"); if($row){ echo(escape($row['icerik'])); }} ?></h2>
              <p><?php if($object){ $row = $object->selectIcerik("pricing4_fiyat"); if($row){ echo(escape($row['icerik'])); }} ?>₺ <span>den başlayan fiyatlar</span></p>
              </div>
              <div class="price-content">
                <ul>
                  <li><i class="fa fa-check-circle"></i>
                    <?php if($object){ $row = $object->selectIcerik("pricing4_li1"); if($row){ echo(escape($row['icerik'])); }} ?></li>
                  <li><i class="fa fa-check-circle"></i>
                    <?php if($object){ $row = $object->selectIcerik("pricing4_li2"); if($row){ echo(escape($row['icerik'])); }} ?></li>
                  <li><i class="fa fa-check-circle"></i>
                    <?php if($object){ $row = $object->selectIcerik("pricing4_li3"); if($row){ echo(escape($row['icerik'])); }} ?></li>
                    <li><i class="fa fa-check-circle"></i>
                      <?php if($object){ $row = $object->selectIcerik("pricing4_li4"); if($row){ echo(escape($row['icerik'])); }} ?></li>
                    <li><i class="fa fa-check-circle"></i>
                      <?php if($object){ $row = $object->selectIcerik("pricing4_li5"); if($row){ echo(escape($row['icerik'])); }} ?></li>
                </ul>
              </div>
              <div class="price-button">
                <a class="buy-btn" href="#">KİRALA</a>
              </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- PRICING SECTION ENDS HERE -->

  <!-- TESTIMONIALS SECTION STARTS HERE -->
  <section id="testimonials">
    <div class="container">
      <h1><?php if($object){ $row = $object->selectIcerik("testimonials_baslik"); if($row){ echo(escape($row['icerik'])); }} ?></h1>
      <div class="row" style="padding-top: 40px;">
        <div class="col-md-4 text-center">
          <div class="profile">
            <img src="<?php if($object){ $row = $object->selectIcerik("testimonial1_resim"); if($row){ echo(escape($row['icerik'])); }} ?>" class="user" alt="">
            <blockquote><?php if($object){ $row = $object->selectIcerik("testimonial1_paragraf"); if($row){ echo(escape($row['icerik'])); }} ?></blockquote>
              <h3><?php if($object){ $row = $object->selectIcerik("testimonial1_isim"); if($row){ echo(escape($row['icerik'])); }} ?> <span><?php if($object){ $row = $object->selectIcerik("testimonial1_pozisyon"); if($row){ echo($row['icerik']); }} ?></span></h3>
          </div>
        </div>
        <div class="col-md-4 text-center">
          <div class="profile">
            <img src="<?php if($object){ $row = $object->selectIcerik("testimonial2_resim"); if($row){ echo(escape($row['icerik'])); }} ?>" class="user" alt="">
            <blockquote><?php if($object){ $row = $object->selectIcerik("testimonial2_paragraf"); if($row){ echo(escape($row['icerik'])); }} ?></blockquote>
              <h3><?php if($object){ $row = $object->selectIcerik("testimonial2_isim"); if($row){ echo(escape($row['icerik'])); }} ?> <span><?php if($object){ $row = $object->selectIcerik("testimonial2_pozisyon"); if($row){ echo($row['icerik']); }} ?></span></h3>
          </div>
        </div>
        <div class="col-md-4 text-center">
          <div class="profile">
            <img src="<?php if($object){ $row = $object->selectIcerik("testimonial3_resim"); if($row){ echo(escape($row['icerik'])); }} ?>" class="user" alt="">
            <blockquote><?php if($object){ $row = $object->selectIcerik("testimonial3_paragraf"); if($row){ echo(escape($row['icerik'])); }} ?></blockquote>
              <h3><?php if($object){ $row = $object->selectIcerik("testimonial3_isim"); if($row){ echo(escape($row['icerik'])); }} ?> <span><?php if($object){ $row = $object->selectIcerik("testimonial3_pozisyon"); if($row){ echo($row['icerik']); }} ?></span></h3>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- TESTIMONIALS SECTION ENDS HERE -->

  <!-- GET IN TOUCH SECTION STARTS HERE -->
  <section id="contact">
    <div class="container">
      <h1>Bize Ulaşın</h1>
      <div class="row">
        <div class="col-md-6">
          <form id="contactForm" onsubmit="event.preventDefault(); validateContactForm();" class="contact-form" action="index.php" method="post">
            <div class="form-group">
              <input class="form-control" required="required" type="text" name="ad" value="" placeholder="Ad" id="ad">
            </div>
            <div class="form-group">
              <input class="form-control" required="required" type="number" name="telefon" value="" placeholder="Telefon" id="telefon">
            </div>
            <div class="form-group">
              <input class="form-control" required="required" type="email" name="email" value="" placeholder="E mail" id="email">
            </div>
            <div class="form-group">
              <textarea class="form-control" required="required" placeholder="Mesaj" name="mesaj" rows="4" id="mesaj"></textarea>
            </div>
            <button name="btn_contact" type="submit" style="margin-left: 70px;" class="btn btn-primary">Gönder</button>
            <input type="hidden" name="gonder" value="0">
          </form>
        </div>
        <div class="col-md-6 contact-info">
          <div class="follow"><i class="fa fa-map-marker"></i><b> Adres:</b> XYZ Caddesi, Ankara, TR
          </div>
          <div class="follow"><i class="fa fa-phone"></i><b> Telefon: </b>0111 111 11 11
          </div>
          <div class="follow"><i class="fa fa-envelope"></i><b> Email: </b>arabakirala@mail.com
          </div>
          <div class="follow"> <label><b>Bizi Takip Edin : </b></label>
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-instagram"></i></a>
            <a href="#"><i class="fa fa-twitter"></i></a>
            <a href="#"><i class="fa fa-linkedin"></i></a>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- GET IN TOUCH SECTION ENDS HERE -->

  <?php
    include 'footer.php';
  ?>

  <!-- FOR SMOOTH SCROLL -->
  <script src="js/smooth-scroll.js"></script>
  <script>
	var scroll = new SmoothScroll('a[href*="#"]');
  </script>
</body>

</html>
<?php
  function escape($string){
    return htmlspecialchars($string, ENT_QUOTES);
  }

  if (isset($_GET['suc'])) {
    echo("<script> alert('Siparişiniz alındı. En kısa sürede size ulaşacağız.'); </script>");
  }
?>
