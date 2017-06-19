<?php
$name = trim(strip_tags($_POST["name"]));
$people = trim(strip_tags($_POST["people"]));
$email = trim(strip_tags($_POST["email"]));
$country = trim(strip_tags($_POST["country"]));
$subject = "Заказ на сайте lavra.github.io";
$msg = "Ваши данные формы регистрации:\n" ."Имя: $name\n" ."количество человек: $people\n" ."Ваш email: $email\n" ."Страна: $country";
$headers = "Content-type: text/plain; charset=UTF-8" . "\r\n";
$headers .= "From: Ваше_имя <elananet@yandex.ru>" . "\r\n";
$headers .= "Bcc: elananet@yandex.ru". "\r\n";
if(!empty($name) && !empty($people) && !empty($email) && !empty($country) && filter_var($email, FILTER_VALIDATE_EMAIL)){
    mail($email, $subject, $msg, $headers);
    echo "Спасибо! Вы успешно выполнили заказ.";
}
?>