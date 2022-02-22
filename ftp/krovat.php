<?php
function ValidateEmail($email)
{
   $pattern = '/^([0-9a-z]([-.\w]*[0-9a-z])*@(([0-9a-z])+([-\w]*[0-9a-z])*\.)+[a-z]{2,6})$/i';
   return preg_match($pattern, $email);
}
function wget_request($url, $post_array, $check_ssl=true) {
  $cmd = "curl -X POST -H 'Content-Type: application/json'";
  $cmd.= " -d '" . json_encode($post_array) . "' '" . $url . "'";
   if (!$check_ssl){
      $cmd.= "'  --insecure";
   }
   $cmd .= " > /dev/null 2>&1 &";
  exec($cmd, $output, $exit);
  return $exit == 0;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formid'])){
  $_POST['viewId'] = $_COOKIE['lptracker_view_id'];
  wget_request('https://arniko-store.ru/api/api.php', $_POST);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formid']) && $_POST['formid'] == 'indexform4')
{
   $mailto = 'spb.info@arnikomeb.ru';
   $mailfrom = isset($_POST['email']) ? $_POST['email'] : $mailto;
   $mailcc = 'Aarniko@yandex.ru';
   $subject = 'Заявка на консультацию (Arniko)';
   $message = 'Контактные данные:';
   $success_url = './success.php';
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
function RecursiveMkdir($path)
{
   if (!file_exists($path))
   {
      RecursiveMkdir(dirname($path));
      mkdir($path, 0777);
   }
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formid']) && $_POST['formid'] == 'indexform1')
{
   $mailto = 'spb.info@arnikomeb.ru';
   $mailfrom = isset($_POST['email']) ? $_POST['email'] : $mailto;
   $mailcc = 'Aarniko@yandex.ru';
   $subject = 'Заявка на рачет стоимости кровати (Arniko)';
   $message = 'Контактные данные:';
   $success_url = './success.php';
   $error_url = './index.php';
   $eol = "\n";
   $error = '';
   $internalfields = array ("submit", "reset", "send", "filesize", "formid", "captcha_code", "recaptcha_challenge_field", "recaptcha_response_field", "g-recaptcha-response");
   $upload_folder = "upload";
   $upload_folder = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME'])."/".$upload_folder;
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
      $prefix = rand(111111, 999999);
      $i = 0;
      while (list ($key, $val) = each ($_FILES))
      {
         if ($_FILES[$key]['name'] != "" and file_exists($_FILES[$key]['tmp_name']) and $_FILES[$key]['size'] > 0)
         {
            $upload_DstName[$i] = $prefix . "_" . str_replace(" ", "_", $_FILES[$key]['name']);
            $upload_SrcName[$i] = $_FILES[$key]['name'];
            $upload_Size[$i] = ($_FILES[$key]['size']);
            $upload_Temp[$i] = ($_FILES[$key]['tmp_name']);
            $upload_URL[$i] = "$upload_folder/$upload_DstName[$i]";
            $upload_FieldName[$i] = $key;
         }
         $i++;
      }
      $uploadfolder = basename($upload_folder);
      for ($i = 0; $i < count($upload_DstName); $i++)
      {
         $uploadFile = $uploadfolder . "/" . $upload_DstName[$i];
         if (!is_dir(dirname($uploadFile)))
         {
            RecursiveMkdir(dirname($uploadFile));
         }
         move_uploaded_file($upload_Temp[$i] , $uploadFile);
         chmod($uploadFile, 0644);
         $name = "$" . $upload_FieldName[$i];
         $message = str_replace($name, $upload_URL[$i], $message);
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
      if (count($upload_SrcName) > 0)
      {
         $message .= "\nThe following files have been uploaded:\n";
         for ($i = 0; $i < count($upload_SrcName); $i++)
         {
            $message .= $upload_SrcName[$i] . ": " . $upload_URL[$i] . "\n";
         }
      }
      $body  = 'This is a multi-part message in MIME format.'.$eol.$eol;
      $body .= '--'.$boundary.$eol;
      $body .= 'Content-Type: text/plain; charset=UTF-8'.$eol;
      $body .= 'Content-Transfer-Encoding: 8bit'.$eol;
      $body .= $eol.stripslashes($message).$eol;
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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formid']) && $_POST['formid'] == 'indexform6')
{
   $mailto = 'spb.info@arnikomeb.ru';
   $mailfrom = isset($_POST['email']) ? $_POST['email'] : $mailto;
   $mailcc = 'Aarniko@yandex.ru';
   $subject = 'Скачали каталог (Arniko)';
   $message = 'Контактные данные:';
   $success_url = './katalog.php';
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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formid']) && $_POST['formid'] == 'indexform2')
{
   $mailto = 'spb.info@arnikomeb.ru';
   $mailfrom = isset($_POST['email']) ? $_POST['email'] : $mailto;
   $mailcc = 'Aarniko@yandex.ru';
   $subject = 'Заявка на заказ кровати (Arniko)';
   $message = 'Контактные данные:';
   $success_url = './success.php';
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
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['formid']) && $_POST['formid'] == 'indexform3')
{
   $mailto = 'spb.info@arnikomeb.ru';
   $mailfrom = isset($_POST['email']) ? $_POST['email'] : $mailto;
   $mailcc = 'Aarniko@yandex.ru';
   $subject = 'Заявка на заказ обратного звонка (Arniko)';
   $message = 'Контактные данные:';
   $success_url = './success.php';
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
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Arniko - кровати для крепкого сна</title>
<meta name="description" content="Arniko - кровати для крепкого сна с мягкой обивкой от производителя">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="favicon.ico" rel="shortcut icon" type="image/x-icon">
<link href="style/site.css" rel="stylesheet">
<link href="style/index.css" rel="stylesheet">
<script src="js/jquery-1.7.2.min.js"></script>
<script src="js/wb.parallax.min.js"></script>
<script src="js/wb.overlay.min.js"></script>
<script src="js/util.min.js"></script>
<script src="js/modal.min.js"></script>
<link rel="stylesheet" href="magnificpopup/magnific-popup.css">
<script src="magnificpopup/jquery.magnific-popup.min.js"></script>
<script src="js/wwb15.min.js"></script>

<script src="js/lazysizes.min.js" async=""></script>

<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(85292497, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/85292497" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->



 
<script src="js/index.js"></script>

 


<!-- LPTracker code start -->
<script type="text/javascript">
(function() {
var projectId = 83436;
var script = document.createElement('script');
script.type = 'text/javascript';
script.src = 'https://lpt-crm.online/lpt_widget/out/parser.min.js';
window.lptWg = window.lptWg || {};
window.lptWg.projectId = projectId;
window.lptWg.parser = true;
document.head.appendChild(script);
})()
</script>

<script type="text/javascript">
(function() {
var projectId = 83436;
var script = document.createElement('script');
script.type = 'text/javascript';
script.src = 'https://lpt-crm.online/lpt_widget/kick-widget.js';
window.lptWg = window.lptWg || {};
window.lptWg.projectId = projectId;
window.lptWg.parser = true;
document.head.appendChild(script);
})()
</script>
<!-- LPTracker code End -->

<script async src="https://lpt-crm.online/code/new/83436"></script> 



<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-202319138-2"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-202319138-2');
</script>



<script src="js/index.js"></script>
 
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-5MZ26QZ');</script>
<!-- End Google Tag Manager -->
</head>
<body>
 <!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5MZ26QZ"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
   <div id="container">
      <div id="modal_krovat3" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText102">
                     <span id="wb_uid0">Шик</span></div>
                  <div id="wb_indexText103">
                     <span id="wb_uid1">Кровать</span></div>
                  <input type="button" id="indexButton29" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat3').modal('hide');return false;" onmouseover="SetStyle('indexButton29', 'knop1_2');return false;" onmouseout="SetStyle('indexButton29', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
                  <div id="wb_indexText104">
                     <span id="wb_uid2">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText105">
                     <span id="wb_uid3">260/225 см<br>280/225 см<br>300/225 см<br>320/225 см</span></div>
                  <div id="wb_indexText106">
                     <span id="wb_uid4">Спальное место:</span></div>
                  <div id="wb_indexText107">
                     <span id="wb_uid5">Габариты кровати:</span></div>
                  <div id="wb_indexText108">
                     <span id="wb_uid6">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
                  <div id="wb_indexText109">
                     <span id="wb_uid7">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="wb_indexText110">
                     <span id="wb_uid8">Дополнительно возможно: <br>&#8226; Усиленное ортопедическое основание<br></span><span id="wb_uid9">&#8226; Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="indexLayer23">
                     <div id="indexLayer23_Container">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="modal_krovat4" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText111">
                     <span id="wb_uid10">Milky Way</span></div>
                  <div id="wb_indexText112">
                     <span id="wb_uid11">Кровать</span></div>
                  <input type="button" id="indexButton30" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat4').modal('hide');return false;" onmouseover="SetStyle('indexButton30', 'knop1_2');return false;" onmouseout="SetStyle('indexButton30', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
                  <div id="wb_indexText113">
                     <span id="wb_uid12">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText114">
                     <span id="wb_uid13">144/220 см<br>164/220 см<br>184/220 см<br>204/220 см</span></div>
                  <div id="wb_indexText115">
                     <span id="wb_uid14">Спальное место:</span></div>
                  <div id="wb_indexText116">
                     <span id="wb_uid15">Габариты кровати:</span></div>
                  <div id="wb_indexText117">
                     <span id="wb_uid16">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
                  <div id="wb_indexText118">
                     <span id="wb_uid17">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="wb_indexText119">
                     <span id="wb_uid18">Дополнительно возможно: <br>&#8226; Усиленное ортопедическое основание<br></span><span id="wb_uid19">&#8226; Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="indexLayer25">
                     <div id="indexLayer25_Container">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="modal_krovat7" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText138">
                     <span id="wb_uid20">Black Wave</span></div>
                  <div id="wb_indexText139">
                     <span id="wb_uid21">Кровать</span></div>
                  <input type="button" id="indexButton33" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat7').modal('hide');return false;" onmouseover="SetStyle('indexButton33', 'knop1_2');return false;" onmouseout="SetStyle('indexButton33', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
                  <div id="wb_indexText140">
                     <span id="wb_uid22">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText141">
                     <span id="wb_uid23">165/260 см<br>185/260 см<br>205/260 см<br>225/260 см</span></div>
                  <div id="wb_indexText142">
                     <span id="wb_uid24">Спальное место:</span></div>
                  <div id="wb_indexText143">
                     <span id="wb_uid25">Габариты кровати:</span></div>
                  <div id="wb_indexText144">
                     <span id="wb_uid26">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
                  <div id="wb_indexText145">
                     <span id="wb_uid27">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="wb_indexText146">
                     <span id="wb_uid28">Дополнительно возможно: <br>&#8226; Усиленное ортопедическое основание<br></span><span id="wb_uid29">&#8226; Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="indexLayer31">
                     <div id="indexLayer31_Container">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="modal_krovat8" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText147">
                     <span id="wb_uid30">Tall</span></div>
                  <div id="wb_indexText148">
                     <span id="wb_uid31">Кровать</span></div>
                  <input type="button" id="indexButton34" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat8').modal('hide');return false;" onmouseover="SetStyle('indexButton34', 'knop1_2');return false;" onmouseout="SetStyle('indexButton34', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
                  <div id="wb_indexText149">
                     <span id="wb_uid32">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText150">
                     <span id="wb_uid33">180/220 см<br>200/220 см<br>220/220 см<br>240/220 см</span></div>
                  <div id="wb_indexText151">
                     <span id="wb_uid34">Спальное место:</span></div>
                  <div id="wb_indexText152">
                     <span id="wb_uid35">Габариты кровати:</span></div>
                  <div id="wb_indexText153">
                     <span id="wb_uid36">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
                  <div id="wb_indexText154">
                     <span id="wb_uid37">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="wb_indexText155">
                     <span id="wb_uid38">Дополнительно возможно: <br></span><span id="wb_uid39">&#8226;</span><span id="wb_uid40"> Усиленное ортопедическое основание<br></span><span id="wb_uid41">&#8226;</span><span id="wb_uid42"> Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="indexLayer71">
                     <div id="indexLayer71_Container">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="modal_krovat1" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText22">
                     <span id="wb_uid43">Балу</span></div>
                  <div id="wb_indexText19">
                     <span id="wb_uid44">Кровать</span></div>
                  <div id="wb_indexText25">
                     <span id="wb_uid45">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText28">
                     <span id="wb_uid46">260/220 см<br>280/220 см<br>300/220 см<br>320/220 см</span></div>
                  <div id="wb_indexText37">
                     <span id="wb_uid47">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
                  <div id="wb_indexText40">
                     <span id="wb_uid48">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="wb_indexText43">
                     <span id="wb_uid49">Дополнительно возможно: <br></span><span id="wb_uid50">&#8226;</span><span id="wb_uid51"> Усиленное ортопедическое основание<br></span><span id="wb_uid52">&#8226;</span><span id="wb_uid53"> Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="indexLayer15">
                     <div id="indexLayer15_Container">
                     </div>
                  </div>
                  <div id="wb_indexText34">
                     <span id="wb_uid54">Габариты кровати:</span></div>
                  <div id="wb_indexText31">
                     <span id="wb_uid55">Спальное место:</span></div>
                  <input type="button" id="indexButton26" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat1').modal('hide');return false;" onmouseover="SetStyle('indexButton26', 'knop1_2');return false;" onmouseout="SetStyle('indexButton26', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               </div>
            </div>
         </div>
      </div>
      <div id="modal_krovat5" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText120">
                     <span id="wb_uid56">Anita</span></div>
                  <div id="wb_indexText121">
                     <span id="wb_uid57">Кровать</span></div>
                  <input type="button" id="indexButton31" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat5').modal('hide');return false;" onmouseover="SetStyle('indexButton31', 'knop1_2');return false;" onmouseout="SetStyle('indexButton31', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
                  <div id="wb_indexText122">
                     <span id="wb_uid58">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText123">
                     <span id="wb_uid59">132/220 см<br>152/220 см<br>172/220 см<br>192/220 см</span></div>
                  <div id="wb_indexText124">
                     <span id="wb_uid60">Спальное место:</span></div>
                  <div id="wb_indexText125">
                     <span id="wb_uid61">Габариты кровати:</span></div>
                  <div id="wb_indexText126">
                     <span id="wb_uid62">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
                  <div id="wb_indexText127">
                     <span id="wb_uid63">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="wb_indexText128">
                     <span id="wb_uid64">Дополнительно возможно: <br>&#8226; Усиленное ортопедическое основание<br></span><span id="wb_uid65">&#8226; Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="indexLayer27">
                     <div id="indexLayer27_Container">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="modal_krovat2" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText90">
                     <span id="wb_uid66">Global</span></div>
                  <div id="wb_indexText91">
                     <span id="wb_uid67">Кровать</span></div>
                  <input type="button" id="indexButton28" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat2').modal('hide');return false;" onmouseover="SetStyle('indexButton28', 'knop1_2');return false;" onmouseout="SetStyle('indexButton28', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
                  <div id="wb_indexText92">
                     <span id="wb_uid68">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText96">
                     <span id="wb_uid69">260/220 см<br>280/220 см<br>300/220 см<br>320/220 см</span></div>
                  <div id="wb_indexText97">
                     <span id="wb_uid70">Спальное место:</span></div>
                  <div id="wb_indexText98">
                     <span id="wb_uid71">Габариты кровати:</span></div>
                  <div id="wb_indexText99">
                     <span id="wb_uid72">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
                  <div id="wb_indexText100">
                     <span id="wb_uid73">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="wb_indexText101">
                     <span id="wb_uid74">Дополнительно возможно: <br>&#8226; Усиленное ортопедическое основание<br></span><span id="wb_uid75">&#8226; Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="indexLayer21">
                     <div id="indexLayer21_Container">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="modal_krovat6" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText135">
                     <span id="wb_uid76">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
                  <div id="wb_indexText129">
                     <span id="wb_uid77">Kim</span></div>
                  <div id="wb_indexText130">
                     <span id="wb_uid78">Кровать</span></div>
                  <input type="button" id="indexButton32" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat6').modal('hide');return false;" onmouseover="SetStyle('indexButton32', 'knop1_2');return false;" onmouseout="SetStyle('indexButton32', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
                  <div id="wb_indexText131">
                     <span id="wb_uid79">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText132">
                     <span id="wb_uid80">240/220 см<br>260/220 см<br>280/220 см<br>300/220 см</span></div>
                  <div id="wb_indexText133">
                     <span id="wb_uid81">Спальное место:</span></div>
                  <div id="wb_indexText134">
                     <span id="wb_uid82">Габариты кровати:</span></div>
                  <div id="wb_indexText136">
                     <span id="wb_uid83">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="wb_indexText137">
                     <span id="wb_uid84">Дополнительно возможно: <br>&#8226; Усиленное ортопедическое основание<br></span><span id="wb_uid85">&#8226; Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="indexLayer29">
                     <div id="indexLayer29_Container">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="modal_krovat10" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText202">
                     <span id="wb_uid86">Грейленд</span></div>
                  <div id="wb_indexText203">
                     <span id="wb_uid87">Кровать</span></div>
                  <div id="wb_indexText204">
                     <span id="wb_uid88">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText205">
                     <span id="wb_uid89">145/215 см<br>165/215 см<br>185/215 см<br>205/215 см</span></div>
                  <div id="wb_indexText206">
                     <span id="wb_uid90">Спальное место:</span></div>
                  <div id="wb_indexText207">
                     <span id="wb_uid91">Габариты кровати:</span></div>
                  <div id="wb_indexText208">
                     <span id="wb_uid92">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
                  <div id="wb_indexText209">
                     <span id="wb_uid93">Дополнительно возможно: <br></span><span id="wb_uid94">&#8226;</span><span id="wb_uid95"> Усиленное ортопедическое основание<br></span><span id="wb_uid96">&#8226;</span><span id="wb_uid97"> Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="wb_indexText210">
                     <span id="wb_uid98">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="indexLayer101">
                     <div id="indexLayer101_Container">
                     </div>
                  </div>
                  <input type="button" id="indexButton64" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat9').modal('hide');return false;" onmouseover="SetStyle('indexButton64', 'knop1_2');return false;" onmouseout="SetStyle('indexButton64', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               </div>
            </div>
         </div>
      </div>
      <div id="modal_krovat11" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText211">
                     <span id="wb_uid99">Гранд Бахия</span></div>
                  <div id="wb_indexText212">
                     <span id="wb_uid100">Кровать</span></div>
                  <input type="button" id="indexButton65" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat9').modal('hide');return false;" onmouseover="SetStyle('indexButton65', 'knop1_2');return false;" onmouseout="SetStyle('indexButton65', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
                  <div id="wb_indexText213">
                     <span id="wb_uid101">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText214">
                     <span id="wb_uid102">145/215 см<br>165/215 см<br>185/215 см<br>205/215 см</span></div>
                  <div id="wb_indexText215">
                     <span id="wb_uid103">Спальное место:</span></div>
                  <div id="wb_indexText216">
                     <span id="wb_uid104">Габариты кровати:</span></div>
                  <div id="wb_indexText217">
                     <span id="wb_uid105">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
                  <div id="wb_indexText218">
                     <span id="wb_uid106">Дополнительно возможно: <br></span><span id="wb_uid107">&#8226;</span><span id="wb_uid108"> Усиленное ортопедическое основание<br></span><span id="wb_uid109">&#8226;</span><span id="wb_uid110"> Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="wb_indexText219">
                     <span id="wb_uid111">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="indexLayer102">
                     <div id="indexLayer102_Container">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="modal_krovat12" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText220">
                     <span id="wb_uid112">Маэстро</span></div>
                  <div id="wb_indexText221">
                     <span id="wb_uid113">Кровать</span></div>
                  <input type="button" id="indexButton66" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat9').modal('hide');return false;" onmouseover="SetStyle('indexButton66', 'knop1_2');return false;" onmouseout="SetStyle('indexButton66', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
                  <div id="wb_indexText222">
                     <span id="wb_uid114">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText223">
                     <span id="wb_uid115">145/215 см<br>165/215 см<br>185/215 см<br>205/215 см</span></div>
                  <div id="wb_indexText224">
                     <span id="wb_uid116">Спальное место:</span></div>
                  <div id="wb_indexText225">
                     <span id="wb_uid117">Габариты кровати:</span></div>
                  <div id="wb_indexText226">
                     <span id="wb_uid118">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
                  <div id="wb_indexText227">
                     <span id="wb_uid119">Дополнительно возможно: <br></span><span id="wb_uid120">&#8226;</span><span id="wb_uid121"> Усиленное ортопедическое основание<br></span><span id="wb_uid122">&#8226;</span><span id="wb_uid123"> Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="wb_indexText228">
                     <span id="wb_uid124">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="indexLayer103">
                     <div id="indexLayer103_Container">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="modal_krovat13" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText229">
                     <span id="wb_uid125">Elena</span></div>
                  <div id="wb_indexText230">
                     <span id="wb_uid126">Кровать</span></div>
                  <input type="button" id="indexButton67" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat9').modal('hide');return false;" onmouseover="SetStyle('indexButton67', 'knop1_2');return false;" onmouseout="SetStyle('indexButton67', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
                  <div id="wb_indexText231">
                     <span id="wb_uid127">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText233">
                     <span id="wb_uid128">145/215 см<br>165/215 см<br>185/215 см<br>205/215 см</span></div>
                  <div id="wb_indexText234">
                     <span id="wb_uid129">Спальное место:</span></div>
                  <div id="wb_indexText235">
                     <span id="wb_uid130">Габариты кровати:</span></div>
                  <div id="wb_indexText237">
                     <span id="wb_uid131">Дополнительно возможно: <br></span><span id="wb_uid132">&#8226;</span><span id="wb_uid133"> Усиленное ортопедическое основание<br></span><span id="wb_uid134">&#8226;</span><span id="wb_uid135"> Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="wb_indexText238">
                     <span id="wb_uid136">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="indexLayer104">
                     <div id="indexLayer104_Container">
                     </div>
                  </div>
                  <div id="wb_indexText236">
                     <span id="wb_uid137">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
               </div>
            </div>
         </div>
      </div>
      <div id="modal_krovat14" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText239">
                     <span id="wb_uid138">Moment</span></div>
                  <div id="wb_indexText240">
                     <span id="wb_uid139">Кровать</span></div>
                  <input type="button" id="indexButton68" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat9').modal('hide');return false;" onmouseover="SetStyle('indexButton68', 'knop1_2');return false;" onmouseout="SetStyle('indexButton68', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
                  <div id="wb_indexText241">
                     <span id="wb_uid140">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText242">
                     <span id="wb_uid141">145/215 см<br>165/215 см<br>185/215 см<br>205/215 см</span></div>
                  <div id="wb_indexText243">
                     <span id="wb_uid142">Спальное место:</span></div>
                  <div id="wb_indexText244">
                     <span id="wb_uid143">Габариты кровати:</span></div>
                  <div id="wb_indexText245">
                     <span id="wb_uid144">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
                  <div id="wb_indexText246">
                     <span id="wb_uid145">Дополнительно возможно: <br></span><span id="wb_uid146">&#8226;</span><span id="wb_uid147"> Усиленное ортопедическое основание<br></span><span id="wb_uid148">&#8226;</span><span id="wb_uid149"> Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="wb_indexText247">
                     <span id="wb_uid150">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="indexLayer105">
                     <div id="indexLayer105_Container">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="modal_krovat15" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText248">
                     <span id="wb_uid151">Аква</span></div>
                  <div id="wb_indexText249">
                     <span id="wb_uid152">Кровать</span></div>
                  <input type="button" id="indexButton69" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat9').modal('hide');return false;" onmouseover="SetStyle('indexButton69', 'knop1_2');return false;" onmouseout="SetStyle('indexButton69', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
                  <div id="wb_indexText250">
                     <span id="wb_uid153">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText251">
                     <span id="wb_uid154">145/215 см<br>165/215 см<br>185/215 см<br>205/215 см</span></div>
                  <div id="wb_indexText252">
                     <span id="wb_uid155">Спальное место:</span></div>
                  <div id="wb_indexText253">
                     <span id="wb_uid156">Габариты кровати:</span></div>
                  <div id="wb_indexText254">
                     <span id="wb_uid157">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
                  <div id="wb_indexText255">
                     <span id="wb_uid158">Дополнительно возможно: <br></span><span id="wb_uid159">&#8226;</span><span id="wb_uid160"> Усиленное ортопедическое основание<br></span><span id="wb_uid161">&#8226;</span><span id="wb_uid162"> Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="wb_indexText256">
                     <span id="wb_uid163">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="indexLayer106">
                     <div id="indexLayer106_Container">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="modal_krovat16" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText257">
                     <span id="wb_uid164">Брианза</span></div>
                  <div id="wb_indexText258">
                     <span id="wb_uid165">Кровать</span></div>
                  <input type="button" id="indexButton70" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat9').modal('hide');return false;" onmouseover="SetStyle('indexButton70', 'knop1_2');return false;" onmouseout="SetStyle('indexButton70', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
                  <div id="wb_indexText259">
                     <span id="wb_uid166">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText260">
                     <span id="wb_uid167">145/215 см<br>165/215 см<br>185/215 см<br>205/215 см</span></div>
                  <div id="wb_indexText261">
                     <span id="wb_uid168">Спальное место:</span></div>
                  <div id="wb_indexText262">
                     <span id="wb_uid169">Габариты кровати:</span></div>
                  <div id="wb_indexText263">
                     <span id="wb_uid170">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
                  <div id="wb_indexText264">
                     <span id="wb_uid171">Дополнительно возможно: <br></span><span id="wb_uid172">&#8226;</span><span id="wb_uid173"> Усиленное ортопедическое основание<br></span><span id="wb_uid174">&#8226;</span><span id="wb_uid175"> Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="wb_indexText265">
                     <span id="wb_uid176">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="indexLayer107">
                     <div id="indexLayer107_Container">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="modal_krovat17" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText266">
                     <span id="wb_uid177">Миа</span></div>
                  <div id="wb_indexText267">
                     <span id="wb_uid178">Кровать</span></div>
                  <input type="button" id="indexButton71" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat9').modal('hide');return false;" onmouseover="SetStyle('indexButton71', 'knop1_2');return false;" onmouseout="SetStyle('indexButton71', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
                  <div id="wb_indexText268">
                     <span id="wb_uid179">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText269">
                     <span id="wb_uid180">145/215 см<br>165/215 см<br>185/215 см<br>205/215 см</span></div>
                  <div id="wb_indexText270">
                     <span id="wb_uid181">Спальное место:</span></div>
                  <div id="wb_indexText271">
                     <span id="wb_uid182">Габариты кровати:</span></div>
                  <div id="wb_indexText272">
                     <span id="wb_uid183">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
                  <div id="wb_indexText273">
                     <span id="wb_uid184">Дополнительно возможно: <br></span><span id="wb_uid185">&#8226;</span><span id="wb_uid186"> Усиленное ортопедическое основание<br></span><span id="wb_uid187">&#8226;</span><span id="wb_uid188"> Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="wb_indexText274">
                     <span id="wb_uid189">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="indexLayer108">
                     <div id="indexLayer108_Container">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="modal_krovat18" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText275">
                     <span id="wb_uid190">Салото</span></div>
                  <div id="wb_indexText276">
                     <span id="wb_uid191">Кровать</span></div>
                  <input type="button" id="indexButton72" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat9').modal('hide');return false;" onmouseover="SetStyle('indexButton72', 'knop1_2');return false;" onmouseout="SetStyle('indexButton72', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
                  <div id="wb_indexText277">
                     <span id="wb_uid192">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText278">
                     <span id="wb_uid193">145/215 см<br>165/215 см<br>185/215 см<br>205/215 см</span></div>
                  <div id="wb_indexText279">
                     <span id="wb_uid194">Спальное место:</span></div>
                  <div id="wb_indexText280">
                     <span id="wb_uid195">Габариты кровати:</span></div>
                  <div id="wb_indexText281">
                     <span id="wb_uid196">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
                  <div id="wb_indexText282">
                     <span id="wb_uid197">Дополнительно возможно: <br></span><span id="wb_uid198">&#8226;</span><span id="wb_uid199"> Усиленное ортопедическое основание<br></span><span id="wb_uid200">&#8226;</span><span id="wb_uid201"> Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="wb_indexText283">
                     <span id="wb_uid202">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="indexLayer109">
                     <div id="indexLayer109_Container">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="modal_krovat19" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText284">
                     <span id="wb_uid203">София</span></div>
                  <div id="wb_indexText285">
                     <span id="wb_uid204">Кровать</span></div>
                  <input type="button" id="indexButton73" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat9').modal('hide');return false;" onmouseover="SetStyle('indexButton73', 'knop1_2');return false;" onmouseout="SetStyle('indexButton73', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
                  <div id="wb_indexText286">
                     <span id="wb_uid205">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText287">
                     <span id="wb_uid206">145/215 см<br>165/215 см<br>185/215 см<br>205/215 см</span></div>
                  <div id="wb_indexText288">
                     <span id="wb_uid207">Спальное место:</span></div>
                  <div id="wb_indexText289">
                     <span id="wb_uid208">Габариты кровати:</span></div>
                  <div id="wb_indexText290">
                     <span id="wb_uid209">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
                  <div id="wb_indexText291">
                     <span id="wb_uid210">Дополнительно возможно: <br></span><span id="wb_uid211">&#8226;</span><span id="wb_uid212"> Усиленное ортопедическое основание<br></span><span id="wb_uid213">&#8226;</span><span id="wb_uid214"> Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="wb_indexText292">
                     <span id="wb_uid215">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="indexLayer110">
                     <div id="indexLayer110_Container">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="modal_krovat20" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText293">
                     <span id="wb_uid216">Феерия</span></div>
                  <div id="wb_indexText294">
                     <span id="wb_uid217">Кровать</span></div>
                  <input type="button" id="indexButton74" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat9').modal('hide');return false;" onmouseover="SetStyle('indexButton74', 'knop1_2');return false;" onmouseout="SetStyle('indexButton74', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
                  <div id="wb_indexText295">
                     <span id="wb_uid218">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText296">
                     <span id="wb_uid219">145/215 см<br>165/215 см<br>185/215 см<br>205/215 см</span></div>
                  <div id="wb_indexText297">
                     <span id="wb_uid220">Спальное место:</span></div>
                  <div id="wb_indexText298">
                     <span id="wb_uid221">Габариты кровати:</span></div>
                  <div id="wb_indexText299">
                     <span id="wb_uid222">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
                  <div id="wb_indexText300">
                     <span id="wb_uid223">Дополнительно возможно: <br></span><span id="wb_uid224">&#8226;</span><span id="wb_uid225"> Усиленное ортопедическое основание<br></span><span id="wb_uid226">&#8226;</span><span id="wb_uid227"> Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="wb_indexText301">
                     <span id="wb_uid228">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="indexLayer111">
                     <div id="indexLayer111_Container">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="modal_krovat21" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText302">
                     <span id="wb_uid229">Диана</span></div>
                  <div id="wb_indexText303">
                     <span id="wb_uid230">Кровать</span></div>
                  <input type="button" id="indexButton75" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat9').modal('hide');return false;" onmouseover="SetStyle('indexButton75', 'knop1_2');return false;" onmouseout="SetStyle('indexButton75', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
                  <div id="wb_indexText304">
                     <span id="wb_uid231">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText305">
                     <span id="wb_uid232">145/215 см<br>165/215 см<br>185/215 см<br>205/215 см</span></div>
                  <div id="wb_indexText306">
                     <span id="wb_uid233">Спальное место:</span></div>
                  <div id="wb_indexText307">
                     <span id="wb_uid234">Габариты кровати:</span></div>
                  <div id="wb_indexText308">
                     <span id="wb_uid235">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
                  <div id="wb_indexText309">
                     <span id="wb_uid236">Дополнительно возможно: <br></span><span id="wb_uid237">&#8226;</span><span id="wb_uid238"> Усиленное ортопедическое основание<br></span><span id="wb_uid239">&#8226;</span><span id="wb_uid240"> Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="wb_indexText310">
                     <span id="wb_uid241">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="indexLayer112">
                     <div id="indexLayer112_Container">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="modal_krovat9" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText156">
                     <span id="wb_uid242">Маквин</span></div>
                  <div id="wb_indexText157">
                     <span id="wb_uid243">Кровать</span></div>
                  <input type="button" id="indexButton35" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat9').modal('hide');return false;" onmouseover="SetStyle('indexButton35', 'knop1_2');return false;" onmouseout="SetStyle('indexButton35', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
                  <div id="wb_indexText158">
                     <span id="wb_uid244">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText159">
                     <span id="wb_uid245">145/215 см<br>165/215 см<br>185/215 см<br>205/215 см</span></div>
                  <div id="wb_indexText160">
                     <span id="wb_uid246">Спальное место:</span></div>
                  <div id="wb_indexText161">
                     <span id="wb_uid247">Габариты кровати:</span></div>
                  <div id="wb_indexText162">
                     <span id="wb_uid248">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
                  <div id="wb_indexText164">
                     <span id="wb_uid249">Дополнительно возможно: <br></span><span id="wb_uid250">&#8226;</span><span id="wb_uid251"> Усиленное ортопедическое основание<br></span><span id="wb_uid252">&#8226;</span><span id="wb_uid253"> Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="wb_indexText163">
                     <span id="wb_uid254">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="indexLayer74">
                     <div id="indexLayer74_Container">
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="modal_zayavka" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText74">
                     <span id="wb_uid255">чтобы заказать кровать</span></div>
                  <div id="wb_indexText75">
                     <span id="wb_uid256">Оставьте заявку</span></div>
                  <div id="wb_indexForm2">
                     <form name="indexForm1" method="post" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" accept-charset="UTF-8" id="indexForm2">
                        <input type="hidden" name="formid" value="indexform2">
                        <div id="wb_indexCheckbox2">
                           <input type="checkbox" id="indexCheckbox2" name="Политика конфидициальности" value="согласны" checked required><label for="indexCheckbox2"></label></div>
                        <div id="wb_indexText73" class="Oswald_ExtraLight">
                           <span id="wb_uid257">&#1053;&#1072;&#1078;&#1080;&#1084;&#1072;&#1103; &#1085;&#1072; &#1082;&#1085;&#1086;&#1087;&#1082;&#1091; "Узнать стоимость", &#1103; &#1076;&#1072;&#1102; &#1089;&#1086;&#1075;&#1083;&#1072;&#1089;&#1080;&#1077; &#1085;&#1072; &#1086;&#1073;&#1088;&#1072;&#1073;&#1086;&#1090;&#1082;&#1091; &#1087;&#1077;&#1088;&#1089;&#1086;&#1085;&#1072;&#1083;&#1100;&#1085;&#1099;&#1093; &#1076;&#1072;&#1085;&#1085;&#1099;&#1093; &#1080; &#1089;&#1086;&#1075;&#1083;&#1072;&#1096;&#1072;&#1102;&#1089;&#1100; c &#1091;&#1089;&#1083;&#1086;&#1074;&#1080;&#1103;&#1084;&#1080; &#1087;&#1086;&#1083;&#1080;&#1090;&#1080;&#1082;&#1080; &#1082;&#1086;&#1085;&#1092;&#1080;&#1076;&#1077;&#1085;&#1094;&#1080;&#1072;&#1083;&#1100;&#1085;&#1086;&#1089;&#1090;&#1080;</span></div>
                        <input type="text" id="indexEditbox5" name="Имя" value="" autocomplete="off" spellcheck="true" required pattern="[A-Za-zАБВГДЕЖЗИЙКЛМНОПРСТУФХЦШЩЪЫЬЭЮЯабвгдежзийклмнопрстуфхцшщъыьэюя \t\r\n\fа,б,в,г,д,е,ё,ж,з,и,й,к,л,м,н,о,п,р,с,т,у,ф,х,ц,ч,ш,щ,ъ,ы,ь,э,ю,я,А,Б,В,Г,Д,Е,Ё,Ж,З,И,Й,К,Л,М,Н,О,П,Р,С,Т,У,Ф,Х,Ц,Ч,Ш,Щ,Ъ,Ы,Ь,Э,Ю,Я]*$" placeholder="&#1042;&#1074;&#1077;&#1076;&#1080;&#1090;&#1077; &#1089;&#1074;&#1086;&#1077; &#1080;&#1084;&#1103;">
                        <input type="text" id="indexEditbox6" name="Номер телефона" value="" autocomplete="off" spellcheck="false" required placeholder="&#1042;&#1074;&#1077;&#1076;&#1080;&#1090;&#1077; &#1089;&#1074;&#1086;&#1081; &#1090;&#1077;&#1083;&#1077;&#1092;&#1086;&#1085;">
                        <div id="indexLayer63" class="blick-button">
                           <div id="indexLayer63_Container">
                              <input type="submit" id="indexButton24" onmouseover="SetStyle('indexButton24', 'knop1_2');return false;" onmouseout="SetStyle('indexButton24', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="modal_zvonok" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText77">
                     <span id="wb_uid258">чтобы мы перезвонили вам</span></div>
                  <div id="wb_indexText78">
                     <span id="wb_uid259">Оставьте заявку</span></div>
                  <div id="wb_indexForm3">
                     <form name="indexForm1" method="post" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" accept-charset="UTF-8" id="indexForm3">
                        <input type="hidden" name="formid" value="indexform3">
                        <div id="wb_indexCheckbox3">
                           <input type="checkbox" id="indexCheckbox3" name="Политика конфидициальности" value="согласны" checked required><label for="indexCheckbox3"></label></div>
                        <div id="wb_indexText76" class="Oswald_ExtraLight">
                           <span id="wb_uid260">&#1053;&#1072;&#1078;&#1080;&#1084;&#1072;&#1103; &#1085;&#1072; &#1082;&#1085;&#1086;&#1087;&#1082;&#1091; "Перезвоните мне", &#1103; &#1076;&#1072;&#1102; &#1089;&#1086;&#1075;&#1083;&#1072;&#1089;&#1080;&#1077; &#1085;&#1072; &#1086;&#1073;&#1088;&#1072;&#1073;&#1086;&#1090;&#1082;&#1091; &#1087;&#1077;&#1088;&#1089;&#1086;&#1085;&#1072;&#1083;&#1100;&#1085;&#1099;&#1093; &#1076;&#1072;&#1085;&#1085;&#1099;&#1093; &#1080; &#1089;&#1086;&#1075;&#1083;&#1072;&#1096;&#1072;&#1102;&#1089;&#1100; c &#1091;&#1089;&#1083;&#1086;&#1074;&#1080;&#1103;&#1084;&#1080; &#1087;&#1086;&#1083;&#1080;&#1090;&#1080;&#1082;&#1080; &#1082;&#1086;&#1085;&#1092;&#1080;&#1076;&#1077;&#1085;&#1094;&#1080;&#1072;&#1083;&#1100;&#1085;&#1086;&#1089;&#1090;&#1080;</span></div>
                        <input type="text" id="indexEditbox7" name="Имя" value="" autocomplete="off" spellcheck="true" required pattern="[A-Za-zАБВГДЕЖЗИЙКЛМНОПРСТУФХЦШЩЪЫЬЭЮЯабвгдежзийклмнопрстуфхцшщъыьэюя \t\r\n\fа,б,в,г,д,е,ё,ж,з,и,й,к,л,м,н,о,п,р,с,т,у,ф,х,ц,ч,ш,щ,ъ,ы,ь,э,ю,я,А,Б,В,Г,Д,Е,Ё,Ж,З,И,Й,К,Л,М,Н,О,П,Р,С,Т,У,Ф,Х,Ц,Ч,Ш,Щ,Ъ,Ы,Ь,Э,Ю,Я]*$" placeholder="&#1042;&#1074;&#1077;&#1076;&#1080;&#1090;&#1077; &#1089;&#1074;&#1086;&#1077; &#1080;&#1084;&#1103;">
                        <div id="indexLayer64" class="blick-button">
                           <div id="indexLayer64_Container">
                              <input type="submit" id="indexButton25" onmouseover="SetStyle('indexButton25', 'knop1_2');return false;" onmouseout="SetStyle('indexButton25', 'knop1_1');return false;" name="" value="Перезвоните мне" class="knop1_1">
                           </div>
                        </div>
                        <input type="text" id="indexEditbox8" name="Номер телефона" value="" autocomplete="off" spellcheck="false" required placeholder="&#1042;&#1074;&#1077;&#1076;&#1080;&#1090;&#1077; &#1089;&#1074;&#1086;&#1081; &#1090;&#1077;&#1083;&#1077;&#1092;&#1086;&#1085;">
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="modal_zayavka_maket" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText71">
                     <span id="wb_uid261">для рассчета стоимости вашей кровати</span></div>
                  <div id="wb_indexText72">
                     <span id="wb_uid262">Оставьте заявку</span></div>
                  <div id="wb_indexForm1">
                     <form name="indexForm1" method="post" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" accept-charset="UTF-8" id="indexForm1">
                        <input type="hidden" name="formid" value="indexform1">
                        <div id="wb_indexCheckbox1">
                           <input type="checkbox" id="indexCheckbox1" name="Политика конфидициальности" value="согласны" checked required><label for="indexCheckbox1"></label></div>
                        <div id="wb_indexText70" class="Oswald_ExtraLight">
                           <span id="wb_uid263">&#1053;&#1072;&#1078;&#1080;&#1084;&#1072;&#1103; &#1085;&#1072; &#1082;&#1085;&#1086;&#1087;&#1082;&#1091; "Узнать цену", &#1103; &#1076;&#1072;&#1102; &#1089;&#1086;&#1075;&#1083;&#1072;&#1089;&#1080;&#1077; &#1085;&#1072; &#1086;&#1073;&#1088;&#1072;&#1073;&#1086;&#1090;&#1082;&#1091; &#1087;&#1077;&#1088;&#1089;&#1086;&#1085;&#1072;&#1083;&#1100;&#1085;&#1099;&#1093; &#1076;&#1072;&#1085;&#1085;&#1099;&#1093; &#1080; &#1089;&#1086;&#1075;&#1083;&#1072;&#1096;&#1072;&#1102;&#1089;&#1100; c &#1091;&#1089;&#1083;&#1086;&#1074;&#1080;&#1103;&#1084;&#1080; &#1087;&#1086;&#1083;&#1080;&#1090;&#1080;&#1082;&#1080; &#1082;&#1086;&#1085;&#1092;&#1080;&#1076;&#1077;&#1085;&#1094;&#1080;&#1072;&#1083;&#1100;&#1085;&#1086;&#1089;&#1090;&#1080;</span></div>
                        <input type="text" id="indexEditbox2" name="Имя" value="" autocomplete="off" spellcheck="true" required pattern="[A-Za-zАБВГДЕЖЗИЙКЛМНОПРСТУФХЦШЩЪЫЬЭЮЯабвгдежзийклмнопрстуфхцшщъыьэюя \t\r\n\fа,б,в,г,д,е,ё,ж,з,и,й,к,л,м,н,о,п,р,с,т,у,ф,х,ц,ч,ш,щ,ъ,ы,ь,э,ю,я,А,Б,В,Г,Д,Е,Ё,Ж,З,И,Й,К,Л,М,Н,О,П,Р,С,Т,У,Ф,Х,Ц,Ч,Ш,Щ,Ъ,Ы,Ь,Э,Ю,Я]*$" placeholder="&#1042;&#1074;&#1077;&#1076;&#1080;&#1090;&#1077; &#1089;&#1074;&#1086;&#1077; &#1080;&#1084;&#1103;">
                        <input type="text" id="indexEditbox3" name="Номер телефона" value="" autocomplete="off" spellcheck="false" required placeholder="&#1042;&#1074;&#1077;&#1076;&#1080;&#1090;&#1077; &#1089;&#1074;&#1086;&#1081; &#1090;&#1077;&#1083;&#1077;&#1092;&#1086;&#1085;">
                        <div id="indexFileUpload1" class="input-group">
                           <input class="form-control" type="text" readonly placeholder="Прикрепите макет">
                           <label class="input-group-btn">
                              <input type="file" name="indexFileUpload1" id="indexFileUpload1-file" multiple><span class="btn">Прикрепить</span>
                           </label>
                        </div>
                        <div id="indexLayer62" class="blick-button">
                           <div id="indexLayer62_Container">
                              <input type="submit" id="indexButton22" onmouseover="SetStyle('indexButton22', 'knop1_2');return false;" onmouseout="SetStyle('indexButton22', 'knop1_1');return false;" name="" value="Узнать цену" class="knop1_1">
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
	  
	  <!--
      <div id="modal_katalog" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <div id="wb_indexText94">
                     <span id="wb_uid264">чтобы cкачать полный каталог кроватей в формате PDF</span></div>
                  <div id="wb_indexText95">
                     <span id="wb_uid265">Оставьте заявку</span></div>
                  <div id="wb_indexForm6">
                     <form name="indexForm1" method="post" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" accept-charset="UTF-8" id="indexForm6">
                        <input type="hidden" name="formid" value="indexform6">
                        <div id="wb_indexCheckbox6">
                           <input type="checkbox" id="indexCheckbox6" name="Политика конфидициальности" value="согласны" checked required><label for="indexCheckbox6"></label></div>
                        <div id="wb_indexText93" class="Oswald_ExtraLight">
                           <span id="wb_uid266">&#1053;&#1072;&#1078;&#1080;&#1084;&#1072;&#1103; &#1085;&#1072; &#1082;&#1085;&#1086;&#1087;&#1082;&#1091; "Скачать каталог", &#1103; &#1076;&#1072;&#1102; &#1089;&#1086;&#1075;&#1083;&#1072;&#1089;&#1080;&#1077; &#1085;&#1072; &#1086;&#1073;&#1088;&#1072;&#1073;&#1086;&#1090;&#1082;&#1091; &#1087;&#1077;&#1088;&#1089;&#1086;&#1085;&#1072;&#1083;&#1100;&#1085;&#1099;&#1093; &#1076;&#1072;&#1085;&#1085;&#1099;&#1093; &#1080; &#1089;&#1086;&#1075;&#1083;&#1072;&#1096;&#1072;&#1102;&#1089;&#1100; c &#1091;&#1089;&#1083;&#1086;&#1074;&#1080;&#1103;&#1084;&#1080; &#1087;&#1086;&#1083;&#1080;&#1090;&#1080;&#1082;&#1080; &#1082;&#1086;&#1085;&#1092;&#1080;&#1076;&#1077;&#1085;&#1094;&#1080;&#1072;&#1083;&#1100;&#1085;&#1086;&#1089;&#1090;&#1080;</span></div>
                        <input type="text" id="indexEditbox11" name="Имя" value="" autocomplete="off" spellcheck="true" required pattern="[A-Za-zАБВГДЕЖЗИЙКЛМНОПРСТУФХЦШЩЪЫЬЭЮЯабвгдежзийклмнопрстуфхцшщъыьэюя \t\r\n\fа,б,в,г,д,е,ё,ж,з,и,й,к,л,м,н,о,п,р,с,т,у,ф,х,ц,ч,ш,щ,ъ,ы,ь,э,ю,я,А,Б,В,Г,Д,Е,Ё,Ж,З,И,Й,К,Л,М,Н,О,П,Р,С,Т,У,Ф,Х,Ц,Ч,Ш,Щ,Ъ,Ы,Ь,Э,Ю,Я]*$" placeholder="&#1042;&#1074;&#1077;&#1076;&#1080;&#1090;&#1077; &#1089;&#1074;&#1086;&#1077; &#1080;&#1084;&#1103;">
                        <input type="text" id="indexEditbox12" name="Номер телефона" value="" autocomplete="off" spellcheck="false" required placeholder="&#1042;&#1074;&#1077;&#1076;&#1080;&#1090;&#1077; &#1089;&#1074;&#1086;&#1081; &#1090;&#1077;&#1083;&#1077;&#1092;&#1086;&#1085;">
                        <div id="indexLayer68" class="blick-button">
                           <div id="indexLayer68_Container">
                              <input type="submit" id="indexButton27" onmouseover="SetStyle('indexButton27', 'knop1_2');return false;" onmouseout="SetStyle('indexButton27', 'knop1_1');return false;" name="" value="Скачать каталог" class="knop1_1">
                           </div>
                        </div>
                     </form>
                  </div>
               </div>
            </div>
         </div>
      </div>
	  -->
	  
	  
      <div id="modal_krovat22" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <input type="button" id="indexButton80" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat22').modal('hide');return false;" onmouseover="SetStyle('indexButton80', 'knop1_2');return false;" onmouseout="SetStyle('indexButton80', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
                  <div id="wb_indexText335">
                     <span id="wb_uid267">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText336">
                     <span id="wb_uid268">230/225 см<br>250/225 см<br>270/225 см<br>290/225 см</span></div>
                  <div id="wb_indexText337">
                     <span id="wb_uid269">Спальное место:</span></div>
                  <div id="wb_indexText338">
                     <span id="wb_uid270">Габариты кровати:</span></div>
                  <div id="wb_indexText339">
                     <span id="wb_uid271">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
                  <div id="wb_indexText340">
                     <span id="wb_uid272">Дополнительно возможно: <br></span><span id="wb_uid273">&#8226;</span><span id="wb_uid274"> Усиленное ортопедическое основание<br></span><span id="wb_uid275">&#8226;</span><span id="wb_uid276"> Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="wb_indexText341">
                     <span id="wb_uid277">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="indexLayer35">
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="modal_krovat23" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <input type="button" id="indexButton81" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat23').modal('hide');return false;" onmouseover="SetStyle('indexButton81', 'knop1_2');return false;" onmouseout="SetStyle('indexButton81', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
                  <div id="wb_indexText333">
                     <span id="wb_uid278">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText334">
                     <span id="wb_uid279">170/215 см<br>190/215 см<br>210/215 см<br>230/215 см</span></div>
                  <div id="wb_indexText342">
                     <span id="wb_uid280">Спальное место:</span></div>
                  <div id="wb_indexText343">
                     <span id="wb_uid281">Габариты кровати:</span></div>
                  <div id="wb_indexText344">
                     <span id="wb_uid282">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
                  <div id="wb_indexText345">
                     <span id="wb_uid283">Дополнительно возможно: <br></span><span id="wb_uid284">&#8226;</span><span id="wb_uid285"> Усиленное ортопедическое основание<br></span><span id="wb_uid286">&#8226;</span><span id="wb_uid287"> Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="wb_indexText346">
                     <span id="wb_uid288">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="indexLayer61">
                  </div>
               </div>
            </div>
         </div>
      </div>
      <div id="modal_krovat24" class="modal fade" role="dialog">
         <div class="modal-dialog">
            <div class="modal-content">
               <div class="modal-body">
                  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                  <input type="button" id="indexButton82" onclick="$('#modal_zayavka').modal('show');$('#modal_krovat24').modal('hide');return false;" onmouseover="SetStyle('indexButton82', 'knop1_2');return false;" onmouseout="SetStyle('indexButton82', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
                  <div id="wb_indexText347">
                     <span id="wb_uid289">120/200 см<br>140/200 см<br>160/200 см<br>180/200 см</span></div>
                  <div id="wb_indexText348">
                     <span id="wb_uid290">170/230 см<br>190/230 см<br>210/230 см<br>230/230 см</span></div>
                  <div id="wb_indexText349">
                     <span id="wb_uid291">Спальное место:</span></div>
                  <div id="wb_indexText350">
                     <span id="wb_uid292">Габариты кровати:</span></div>
                  <div id="wb_indexText351">
                     <span id="wb_uid293">Возможно изготовление кроватей нестандартного размера и вариантов обивки!</span></div>
                  <div id="wb_indexText352">
                     <span id="wb_uid294">Дополнительно возможно: <br></span><span id="wb_uid295">&#8226;</span><span id="wb_uid296"> Усиленное ортопедическое основание<br></span><span id="wb_uid297">&#8226;</span><span id="wb_uid298"> Подъемный механизм с бельевыми ящиками</span></div>
                  <div id="wb_indexText353">
                     <span id="wb_uid299">Огромный выбор обивочных материалов: <strong>бархат</strong>; <strong>велюр</strong>; <strong>велюр</strong> <strong>люкс</strong>; <strong>микровелюр</strong>; <strong>рогожка</strong>, <strong>жаккард</strong>; <strong>шенилл</strong>; <strong>эко кожа высшего сорта</strong>; <strong>кожа</strong></span></div>
                  <div id="indexLayer67">
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div id="indexLayer36">
      <div id="indexLayer36_Container">
         <div id="wb_indexText48">
            <span id="wb_uid300">Нечего страшного, мы изготовим для вас кровать по вашему макету или просто фото.<br><!--<br>А также мы можем изготовить реплику любых известных брендов кроватей, по стоимости в 10 раз дешевле оригинала.--></span></div>
         <div id="wb_indexText47">
            <span id="wb_uid301">Не нашли понравившуюся модель кровати в нашем каталоге?</span></div>
         <div id="indexLayer37">
            <div id="indexLayer37_Container">
            </div>
         </div>
         <div id="indexLayer57" class="blick-button">
            <div id="indexLayer57_Container">
               <input type="button" id="indexButton21" onclick="$('#modal_zayavka_maket').modal('show');return false;" onmouseover="SetStyle('indexButton21', 'knop1_2');return false;" onmouseout="SetStyle('indexButton21', 'knop1_1');return false;" name="" value="Отправить макет или фото" class="knop1_1">
            </div>
         </div>
      </div>
   </div>
   <div id="indexLayer13">
      <div id="indexLayer13_Container">
         <div id="wb_indexText15">
            <span id="wb_uid302">Популярные модели</span></div>
         <div id="wb_indexText16">
            <span id="wb_uid303">кроватей с мягкой обивкой</span></div>
         <div id="indexLayer16">
            <div id="indexLayer16_Container">
               <div id="indexSlideShow2">
                  <a href="images/2%2d%282%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/2%2d%282%29.jpg" alt="" title=""></a>
                  <a href="images/2%2d%281%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/2%2d%281%29.jpg" id="wb_uid304" alt="" title=""></a>
               </div>
               <div id="wb_indexText20">
                  <span id="wb_uid305">Global</span></div>
               <div id="wb_indexText21">
                  <span id="wb_uid306">Кровать</span></div>
               <input type="button" id="indexButton3" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexButton3', 'knop1_2');return false;" onmouseout="SetStyle('indexButton3', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <input type="button" id="indexButton5" onclick="$('#modal_krovat2').modal('show');return false;" onmouseover="SetStyle('indexButton5', 'knop2_2');return false;" onmouseout="SetStyle('indexButton5', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexPhotoGallery2">
                  <div id="indexPhotoGallery2">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/2-(1).jpg" data-rel=""><img loading="lazy"   alt="" src="images/2-(1).jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/2-(2).jpg" data-rel=""><img loading="lazy"   loading="lazy"   alt="" src="images/2-(2).jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
               <div id="wb_indexText198">
                  <span id="wb_uid307">53.000 рублей</span></div>
            </div>
         </div>
         <div id="indexLayer18">
            <div id="indexLayer18_Container">
               <div id="indexSlideShow4">
                  <a href="images/3%2d%282%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/3%2d%282%29.jpg" alt="" title=""></a>
                  <a href="images/3%2d%281%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/3%2d%281%29.jpg" id="wb_uid308" alt="" title=""></a>
               </div>
               <div id="wb_indexText23">
                  <span id="wb_uid309">Шик</span></div>
               <div id="wb_indexText24">
                  <span id="wb_uid310">Кровать</span></div>
               <input type="button" id="indexButton6" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexButton6', 'knop1_2');return false;" onmouseout="SetStyle('indexButton6', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <input type="button" id="indexButton7" onclick="$('#modal_krovat3').modal('show');return false;" onmouseover="SetStyle('indexButton7', 'knop2_2');return false;" onmouseout="SetStyle('indexButton7', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexPhotoGallery12">
                  <div id="indexPhotoGallery12">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/3-(1).jpg" data-rel=""><img loading="lazy"   alt="" src="images/3-(1).jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/3-(2).jpg" data-rel=""><img loading="lazy"   alt="" src="images/3-(2).jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
               <div id="wb_indexText199">
                  <span id="wb_uid311">70.000 рублей</span></div>
            </div>
         </div>
         <div id="indexLayer24">
            <div id="indexLayer24_Container">
               <div id="indexSlideShow6">
                  <a href="images/4%2d%282%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/4%2d%282%29.jpg" alt="" title=""></a>
                  <a href="images/4%2d%281%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/4%2d%281%29.jpg" id="wb_uid312" alt="" title=""></a>
               </div>
               <div id="wb_indexText32">
                  <span id="wb_uid313">Milky Way</span></div>
               <div id="wb_indexText33">
                  <span id="wb_uid314">Кровать</span></div>
               <input type="button" id="indexButton12" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexButton12', 'knop1_2');return false;" onmouseout="SetStyle('indexButton12', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <input type="button" id="indexButton13" onclick="$('#modal_krovat4').modal('show');return false;" onmouseover="SetStyle('indexButton13', 'knop2_2');return false;" onmouseout="SetStyle('indexButton13', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexPhotoGallery13">
                  <div id="indexPhotoGallery13">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/4-(1).jpg" data-rel=""><img loading="lazy"   alt="" src="images/4-(1).jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/4-(2).jpg" data-rel=""><img loading="lazy"   alt="" src="images/4-(2).jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
               <div id="wb_indexText200">
                  <span id="wb_uid315">55.000 рублей</span></div>
            </div>
         </div>
         <div id="indexLayer22">
            <div id="indexLayer22_Container">
               <div id="indexSlideShow7">
                  <a href="images/5%2d%282%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/5%2d%282%29.jpg" alt="" title=""></a>
                  <a href="images/5%2d%281%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/5%2d%281%29.jpg" id="wb_uid316" alt="" title=""></a>
               </div>
               <div id="wb_indexText29">
                  <span id="wb_uid317">Anita</span></div>
               <div id="wb_indexText30">
                  <span id="wb_uid318">Кровать</span></div>
               <input type="button" id="indexButton10" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexButton10', 'knop1_2');return false;" onmouseout="SetStyle('indexButton10', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <input type="button" id="indexButton11" onclick="$('#modal_krovat5').modal('show');return false;" onmouseover="SetStyle('indexButton11', 'knop2_2');return false;" onmouseout="SetStyle('indexButton11', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexPhotoGallery14">
                  <div id="indexPhotoGallery14">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/5-(1).jpg" data-rel=""><img loading="lazy"   alt="" src="images/5-(1).jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/5-(2).jpg" data-rel=""><img loading="lazy"   alt="" src="images/5-(2).jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
               <div id="wb_indexText201">
                  <span id="wb_uid319">43.500 рублей</span></div>
            </div>
         </div>
         <div id="indexLayer20">
            <div id="indexLayer20_Container">
               <div id="indexSlideShow9">
                  <a href="images/6%2d%282%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/6%2d%282%29.jpg" alt="" title=""></a>
                  <a href="images/6%2d%281%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/6%2d%281%29.jpg" id="wb_uid320" alt="" title=""></a>
               </div>
               <div id="wb_indexText26">
                  <span id="wb_uid321">Kim</span></div>
               <div id="wb_indexText27">
                  <span id="wb_uid322">Кровать</span></div>
               <input type="button" id="indexButton8" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexButton8', 'knop1_2');return false;" onmouseout="SetStyle('indexButton8', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <input type="button" id="indexButton9" onclick="$('#modal_krovat6').modal('show');return false;" onmouseover="SetStyle('indexButton9', 'knop2_2');return false;" onmouseout="SetStyle('indexButton9', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexPhotoGallery15">
                  <div id="indexPhotoGallery15">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/6-(1).jpg" data-rel=""><img loading="lazy"   alt="" src="images/6-(1).jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/6-(2).jpg" data-rel=""><img loading="lazy"   alt="" src="images/6-(2).jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
               <div id="wb_indexText311">
                  <span id="wb_uid323">97.000 рублей</span></div>
            </div>
         </div>
         <div id="indexLayer26">
            <div id="indexLayer26_Container">
               <div id="indexSlideShow13">
                  <a href="images/9%2d%282%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/9%2d%282%29.jpg" alt="" title=""></a>
                  <a href="images/9%2d%281%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/9%2d%281%29.jpg" id="wb_uid324" alt="" title=""></a>
               </div>
               <div id="wb_indexText36">
                  <span id="wb_uid325">Кровать</span></div>
               <input type="button" id="indexButton14" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexButton14', 'knop1_2');return false;" onmouseout="SetStyle('indexButton14', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <input type="button" id="indexButton15" onclick="$('#modal_krovat9').modal('show');return false;" onmouseover="SetStyle('indexButton15', 'knop2_2');return false;" onmouseout="SetStyle('indexButton15', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexPhotoGallery18">
                  <div id="indexPhotoGallery18">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/9-(1).jpg" data-rel=""><img loading="lazy"   alt="" src="images/9-(1).jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/9-(2).jpg" data-rel=""><img loading="lazy"   alt="" src="images/9-(2).jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
               <div id="wb_indexText314">
                  <span id="wb_uid326">53.000 рублей</span></div>
               <div id="wb_indexText35">
                  <span id="wb_uid327">Маквин</span></div>
            </div>
         </div>
         <div id="indexLayer28">
            <div id="indexLayer28_Container">
               <div id="indexSlideShow12">
                  <a href="images/8%2d%282%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/8%2d%282%29.jpg" alt="" title=""></a>
                  <a href="images/8%2d%281%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/8%2d%281%29.jpg" id="wb_uid328" alt="" title=""></a>
               </div>
               <div id="wb_indexText38">
                  <span id="wb_uid329">Tall</span></div>
               <div id="wb_indexText39">
                  <span id="wb_uid330">Кровать</span></div>
               <input type="button" id="indexButton16" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexButton16', 'knop1_2');return false;" onmouseout="SetStyle('indexButton16', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <input type="button" id="indexButton17" onclick="$('#modal_krovat8').modal('show');return false;" onmouseover="SetStyle('indexButton17', 'knop2_2');return false;" onmouseout="SetStyle('indexButton17', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexPhotoGallery17">
                  <div id="indexPhotoGallery17">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/8-(1).jpg" data-rel=""><img loading="lazy"   alt="" src="images/8-(1).jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/8-(2).jpg" data-rel=""><img loading="lazy"   alt="" src="images/8-(2).jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
               <div id="wb_indexText313">
                  <span id="wb_uid331">от 60.000 рублей</span></div>
            </div>
         </div>
         <div id="indexLayer30">
            <div id="indexLayer30_Container">
               <div id="indexSlideShow10">
                  <a href="images/7%2d%282%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/7%2d%282%29.jpg" alt="" title=""></a>
                  <a href="images/7%2d%281%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/7%2d%281%29.jpg" id="wb_uid332" alt="" title=""></a>
               </div>
               <div id="wb_indexText41">
                  <span id="wb_uid333">Black Wave</span></div>
               <div id="wb_indexText42">
                  <span id="wb_uid334">Кровать</span></div>
               <input type="button" id="indexButton18" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexButton18', 'knop1_2');return false;" onmouseout="SetStyle('indexButton18', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <input type="button" id="indexButton19" onclick="$('#modal_krovat7').modal('show');return false;" onmouseover="SetStyle('indexButton19', 'knop2_2');return false;" onmouseout="SetStyle('indexButton19', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexPhotoGallery16">
                  <div id="indexPhotoGallery16">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/7-(1).jpg" data-rel=""><img loading="lazy"   alt="" src="images/7-(1).jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/7-(2).jpg" data-rel=""><img loading="lazy"   alt="" src="images/7-(2).jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
               <div id="wb_indexText312">
                  <span id="wb_uid335">60.000 рублей</span></div>
            </div>
         </div>
         <div id="indexLayer60">
            <div id="indexLayer60_Container">
               <div id="indexSlideShow5">
                  <a href="images/10%2d%281%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/10%2d%281%29.jpg" alt="" title=""></a>
                  <a href="images/10%2d%282%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/10%2d%282%29.jpg" id="wb_uid336" alt="" title=""></a>
               </div>
               <div id="wb_indexText68">
                  <span id="wb_uid337">Грейленд</span></div>
               <div id="wb_indexText69">
                  <span id="wb_uid338">Кровать</span></div>
               <input type="button" id="indexButton39" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexButton39', 'knop1_2');return false;" onmouseout="SetStyle('indexButton39', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <input type="button" id="indexButton40" onclick="$('#modal_krovat10').modal('show');return false;" onmouseover="SetStyle('indexButton40', 'knop2_2');return false;" onmouseout="SetStyle('indexButton40', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexPhotoGallery3">
                  <div id="indexPhotoGallery3">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/10-(1).jpg" data-rel=""><img loading="lazy"   alt="" src="images/10-(1).jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/10-(2).jpg" data-rel=""><img loading="lazy"   alt="" src="images/10-(2).jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
               <div id="wb_indexText315">
                  <span id="wb_uid339">51.000 рублей</span></div>
            </div>
         </div>
         <div id="indexLayer75">
            <div id="indexLayer75_Container">
               <div id="indexSlideShow8">
                  <a href="images/11%2d%281%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/11%2d%281%29.jpg" alt="" title=""></a>
                  <a href="images/11%2d%282%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/11%2d%282%29.jpg" id="wb_uid340" alt="" title=""></a>
               </div>
               <div id="wb_indexText80">
                  <span id="wb_uid341">Гранд Бахия</span></div>
               <div id="wb_indexText84">
                  <span id="wb_uid342">Кровать</span></div>
               <input type="button" id="indexButton41" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexButton41', 'knop1_2');return false;" onmouseout="SetStyle('indexButton41', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <input type="button" id="indexButton42" onclick="$('#modal_krovat11').modal('show');return false;" onmouseover="SetStyle('indexButton42', 'knop2_2');return false;" onmouseout="SetStyle('indexButton42', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexPhotoGallery4">
                  <div id="indexPhotoGallery4">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/11-(1).jpg" data-rel=""><img loading="lazy"   alt="" src="images/11-(1).jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/11-(2).jpg" data-rel=""><img loading="lazy"   alt="" src="images/11-(2).jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
               <div id="wb_indexText316">
                  <span id="wb_uid343">от 70.000 рублей</span></div>
            </div>
         </div>
         <div id="indexLayer79">
            <div id="indexLayer79_Container">
               <div id="indexSlideShow16">
                  <a href="images/13%2d%281%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/13%2d%281%29.jpg" alt="" title=""></a>
                  <a href="images/13%2d%282%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/13%2d%282%29.jpg" id="wb_uid344" alt="" title=""></a>
               </div>
               <div id="wb_indexText179">
                  <span id="wb_uid345">Elena</span></div>
               <div id="wb_indexText180">
                  <span id="wb_uid346">Кровать</span></div>
               <input type="button" id="indexButton49" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexButton49', 'knop1_2');return false;" onmouseout="SetStyle('indexButton49', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <input type="button" id="indexButton50" onclick="$('#modal_krovat13').modal('show');return false;" onmouseover="SetStyle('indexButton50', 'knop2_2');return false;" onmouseout="SetStyle('indexButton50', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexPhotoGallery8">
                  <div id="indexPhotoGallery8">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/13-(1).jpg" data-rel=""><img loading="lazy"   alt="" src="images/13-(1).jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/13-(2).jpg" data-rel=""><img loading="lazy"   alt="" src="images/13-(2).jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
               <div id="wb_indexText318">
                  <span id="wb_uid347">42.000 рублей</span></div>
            </div>
         </div>
         <div id="indexLayer80">
            <div id="indexLayer80_Container">
               <div id="indexSlideShow17">
                  <a href="images/16%2d%281%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/16%2d%281%29.jpg" alt="" title=""></a>
                  <a href="images/16%2d%282%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/16%2d%282%29.jpg" id="wb_uid348" alt="" title=""></a>
               </div>
               <div id="wb_indexText181">
                  <span id="wb_uid349">Брианза</span></div>
               <div id="wb_indexText182">
                  <span id="wb_uid350">Кровать</span></div>
               <input type="button" id="indexButton51" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexButton51', 'knop1_2');return false;" onmouseout="SetStyle('indexButton51', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <input type="button" id="indexButton52" onclick="$('#modal_krovat16').modal('show');return false;" onmouseover="SetStyle('indexButton52', 'knop2_2');return false;" onmouseout="SetStyle('indexButton52', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexPhotoGallery9">
                  <div id="indexPhotoGallery9">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/16-(1).jpg" data-rel=""><img loading="lazy"   alt="" src="images/16-(1).jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/16-(2).jpg" data-rel=""><img loading="lazy"   alt="" src="images/16-(2).jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
               <div id="wb_indexText321">
                  <span id="wb_uid351">45.000 рублей</span></div>
            </div>
         </div>
         <div id="indexLayer84">
            <div id="indexLayer84_Container">
               <div id="indexSlideShow21">
                  <a href="images/20%2d%281%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/20%2d%281%29.jpg" alt="" title=""></a>
                  <a href="images/20%2d%282%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/20%2d%282%29.jpg" id="wb_uid352" alt="" title=""></a>
               </div>
               <div id="wb_indexText189">
                  <span id="wb_uid353">Феерия</span></div>
               <div id="wb_indexText190">
                  <span id="wb_uid354">Кровать</span></div>
               <input type="button" id="indexButton59" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexButton59', 'knop1_2');return false;" onmouseout="SetStyle('indexButton59', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <input type="button" id="indexButton60" onclick="$('#modal_krovat20').modal('show');return false;" onmouseover="SetStyle('indexButton60', 'knop2_2');return false;" onmouseout="SetStyle('indexButton60', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexPhotoGallery21">
                  <div id="indexPhotoGallery21">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/20-(1).jpg" data-rel=""><img loading="lazy"   alt="" src="images/20-(1).jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/20-(2).jpg" data-rel=""><img loading="lazy"   alt="" src="images/20-(2).jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
               <div id="wb_indexText325">
                  <span id="wb_uid355">49.000 рублей</span></div>
            </div>
         </div>
         <div id="indexLayer83">
            <div id="indexLayer83_Container">
               <div id="indexSlideShow20">
                  <a href="images/19%2d%281%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/19%2d%281%29.jpg" alt="" title=""></a>
                  <a href="images/19%2d%282%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/19%2d%282%29.jpg" id="wb_uid356" alt="" title=""></a>
               </div>
               <input type="button" id="indexButton57" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexButton57', 'knop1_2');return false;" onmouseout="SetStyle('indexButton57', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <input type="button" id="indexButton58" onclick="$('#modal_krovat19').modal('show');return false;" onmouseover="SetStyle('indexButton58', 'knop2_2');return false;" onmouseout="SetStyle('indexButton58', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexPhotoGallery20">
                  <div id="indexPhotoGallery20">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/19-(1).jpg" data-rel=""><img loading="lazy"   alt="" src="images/19-(1).jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/19-(2).jpg" data-rel=""><img loading="lazy"   alt="" src="images/19-(2).jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
               <div id="wb_indexText324">
                  <span id="wb_uid357">43.000 рублей</span></div>
               <div id="wb_indexText187">
                  <span id="wb_uid358">София</span></div>
               <div id="wb_indexText188">
                  <span id="wb_uid359">Кровать</span></div>
            </div>
         </div>
         <div id="indexLayer85">
            <div id="indexLayer85_Container">
               <div id="indexSlideShow22">
                  <a href="images/21%2d%281%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/21%2d%281%29.jpg" alt="" title=""></a>
                  <a href="images/21%2d%282%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/21%2d%282%29.jpg" id="wb_uid360" alt="" title=""></a>
               </div>
               <div id="wb_indexText191">
                  <span id="wb_uid361">Диана</span></div>
               <div id="wb_indexText192">
                  <span id="wb_uid362">Детская кровать</span></div>
               <input type="button" id="indexButton62" onclick="$('#modal_krovat21').modal('show');return false;" onmouseover="SetStyle('indexButton62', 'knop2_2');return false;" onmouseout="SetStyle('indexButton62', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexPhotoGallery22">
                  <div id="indexPhotoGallery22">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/21-(1).jpg" data-rel=""><img loading="lazy"   alt="" src="images/21-(1).jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/21-(2).jpg" data-rel=""><img loading="lazy"   alt="" src="images/21-(2).jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
               <input type="button" id="indexButton61" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexButton61', 'knop1_2');return false;" onmouseout="SetStyle('indexButton61', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <div id="wb_indexText326">
                  <span id="wb_uid363">43.000 рублей</span></div>
            </div>
         </div>
         <div id="indexLayer82">
            <div id="indexLayer82_Container">
               <div id="indexSlideShow19">
                  <a href="images/18%2d%281%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/18%2d%281%29.jpg" alt="" title=""></a>
                  <a href="images/18%2d%282%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/18%2d%282%29.jpg" id="wb_uid364" alt="" title=""></a>
               </div>
               <div id="wb_indexText185">
                  <span id="wb_uid365">Салото</span></div>
               <div id="wb_indexText186">
                  <span id="wb_uid366">Кровать</span></div>
               <input type="button" id="indexButton55" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexButton55', 'knop1_2');return false;" onmouseout="SetStyle('indexButton55', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <input type="button" id="indexButton56" onclick="$('#modal_krovat18').modal('show');return false;" onmouseover="SetStyle('indexButton56', 'knop2_2');return false;" onmouseout="SetStyle('indexButton56', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexPhotoGallery19">
                  <div id="indexPhotoGallery19">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/18-(1).jpg" data-rel=""><img loading="lazy"   alt="" src="images/18-(1).jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/18-(2).jpg" data-rel=""><img loading="lazy"   alt="" src="images/18-(2).jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
               <div id="wb_indexText323">
                  <span id="wb_uid367">50.000 рублей</span></div>
            </div>
         </div>
         <div id="indexLayer81">
            <div id="indexLayer81_Container">
               <div id="indexSlideShow18">
                  <a href="images/17%2d%281%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/17%2d%281%29.jpg" alt="" title=""></a>
                  <a href="images/17%2d%282%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/17%2d%282%29.jpg" id="wb_uid368" alt="" title=""></a>
               </div>
               <div id="wb_indexText183">
                  <span id="wb_uid369">Миа</span></div>
               <div id="wb_indexText184">
                  <span id="wb_uid370">Кровать</span></div>
               <input type="button" id="indexButton53" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexButton53', 'knop1_2');return false;" onmouseout="SetStyle('indexButton53', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <input type="button" id="indexButton54" onclick="$('#modal_krovat17').modal('show');return false;" onmouseover="SetStyle('indexButton54', 'knop2_2');return false;" onmouseout="SetStyle('indexButton54', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexPhotoGallery10">
                  <div id="indexPhotoGallery10">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/17-(1).jpg" data-rel=""><img loading="lazy"   alt="" src="images/17-(1).jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/17-(2).jpg" data-rel=""><img loading="lazy"   alt="" src="images/17-(2).jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
               <div id="wb_indexText322">
                  <span id="wb_uid371">45.000 рублей</span></div>
            </div>
         </div>
         <div id="indexLayer78">
            <div id="indexLayer78_Container">
               <div id="indexSlideShow15">
                  <a href="images/14%2d%281%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/14%2d%281%29.jpg" alt="" title=""></a>
                  <a href="images/14%2d%282%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/14%2d%282%29.jpg" id="wb_uid372" alt="" title=""></a>
               </div>
               <div id="wb_indexText177">
                  <span id="wb_uid373">Moment</span></div>
               <div id="wb_indexText178">
                  <span id="wb_uid374">Кровать</span></div>
               <input type="button" id="indexButton47" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexButton47', 'knop1_2');return false;" onmouseout="SetStyle('indexButton47', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <input type="button" id="indexButton48" onclick="$('#modal_krovat14').modal('show');return false;" onmouseover="SetStyle('indexButton48', 'knop2_2');return false;" onmouseout="SetStyle('indexButton48', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexPhotoGallery7">
                  <div id="indexPhotoGallery7">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/14-(1).jpg" data-rel=""><img loading="lazy"   alt="" src="images/14-(1).jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/14-(2).jpg" data-rel=""><img loading="lazy"   alt="" src="images/14-(2).jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
               <div id="wb_indexText319">
                  <span id="wb_uid375">47.000 рублей</span></div>
            </div>
         </div>
         <div id="indexLayer77">
            <div id="indexLayer77_Container">
               <div id="indexSlideShow14">
                  <a href="images/15%2d%281%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/15%2d%281%29.jpg" alt="" title=""></a>
                  <a href="images/15%2d%282%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/15%2d%282%29.jpg" id="wb_uid376" alt="" title=""></a>
               </div>
               <div id="wb_indexText175">
                  <span id="wb_uid377">Аква</span></div>
               <div id="wb_indexText176">
                  <span id="wb_uid378">Кровать</span></div>
               <input type="button" id="indexButton45" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexLayer45', 'knop1_2');return false;" onmouseout="SetStyle('indexLayer45', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <input type="button" id="indexButton46" onclick="$('#modal_krovat15').modal('show');return false;" onmouseover="SetStyle('indexLayer46', 'knop2_2');return false;" onmouseout="SetStyle('indexLayer46', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexPhotoGallery6">
                  <div id="indexPhotoGallery6">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/15-(1).jpg" data-rel=""><img loading="lazy"   alt="" src="images/15-(1).jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/15-(2).jpg" data-rel=""><img loading="lazy"   alt="" src="images/15-(2).jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
               <div id="wb_indexText320">
                  <span id="wb_uid379">45.000 рублей</span></div>
            </div>
         </div>
         <div id="indexLayer76">
            <div id="indexLayer76_Container">
               <div id="indexSlideShow11">
                  <a href="images/12%2d%281%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/12%2d%281%29.jpg" alt="" title=""></a>
                  <a href="images/12%2d%282%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/12%2d%282%29.jpg" id="wb_uid380" alt="" title=""></a>
               </div>
               <div id="wb_indexText173">
                  <span id="wb_uid381">Маэстро</span></div>
               <div id="wb_indexText174">
                  <span id="wb_uid382">Кровать</span></div>
               <input type="button" id="indexButton44" onclick="$('#modal_krovat12').modal('show');return false;" onmouseover="SetStyle('indexButton44', 'knop2_2');return false;" onmouseout="SetStyle('indexButton44', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexPhotoGallery5">
                  <div id="indexPhotoGallery5">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/12-(1).jpg" data-rel=""><img loading="lazy"   alt="" src="images/12-(1).jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/12-(2).jpg" data-rel=""><img loading="lazy"   alt="" src="images/12-(2).jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
               <input type="button" id="indexButton43" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexButton43', 'knop1_2');return false;" onmouseout="SetStyle('indexButton43', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <div id="wb_indexText317">
                  <span id="wb_uid383">80.000 рублей</span></div>
            </div>
         </div>
         <div id="indexLayer114">
            <div id="wb_indexText194">
               <span id="wb_uid384">Кровати</span></div>
         </div>
         <div id="indexLayer14">
            <div id="indexLayer14_Container">
               <div id="wb_indexText17">
                  <span id="wb_uid385">Балу</span></div>
               <div id="wb_indexText18">
                  <span id="wb_uid386">Кровать</span></div>
               <input type="button" id="indexButton1" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexButton1', 'knop1_2');return false;" onmouseout="SetStyle('indexButton1', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <input type="button" id="indexButton2" onclick="$('#modal_krovat1').modal('show');return false;" onmouseover="SetStyle('indexButton2', 'knop2_2');return false;" onmouseout="SetStyle('indexButton2', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexPhotoGallery11">
                  <div id="indexPhotoGallery11">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/1-(1).jpg" data-rel=""><img loading="lazy"   alt="" src="images/1-(1).jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/1-(2).jpg" data-rel=""><img loading="lazy"   alt="" src="images/1-(2).jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
               <div id="indexSlideShow3">
                  <a href="images/1%2d%282%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/1%2d%282%29.jpg" alt="" title=""></a>
                  <a href="images/1%2d%281%29.jpg" data-rel=""><img loading="lazy"   class="image" src="images/1%2d%281%29.jpg" id="wb_uid387" alt="" title=""></a>
               </div>
               <div id="wb_indexText197">
                  <span id="wb_uid388">67.000 рублей</span></div>
            </div>
         </div>
		 
		 
         <div id="wb_indexText195">
            <span id="wb_uid389"><a href="./divany.php" class="menu_black">Диваны</a></span></div>
         
		 <!--
		 <div id="indexLayer59" class="blick-button">
            <div id="indexLayer59_Container">
               <input type="button" id="indexButton20" onclick="$('#modal_katalog').modal('show');return false;" onmouseover="SetStyle('indexButton20', 'knop3_2');return false;" onmouseout="SetStyle('indexButton20', 'knop3_1');return false;" name="" value="Полный каталог" class="knop3_1">
            </div>
         </div>
		 
		 
         <div id="wb_indexText44">
            <span id="wb_uid390">Хотите увидеть еще модели кроватей, тогда вы можете скачать наш полный каталог кроватей, в формате PDF</span>
			</div>
		-->	
			
         <div id="indexLayer34">
            <div id="indexLayer34_Container">
               <div id="wb_indexText330">
                  <span id="wb_uid391">Балатели</span></div>
               <div id="wb_indexText331">
                  <span id="wb_uid392">Детская кровать</span></div>
               <input type="button" id="indexButton78" onclick="$('#modal_krovat24').modal('show');return false;" onmouseover="SetStyle('indexButton78', 'knop2_2');return false;" onmouseout="SetStyle('indexButton78', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexPhotoGallery25">
                  <div id="indexPhotoGallery25">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/Balateli.jpg" data-rel=""><img loading="lazy"   alt="" src="images/Balateli.jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/Balateli1.jpg" data-rel=""><img loading="lazy"   alt="" src="images/Balateli1.jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
               <div id="wb_indexText332">
                  <span id="wb_uid393">120.000 рублей</span></div>
               <input type="button" id="indexButton79" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexButton79', 'knop1_2');return false;" onmouseout="SetStyle('indexButton79', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <div id="indexSlideShow25">
                  <a href="images/Balateli.jpg" data-rel=""><img loading="lazy"   class="image" src="images/Balateli.jpg" alt="" title=""></a>
                  <a href="images/Balateli1.jpg" data-rel=""><img loading="lazy"   class="image" src="images/Balateli1.jpg" id="wb_uid394" alt="" title=""></a>
               </div>
            </div>
         </div>
         <div id="indexLayer33">
            <div id="indexLayer33_Container">
               <div id="indexSlideShow24">
                  <a href="images/Ezvis.jpg" data-rel=""><img loading="lazy"   class="image" src="images/Ezvis.jpg" alt="" title=""></a>
                  <a href="images/Ezvis1.jpg" data-rel=""><img loading="lazy"   class="image" src="images/Ezvis1.jpg" id="wb_uid395" alt="" title=""></a>
               </div>
               <div id="wb_indexText327">
                  <span id="wb_uid396">Эзвис</span></div>
               <div id="wb_indexText328">
                  <span id="wb_uid397">Кровать</span></div>
               <input type="button" id="indexButton76" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexButton76', 'knop1_2');return false;" onmouseout="SetStyle('indexButton76', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <input type="button" id="indexButton77" onclick="$('#modal_krovat23').modal('show');return false;" onmouseover="SetStyle('indexButton77', 'knop2_2');return false;" onmouseout="SetStyle('indexButton77', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexPhotoGallery24">
                  <div id="indexPhotoGallery24">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/Ezvis.jpg" data-rel=""><img loading="lazy"   alt="" src="images/Ezvis.jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/Ezvis1.jpg" data-rel=""><img loading="lazy"   alt="" src="images/Ezvis1.jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
               <div id="wb_indexText329">
                  <span id="wb_uid398">65.000 рублей</span></div>
            </div>
         </div>
         <div id="indexLayer32">
            <div id="indexLayer32_Container">
               <div id="indexSlideShow23">
                  <a href="images/Lambo.jpg" data-rel=""><img loading="lazy"   class="image" src="images/Lambo.jpg" alt="" title=""></a>
                  <a href="images/Lambo1.jpg" data-rel=""><img loading="lazy"   class="image" src="images/Lambo1.jpg" id="wb_uid399" alt="" title=""></a>
               </div>
               <input type="button" id="indexButton37" onclick="$('#modal_zayavka').modal('show');return false;" onmouseover="SetStyle('indexButton37', 'knop1_2');return false;" onmouseout="SetStyle('indexButton37', 'knop1_1');return false;" name="" value="Заказать" class="knop1_1">
               <input type="button" id="indexButton63" onclick="$('#modal_krovat22').modal('show');return false;" onmouseover="SetStyle('indexButton63', 'knop2_2');return false;" onmouseout="SetStyle('indexButton63', 'knop2_1');return false;" name="" value="Подробнее" class="knop2_1">
               <div id="wb_indexText165">
                  <span id="wb_uid400">Ламбо</span></div>
               <div id="wb_indexText166">
                  <span id="wb_uid401">Кровать</span></div>
               <div id="wb_indexText4">
                  <span id="wb_uid402">120.000 рублей</span></div>
               <div id="wb_indexPhotoGallery23">
                  <div id="indexPhotoGallery23">
                     <div class="thumbnails">
                        <figure class="thumbnail">
                           <a href="images/Lambo.jpg" data-rel=""><img loading="lazy"   alt="" src="images/Lambo.jpg"></a>
                        </figure>
                        <figure class="thumbnail">
                           <a href="images/Lambo1.jpg" data-rel=""><img loading="lazy"   alt="" src="images/Lambo1.jpg"></a>
                        </figure>
                        <div class="clearfix visible-col2"></div>
                     </div>
                  </div></div>
            </div>
         </div>
      </div>
   </div>
   <div id="indexLayer4">
      <div id="indexLayer4_Container">
         <div id="indexLayer9">
            <div id="indexLayer9_Container">
               <div id="wb_indexText10">
                  <span id="wb_uid403">Собственное производство</span></div>
               <div id="wb_indexText12">
                  <span id="wb_uid404">Контроль качества продукции на каждом этапе изготовления</span></div>
               <div id="indexLayer10">
                  <div id="indexLayer10_Container">
                  </div>
               </div>
            </div>
         </div>
         <div id="indexLayer11">
            <div id="indexLayer11_Container">
               <div id="wb_indexText13">
                  <span id="wb_uid405">Изготовление на заказ</span></div>
               <div id="wb_indexText14">
                  <span id="wb_uid406">Изготовим любую модель<br>кровати или дивана по вашему эскизу </span></div>
               <div id="indexLayer12">
                  <div id="indexLayer12_Container">
                  </div>
               </div>
            </div>
         </div>

                  <div id="indexLayer7">
            <div id="indexLayer7_Container">
               <div id="wb_indexText8">
                  <span id="wb_uid407">Минимальные сроки</span></div>
               <div id="wb_indexText9">
                  <span id="wb_uid408">Минимальный срок изготовления от 3 до 7 дней</span></div>
               <div id="indexLayer8">
                  <div id="indexLayer8_Container">
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
   <div id="indexLayer45">
      <div id="indexLayer45_Container">
         <div id="wb_indexText45">
            <span id="wb_uid409">Мы ждем вас в нашем Шоуруме</span></div>
         <div id="wb_indexText46">
            <span id="wb_uid410">Где вы сможете в живую увидеть наши модели кроватей, пообщаться с консультантами и выбрать подходящую для вас модель!</span></div>
         <div id="wb_indexText51">
            <span id="wb_uid411">г. Санкт-Петербург, Лахтинский проспект 85Б <br>(ТЦ "Гарден Сити", 2 этаж) </span></div>
         <div id="indexLayer46">
            <div id="indexLayer46_Container">
            </div>
         </div>
         <div id="indexSlideShow1">
            <a href="images/showroom/1.jpg" data-rel=""><img loading="lazy"   class="image" src="images/showroom/1.jpg" alt="" title=""></a>
            <a href="images/showroom/2.jpg" data-rel=""><img loading="lazy"   class="image" src="images/showroom/2.jpg" id="wb_uid412" alt="" title=""></a>
			<a href="images/showroom/3.jpg" data-rel=""><img loading="lazy"   class="image" src="images/showroom/3.jpg" id="wb_uid412" alt="" title=""></a>
			
         </div>
         <div id="wb_indexPhotoGallery1">
            <div id="indexPhotoGallery1">
               <div class="thumbnails">
                  <figure class="thumbnail">
                     <a href="images/showroom/1.jpg" data-rel=""><img loading="lazy"   alt="" id="indexPhotoGallery1_img0" src="images/showroom/1.jpg"></a>
                  </figure>
                  <div class="clearfix visible-col1"></div>
                  <figure class="thumbnail">
                     <a href="images/showroom/2.jpg" data-rel=""><img loading="lazy"   alt="" id="indexPhotoGallery1_img1" src="images/showroom/2.jpg"></a>
                  </figure>
				  <figure class="thumbnail">
                     <a href="images/showroom/3.jpg" data-rel=""><img loading="lazy"   alt="" id="indexPhotoGallery1_img1" src="images/showroom/3.jpg"></a>
                  </figure>
				  
				  
                  <div class="clearfix visible-col1"></div>
               </div>
            </div></div>
         <div id="wb_indexText60">
            <span id="wb_uid413">Вы дизайнер?</span></div>
         <div id="wb_indexText61">
            <span id="wb_uid414">В таком случае, у вас есть возможность стать нашим партнёром и получать 10% (вознаграждение) от стоимости кровати за каждого приведённого клиента</span></div>
         <div id="indexLayer49">
            <div id="indexLayer49_Container">
            </div>
         </div>
         <div id="indexLayer50">
            <div id="indexLayer50_Container">
            </div>
         </div>
         <div id="wb_indexText62">
            <span id="wb_uid415">Мы предоставим вам 3D модели всех наших кроватей</span></div>
      </div>
   </div>
   <div id="indexLayer54">
      <div id="indexLayer54_Container">
         <div id="wb_indexText5">
            <span id="wb_uid416">Copyright © 2021</span></div>
         <div id="indexLayer53">
            <div id="indexLayer53_Container">
            </div>
         </div>
         <div id="wb_indexText66">
            <span id="wb_uid417">г. Санкт-Петербург, Лахтинский проспект 85Б (ТЦ "Гарден Сити", 2 этаж)</span></div>
         <div id="wb_indexText67">
            <span id="wb_uid418"><a href="tel:+78129882792" class="menu_black">+7 (812) 988-27-92</a></span></div>
         <div id="indexHtml1">
            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1995.310139089868!2d30.145761316162854!3d59.993350714645736!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x469636162fe05883%3A0x1b463911e4466c3!2z0JvQsNGF0YLQuNC90YHQutC40Lkg0L_RgC3Rgi4sINCh0LDQvdC60YIt0J_QtdGC0LXRgNCx0YPRgNCzLCAxOTcyMjk!5e0!3m2!1sru!2sru!4v1633779496935!5m2!1sru!2sru" width="350" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe></div>
         <div id="indexLayer55" onmouseover="SetStyle('indexLayer55', 'insta2');return false;" onmouseout="SetStyle('indexLayer55', 'insta1');return false;" class="insta1" onclick="ym(85292497,'reachGoal','inst_click'); window.open('https://www.instagram.com/Arnikomeb/')">
            <div id="indexLayer55_Container">
            </div>
         </div>
         <div id="wb_indexText193">
            <span id="wb_uid420"><a href="tel:+79967604791" class="menu_black">+7 (996) 760-47-91</a></span></div>
         <div id="indexLayer98" onmouseover="SetStyle('indexLayer55', 'insta2');return false;" onmouseout="SetStyle('indexLayer55', 'insta1');return false;" onclick="ym(85292497,'reachGoal','messenger'); window.open('https://viber.click/79775199267')">
            <div id="indexLayer98_Container">
            </div>
         </div>
         <div id="indexLayer99" onmouseover="SetStyle('indexLayer55', 'insta2');return false;" onmouseout="SetStyle('indexLayer55', 'insta1');return false;" onclick="ym(85292497,'reachGoal','messenger'); window.open('https://wa.me/79967604791')">
            <div id="indexLayer99_Container">
            </div>
         </div>
      </div>
   </div>
   <div id="indexLayer38">
      <div id="indexLayer38_Container">
         <div id="wb_indexText52">
            <span id="wb_uid421">Вы можете выбрать</span></div>
         <div id="wb_indexText53">
            <span id="wb_uid422">любой вариант комплектации, материала и обивки кровати</span></div>
         <div id="indexLayer41">
            <div id="indexLayer41_Container">
            </div>
         </div>
         <div id="wb_indexText54">
            <span id="wb_uid423">Варианты ткани <br>и обивки</span></div>
         <div id="wb_indexText55">
            <span id="wb_uid424">Ортопедическую<br>решетку</span></div>
         <div id="indexLayer42">
            <div id="indexLayer42_Container">
            </div>
         </div>
         <div id="indexLayer43">
            <div id="indexLayer43_Container">
            </div>
         </div>
         <div id="wb_indexText56">
            <span id="wb_uid425">Подъемный <br>механизм</span></div>
         <div id="indexLayer44">
            <div id="indexLayer44_Container">
            </div>
         </div>
         <div id="wb_indexText57">
            <span id="wb_uid426">Фурнитуру и <br>основание</span></div>
      </div>
   </div>
   <div id="indexLayer39">
      <div id="indexLayer39_Container">
         <div id="wb_indexText50">
            <span id="wb_uid427">Большой выбор матрасов по высоте&nbsp; и жесткости со скидкой 10%</span></div>
         <div id="indexLayer40">
            <div id="indexLayer40_Container">
            </div>
         </div>
         <div id="wb_indexText49">
            <span id="wb_uid428">Выбрали кровать? Теперь предстоит определиться с выбором матраса, ведь от него будет зависеть качество вашего сна!<br><br>У нашей компании есть большой выбор матрасов разного размера, высоты и жесткости</span></div>
      </div>
   </div>
   <div id="indexLayer51">
      <div id="indexLayer51_Container">
         <div id="wb_indexText63">
            <span id="wb_uid429">Свяжитесь с нами!</span></div>
         <div id="wb_indexText64">
            <span id="wb_uid430">Наши менеджеры подробно проконсультируют вас по выбору кровати и просчитают любое изделие на заказ в течение 15 минут</span></div>
         <div id="indexLayer52">
            <div id="indexLayer52_Container">
               <div id="wb_indexForm4">
                  <form name="indexForm1" method="post" action="<?php echo basename(__FILE__); ?>" enctype="multipart/form-data" accept-charset="UTF-8" id="indexForm4">
                     <input type="hidden" name="formid" value="indexform4">
                     <div id="wb_indexCheckbox4">
                        <input type="checkbox" id="indexCheckbox4" name="Политика конфидициальности" value="согласны" checked required><label for="indexCheckbox4"></label></div>
                     <div id="wb_indexText232" class="Oswald_ExtraLight">
                        <span id="wb_uid431">&#1053;&#1072;&#1078;&#1080;&#1084;&#1072;&#1103;</span><span id="wb_uid432"> </span><span id="wb_uid433">&#1085;&#1072;</span><span id="wb_uid434"> </span><span id="wb_uid435">&#1082;&#1085;&#1086;&#1087;&#1082;&#1091;</span><span id="wb_uid436"> "Получить консультацию", </span><span id="wb_uid437">&#1103;</span><span id="wb_uid438"> </span><span id="wb_uid439">&#1076;&#1072;&#1102;</span><span id="wb_uid440"> </span><span id="wb_uid441">&#1089;&#1086;&#1075;&#1083;&#1072;&#1089;&#1080;&#1077;</span><span id="wb_uid442"> </span><span id="wb_uid443">&#1085;&#1072;</span><span id="wb_uid444"> </span><span id="wb_uid445">&#1086;&#1073;&#1088;&#1072;&#1073;&#1086;&#1090;&#1082;&#1091;</span><span id="wb_uid446"> </span><span id="wb_uid447">&#1087;&#1077;&#1088;&#1089;&#1086;&#1085;&#1072;&#1083;&#1100;&#1085;&#1099;&#1093;</span><span id="wb_uid448"> </span><span id="wb_uid449">&#1076;&#1072;&#1085;&#1085;&#1099;&#1093;</span><span id="wb_uid450"> </span><span id="wb_uid451">&#1080;</span><span id="wb_uid452"> </span><span id="wb_uid453">&#1089;&#1086;&#1075;&#1083;&#1072;&#1096;&#1072;&#1102;&#1089;&#1100;</span><span id="wb_uid454"> c </span><span id="wb_uid455">&#1091;&#1089;&#1083;&#1086;&#1074;&#1080;&#1103;&#1084;&#1080;</span><span id="wb_uid456"> </span><span id="wb_uid457">&#1087;&#1086;&#1083;&#1080;&#1090;&#1080;&#1082;&#1080;</span><span id="wb_uid458"> </span><span id="wb_uid459">&#1082;&#1086;&#1085;&#1092;&#1080;&#1076;&#1077;&#1085;&#1094;&#1080;&#1072;&#1083;&#1100;&#1085;&#1086;&#1089;&#1090;&#1080;</span></div>
                     <input type="text" id="indexEditbox1" name="Имя" value="" autocomplete="off" spellcheck="true" required pattern="[A-Za-zАБВГДЕЖЗИЙКЛМНОПРСТУФХЦШЩЪЫЬЭЮЯабвгдежзийклмнопрстуфхцшщъыьэюя \t\r\n\fа,б,в,г,д,е,ё,ж,з,и,й,к,л,м,н,о,п,р,с,т,у,ф,х,ц,ч,ш,щ,ъ,ы,ь,э,ю,я,А,Б,В,Г,Д,Е,Ё,Ж,З,И,Й,К,Л,М,Н,О,П,Р,С,Т,У,Ф,Х,Ц,Ч,Ш,Щ,Ъ,Ы,Ь,Э,Ю,Я]*$" placeholder="&#1042;&#1074;&#1077;&#1076;&#1080;&#1090;&#1077; &#1089;&#1074;&#1086;&#1077; &#1080;&#1084;&#1103;">
                     <div id="indexLayer58" class="blick-button">
                        <div id="indexLayer58_Container">
                           <input type="submit" id="indexButton23" onmouseover="SetStyle('indexButton23', 'knop1_2');return false;" onmouseout="SetStyle('indexButton23', 'knop1_1');return false;" name="" value="Получить консультацию" class="knop1_1">
                        </div>
                     </div>
                     <input type="text" id="indexEditbox4" name="Номер телефона" value="" autocomplete="off" spellcheck="false" required placeholder="&#1042;&#1074;&#1077;&#1076;&#1080;&#1090;&#1077; &#1089;&#1074;&#1086;&#1081; &#1090;&#1077;&#1083;&#1077;&#1092;&#1086;&#1085;">
                  </form>
               </div>
               <div id="wb_indexText65">
                  <span id="wb_uid460">Введите свои данные и наш<br>специалист свяжется с вами</span></div>
            </div>
         </div>
      </div>
   </div>
   <div id="indexOverlayMenu1-overlay">
      <div class="indexOverlayMenu1">
         <ul class="drilldown-menu" role="menu">
            <li><a role="menuitem" href="#indexLayer13">&#1050;&#1088;&#1086;&#1074;&#1072;&#1090;&#1080;</a></li>
            <li><a role="menuitem" href="#indexLayer36">&#1053;&#1072;&nbsp;&#1079;&#1072;&#1082;&#1072;&#1079;</a></li>
            <li><a role="menuitem" href="#indexLayer45">&#1064;&#1086;&#1091;&#1088;&#1091;&#1084;</a></li>
         </ul>
      </div>
      <a class="close-button" id="indexOverlayMenu1-close" href="#" role="button" aria-hidden="true"><span></span></a>
   </div>
   <div id="indexOverlayMenu2-overlay">
      <div class="indexOverlayMenu2">
         <ul class="drilldown-menu" role="menu">
            <li><a role="menuitem" href="#indexLayer13">&#1050;&#1088;&#1086;&#1074;&#1072;&#1090;&#1080;</a></li>
            <li><a role="menuitem" href="./divany.php">&#1044;&#1080;&#1074;&#1072;&#1085;&#1099;</a></li>
            <li><a role="menuitem" href="#indexLayer36">&#1053;&#1072;&nbsp;&#1079;&#1072;&#1082;&#1072;&#1079;</a></li>
            <li><a role="menuitem" href="#indexLayer45">&#1064;&#1086;&#1091;&#1088;&#1091;&#1084;</a></li>
         </ul>
      </div>
      <a class="close-button" id="indexOverlayMenu2-close" href="#" role="button" aria-hidden="true"><span></span></a>
   </div>
   <div id="indexLayer47">
      <div id="indexLayer47_Container">
         <div id="indexLayer48">
            <div id="indexLayer48_Container">
            </div>
         </div>
         <div id="wb_indexText58">
            <span id="wb_uid461">Арнико это прямой фабричный производитель мягкой мебели на заказ по любым размерам, эскизам и фото. <!--Мы делаем любые реплики известных кроватей по стоимости в 10 раз ниже оригинала. --></span></div>
         <div id="wb_indexText59">
            <span id="wb_uid462">Мы производители</span></div>
      </div>
   </div>
   <div id="menu">
      <div id="menu_Container">
         <a href="#indexLayer1"><div id="indexLayer65">
               <div id="indexLayer65_Container">
               </div>
            </div></a>
         <div id="wb_indexText83">
            <span id="wb_uid463"><a href="#indexLayer13" class="menu_black">Кровати</a></span></div>
         <div id="wb_indexText85">
            <span id="wb_uid464"><a href="#indexLayer36" class="menu_black">На заказ</a></span></div>
         <div id="wb_indexText86">
            <span id="wb_uid465"><a href="#indexLayer45" class="menu_black">Шоурум</a></span></div>
         <div id="wb_indexText89">
            <span id="wb_uid466">г. Санкт-Петербург, Лахтинский проспект 85Б <br>(ТЦ "Гарден Сити", 2 этаж) </span></div>
         <div id="wb_indexText87">
            <span id="wb_uid467"><a href="tel:+78129882792" class="menu_black">+7 (812) 988-27-92</a></span></div>
         <div id="wb_indexText88">
            <span id="wb_uid468"><a href="#" class="menu_black" onclick="$('#modal_zvonok').modal('show');return false;">Заказать звонок</a></span></div>
         <div id="wb_indexOverlayMenu1">
            <a href="#" id="indexOverlayMenu1">
               <span class="line"></span>
               <span class="line">
               </span><span class="line"></span>
            </a>
         </div>
         <div id="indexLayer115" onmouseover="SetStyle('indexLayer55', 'insta2');return false;" onmouseout="SetStyle('indexLayer55', 'insta1');return false;" class="insta1" onclick="ym(85292497,'reachGoal','inst_click'); window.open('https://www.instagram.com/Arnikomeb/')">
            <div id="indexLayer115_Container">
            </div>
         </div>
         <div id="indexLayer117" onmouseover="SetStyle('indexLayer55', 'insta2');return false;" onmouseout="SetStyle('indexLayer55', 'insta1');return false;" onclick="ym(85292497,'reachGoal','messenger'); window.open('https://viber.click/79967604791')">
            <div id="indexLayer117_Container">
            </div>
         </div>
         <div id="indexLayer116" onmouseover="SetStyle('indexLayer55', 'insta2');return false;" onmouseout="SetStyle('indexLayer55', 'insta1');return false;" onclick="ym(85292497,'reachGoal','messenger'); window.open('https://wa.me/79967604791')">
            <div id="indexLayer116_Container">
            </div>
         </div>
      </div>
   </div>
   <div id="indexLayer1">
      <div id="indexLayer1_Container">
         <div id="wb_indexText3">
            <span id="wb_uid469">Кровати на заказ</span></div>
         <div id="indexLayer2">
            <div id="indexLayer2_Container">
            </div>
         </div>
         <div id="wb_indexText2">
            <span id="wb_uid470"><a href="tel:+78129882792" class="menu">+7 (812) 988-27-92</a></span></div>
         <div id="wb_indexText6">
            <span id="wb_uid471">г. Санкт-Петербург, Лахтинский проспект 85Б <br> (ТЦ "Гарден Сити", 2 этаж)</span></div>
         <div id="wb_indexText1">
            <span id="wb_uid472">по дизайн проектам</span></div>
         <div id="wb_indexText7">
            <span id="wb_uid473">С мягкой обивкой от производителя</span></div>
         <div id="wb_indexText79">
            <span id="wb_uid474"><a href="#indexLayer13" class="menu">Кровати</a></span></div>
         <div id="wb_indexText81">
            <span id="wb_uid475"><a href="#indexLayer36" class="menu">На заказ</a></span></div>
         <div id="wb_indexText82">
            <span id="wb_uid476"><a href="#indexLayer45" class="menu">Шоурум</a></span></div>
         <input type="button" id="indexButton36" onclick="$('#modal_zvonok').modal('show');return false;" onmouseover="SetStyle('indexButton36', 'knop4_2');return false;" onmouseout="SetStyle('indexButton36', 'knop4_1');return false;" name="" value="Заказать звонок" class="knop4_1">
         <div id="indexLayer3">
            <div id="indexLayer3_Container">
               <div id="indexLayer6">
                  <div id="indexLayer6_Container">
                  </div>
               </div>
               <div id="wb_indexText11">
                  <span id="wb_uid477">Подберем кровать под ваш дизайн, либо изготовим по вашему макету или фото и привезем в срок от 3-х дней</span></div>
               <div id="indexLayer56" class="blick-button">
                  <div id="indexLayer56_Container">
                     <input type="button" id="indexButton4" onclick="$('#modal_zayavka_maket').modal('show');return false;" onmouseover="SetStyle('indexButton4', 'knop1_2');return false;" onmouseout="SetStyle('indexButton4', 'knop1_1');return false;" name="" value="Подобрать и рассчитать" class="knop1_1">
                  </div>
               </div>
            </div>
         </div>
         <div id="wb_indexText196">
            <span id="wb_uid478"><a href="./divany.php" class="menu">Диваны</a></span></div>
      </div>
   </div>
   <div id="indexLayer5">
      <div id="indexLayer5_Container">
         <div id="wb_indexImage1">
            <a href="#indexLayer4"><img loading="lazy"   src="images/skroll.png" id="indexImage1" alt=""></a></div>
      </div>
   </div>
   <div id="indexLayer17">
      <div id="indexLayer17_Container">
         <a href="#indexLayer1"><div id="indexLayer19">
               <div id="indexLayer19_Container">
               </div>
            </div></a>
         <div id="wb_indexText167">
            <span id="wb_uid479"><a href="#indexLayer13" class="menu_black">Кровати</a></span></div>
         <div id="wb_indexText168">
            <span id="wb_uid480"><a href="/divany" class="menu_black">Диваны</a></span></div>
         <div id="wb_indexText169">
            <span id="wb_uid481"><a href="#indexLayer36" class="menu_black">На заказ</a></span></div>
         <div id="wb_indexText170">
            <span id="wb_uid482"><a href="#indexLayer45" class="menu_black">Шоурум</a></span></div>
         <div id="wb_indexText171">
            <span id="wb_uid483">г. Санкт-Петербург, Лахтинский проспект 85Б <br> (ТЦ "Гарден Сити", 2 этаж)</span></div>
         <div id="wb_indexText172">
            <span id="wb_uid484"><a href="tel:+78129882792" class="menu_black">+7 (812) 988-27-92</a></span></div>
         <div id="wb_indexOverlayMenu2">
            <a href="#" id="indexOverlayMenu2">
               <span class="line"></span>
               <span class="line">
               </span><span class="line"></span>
            </a>
         </div>
         <input type="button" id="indexButton38" onclick="$('#modal_zvonok').modal('show');return false;" onmouseover="SetStyle('indexButton38', 'knop5_2');return false;" onmouseout="SetStyle('indexButton38', 'knop5_1');return false;" name="" value="Заказать звонок" class="knop5_1">
         <div id="indexLayer100" onmouseover="SetStyle('indexLayer55', 'insta2');return false;" onmouseout="SetStyle('indexLayer55', 'insta1');return false;" onclick="ym(85292497,'reachGoal','messenger'); window.open('https://wa.me/79967604791')">
            <div id="indexLayer100_Container">
            </div>
         </div>
         <div id="indexLayer113" onmouseover="SetStyle('indexLayer55', 'insta2');return false;" onmouseout="SetStyle('indexLayer55', 'insta1');return false;" onclick="ym(85292497,'reachGoal','messenger'); window.open('https://viber.click/79967604791')">
            <div id="indexLayer113_Container">
            </div>
         </div>
         <div id="indexLayer66" onmouseover="SetStyle('indexLayer55', 'insta2');return false;" onmouseout="SetStyle('indexLayer55', 'insta1');return false;" class="insta1" onclick="ym(85292497,'reachGoal','inst_click'); window.open('https://www.instagram.com/Arnikomeb/')">
            <div id="indexLayer66_Container">
            </div>
         </div>
      </div>
   </div>
    
	<script src="js/jquery.maskedinput-1.3.min.js"></script>
</body>
</html>