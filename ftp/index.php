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
   $success_url = './success_krovat.php';
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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formid']) && $_POST['formid'] == 'form_navbar')
{
   $mailto = 'info@gsquare.site';
   $mailfrom = isset($_POST['email']) ? $_POST['email'] : $mailto;
   $mailcc = 'gevghazaryan@gmail.com';
   $subject = 'Заявка на заказ обратного звонка (Arniko)';
   $message = 'Контактные данные:';
   $success_url = './success_krovat.php';
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
   $success_url = './success_krovat.php';
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
   $success_url = './success_krovat.php';
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
    <title>Arniko - кровати для крепкого сна</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Arniko - кровати для крепкого сна с мягкой обивкой от производителя">
    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon">
    <link rel="stylesheet" href="css/fonts.css">
    <link rel="stylesheet" href="css/main.css">
    <script src="js/tingle.js"></script>
    <script src="js/imask.js"></script>
</head>
    <body>
        <div data-jarallax data-speed="0.5" class="main jarallax"  data-parallax="scroll" data-image-src="./img/bg-1.jpg">
            <div class="header">
                <div class="header__inner container">
                    <div class="logo">
                        <a href="#" class="logo__link" onclick="return false">
                            <img src="img/logo.png" alt="brand icon image" class="logo__icon">
                        </a>
                    </div>
                    <div class="header__menu-wrapper">
                        <ul class="menu menu--header">
                            <li class="menu__item"><a class="menu__link animated-underline js-scrollto" href="#gallery">Кровати</a></li>
                            <li class="menu__item hidden--md visible--lg"><a class="menu__link animated-underline" href="/divany.php">Диваны</a></li>
                            <li class="menu__item"><a class="menu__link animated-underline js-scrollto" href="#showroom">Шоурум</a></li>
                        </ul>
                    </div>
                    <div class="header__aside">
                        г. Москва, ул. Тимирязевская, д.2/3
                        <br/>
                        (ТЦ "Парк 11", 3 этаж)
                    </div>
                    <div class="header__contacts">
                        <a  class="header__link header__link--primary animated-underline" href="tel:+74951429380">+7 (495) 142-93-80</a>
                        <input id="call-from-navbar" class="button button--tiny button--secondary" type="button" value="Заказать звонок" data-toggle="modal" data-target="#js-call-navbar-modal">
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
                            Кровати на заказ
                            <br>
                            по дизайн проектам
                            <br>
                            <span>С мягкой обивкой от производителя</span>
                        </h1>
                    </div>
                    <div class="hero__block col-2 md-col-1">
                        <p class="hero__block-text">
                            Подберем кровать под ваш дизайн, либо изготовим по вашему макету или фото и привезем в срок от 3-х дней
                        </p>
                        <div class="hero__icon-container">
                            <img src="img/bed-icon-small.png" alt="">
                        </div>
                        <div class="hero__input-container a-blink">
                            <input type="button" class="hero__cta button button--attention button--large" id="js-main__cta" value="Подобрать и рассчитать" />
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
                    <img  class="card__image a-rotate a-std" src="img/peim2.png" alt="">
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
                        <span>кроватей с мягкой обивкой</span>
                    </h2>
                    <ul class="gallery__type">
                        <li><a class="button button--tag active gallery__type-button" href="#">Кровати</a></li>
                        <li><a class="button button--transparent gallery__type-button"  href="/divany.php">Диваны</a></li>
                    </ul>
                </div>
                <div id="gallery" >
                    <ul class="gallery__list u-jc--space-between">
                        <li class="gallery__item">
                            <div class="gallery__item-img"><img src="img/1-(2).jpg" alt=""></div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Кровать</span>
                                    <h4 class="gallery__item-name">Балу</h4>
                                    <span class="gallery__item-price">67.000 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/1-(1).jpg" data-fslightbox="gallery-21"><img src="img/1-(2).jpg" alt=""></a>
                                    <a href="img/1-(2).jpg" data-fslightbox="gallery-21"><img src="img/1-(1).jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent js-more-trigger button--bordered" data-target="#modal-20" data-orderName="Балу" data-price="67.000 рублей" data-type="Кровать" data-img="img/1-(1).jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-orderName="Балу" data-price="67.000 рублей" data-type="Кровать">
                            </div>
                        </li>                        
                        <li class="gallery__item">
                            <div class="gallery__item-img">
                                <img src="img/2-(2).jpg" alt="">
                            </div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Кровать</span>
                                    <h4 class="gallery__item-name">Global</h4>
                                    <span class="gallery__item-price">53.000 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/2-(1).jpg" data-fslightbox="gallery-1"><img src="img/2-(2).jpg" alt=""></a>
                                    <a href="img/2-(2).jpg" data-fslightbox="gallery-1"><img src="img/2-(1).jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent button--bordered js-more-trigger" data-image="#modal-0" data-orderName="Global" data-price="53.000 рублей" data-type="Кровать" data-img="img/2-(1).jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-orderName="Global" data-price="53.000 рублей" data-type="Кровать">
                            </div>
                        </li>
                            
                        <li class="gallery__item">
                            <div class="gallery__item-img"><img src="img/3-(2).jpg" alt=""></div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Кровать</span>
                                    <h4 class="gallery__item-name">Шик</h4>
                                    <span class="gallery__item-price">70.000 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/3-(1).jpg" data-fslightbox="gallery-2"><img src="img/3-(2).jpg" alt=""></a>
                                    <a href="img/3-(2).jpg" data-fslightbox="gallery-2"><img src="img/3-(1).jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent button--bordered js-more-trigger" data-target="#modal-1" data-orderName="Шик" data-price="70.000 рублей" data-type="Кровать" data-img="img/3-(1).jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-orderName="Шик" data-price="70.000 рублей" data-type="Кровать">
                            </div>
                        </li>
                    
                        <li class="gallery__item">
                            <div class="gallery__item-img"><img src="img/4-(2).jpg" alt=""></div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Кровать</span>
                                    <h4 class="gallery__item-name">Milky Way</h4>
                                    <span class="gallery__item-price">55.000 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/4-(1).jpg" data-fslightbox="gallery-3"><img src="img/4-(2).jpg" alt=""></a>
                                    <a href="img/4-(2).jpg" data-fslightbox="gallery-3"><img src="img/4-(1).jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent  js-more-trigger button--bordered" data-orderName="Milky Way" data-price="55.000 рублей" data-type="Кровать" data-img="img/4-(1).jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-orderName="Milky Way" data-price="55.000 рублей" data-type="Кровать">
                            </div>
                        </li>
                    
                        <li class="gallery__item">
                            <div class="gallery__item-img"><img src="img/5-(2).jpg" alt=""></div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Кровать</span>
                                    <h4 class="gallery__item-name">Anita</h4>
                                    <span class="gallery__item-price">43.500 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/5-(1).jpg" data-fslightbox="gallery-4"><img src="img/5-(2).jpg" alt=""></a>
                                    <a href="img/5-(2).jpg" data-fslightbox="gallery-4"><img src="img/5-(1).jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent js-more-trigger button--bordered" data-target="#modal-3" data-orderName="Anita" data-price="43.500 рублей" data-type="Кровать" data-img="img/5-(1).jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-orderName="Anita" data-price="43.500 рублей" data-type="Кровать">
                            </div>
                        </li>
                    
                        <li class="gallery__item">
                            <div class="gallery__item-img"><img src="img/6-(2).jpg" alt=""></div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Кровать</span>
                                    <h4 class="gallery__item-name">Kim</h4>
                                    <span class="gallery__item-price">97.000 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/6-(1).jpg" data-fslightbox="gallery-5"><img src="img/6-(2).jpg" alt=""></a>
                                    <a href="img/6-(2).jpg" data-fslightbox="gallery-5"><img src="img/6-(1).jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent js-more-trigger button--bordered" data-target="#modal-4" data-orderName="Kim" data-price="97.000 рублей" data-type="Кровать" data-img="img/6-(1).jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-orderName="Kim" data-price="97.000 рублей" data-type="Кровать">
                            </div>
                        </li>
                    
                        <li class="gallery__item">
                            <div class="gallery__item-img"><img src="img/9-(2).jpg" alt=""></div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Кровать</span>
                                    <h4 class="gallery__item-name">Маквин</h4>
                                    <span class="gallery__item-price">53.000 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/9-(1).jpg" data-fslightbox="gallery-6"><img src="img/9-(2).jpg" alt=""></a>
                                    <a href="img/9-(2).jpg" data-fslightbox="gallery-6"><img src="img/9-(1).jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent js-more-trigger button--bordered" data-target="#modal-5" data-type="Кровать" data-orderName="Маквин" data-price="53.000 рублей" data-img="img/9-(1).jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-orderName="Кровать" data-price="Маквин" data-type="53.000 рублей">
                            </div>
                        </li>
                    
                        <li class="gallery__item">
                            <div class="gallery__item-img"><img src="img/8-(2).jpg" alt=""></div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Кровать</span>
                                    <h4 class="gallery__item-name">Tall</h4>
                                    <span class="gallery__item-price">от 60.000 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/8-(1).jpg" data-fslightbox="gallery-7"><img src="img/8-(2).jpg" alt=""></a>
                                    <a href="img/8-(2).jpg" data-fslightbox="gallery-7"><img src="img/8-(1).jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent js-more-trigger button--bordered" data-target="#modal-6" data-orderName="Tall" data-price="от 60.000 рублей" data-type="Кровать" data-img="img/8-(1).jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-orderName="Tall" data-price="от 60.000 рублей" data-type="Кровать">
                            </div>
                        </li>
                    
                        <li class="gallery__item">
                            <div class="gallery__item-img"><img src="img/7-(2).jpg" alt=""></div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Кровать</span>
                                    <h4 class="gallery__item-name">Black Wave</h4>
                                    <span class="gallery__item-price">60.000 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/7-(1).jpg" data-fslightbox="gallery-8"><img src="img/7-(2).jpg" alt=""></a>
                                    <a href="img/7-(2).jpg" data-fslightbox="gallery-8"><img src="img/7-(1).jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent js-more-trigger button--bordered" data-target="#modal-7" data-orderName="Black Wave" data-price="65.000 рублей" data-type="Кровать" data-img="img/7-(1).jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-orderName="Black Wave" data-price="65.000 рублей" data-type="Кровать">
                            </div>
                        </li>
                    
                        <li class="gallery__item">
                            <div class="gallery__item-img"><img src="img/10-(1).jpg" alt=""></div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Кровать</span>
                                    <h4 class="gallery__item-name">Грейленд</h4>
                                    <span class="gallery__item-price">51.000 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/10-(2).jpg" data-fxlightbox="gallery-9"><img src="img/10-(1).jpg" alt=""></a>
                                    <a href="img/10-(1).jpg" data-fslightbox="gallery-9"><img src="img/10-(2).jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent js-more-trigger button--bordered" data-target="#modal-8" data-orderName="Грейленд" data-price="51.000 рублей" data-type="Кровать" data-img="img/10-(1).jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-orderName="Грейленд" data-price="51.000 рублей" data-type="Кровать">
                            </div>
                        </li>
                    
                        <li class="gallery__item">
                            <div class="gallery__item-img"><img src="img/11-(1).jpg" alt=""></div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Кровать</span>
                                    <h4 class="gallery__item-name">Гранд Бахия</h4>
                                    <span class="gallery__item-price">от 70.000 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/11-(2).jpg" data-fslightbox="gallery-10"><img src="img/11-(1).jpg" alt=""></a>
                                    <a href="img/11-(1).jpg" data-fslightbox="gallery-10"><img src="img/11-(2).jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent js-more-trigger button--bordered" data-target="#modal-9" data-orderName="Гранд Бахия" data-price="от 70.000 рублей" data-type="Кровать" data-img="img/11-(1).jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-orderName="Гранд Бахия" data-price="от 70.000 рублей" data-type="Кровать">
                            </div>
                        </li>
                    
                        <li class="gallery__item">
                            <div class="gallery__item-img"><img src="img/13-(1).jpg" alt=""></div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Кровать</span>
                                    <h4 class="gallery__item-name">Elena</h4>
                                    <span class="gallery__item-price">42.000 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/13-(2).jpg" data-fslightbox="gallery-11"><img src="img/13-(1).jpg" alt=""></a>
                                    <a href="img/13-(1).jpg" data-fslightbox="gallery-11"><img src="img/13-(2).jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent js-more-trigger button--bordered" data-target="#modal-10" data-orderName="Elena" data-price="42.000 рублей" data-type="Кровать" data-img="img/13-(1).jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-orderName="Elena" data-price="42.000 рублей" data-type="Кровать">
                            </div>
                        </li>
                    
                        <li class="gallery__item">
                            <div class="gallery__item-img"><img src="img/16-(1).jpg" alt=""></div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Кровать</span>
                                    <h4 class="gallery__item-name">Брианза</h4>
                                    <span class="gallery__item-price">45.000 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/16-(2).jpg" data-fslightbox="gallery-12"><img src="img/16-(1).jpg" alt=""></a>
                                    <a href="img/16-(1).jpg" data-fxlightbox="gallery-12"><img src="img/16-(2).jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent js-more-trigger button--bordered" data-target="#modal-11" data-orderName="Брианза" data-price="45.000 рублей" data-type="Кровать" data-img="img/16-(1).jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-orderName="Брианза" data-price="45.000 рублей" data-type="Кровать">
                            </div>
                        </li>
                    
                        <li class="gallery__item">
                            <div class="gallery__item-img"><img src="img/20-(1).jpg" alt=""></div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Кровать</span>
                                    <h4 class="gallery__item-name">Феерия</h4>
                                    <span class="gallery__item-price">49.000 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/20-(2).jpg" data-fslightbox="gallery-13"><img src="img/20-(1).jpg" alt=""></a>
                                    <a href="img/20-(1).jpg" data-fslightbox="gallery-13"><img src="img/20-(2).jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent js-more-trigger button--bordered" data-target="#modal-12" data-orderName="Феерия" data-price="49.000 рублей" data-type="Кровать" data-img="img/20-(1).jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-orderName="Феерия" data-price="49.000 рублей" data-type="Кровать">
                            </div>
                        </li>
                    
                        <li class="gallery__item">
                            <div class="gallery__item-img"><img src="img/19-(1).jpg" alt=""></div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Кровать</span>
                                    <h4 class="gallery__item-name">София</h4>
                                    <span class="gallery__item-price">43.000 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/19-(2).jpg" data-fslightbox="gallery-14"><img src="img/19-(1).jpg" alt=""></a>
                                    <a href="img/19-(1).jpg" data-fslightbox="gallery-14"><img src="img/19-(2).jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent js-more-trigger button--bordered" data-target="#modal-13" data-price="43.000 рублей" data-type="Кровать" data-orderName="София" data-img="img/19-(1).jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-price="43.000 рублей" data-type="Кровать" data-name="София">
                            </div>
                        </li>
                    
                        <li class="gallery__item">
                            <div class="gallery__item-img"><img src="img/21-(1).jpg" alt=""></div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Детская кровать</span>
                                    <h4 class="gallery__item-name">Диана</h4>
                                    <span class="gallery__item-price">43.000 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/21-(2).jpg" data-fslightbox="gallery-15"><img src="img/21-(1).jpg" alt=""></a>
                                    <a href="img/21-(1).jpg" data-fslightbox="gallery-15"><img src="img/21-(2).jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent js-more-trigger button--bordered" data-target="#modal-14" data-orderName="Диана" data-price="43.000 рублей" data-type="Детская кровать" data-img="img/21-(1).jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-orderName="Диана" data-price="43.000 рублей" data-type="Детская кровать">
                            </div>
                        </li>
                    
                        <li class="gallery__item">
                            <div class="gallery__item-img"><img src="img/18-(1).jpg" alt=""></div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Кровать</span>
                                    <h4 class="gallery__item-name">Салото</h4>
                                    <span class="gallery__item-price">50.000 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/18-(2).jpg" data-fslightbox="gallery-16"><img src="img/18-(1).jpg" alt=""></a>
                                    <a href="img/18-(1).jpg" data-fslightbox="gallery-16"><img src="img/18-(2).jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent js-more-trigger button--bordered" data-target="#modal-15" data-orderName="Салото" data-price="50.000 рублей" data-type="Кровать" data-img="img/18-(1).jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-orderName="Салото" data-price="50.000 рублей" data-type="Кровать">
                            </div>
                        </li>
                    
                        <li class="gallery__item">
                            <div class="gallery__item-img"><img src="img/17-(1).jpg" alt=""></div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Кровать</span>
                                    <h4 class="gallery__item-name">Миа</h4>
                                    <span class="gallery__item-price">45.000 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/17-(2).jpg" data-fslightbox="gallery-17"><img src="img/17-(1).jpg" alt=""></a>
                                    <a href="img/17-(1).jpg" data-fslightbox="gallery-17"><img src="img/17-(2).jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent js-more-trigger button--bordered" data-target="#modal-16" data-orderName="Миа" data-price="45.000 рублей" data-type="Кровать" data-img="img/17-(1).jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-orderName="Миа" data-price="45.000 рублей" data-type="Кровать">
                            </div>
                        </li>
                    
                        <li class="gallery__item">
                            <div class="gallery__item-img"><img src="img/14-(1).jpg" alt=""></div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Кровать</span>
                                    <h4 class="gallery__item-name">Moment</h4>
                                    <span class="gallery__item-price">47.000 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/14-(2).jpg" data-fslightbox="gallery-18"><img src="img/14-(1).jpg" alt=""></a>
                                    <a href="img/14-(1).jpg" data-fslightbox="gallery-18"><img src="img/14-(2).jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent js-more-trigger button--bordered" data-target="#modal-17" data-orderName="Moment" data-price="47.000 рублей" data-type="Кровать" data-img="img/14-(1).jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-orderName="Moment" data-price="47.000 рублей" data-type="Кровать">
                            </div>
                        </li>
                    
                        <li class="gallery__item">
                            <div class="gallery__item-img"><img src="img/15-(1).jpg" alt=""></div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Кровать</span>
                                    <h4 class="gallery__item-name">Аква</h4>
                                    <span class="gallery__item-price">45.000 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/15-(2).jpg" data-fslightbox="gallery-19"><img src="img/15-(1).jpg" alt=""></a>
                                    <a href="img/15-(1).jpg" data-fslightbox="gallery-19"><img src="img/15-(2).jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent js-more-trigger button--bordered" data-target="#modal-18" data-orderName="Аква" data-price="45.000 рублей" data-type="Кровать" data-img="img/14-(1).jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-orderName="Аква" data-price="45.000 рублей" data-type="Кровать">
                            </div>
                        </li>
                    
                        <li class="gallery__item">
                            <div class="gallery__item-img"><img src="img/12-(1).jpg" alt=""></div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Кровать</span>
                                    <h4 class="gallery__item-name">Маэстро</h4>
                                    <span class="gallery__item-price">80.000 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/12-(2).jpg" data-fslightbox="gallery-20"><img src="img/12-(1).jpg" alt=""></a>
                                    <a href="img/12-(1).jpg" data-fslightbox="gallery-20"><img src="img/12-(2).jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent js-more-trigger button--bordered" data-target="#modal-19" data-orderName="Маэстро" data-price="80.000 рублей" data-type="Кровать" data-img="img/12-(1).jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-orderName="Маэстро" data-price="80.000 рублей" data-type="Кровать">
                            </div>
                        </li>

                        <li class="gallery__item">
                            <div class="gallery__item-img"><img src="img/Dream.jpg"></div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Кровать</span>
                                    <h4 class="gallery__item-name">Ламбо</h4>
                                    <span class="gallery__item-price">120.000 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/Dream.jpg" data-fslightbox="gallery-22"><img src="img/Dream.jpg" alt=""></a>
                                    <a href="img/Dream1.jpg" data-fslightbox="gallery-22"><img src="img/Dream1.jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent js-more-trigger button--bordered" data-target="#modal-19" data-orderName="Ламбо" data-price="120.000 рублей" data-type="Кровать" data-img="img/Dream.jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-orderName="Маэстро" data-price="120.000 рублей" data-type="Кровать">
                            </div>
                        </li>

                        <li class="gallery__item">
                            <div class="gallery__item-img"><img src="img/Ezviz.jpg"></div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Кровать</span>
                                    <h4 class="gallery__item-name">Эзвис</h4>
                                    <span class="gallery__item-price">65.000 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/Ezviz.jpg" data-fslightbox="gallery-23"><img src="img/Ezviz.jpg" alt=""></a>
                                    <a href="img/Ezviz1.jpg" data-fslightbox="gallery-23"><img src="img/Ezviz1.jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent js-more-trigger button--bordered" data-target="#modal-19" data-orderName="Эзвис" data-price="65.000 рублей" data-type="Кровать" data-img="img/Ezviz.jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-orderName="Эзвис" data-price="65.000 рублей" data-type="Кровать">
                            </div>
                        </li>

                        <li class="gallery__item">
                            <div class="gallery__item-img"><img src="img/balateli.jpg"></div>
                            <div class="gallery__item-content">
                                <div class="gallery__item-info">
                                    <span class="gallery__item-type">Кровать</span>
                                    <h4 class="gallery__item-name">Балатели</h4>
                                    <span class="gallery__item-price">120.000 рублей</span>
                                </div>
                                <div class="gallery__item-thumbs">
                                    <a href="img/balateli.jpg" data-fslightbox="gallery-24"><img src="img/balateli.jpg" alt=""></a>
                                    <a href="img/balateli1.jpg" data-fslightbox="gallery-24"><img src="img/balateli1.jpg" alt=""></a>
                                </div>
                            </div>
                            <div class="gallery__item-ordering">
                                <input type="button" value="Подробнее" class="button button--transparent js-more-trigger button--bordered" data-target="#modal-19" data-orderName="Балатели" data-price="120.000 рублей" data-type="Кровать" data-img="img/balateli.jpg">
                                <input type="button" value="Заказать" class="button button--attention js-order" data-orderName="Балатели" data-price="120.000 рублей" data-type="Кровать">
                            </div>
                        </li>




                    </ul>
                </div>
            </div>
        </div>
        <div id ="looking-for" data-jarallax data-speed="0.5" class="jarallax looking-for js-parallax std-blck std-blck--bg js-parallax" data-parallax="scroll" data-image-src="./img/bg-2.jpg">
            <div class="std-blck__inner u-jc--space-between">
                <div class="std-blck__full">
                    <h2 class="heading heading--secondary looking-for__heading">
                        <span class="u-block u-block--p">Не нашли понравившуюся модель</span>
                        <span class="u-block u-block--p"> кровати в нашем каталоге?</span>
                    </h2>
                </div>
                <div class="std-blck__left looking-for__content">
                    <p class="col-2 md-col-1 looking-for__text">Нечего страшного, мы изготовим для вас <br> кровать по вашему макету или просто фото.</p>
                        <div class="looking-for__img-box u-hidden--tl">
                          <img class="col-2 md-col-1 looking-for__img" src="img/bed-icon.png" alt="">
                        </div>
                    <div class="input-container">

                        <button type="button" class="button button--attention button--large looking-for__cta a-blink" id="js-looking-for__cta">Отправить макет или фото</button>
                    </div>                    
                </div>
                <div class="std-blck__right">
                    <div class="looking-for__img-box u-visible--tl">
                        <img class="col-2 md-col-1 looking-for__img" src="img/bed-icon.png" alt="">
                    </div>
                </div>
            </div>
        </div>
        <div class="your-choice">
            <div class="container">
                <div class="your-choice__content col-4">                
                    <h2 class="heading heading--primary">Вы можете выбрать</h2>
                    <p>любой вариант комплектации, материала и обивки кровати</p>
                </div>
                <ul class="your-choice__card-container">
                    <li class="your-choice__card">

                        <div class="your-choice__image-box">
                            <img src="img/variant6.jpg" alt="">
                        </div>
                        <p>Варианты ткани<br>
                            и обивки</p>
                    </li>
                    <li class="your-choice__card">
                        <div class="your-choice__image-box">
                            <img src="img/variant5.jpg" alt="">
                        </div>
                        <p>Ортопедическую<br>
                            решетку</p>
                    </li>
                    <li class="your-choice__card">
                        <div class="your-choice__image-box">
                            <img src="img/variant7.jpg" alt="">
                        </div>
                        <p>Подъемный<br>
                            механизм</p>
                    </li>
                    <li class="your-choice__card">
                        <div class="your-choice__image-box">
                            <img src="img/variant8.jpg" alt="">
                        </div>
                        <p>Фурнитуру и<br>
                            основание</p>
                    </li>
                </ul>
            </div>
        </div>
        <div data-jarallax data-speed="0.5" class="jarallax matras std-blck std-blck--bg js-parallax" data-parallax="scroll" data-image-src="./img/bg/bg-3.jpg">
            <div class="std-blck__inner">
                <div class="std-blck__left">
                    <div class="matras__image-container u-visible--tl js-move" data-moveto="matras__image-container-2" data-screen="1200">
                        <img class="" src="img/matras.png" alt="matras">
                    </div>
                </div>
                <div class="std-blck__right matras__text-content">
                    <h2 class="heading heading--secondary">Большой выбор матрасов по высоте  и жесткости со скидкой 10%</h2>
                    <div class="matras__image-container u-hidden--tl" data-moveto="matras__image-container-2" data-screen="1200">
                        <img class="" src="img/matras.png" alt="matras">
                    </div>
                    <p class="matras__par">
                        Выбрали кровать? Теперь предстоит определиться с выбором матраса, ведь от него будет зависеть качество вашего сна!
                    </p>
                    <p class="matras__par">
                        У нашей компании есть большой выбор матрасов разного размера, высоты и жесткости
                    </p>
                </div>
            </div>
        </div>
        <div id="showroom" class="showroom std-blck">
            <div class="std-blck__inner">
                <div id="showroom__heading2" class="hidden--lg">

                </div>
                <div class="std-blck__left showroom__text-content">
                    <div>
                        <h2 class="heading heading--secondary js-move showroom__heading" data-moveto="showroom__heading2" data-screen="1200">Мы ждем вас в нашем Шоуруме</h2>
                    </div>
                    <p class="showroom__text">Где вы сможете в живую увидеть наши модели кроватей, пообщаться с консультантами и выбрать подходящую для вас модель!</p>
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
                        <a href="img/showroom6.jpg" class="showroom__img-link"  data-fslightbox="gallery-show">
                            <img src="img/showroom6.jpg" alt="" class="showroom__image">
                        </a>
                        <a href="img/showroom7.jpg" class="showroom__img-link"  data-fslightbox="gallery-show"> 
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
        <div data-jarallax data-speed="0.5" class="jarallax manufacturer text-light js-parallax" data-parallax="scroll" data-image-src="./img/bg-4.jpg">
            <div class="container manufacturer__inner">
                <div class="manufacturer__img-box">
                    <img src="img/cog.png" alt="">
                </div>
                <div class="manufacturer__text">
                    <h2 class="heading heading--tertiary">Мы производители</h2>            
                    <p class="manufacturer__par">Арнико это прямой фабричный производитель мягкой мебели на заказ по любым размерам, эскизам и фото.</p>
                </div>
            </div>
        </div>
        <div class="designer std-blck">
            <div class="container">
                <div class="std-blck__left">
                    <h2 class="heading heading--secondary designer__heading u-hidden--tl">Вы дизайнер?</h2>
                    <img class="designer__image" src="img/dizayn.png" alt="bed-design">
                </div>
                <div class="std-blck__right">
                    <h2 class="heading heading--secondary designer__heading u-visible--tl">Вы дизайнер?</h2>
                    <p class="">В таком случае, у вас есть возможность стать нашим партнёром и получать 10% (вознаграждение) от стоимости кровати за каждого приведённого клиента</p>
                    <div class="designer__footer">
                        <img class="designer__icon u-fl" src="img/small-bed.png" alt="">
                        <p class="text-small">
                            Мы предоставим вам 3D <br> модели всех наших кроватей
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div data-jarallax data-speed="0.5" class="jarallax std-blck std-blck--bg contact-us js-parallax" data-parallax="scroll" data-image-src="./img/bg10.jpg">
            <div class="container contact-us__inner u-jc--space-around">
                <div class="contact-us__text-content">
                    <h2 class="heading heading--primary contact-us__heading">Свяжитесь с нами!</h2>
                    <p class="contact-us__text">
                        Наши менеджеры подробно<br> проконсультируют вас по выбору<br> кровати и просчитают любое изделие<br> на заказ в течение 15 минут
                    </p>
                </div>
                <div class="form__container">
                    <form method="post" class="form form--small js-form" action="<? echo basename(__FILE__); ?>" accepth-charset="UTF-8" id="form-contact-us">
                        <input type="hidden" name="action_taken_from" value="Форма заявки перед футером">
                        <input type="hidden" name="formid" value="form_contact_us">
                        <legend class="form__legend">Введите свои данные и наш<br>
                            специалист свяжется с вами</legend>
                        <div class="input__group">
                            <input class="contact-us__input contact-us__input--text input" type="text" placeholder="Введите свое имя" name="userName" required>
                        </div>
                        <div class="input__group">
                            <input id="js-contact-us__phone" class="js-phone__input contact-us__input contact-us__input--password input" type="text" name="userPhone" placeholder="Введите свой телефон" required pattern="\+7\([0-9]{3}\)[0-9]{3}-[0-9]{2}-[0-9]{2}">
                        </div>
                        <div class="input__group a-blink">
                            <input type="submit" class="button button--attention button--block contact-us__cta" value="Получить консультацию" >
                        </div>
                        <div class="input__group">
                            <input type="checkbox" id="check" class="contact-us__checkbox form__agreement" value="hello" placeholder="hello" required checked>
                            <label for="check" class="contact-us__label  form__agreement-label">   
                                Нажимая на кнопку "Получить консультацию", я даю согласие на обработку персональных данных и соглашаюсь c условиями политики конфиденциальности                        
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
                        <a  href="https://viber.click/79775199267" class="footer__link--icon" target="_blank" rel="noopener noreferrer">
                            <i class="icon viber"></i>
                        </a>        
                        <a href="https://wa.me/79775199267" class="footer__link--icon" target="_blank" rel="noopener noreferrer">
                            <i class="icon whatsapp"></i>
                        </a>
                    </div>
                    <div class="footer__info-line">
                        <p class="address footer__text footer__text--address">
                            г. Москва, ул. Тимирязевская, д.2/3 <br> (ТЦ "Парк 11", 3 этаж)                
                        </p>
                        <a href="https://www.instagram.com/Arnikomeb/" class="footer__link footer__link--icon footer__link--block"  target="_blank" rel="noopener noreferrer">
                            <img src="img/insta1.png" alt="instagram">
                        </a>
                    </div>
                </div>
                <div class="footer__map-container u-map__container">
                    <script type="text/javascript" charset="utf-8" async src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3Abfd18d127c1ae4253d27aaa7cdae43f5a4446af54a0dee7189da9cedd8f25937&amp;lang=en_FR&amp;scroll=true"></script>                

                </div>
                <div class="footer__copyright">
                    Made by G. Ghazaryan/2022
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
                        <li class="menu__item js-f-menuitem"><a class="menu__link animated-underline" href="#gallery">Кровати</a></li>
                        <li class="menu__item "><a class="menu__link animated-underline" href="/divany.php">Диваны</a></li>
                        <li class="menu__item js-f-menuitem"><a class="menu__link animated-underline" href="#showroom">Шоурум</a></li>
                    </ul>
                </div>
                <!-- <div class="header__socials u-hidden--tp">
                    <a class="he/ader__link header__link--social" href=""><img class="header__icon header__icon--socials" src="img/insta1.png" alt=""></a>
                    <a class="header__link header__link--social" href=""><img class="header__icon header__icon--socials" src="img/whatsapp.jpg" alt=""></a>
                </div> -->
                <div class="header__aside header__address u-visible--p">
                    г. Москва, ул. Тимирязевская, д.2/3
                    <br/>
                    (ТЦ "Парк 11", 3 этаж)
                </div>
                <div class="header__aside">
                    <a  class="header__link header__link--primary animated-underline" href="tel:+74951429380">+7 (495) 142-93-80</a>
                    <a class="header__link header__link--secondary animated-underline" id="call-from-fixed-navbar" href="#">Заказать звонок</a>
                </div>
                <div class="header__contacts">
                        <a class="header__link header__link--social u-visible--p" href="https://viber.click/79775199267" target="_blank" rel="noopener noreferrer">
                            <img class="header__social-icon" src="img/viber.jpg" alt="">
                        </a>
                        <a class="header__link header__link--social" href="https://wa.me/79775199267" target="_blank" rel="noopener noreferrer">
                            <img  class="header__social-icon" src="img/whatsapp.jpg" alt="">
                        </a>
                        <a class="header__link header__link--social" href="https://www.instagram.com/Arnikomeb/"  target="_blank" rel="noopener noreferrer">
                            <img  class="header__social-icon" src="img/insta1.png" alt="">
                        </a>
                </div>
            </div>
        </div>
        <!-- modals -->
        <div class="form__container" id="js-navbar-order-modal">
            <form name="form-navbar" method="post" class="form js-form" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" accept-charset="UTF-8" id="form_navbar">
                <h2 class="modal__title heading heading--tertiary form__heading">Оставьте заявку <br> <span>чтобы мы перезвонили вам</span></h2>
                <input type="hidden" name="action_taken_from" value="Заказать звонок через панел навигации">
                <input type="hidden" name="formid" value="form_navbar">

                <div class="input__group">
                    <input class="input" type="text" name="userName" placeholder="Введите свое имя" required>
                </div>
                <div class="input__group">
                    <input class="input js-phone__input" name="userPhone" type="text" placeholder="Введите свой телефон"  required pattern="\+7\([0-9]{3}\)[0-9]{3}-[0-9]{2}-[0-9]{2}" required>
                </div>
                <div class="input__group">
                    <input class="button button--attention button--block" type="submit" value="Перезвоните мне">
                </div> 
                <div class="input__group">
                    <input type="checkbox" id="navbar-order-modal-check" class="form__agreement" required checked>
                    <label for="navbar-order-modal-check" class="form__agreement-label">                        
                        Нажимая на кнопку "Получить консультацию", я даю согласие на обработку персональных данных и соглашаюсь c условиями политики конфиденциальности                        
                    </label>                    
                </div>
            </form>
        </div>
        <div class="form__container" tabindex="-1" id="js-call-looking-for-modal">
            <form name="form-navbar" method="post" class="form js-form" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" accept-charset="UTF-8" id="form_navbar">
                <h2 class="tingle-modal__title heading heading--tertiary form__heading">Оставьте заявку <br> <span>для рассчета стоимости вашей кровати</span></h2>
                    <input type="hidden" name="action_taken_from" id="hidden__call-looking-for-modal" value="Кнопка верхнего блока/не нашли модель в галереи">
                    <input type="hidden" name="formid" id="hidden__call-looking-for-modal" value="form_consult_krovat">
                    <div class="input__group">
                        <input class="input" type="text" name="userName" placeholder="Введите свое имя" required>
                    </div>
                    <div class="input__group">
                        <input class="input js-phone__input" type="text" name="userPhone" placeholder="Введите свой телефон" pattern="\+7\([0-9]{3}\)[0-9]{3}-[0-9]{2}-[0-9]{2}" required>
                    </div>
                    <div class="input__group">
                        <label class="file-input__label">
                            <input class="input file-input" type="file" id="js-file-input" aria-label="input file" multiple="multiple" >
                            <span class="file-input__custom" id="js-file-input-fake">
                                Прикрепить макет
                            </span>
                        </label>                        
                    </div>

                    <div class="input__group">
                        <input class="button button--attention button--block" type="submit" value="Узнать цену">
                    </div>
                    <div class="input__group">
                        <input type="checkbox" id="order-modal-check" class="form__agreement" required checked>
                    <label for="order-modal-check" class="form__agreement-label">                        
                        Нажимая на кнопку "Получить консультацию", я даю согласие на обработку персональных данных и соглашаюсь c условиями политики конфиденциальности                        
                    </label>                    
                </div>                    
            </form>
        </div>
        <div class="form__container" id="js-order-gallery-modal">
            <form name="form-navbar" method="post" class="form js-form" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" accept-charset="UTF-8" id="form_navbar">
                <h2 class="modal__title heading heading--tertiary form__heading">Оставьте заявку <br> <span> чтобы заказать диван</span></h2>
                <input type="hidden" value="Заказ кровати через галлерею" id="order__hidden" name="action_taken_from">
                <input type="hidden" value="form_order" id="order__hidden" name="formid">
                <input type="hidden" value="Krovat" id="order-type" name="order_type">
                <input type="hidden" value="" id="order-name" name="order_name">
                <input type="hidden" value="" id="order-price" name="order_price">
                <div class="input__group">
                    <input class="input" type="text" required placeholder="Введите свое имя" name="userName">
                </div>
                <div class="input__group">
                    <input class="input js-phone__input" type="text" name="userPhone" placeholder="Введите свой телефон"  required pattern="\+7\([0-9]{3}\)[0-9]{3}-[0-9]{2}-[0-9]{2}" required>
                </div>
                <div class="input__group">
                    <input class="button button--attention button--block" type="submit" value="Заказать">
                </div> 
                <div class="input__group">
                    <input type="checkbox" id="order-modal-check" class="form__agreement" required checked>
                    <label for="order-modal-check" class="form__agreement-label">                        
                        Нажимая на кнопку "Получить консультацию", я даю согласие на обработку персональных данных и соглашаюсь c условиями политики конфиденциальности                        
                    </label>                    
                </div>                    
            </form>
        </div>

        <div class="more" id="more">

        </div>
    <script src="js/jarallax.min.js"></script>        
    <script src="js/fslightbox.js"></script>    
    <script src="js/krovat/gallery_krovat.js"></script>
    <script src="js/main.js"></script>    
    </body>
</html>