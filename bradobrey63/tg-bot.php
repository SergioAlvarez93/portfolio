<?php

$name = $_POST['modalName'];
$phone = $_POST['modalTel'];
$comment = $_POST['modalComment'];
$token = "6067849644:AAEPazHhPWyIcYxHNRoTDYRPMEQ13weHWyM";
$chat_id = "-825435037";
$arr = array(
	'Имя:' => $name,
	'Телефон:' => $phone,
	'Комментарий:' => $comment
);

foreach($arr as $key => $value) {
  $txt .= "<b>".$key."</b> ".$value."%0A";
};

$sendToTelegram = fopen("https://api.telegram.org/bot{$token}/sendMessage?chat_id={$chat_id}&parse_mode=html&text={$txt}","r");

?>

