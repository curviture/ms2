<?php
function ValidateEmail($email)
{
    $pattern = '/^([0-9a-z]([-.\w]*[0-9a-z])*@(([0-9a-z])+([-\w]*[0-9a-z])*\.)+[a-z]{2,6})$/i';
    return preg_match($pattern, $email);
};

// function wget_request($url, $post_array, $check_ssl=true) {
//     $cmd = "curl -X POST -H 'Content-Type: application/json'";
//     $cmd.= " -d '" . json_encode($post_array) . "' '" . $url . "'";
//      if (!$check_ssl){
//         $cmd.= "'  --insecure";
//      }
//      $cmd .= " > /dev/null 2>&1 &";
//     exec($cmd, $output, $exit);
//     return $exit == 0;
//   }
//   if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formid'])){
//     $_POST['viewId'] = $_COOKIE['lptracker_view_id'];
//     wget_request('https://arniko-store.ru/api/api.php', $_POST);
//   }
  


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formid']) && $_POST['formid'] == 'form_contact_us')
{
   $mailto = 'info@gsquare.site';
   $mailfrom = isset($_POST['email']) ? $_POST['email'] : $mailto;
   $mailcc = 'gevghazaryan@gmail.com';
   $subject = 'Заявка на консультацию (Arniko)';
   $message = 'Контактные данные:';
   $success_url = './success_divan.php';
   $error_url = './divany.php';
   $eol = "\n";
   $error = '';
   $internalfields = array ("submit", "reset", "send", "filesize", "formid", "captcha_code", "recaptcha_challenge_field", "recaptcha_response_field", "g-recaptcha-response");
   $boundary = md5(uniqid(time()));
   $header  = 'From: '.$mailfrom.$eol;
   $header .= 'Reply-To: '.$mailfrom.$eol;
   $header .= 'Cc: '.$mailcc.$eol;
   $header .= 'MIME-Version: 1.0'.$eol;
   $header .= 'Content-Type: multipart/mixed; boundary="'.$boundary.'"'.$eol;
   $header .= 'X-Mailer: PHP v'.phpversion().$eol;
   try
   {
      if (!ValidateEmail($mailfrom))
      {
         $error .= "The specified email address (" . $mailfrom . ") is invalid!\n<br>";
         throw new Exception($error);
      }
      $message .= $eol;
      $message .= "IP Address : ";
      $message .= $_SERVER['REMOTE_ADDR'];
      $message .= $eol;
      foreach ($_POST as $key => $value)
      {
         if (!in_array(strtolower($key), $internalfields))
         {
            if (!is_array($value))
            {
               $message .= ucwords(str_replace("_", " ", $key)) . " : " . $value . $eol;
            }
            else
            {
               $message .= ucwords(str_replace("_", " ", $key)) . " : " . implode(",", $value) . $eol;
            }
         }
      }
      $body  = 'This is a multi-part message in MIME format.'.$eol.$eol;
      $body .= '--'.$boundary.$eol;
      $body .= 'Content-Type: text/plain; charset=UTF-8'.$eol;
      $body .= 'Content-Transfer-Encoding: 8bit'.$eol;
      $body .= $eol.stripslashes($message).$eol;
      if (!empty($_FILES))
      {
         foreach ($_FILES as $key => $value)
         {
             if ($_FILES[$key]['error'] == 0)
             {
                $body .= '--'.$boundary.$eol;
                $body .= 'Content-Type: '.$_FILES[$key]['type'].'; name='.$_FILES[$key]['name'].$eol;
                $body .= 'Content-Transfer-Encoding: base64'.$eol;
                $body .= 'Content-Disposition: attachment; filename='.$_FILES[$key]['name'].$eol;
                $body .= $eol.chunk_split(base64_encode(file_get_contents($_FILES[$key]['tmp_name']))).$eol;
             }
         }
      }
      $body .= '--'.$boundary.'--'.$eol;
      if ($mailto != '')
      {
         mail($mailto, $subject, $body, $header);
      }
      header('Location: '.$success_url);
   }
   catch (Exception $e)
   {
      $errorcode = file_get_contents($error_url);
      $replace = "##error##";
      $errorcode = str_replace($replace, $e->getMessage(), $errorcode);
      echo $errorcode;
   }
   exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formid']) && $_POST['formid'] == 'form_navbar')
{
   $mailto = 'info@gsquare.site';
   $mailfrom = isset($_POST['email']) ? $_POST['email'] : $mailto;
   $mailcc = 'gevghazaryan@gmail.com';
   $subject = 'Заявка на заказ обратного звонка (Arniko)';
   $message = 'Контактные данные:';
   $success_url = './success_divany.php';
   $error_url = './index.php';
   $eol = "\n";
   $error = '';
   $internalfields = array ("submit", "reset", "send", "filesize", "formid", "captcha_code", "recaptcha_challenge_field", "recaptcha_response_field", "g-recaptcha-response");
   $boundary = md5(uniqid(time()));
   $header  = 'From: '.$mailfrom.$eol;
   $header .= 'Reply-To: '.$mailfrom.$eol;
   $header .= 'Cc: '.$mailcc.$eol;
   $header .= 'MIME-Version: 1.0'.$eol;
   $header .= 'Content-Type: multipart/mixed; boundary="'.$boundary.'"'.$eol;
   $header .= 'X-Mailer: PHP v'.phpversion().$eol;
   try
   {
      if (!ValidateEmail($mailfrom))
      {
         $error .= "The specified email address (" . $mailfrom . ") is invalid!\n<br>";
         throw new Exception($error);
      }
      $message .= $eol;
      $message .= "IP Address : ";
      $message .= $_SERVER['REMOTE_ADDR'];
      $message .= $eol;
      foreach ($_POST as $key => $value)
      {
         if (!in_array(strtolower($key), $internalfields))
         {
            if (!is_array($value))
            {
               $message .= ucwords(str_replace("_", " ", $key)) . " : " . $value . $eol;
            }
            else
            {
               $message .= ucwords(str_replace("_", " ", $key)) . " : " . implode(",", $value) . $eol;
            }
         }
      }
      $body  = 'This is a multi-part message in MIME format.'.$eol.$eol;
      $body .= '--'.$boundary.$eol;
      $body .= 'Content-Type: text/plain; charset=UTF-8'.$eol;
      $body .= 'Content-Transfer-Encoding: 8bit'.$eol;
      $body .= $eol.stripslashes($message).$eol;
      if (!empty($_FILES))
      {
         foreach ($_FILES as $key => $value)
         {
             if ($_FILES[$key]['error'] == 0)
             {
                $body .= '--'.$boundary.$eol;
                $body .= 'Content-Type: '.$_FILES[$key]['type'].'; name='.$_FILES[$key]['name'].$eol;
                $body .= 'Content-Transfer-Encoding: base64'.$eol;
                $body .= 'Content-Disposition: attachment; filename='.$_FILES[$key]['name'].$eol;
                $body .= $eol.chunk_split(base64_encode(file_get_contents($_FILES[$key]['tmp_name']))).$eol;
             }
         }
      }
      $body .= '--'.$boundary.'--'.$eol;
      if ($mailto != '')
      {
         mail($mailto, $subject, $body, $header);
      }
      header('Location: '.$success_url);
   }
   catch (Exception $e)
   {
      $errorcode = file_get_contents($error_url);
      $replace = "##error##";
      $errorcode = str_replace($replace, $e->getMessage(), $errorcode);
      echo $errorcode;
   }
   exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formid']) && $_POST['formid'] == 'form_consult_krovat')
{
   $mailto = 'info@gsquare.site';
   $mailfrom = isset($_POST['email']) ? $_POST['email'] : $mailto;
   $mailcc = 'gevghazaryan@gmail.com';
   $subject = 'Заявка на рачет стоимости кровати (Arniko)';
   $message = 'Контактные данные:';
   $success_url = './success_divany.php';
   $error_url = './index.php';
   $eol = "\n";
   $error = '';
   $internalfields = array ("submit", "reset", "send", "filesize", "formid", "captcha_code", "recaptcha_challenge_field", "recaptcha_response_field", "g-recaptcha-response");
   $boundary = md5(uniqid(time()));
   $header  = 'From: '.$mailfrom.$eol;
   $header .= 'Reply-To: '.$mailfrom.$eol;
   $header .= 'Cc: '.$mailcc.$eol;
   $header .= 'MIME-Version: 1.0'.$eol;
   $header .= 'Content-Type: multipart/mixed; boundary="'.$boundary.'"'.$eol;
   $header .= 'X-Mailer: PHP v'.phpversion().$eol;
   try
   {
      if (!ValidateEmail($mailfrom))
      {
         $error .= "The specified email address (" . $mailfrom . ") is invalid!\n<br>";
         throw new Exception($error);
      }
      $message .= $eol;
      $message .= "IP Address : ";
      $message .= $_SERVER['REMOTE_ADDR'];
      $message .= $eol;
      foreach ($_POST as $key => $value)
      {
         if (!in_array(strtolower($key), $internalfields))
         {
            if (!is_array($value))
            {
               $message .= ucwords(str_replace("_", " ", $key)) . " : " . $value . $eol;
            }
            else
            {
               $message .= ucwords(str_replace("_", " ", $key)) . " : " . implode(",", $value) . $eol;
            }
         }
      }
      $body  = 'This is a multi-part message in MIME format.'.$eol.$eol;
      $body .= '--'.$boundary.$eol;
      $body .= 'Content-Type: text/plain; charset=UTF-8'.$eol;
      $body .= 'Content-Transfer-Encoding: 8bit'.$eol;
      $body .= $eol.stripslashes($message).$eol;
      if (!empty($_FILES))
      {
         foreach ($_FILES as $key => $value)
         {
             if ($_FILES[$key]['error'] == 0)
             {
                $body .= '--'.$boundary.$eol;
                $body .= 'Content-Type: '.$_FILES[$key]['type'].'; name='.$_FILES[$key]['name'].$eol;
                $body .= 'Content-Transfer-Encoding: base64'.$eol;
                $body .= 'Content-Disposition: attachment; filename='.$_FILES[$key]['name'].$eol;
                $body .= $eol.chunk_split(base64_encode(file_get_contents($_FILES[$key]['tmp_name']))).$eol;
             }
         }
      }
      $body .= '--'.$boundary.'--'.$eol;
      if ($mailto != '')
      {
         mail($mailto, $subject, $body, $header);
      }
      header('Location: '.$success_url);
   }
   catch (Exception $e)
   {
      $errorcode = file_get_contents($error_url);
      $replace = "##error##";
      $errorcode = str_replace($replace, $e->getMessage(), $errorcode);
      echo $errorcode;
   }
   exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formid']) && $_POST['formid'] == 'form_order')
{
   $mailto = 'info@gsquare.site';
   $mailfrom = isset($_POST['email']) ? $_POST['email'] : $mailto;
   $mailcc = 'gevghazaryan@gmail.com';
   $subject = 'Заявка на заказ кровати (Arniko)';
   $message = 'Контактные данные:';
   $success_url = './success_divany.php';
   $error_url = './index.php';
   $eol = "\n";
   $error = '';
   $internalfields = array ("submit", "reset", "send", "filesize", "formid", "captcha_code", "recaptcha_challenge_field", "recaptcha_response_field", "g-recaptcha-response");
   $boundary = md5(uniqid(time()));
   $header  = 'From: '.$mailfrom.$eol;
   $header .= 'Reply-To: '.$mailfrom.$eol;
   $header .= 'Cc: '.$mailcc.$eol;
   $header .= 'MIME-Version: 1.0'.$eol;
   $header .= 'Content-Type: multipart/mixed; boundary="'.$boundary.'"'.$eol;
   $header .= 'X-Mailer: PHP v'.phpversion().$eol;
   try
   {
      if (!ValidateEmail($mailfrom))
      {
         $error .= "The specified email address (" . $mailfrom . ") is invalid!\n<br>";
         throw new Exception($error);
      }
      $message .= $eol;
      $message .= "IP Address : ";
      $message .= $_SERVER['REMOTE_ADDR'];
      $message .= $eol;
      foreach ($_POST as $key => $value)
      {
         if (!in_array(strtolower($key), $internalfields))
         {
            if (!is_array($value))
            {
               $message .= ucwords(str_replace("_", " ", $key)) . " : " . $value . $eol;
            }
            else
            {
               $message .= ucwords(str_replace("_", " ", $key)) . " : " . implode(",", $value) . $eol;
            }
         }
      }
      $body  = 'This is a multi-part message in MIME format.'.$eol.$eol;
      $body .= '--'.$boundary.$eol;
      $body .= 'Content-Type: text/plain; charset=UTF-8'.$eol;
      $body .= 'Content-Transfer-Encoding: 8bit'.$eol;
      $body .= $eol.stripslashes($message).$eol;
      if (!empty($_FILES))
      {
         foreach ($_FILES as $key => $value)
         {
             if ($_FILES[$key]['error'] == 0)
             {
                $body .= '--'.$boundary.$eol;
                $body .= 'Content-Type: '.$_FILES[$key]['type'].'; name='.$_FILES[$key]['name'].$eol;
                $body .= 'Content-Transfer-Encoding: base64'.$eol;
                $body .= 'Content-Disposition: attachment; filename='.$_FILES[$key]['name'].$eol;
                $body .= $eol.chunk_split(base64_encode(file_get_contents($_FILES[$key]['tmp_name']))).$eol;
             }
         }
      }
      $body .= '--'.$boundary.'--'.$eol;
      if ($mailto != '')
      {
         mail($mailto, $subject, $body, $header);
      }
      header('Location: '.$success_url);
   }
   catch (Exception $e)
   {
      $errorcode = file_get_contents($error_url);
      $replace = "##error##";
      $errorcode = str_replace($replace, $e->getMessage(), $errorcode);
      echo $errorcode;
   }
   exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Arniko - дивани для вашего комфорта</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Arniko - кровати для крепкого сна с мягкой обивкой от производителя">
    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon">
    <link rel="stylesheet" href="css/fonts.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/divany.css">
    <script src="js/tingle.js"></script>
    <script src="js/imask.js"></script>
</head>

<body>
    <div data-jarallax data-speed="0.5" class="main jarallax" data-parallax="scroll"
        data-image-src="./img/divany/bg10.jpg">
        <!-- <img class="jarallax-img" src="img/bg-1.jpg" alt=""> -->
        <div class="header">
            <div class="header__inner container">
                <div class="logo">
                    <a href="#" class="logo__link" onclick="return false">
                        <img src="img/logo1.png" alt="brand icon image" class="logo__icon">
                    </a>
                </div>
                <div class="header__menu-wrapper">
                    <!-- <a href="#" class="menu__closer u-hidden--tp">
                            &#x2716;
                        </a> -->
                    <ul class="menu menu--header">
                        <li class="menu__item"><a class="menu__link animated-underline js-scrollto"
                                href="#gallery">Диваны</a></li>
                        <li class="menu__item hidden--md visible--lg"><a class="menu__link animated-underline"
                                href="/index.php">Кровати</a></li>
                        <!-- <li class="menu__item"><a class="menu__link animated-underline js-scrollto"
                                href="#looking-for">На заказ</a></li> -->
                        <li class="menu__item"><a class="menu__link animated-underline js-scrollto"
                                href="#showroom">Шоурум</a></li>
                    </ul>
                </div>
                <!-- <div class="header__socials u-visible--tp">
                        <a class="header__link header__link--social" href=""><img class="header__icon header__icon--socials" src="img/insta1.png" alt=""></a>
                        <a class="header__link header__link--social" href=""><img class="header__icon header__icon--socials" src="img/whatsapp.jpg" alt=""></a>
                    </div> -->
                <div class="header__aside">
                    г. Москва, ул. Тимирязевская, д.2/3
                    <br />
                    (ТЦ "Парк 11", 3 этаж)
                </div>
                <div class="header__contacts">
                    <a class="header__link header__link--primary animated-underline" href="tel:+74951429380">+7 (495)
                        142-93-80</a>
                    <input id="call-from-navbar" class="button button--tiny button--secondary" type="button"
                        value="Заказать звонок" data-toggle="modal" data-target="#js-call-navbar">
                </div>
            </div>
        </div>
        <div class="hero">
            <div class="container hero__inner">
                <div class="hero__address u-hidden u-hidden--p">
                    г. Москва, ул. Тимирязевская, д.2/3
                    <br>
                    (ТЦ "Парк 11", 3 этаж)
                </div>
                <div class="hero__content col-2 md-col-1">
                    <h1 class="heading heading--primary hero__heading text-light">
                        Диваны на заказ
                        <br>
                        по дизайн проектам
                        <br>
                        <span>С мягкой обивкой от производителя</span>
                    </h1>
                </div>
                <div class="hero__block col-2 md-col-1">
                    <p class="hero__block-text">
                        Подберем диван под ваш дизайн, либо изготовим по вашему макету или фото и привезем в срок от 3-х
                        дней
                    </p>
                    <div class="hero__icon-container">
                        <img src="img/divany/eskiz-divan.png" alt="">
                    </div>
                    <div class="hero__input-container a-blink">
                        <input type="button" class="hero__cta button button--attention button--large" id="js-main__cta"
                            value="Подобрать и рассчитать" />
                    </div>
                </div>
            </div>
            <a href="#gallery" id="js-scrollto" class="button--slidedown">&darr;</a>
        </div>
    </div>
    <div class="container u-pt--large u-pb u-jc--space-around features">
        <div class="card">
            <div class="card__image-wrapper">
                <img class="card__image a-squeeze a-std" src="img/peim1.png" alt="">
            </div>
            <div class="card__text">
                <h2 class="card__header">Минимальные сроки</h2>
                <p>Минимальный срок изготовления от 3 до 7 дней </p>
            </div>
        </div>
        <div class="card md-col-3 col-3">
            <div class="card__image-wrapper">
                <img class="card__image a-rotate a-std" src="img/peim2.png" alt="">
            </div>
            <div class="card__text">
                <h2 class="card__header">Собственное производство</h2>
                <p>Контроль качества продукции на каждом этапе изготовления</p>
            </div>

        </div>
        <div class="card md-col-3 col-3">
            <div class="card__image-wrapper">
                <img class="card__image a-stretch a-std" src="img/peim3.png" alt="">
            </div>
            <div class="card__text">
                <h2 class="card__header">Изготовление на заказ</h2>
                <p>Изготовим любую модель
                    кровати или дивана по вашему эскизу</p>
            </div>
        </div>
    </div>
    <div class="container" id="gallery">
        <div class="gallery text-dark u-pt u-pb">
            <div class="gallery__header u-jc--space-between">
                <h2 class="heading heading--primary heading--gallery gallery__heading">Популярные модели
                    <br>
                    <span>диванов с мягкой обивкой</span>
                </h2>
                <ul class="gallery__type">
                    <li><a class="button button--transparent gallery__type-button" href="/index.php">Кровати</a></li>
                    <li><a class="button button--transparent active gallery__type-button" href="#" disabled>Диваны</a></li>
                </ul>
            </div>
            <div id="gallery">
                <ul class="gallery__list u-jc--space-between">
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/ambra.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Амбра</h4>
                                <span class="gallery__item-price">80.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/ambra.jpg" data-fslightbox="gallery-0"><img
                                        src="img/divany/models/ambra.jpg" alt=""></a>
                                <a href="img/divany/models/ambra1.jpg" data-fslightbox="gallery-0"><img
                                        src="img/divany/models/ambra1.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-0" data-price="80.000 рублей" data-type="Диван"
                                data-orderName="Амбра" data-img="img/divany/models/ambra.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-0" data-price="80.000 рублей" data-type="Диван"
                                data-orderName="Амбра" data-img="img/divany/models/ambra.jpg">
                        </div>
                    </li>
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/loft.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Loft</h4>
                                <span class="gallery__item-price">110.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/loft.jpg" data-fslightbox="gallery-1"><img
                                        src="img/divany/models/loft.jpg" alt=""></a>
                                <a href="img/divany/models/loft1.jpg" data-fslightbox="gallery-1"><img
                                        src="img/divany/models/loft1.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-1" data-price="110.000 рублей" data-type="Диван"
                                data-orderName="Loft" data-img="img/divany/models/loft.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-1" data-price="110.000 рублей" data-type="Диван"
                                data-orderName="Loft" data-img="img/divany/models/loft.jpg">
                        </div>
                    </li>
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/Neapole.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Neapole</h4>
                                <span class="gallery__item-price">170.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/Neapole.jpg" data-fslightbox="gallery-2"><img
                                        src="img/divany/models/Neapole.jpg" alt=""></a>
                                <a href="img/divany/models/Neapole1.jpg" data-fslightbox="gallery-2"><img
                                        src="img/divany/models/Neapole1.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-2" data-price="170.000 рублей" data-type="Диван"
                                data-orderName="Neapole" data-img="img/divany/models/Neapole.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-2" data-price="170.000 рублей" data-type="Диван"
                                data-orderName="Neapole" data-img="img/divany/models/Neapole.jpg">
                        </div>
                    </li>
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/arnold1.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Арнольд</h4>
                                <span class="gallery__item-price">85.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/arnold.jpg" data-fslightbox="gallery-3"><img
                                        src="img/divany/models/arnold.jpg" alt=""></a>
                                <a href="img/divany/models/arnold1.jpg" data-fslightbox="gallery-3"><img
                                        src="img/divany/models/arnold1.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-3" data-price="85.000 рублей" data-type="Диван"
                                data-orderName="Арнольд" data-img="img/divany/models/arnold.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-3" data-price="85.000 рублей" data-type="Диван"
                                data-orderName="Арнольд" data-img="img/divany/models/arnold.jpg">
                        </div>
                    </li>
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/arflex.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Арфлекс</h4>
                                <span class="gallery__item-price">180.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/arflex.jpg" data-fslightbox="gallery-4"><img
                                        src="img/divany/models/arflex.jpg" alt=""></a>
                                <a href="img/divany/models/arflex1.jpg" data-fslightbox="gallery-4"><img
                                        src="img/divany/models/arflex1.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-4" data-price="180.000 рублей" data-type="Диван"
                                data-orderName="Арфлекс" data-img="img/divany/models/arflex.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-4" data-price="180.000 рублей" data-type="Диван"
                                data-orderName="Арфлекс" data-img="img/divany/models/arflex.jpg">
                        </div>
                    </li>
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/bali.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Бали</h4>
                                <span class="gallery__item-price">130.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/bali.jpg" data-fslightbox="gallery-5"><img
                                        src="img/divany/models/bali.jpg" alt=""></a>
                                <a href="img/divany/models/bali1.jpg" data-fslightbox="gallery-5"><img
                                        src="img/divany/models/bali1.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-5" data-price="130.000 рублей" data-type="Диван"
                                data-orderName="Бали" data-img="img/divany/models/bali.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-5" data-price="130.000 рублей" data-type="Диван"
                                data-orderName="Бали" data-img="img/divany/models/bali.jpg">
                        </div>
                    </li>
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/berlingo1.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Берлинго</h4>
                                <span class="gallery__item-price">140.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/berlingo.jpg" data-fslightbox="gallery-6"><img
                                        src="img/divany/models/berlingo.jpg" alt=""></a>
                                <a href="img/divany/models/berlingo1.jpg" data-fslightbox="gallery-6"><img
                                        src="img/divany/models/berlingo1.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-6" data-price="140.000 рублей" data-type="Диван"
                                data-orderName="Берлинго" data-img="img/divany/models/berlingo.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-6" data-price="140.000 рублей" data-type="Диван"
                                data-orderName="Берлинго" data-img="img/divany/models/berlingo.jpg">
                        </div>
                    </li>
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/vito1.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Вито</h4>
                                <span class="gallery__item-price">140.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/vito.jpg" data-fslightbox="gallery-7"><img
                                        src="img/divany/models/vito.jpg" alt=""></a>
                                <a href="img/divany/models/vito1.jpg" data-fslightbox="gallery-7"><img
                                        src="img/divany/models/vito1.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-7" data-price="140.000 рублей" data-type="Диван"
                                data-orderName="Вито" data-img="img/divany/models/vito.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-7" data-price="140.000 рублей" data-type="Диван"
                                data-orderName="Вито" data-img="img/divany/models/vito.jpg">
                        </div>
                    </li>
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/grand.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Гранд</h4>
                                <span class="gallery__item-price">200.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/grand.11jpg" data-fslightbox="gallery-8"><img
                                        src="img/divany/models/grand.jpg" alt=""></a>
                                <a href="img/divany/models/grand1.jpg" data-fslightbox="gallery-8"><img
                                        src="img/divany/models/grand1.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-8" data-price="200.000 рублей" data-type="Диван"
                                data-orderName="Гранд" data-img="img/divany/models/grand.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-8" data-price="200.000 рублей" data-type="Диван"
                                data-orderName="Гранд" data-img="img/divany/models/grand.jpg">
                        </div>
                    </li>
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/dante.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Данте</h4>
                                <span class="gallery__item-price">85.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/dante.jpg" data-fslightbox="gallery-9"><img
                                        src="img/divany/models/dante.jpg" alt=""></a>
                                <a href="img/divany/models/dante1.jpg" data-fslightbox="gallery-9"><img
                                        src="img/divany/models/dante1.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-9" data-price="85.000 рублей" data-type="Диван"
                                data-orderName="Данте" data-img="img/divany/models/dante.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-9" data-price="85.000 рублей" data-type="Диван"
                                data-orderName="Данте" data-img="img/divany/models/dante.jpg">
                        </div>
                    </li>
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/camaleonda.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Камалеонда</h4>
                                <span class="gallery__item-price">165.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/camaleonda.jpg" data-fslightbox="gallery-10"><img
                                        src="img/divany/models/camaleonda.jpg" alt=""></a>
                                <a href="img/divany/models/camaleonda1.jpg" data-fslightbox="gallery-10"><img
                                        src="img/divany/models/camaleonda1.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-10" data-price="165.000 рублей" data-type="Диван"
                                data-orderName="Камалеонда" data-img="img/divany/models/camaleonda.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-10" data-price="165.000 рублей" data-type="Диван"
                                data-orderName="Камалеонда" data-img="img/divany/models/camaleonda.jpg">
                        </div>
                    </li>
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/lando.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Ландо</h4>
                                <span class="gallery__item-price">180.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/lando.jpg" data-fslightbox="gallery-11"><img
                                        src="img/divany/models/lando.jpg" alt=""></a>
                                <a href="img/divany/models/lando1.jpg" data-fslightbox="gallery-11"><img
                                        src="img/divany/models/lando1.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-11" data-price="180.000 рублей" data-type="Диван"
                                data-orderName="Ландо" data-img="img/divany/models/lando.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-11" data-price="180.000 рублей" data-type="Диван"
                                data-orderName="Ландо" data-img="img/divany/models/lando.jpg">
                        </div>
                    </li>
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/launge.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Лаундж</h4>
                                <span class="gallery__item-price">135.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/launge.jpg" data-fslightbox="gallery-12"><img
                                        src="img/divany/models/launge.jpg" alt=""></a>
                                <a href="img/divany/models/launge1.jpg" data-fslightbox="gallery-12"><img
                                        src="img/divany/models/launge1.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-12" data-price="135.000 рублей" data-type="Диван"
                                data-orderName="Лаундж" data-img="img/divany/models/launge.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-12" data-price="135.000 рублей" data-type="Диван"
                                data-orderName="Лаундж" data-img="img/divany/models/launge.jpg">
                        </div>
                    </li>
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/loftangle.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Лофт угловой</h4>
                                <span class="gallery__item-price">170.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/loftangle.jpg" data-fslightbox="gallery-13"><img
                                        src="img/divany/models/loftangle.jpg" alt=""></a>
                                <a href="img/divany/models/loftangle1.jpg" data-fslightbox="gallery-13"><img
                                        src="img/divany/models/loftangle1.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-13" data-price="170.000 рублей" data-type="Диван"
                                data-orderName="Лофт угловой" data-img="img/divany/models/loftangle.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-13" data-price="170.000 рублей" data-type="Диван"
                                data-orderName="Лофт угловой" data-img="img/divany/models/loftangle.jpg">
                        </div>
                    </li>
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/notti.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Нотти</h4>
                                <span class="gallery__item-price">140.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/notti.jpg" data-fslightbox="gallery-14"><img
                                        src="img/divany/models/notti.jpg" alt=""></a>
                                <a href="img/divany/models/notti1.jpg" data-fslightbox="gallery-14"><img
                                        src="img/divany/models/notti1.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-14" data-price="140.000 рублей" data-type="Диван"
                                data-orderName="Нотти" data-img="img/divany/models/notti.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-14" data-price="140.000 рублей" data-type="Диван"
                                data-orderName="Нотти" data-img="img/divany/models/notti.jpg">
                        </div>
                    </li>
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/monica.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Моника</h4>
                                <span class="gallery__item-price">90.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/monica.jpg" data-fslightbox="gallery-15"><img
                                        src="img/divany/models/monica.jpg" alt=""></a>
                                <a href="img/divany/models/monica1.jpg" data-fslightbox="gallery-15"><img
                                        src="img/divany/models/monica1.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-15" data-price="90.000 рублей" data-type="Диван"
                                data-orderName="Моника" data-img="img/divany/models/monica.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-15" data-price="90.000 рублей" data-type="Диван"
                                data-orderName="Моника" data-img="img/divany/models/monica.jpg">
                        </div>
                    </li>
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/stake.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Стейк</h4>
                                <span class="gallery__item-price">171.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/stake.jpg" data-fslightbox="gallery-16"><img
                                        src="img/divany/models/stake.jpg" alt=""></a>
                                <a href="img/divany/models/stake1.jpg" data-fslightbox="gallery-16"><img
                                        src="img/divany/models/stake1.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-16" data-price="170.000 рублей" data-type="Диван"
                                data-orderName="Стейк" data-img="img/divany/models/stake.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-16" data-price="170.000 рублей" data-type="Диван"
                                data-orderName="Стейк" data-img="img/divany/models/stake.jpg">
                        </div>
                    </li>
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/fama.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Фама</h4>
                                <span class="gallery__item-price">150.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/fama.jpg" data-fslightbox="gallery-17"><img
                                        src="img/divany/models/fama.jpg" alt=""></a>
                                <a href="img/divany/models/fama1.jpg" data-fslightbox="gallery-17"><img
                                        src="img/divany/models/fama1.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-17" data-price="150.000 рублей" data-type="Диван"
                                data-orderName="Фама" data-img="img/divany/models/fama.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-17" data-price="150.000 рублей" data-type="Диван"
                                data-orderName="Фама" data-img="img/divany/models/fama.jpg">
                        </div>
                    </li>
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/elios.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Элиос</h4>
                                <span class="gallery__item-price">150.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/elios.jpg" data-fslightbox="gallery-18"><img
                                        src="img/divany/models/elios.jpg" alt=""></a>
                                <a href="img/divany/models/elios1.jpg" data-fslightbox="gallery-18"><img
                                        src="img/divany/models/elios1.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-18" data-price="150.000 рублей" data-type="Диван"
                                data-orderName="Элиос" data-img="img/divany/models/elios.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-18" data-price="150.000 рублей" data-type="Диван"
                                data-orderName="Элиос" data-img="img/divany/models/elios.jpg">
                        </div>
                    </li>
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/state.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Стейт</h4>
                                <span class="gallery__item-price">130.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/state.jpg" data-fslightbox="gallery-19"><img
                                        src="img/divany/models/state.jpg" alt=""></a>
                                <a href="img/divany/models/state1.jpg" data-fslightbox="gallery-19"><img
                                        src="img/divany/models/state1.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-19" data-price="130.000 рублей" data-type="Диван"
                                data-orderName="Стейт" data-img="img/divany/models/state.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-19" data-price="130.000 рублей" data-type="Диван"
                                data-orderName="Стейт" data-img="img/divany/models/state.jpg">
                        </div>
                    </li>
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/chase.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Чейз</h4>
                                <span class="gallery__item-price">100.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/chase.jpg" data-fslightbox="gallery-20"><img
                                        src="img/divany/models/chase.jpg" alt=""></a>
                                <a href="img/divany/models/chase1.jpg" data-fslightbox="gallery-20"><img
                                        src="img/divany/models/chase1.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-20" data-price="100.000 рублей" data-type="Диван"
                                data-orderName="Чейз" data-img="img/divany/models/chase.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-20" data-price="100.000 рублей" data-type="Диван"
                                data-orderName="Чейз" data-img="img/divany/models/chase.jpg">
                        </div>
                    </li>
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/arina.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Арина</h4>
                                <span class="gallery__item-price">100.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/arina.jpg" data-fslightbox="gallery-21"><img
                                        src="img/divany/models/arina.jpg" alt=""></a>
                                <a href="img/divany/models/arina1.jpg" data-fslightbox="gallery-21"><img
                                        src="img/divany/models/arina1.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-21" data-price="100.000 рублей" data-type="Диван"
                                data-orderName="Арина" data-img="img/divany/models/arina.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-21" data-price="100.000 рублей" data-type="Диван"
                                data-orderName="Арина" data-img="img/divany/models/arina.jpg">
                        </div>
                    </li>
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/molteni1.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Молтени</h4>
                                <span class="gallery__item-price">110.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/molteni1.jpg" data-fslightbox="gallery-22"><img
                                        src="img/divany/models/molteni1.jpg" alt=""></a>
                                <a href="img/divany/models/molteni.jpg" data-fslightbox="gallery-22"><img
                                        src="img/divany/models/molteni.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-22" data-price="100.000 рублей" data-type="Диван"
                                data-orderName="Твин" data-img="img/divany/models/twin.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-22" data-price="100.000 рублей" data-type="Диван"
                                data-orderName="Твин" data-img="img/divany/models/twin.jpg">
                        </div>
                    </li>
                    <li class="gallery__item">
                        <div class="gallery__item-img"><img src="img/divany/models/twin1.jpg" alt=""></div>
                        <div class="gallery__item-content">
                            <div class="gallery__item-info">
                                <span class="gallery__item-type">Диван</span>
                                <h4 class="gallery__item-name">Твин</h4>
                                <span class="gallery__item-price">100.000 рублей</span>
                            </div>
                            <div class="gallery__item-thumbs">
                                <a href="img/divany/models/twin.jpg" data-fslightbox="gallery-23"><img
                                        src="img/divany/models/twin.jpg" alt=""></a>
                                <a href="img/divany/models/twin1.jpg" data-fslightbox="gallery-23"><img
                                        src="img/divany/models/twin1.jpg" alt=""></a>
                            </div>
                        </div>
                        <div class="gallery__item-ordering">
                            <input type="button" value="Подробнее"
                                class="button button--transparent js-more-trigger button--bordered"
                                data-target="modal-22" data-price="100.000 рублей" data-type="Диван"
                                data-orderName="Твин" data-img="img/divany/models/twin.jpg">
                            <input type="button" value="Заказать" class="button button--attention js-order"
                                data-target="modal-22" data-price="100.000 рублей" data-type="Диван"
                                data-orderName="Твин" data-img="img/divany/models/twin.jpg">
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div id="looking-for" data-jarallax data-speed="0.5"
        class="jarallax looking-for js-parallax std-blck std-blck--bg js-parallax"
        data-image-src="./img/divany/bg14.jpg">
        <div class="std-blck__inner u-jc--space-between">
            <div class="std-blck__full">
                <h2 class="heading heading--secondary looking-for__heading">
                    <span class="u-block u-block--p">Не нашли понравившуюся модель</span>
                    <span class="u-block u-block--p"> дивана в нашем каталоге?</span>
                </h2>
            </div>
            <div class="std-blck__left looking-for__content">
                <p class="col-2 md-col-1 looking-for__text">Нечего страшного, мы изготовим для вас <br> кровать по
                    вашему макету или просто фото.</p>
                <div class="looking-for__img-box u-hidden--tl">
                    <img class="col-2 md-col-1 looking-for__img" src="img/divany/eskiz-divan2.png" alt="">
                </div>
                <div class="input-container">
                    <button type="button" class="button button--attention button--large looking-for__cta a-blink"
                        id="js-looking-for__cta">Отправить макет или фото</button>
                </div>
            </div>
            <div class="std-blck__right">
                <div class="looking-for__img-box u-visible--tl">
                    <img class="col-2 md-col-1 looking-for__img" src="img/divany/eskiz-divan2.png" alt="">
                </div>
            </div>
        </div>
    </div>
    <div class="your-choice">
        <div class="container">
            <div class="your-choice__content col-4">
                <h2 class="heading heading--primary">Вы можете выбрать</h2>
                <p>любой вариант комплектации, материала и обивки дивана</p>
            </div>
            <ul class="your-choice__card-container">
                <li class="your-choice__card">

                    <div class="your-choice__image-box">
                        <img src="img/divany/variant6.jpg" alt="">
                    </div>
                    <p>Варианты ткани<br>
                        и обивки</p>
                </li>
                <li class="your-choice__card">
                    <div class="your-choice__image-box">
                        <img src="img/divany/variant9.jpg" alt="">
                    </div>
                    <p>Раскладывающийся<br>
                        механизм<br>
                    </p>
                </li>
                <li class="your-choice__card">
                    <div class="your-choice__image-box">
                        <img src="img/divany/variant10.jpg" alt="">
                    </div>
                    <p>Фурнитуру и<br>
                        ножки
                    </p>
                </li>
            </ul>
        </div>
    </div>
    <div id="showroom" class="showroom std-blck">
        <div class="std-blck__inner">
            <div id="showroom__heading2" class="hidden--lg">

            </div>
            <div class="std-blck__left showroom__text-content">
                <div>
                    <h2 class="heading heading--secondary js-move showroom__heading" data-moveto="showroom__heading2"
                        data-screen="1200">Мы ждем вас в нашем Шоуруме</h2>
                </div>
                <p class="showroom__text">Где вы сможете в живую увидеть наши модели кроватей, пообщаться с
                    консультантами и выбрать подходящую для вас модель!</p>
                <div class="showroom__address u-visible--tl">
                    <img class="showroom__address-icon" src="img/map.png" alt="" class="map-icon">
                    <p class="showroom__text showroom__text--small">Москва, ул. Тимирязевская, д.2/3 <br>
                        (ТЦ "Парк 11", 3 этаж)</p>
                </div>
            </div>
            <div class="std-blck__right showroom__img-content">
                <!-- <a class="showroom__img-link showroom__img-link--large" href="/img/showroom4.jpg" data-fslightbox="gallery">
                        <img src="img/showroom4.jpg" alt="" class="showroom__img showroom__img--large">
                    </a>
                    <div class="showroom__thumbs">
                        <a href="img/showroom4.jpg" data-fslightbox="gallery" class="js-showroom-modaal"><img class="showroom__img--thumbs" src="img/showroom4.jpg" alt=""></a>
                        <a href="img/showroom5.jpg" data-fslightbox="gallery" class="js-showroom-modaal"><img class="showroom__img--thumbs js-showroom-modaal" src="img/showroom5.jpg" alt=""></a>
                    </div> -->
                <div class="showroom__images">
                    <a href="img/showroom5.jpg" class="showroom__img-link" data-fslightbox="gallery-show">
                        <img src="img/showroom5.jpg" alt="" class="showroom__image">
                    </a>
                    <a href="img/showroom6.jpg" class="showroom__img-link" data-fslightbox="gallery-show">
                        <img src="img/showroom6.jpg" alt="" class="showroom__image">
                    </a>
                    <a href="img/showroom7.jpg" class="showroom__img-link" data-fslightbox="gallery-show">
                        <img src="img/showroom7.jpg" alt="" class="showroom__image">
                    </a>
                </div>

            </div>
            <div class="showroom__address u-hidden--tl">
                <img class="showroom__address-icon" src="img/map.png" alt="" class="map-icon">
                <p class="showroom__text showroom__text--small">Москва, ул. Тимирязевская, д.2/3 <br>
                    (ТЦ "Парк 11", 3 этаж)</p>
            </div>
        </div>
    </div>
    <div data-jarallax data-speed="0.5" class="jarallax manufacturer text-light js-parallax" data-parallax="scroll"
        data-image-src="./img/divany/bg15.webp">
        <div class="container manufacturer__inner">
            <div class="manufacturer__img-box">
                <img src="img/cog.png" alt="">
            </div>
            <div class="manufacturer__text">
                <h2 class="heading heading--tertiary">Мы производители</h2>
                <p class="manufacturer__par">Арнико это прямой фабричный производитель мягкой мебели на заказ по любым
                    размерам, эскизам и фото.</p>
            </div>
        </div>
    </div>
    <div class="designer std-blck">
        <div class="container">
            <div class="std-blck__left">
                <h2 class="heading heading--secondary designer__heading u-hidden--tl">Вы дизайнер?</h2>
                <img class="designer__image" src="img/divany/dizayn.png" alt="bed-design">
            </div>
            <div class="std-blck__right">
                <h2 class="heading heading--secondary designer__heading u-visible--tl">Вы дизайнер?</h2>
                <p class="">В таком случае, у вас есть возможность стать нашим партнёром и получать 10% (вознаграждение)
                    от стоимости кровати за каждого приведённого клиента</p>
                <div class="designer__footer">
                    <img class="designer__icon u-fl" src="img/divany/couch.png" alt="">
                    <p class="text-small">
                        Мы предоставим вам 3D <br> модели всех наших диванов
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div data-jarallax data-speed="0.5" class="jarallax std-blck std-blck--bg contact-us js-parallax"
        data-parallax="scroll" data-image-src="./img/bg-7.jpg">
        <div class="container contact-us__inner u-jc--space-around">
            <div class="contact-us__text-content">
                <h2 class="heading heading--primary contact-us__heading">Свяжитесь с нами!</h2>
                <p class="contact-us__text">
                    Наши менеджеры подробно<br> проконсультируют вас по выбору<br> кровати и просчитают любое
                    изделие<br> на заказ в течение 15 минут
                </p>
            </div>
            <div class="form__container">
                <form method="post" class="form form--small js-form" action="<? echo basename(__FILE__); ?>" accepth-charset="UTF-8" id="form-contact-us">
                    <input type="hidden" name="action_taken_from" value="Форма заявки перед футером(диван)">
                    <input type="hidden" name="formid" value="form_contact_us">
                    <legend class="form__legend">Введите свои данные и наш<br>
                        специалист свяжется с вами</legend>
                    <div class="input__group">
                        <input class="contact-us__input contact-us__input--text input" type="text"
                            placeholder="Введите свое имя" name="userName" required>
                    </div>
                    <div class="input__group">
                        <input id="js-contact-us__phone"
                            class="js-phone__input contact-us__input contact-us__input--password input" type="text"
                            name="userPhone" placeholder="Введите свой телефон" required
                            pattern="\+7\([0-9]{3}\)[0-9]{3}-[0-9]{2}-[0-9]{2}">
                    </div>
                    <div class="input__group a-blink">
                        <input type="submit" class="button button--attention button--block contact-us__cta"
                            value="Получить консультацию">
                    </div>
                    <div class="input__group">
                        <input type="checkbox" id="check" class="contact-us__checkbox form__agreement"
                            value="hello" placeholder="hello" required checked>
                        <label for="check" class="contact-us__label  form__agreement-label">
                            Нажимая на кнопку "Получить консультацию", я даю согласие на обработку персональных данных и
                            соглашаюсь c условиями политики конфиденциальности
                        </label>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <footer class="footer u-pt">
        <div class="footer__inner">
            <div class="footer__icon-container">
                <a href="" class="footer__icon-link">
                    <img class="footer__icon-img" src="img/logo.png" alt="">
                </a>
            </div>
            <div class="footer__contacts">
                <div class="footer__info-line">
                    <a class="animated-underline footer__link" href="#">+7 (495) 142-93-80</a>
                </div>
                <div class="footer__info-line info-section--socials">
                    <a class="animated-underline footer__link" href="#">+7 (495) 142-93-80</a>
                    <a href="https://viber.click/79775199267" class="footer__link--icon" target="_blank"
                        rel="noopener noreferrer">
                        <i class="icon viber"></i>
                    </a>
                    <a href="https://wa.me/79775199267" class="footer__link--icon" target="_blank"
                        rel="noopener noreferrer">
                        <i class="icon whatsapp"></i>
                    </a>
                </div>
                <div class="footer__info-line">
                    <p class="address footer__text footer__text--address">
                        г. Москва, ул. Тимирязевская, д.2/3 <br> (ТЦ "Парк 11", 3 этаж)
                    </p>
                    <a href="https://www.instagram.com/Arnikomeb/"
                        class="footer__link footer__link--icon footer__link--block" target="_blank"
                        rel="noopener noreferrer">
                        <img src="img/insta1.png" alt="instagram">
                    </a>
                </div>
            </div>
            <div class="footer__map-container u-map__container">
                <script type="text/javascript" charset="utf-8" async
                    src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3Abfd18d127c1ae4253d27aaa7cdae43f5a4446af54a0dee7189da9cedd8f25937&amp;lang=en_FR&amp;scroll=true"></script>

            </div>
            <div class="footer__copyright">
                Copyright © 2021 Gevorg Ghazaryan
            </div>
        </div>
    </footer>
    <!--fixed header-->
    <div class="header header--fixed">
        <div class="header__inner container">
            <a href="#" class="hidden--tp menu__trigger" id="js-menu__trigger" onclick="return false">
                <span class="line"></span>
                <span class="line"></span>
                <span class="line"></span>
            </a>
            <div class="logo">
                <a href="">
                    <img src="img/logo.png" alt="brand icon image" class="logo__icon">
                </a>
            </div>
            <div class="menu__box" id="js-menu__totrigger">
                <a href="#" class="menu__closer u-hidden--tp" id="js-menu__closer">
                    &#x2716;
                </a>
                <ul class="menu menu--header">
                    <li class="menu__item js-f-menuitem"><a class="menu__link animated-underline"
                            href="#gallery">Диваны</a></li>
                    <li class="menu__item js-f-menuitem"><a class="menu__link animated-underline"
                            href="/index.php">Кровати</a></li>
                    <li class="menu__item js-f-menuitem u-hidden--tl"><a
                            class="menu__link animated-underline u-hidden--tl" href="#">На заказ</a></li>
                    <li class="menu__item js-f-menuitem"><a class="menu__link animated-underline"
                            href="#showroom">Шоурум</a></li>
                </ul>
            </div>
            <div class="header__aside header__address u-visible--p">
                г. Москва, ул. Тимирязевская, д.2/3
                <br />
                (ТЦ "Парк 11", 3 этаж)
            </div>
            <div class="header__aside">
                <a class="header__link header__link--primary animated-underline" href="tel:+74951429380">+7 (495)
                    142-93-80</a>
                <a class="header__link header__link--secondary animated-underline" id="call-from-fixed-navbar"
                    href="#">Заказать звонок</a>
            </div>
            <div class="header__contacts">
                <a class="header__link header__link--social u-visible--p" href="https://viber.click/79775199267"
                    target="_blank" rel="noopener noreferrer">
                    <img class="header__social-icon" src="img/viber.jpg" alt="">
                </a>
                <a class="header__link header__link--social" href="https://wa.me/79775199267" target="_blank"
                    rel="noopener noreferrer">
                    <img class="header__social-icon" src="img/whatsapp.jpg" alt="">
                </a>
                <a class="header__link header__link--social" href="https://www.instagram.com/Arnikomeb/" target="_blank"
                    rel="noopener noreferrer">
                    <img class="header__social-icon" src="img/insta1.png" alt="">
                </a>
            </div>
        </div>
    </div>
    <!-- modals -->
    <!-- modal for form, gets activated from navigation "заказать звонок" button -->
    <div class="form__container" id="js-navbar-order-modal">
        <form name="form-navbar" method="post" class="form js-form" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" accept-charset="UTF-8" id="form_navbar">
            <h2 class="modal__title heading heading--tertiary form__heading">Оставьте заявку <br> <span>чтобы мы
                    перезвонили вам</span></h2>
            <input type="hidden" name="action_taken_from" value="Заказать звонок через панел навигации(диван)">
            <input type="hidden" name="formid" value="form_navbar">
            <div class="input__group">
                <input class="input" type="text" name="userName" placeholder="Введите свое имя" required>
            </div>
            <div class="input__group">
                <input class="input js-phone__input" name="userPhone" type="text" placeholder="Введите свой телефон" required
                    pattern="\+7\([0-9]{3}\)[0-9]{3}-[0-9]{2}-[0-9]{2}" required>
            </div>
            <div class="input__group">
                <input class="button button--attention button--block" type="submit" value="Перезвоните мне">
            </div>
            <div class="input__group">
                <input type="checkbox" id="navbar-order-modal-check"
                    class="form__agreement" required checked>
                <label for="navbar-order-modal-check" class="form__agreement-label">
                    Нажимая на кнопку "Получить консультацию", я даю согласие на обработку персональных данных и
                    соглашаюсь c условиями политики конфиденциальности
                </label>
            </div>
        </form>
    </div>
    <div class="form__container" tabindex="-1" id="js-call-looking-for-modal">
        <form name="form-navbar" method="post" class="form js-form" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" accept-charset="UTF-8" id="form_navbar">
            <h2 class="tingle-modal__title heading heading--tertiary form__heading">Оставьте заявку <br> <span>для
                    рассчета стоимости вашего дивана</span></h2>
            <input type="hidden" name="action_taken_from" id="hidden__call-looking-for-modal" value="Кнопка верхнего блока/не нашли модель в галереи(divan)">
            <input type="hidden" name="formid" id="hidden__call-looking-for-modal" value="form_consult_krovat">
            <div class="input__group">
                <input class="input" type="text" name="userName" placeholder="Введите свое имя" required>
            </div>
            <div class="input__group">
                <input class="input js-phone__input" type="text" name="userPhone" placeholder="Введите свой телефон"
                    pattern="\+7\([0-9]{3}\)[0-9]{3}-[0-9]{2}-[0-9]{2}" required>
            </div>
            <div class="input__group">

                <label class="file-input__label">
                    <input class="input file-input" type="file" id="js-file-input" aria-label="input file"
                        multiple="multiple">
                    <span class="file-input__custom" id="js-file-input-fake">
                        Прикрепить макет
                    </span>
                </label>
            </div>

            <div class="input__group">
                <input class="button button--attention button--block" type="submit" value="Узнать цену">
            </div>
            <div class="input__group">
                <input type="checkbox" id="call-looking-for-modal" class="form__agreement"
                    checked>
                <label for="call-looking-for-modal" class="form__agreement-label">
                    Нажимая на кнопку "Получить консультацию", я даю согласие на обработку персональных данных и
                    соглашаюсь c условиями политики конфиденциальности
                </label>
            </div>
        </form>
    </div>
    <div class="form__container" id="js-order-gallery-modal">
        <form name="form-navbar" method="post" class="form js-form" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" accept-charset="UTF-8" id="form_navbar">
            <h2 class="modal__title heading heading--tertiary form__heading">Оставьте заявку <br> <span> чтобы заказать
                    диван</span></h2>
            <input type="hidden" value="Заказ кровати через галлерею" id="order__hidden" name="action_taken_from">
            <input type="hidden" value="form_order" id="order__hidden" name="formid">                    
            <input type="hidden" value="divan" id="order-type" name="order_type">
            <input type="hidden" value="" id="order-name" name="order_name">
            <input type="hidden" value="" id="order-price" name="order_price">
            <div class="input__group">
                <input class="input" type="text" required placeholder="Введите свое имя" name="userName">
            </div>
            <div class="input__group">
                <input class="input js-phone__input" type="text" name="userPhone" placeholder="Введите свой телефон"
                    pattern="\+7\([0-9]{3}\)[0-9]{3}-[0-9]{2}-[0-9]{2}" required>
            </div>
            <div class="input__group">
                <input class="button button--attention button--block" type="submit" value="Заказать">
            </div>
            <div class="input__group">
                <input type="checkbox" id="order-modal-check" class="form__agreement" required
                    checked>
                <label for="order-modal-check" class="form__agreement-label">
                    Нажимая на кнопку "Получить консультацию", я даю согласие на обработку персональных данных и
                    соглашаюсь c условиями политики конфиденциальности
                </label>
            </div>
        </form>
    </div>
    <!--div below will get filled by script, keep it-->
    <div class="more" id="more">

    </div>
    <script src="js/jarallax.min.js"></script>
    <script src="js/fslightbox.js"></script>
    <script src="js/divan/gallery_divan.js"></script>
    <script src="js/main.js"></script>
</body>

</html