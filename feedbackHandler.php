<?php

	// Указываем свой почтовый ящик
	$my_email = "elananet@yandex.ru";
	// Указываем где будут храниться логи
	$path_log = "log.txt";
	// Время возвращения пользователя на сайт (сек)
	$time_back = 3;

	function error_msg($message){

		$message = "<h2 style='color: red; font-size: 25px; margin-top: 120px;'>".$message."</h2>";
		return $message;
	}

	function success_msg($message){

		$message = "<h2 style='color: green; font-size: 25px; margin-top: 120px;'>".$message."</h2>";
		return $message;
	}

	function clear_data($var){

		return trim(strip_tags($var));
	}

	function send_mail($email, $subj, $text, $from){

		$headers  = "From: ".$from." \r\n";
		$headers .= "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=utf-8 \r\n";

		$result = mail($email, $subj, $text, $headers);

		if(!$result){

			return false;
		}

		return true;
	}

	function check_format($data, $type){

		switch($type){

			case "email":
				$pattern = "/^[a-z0-9_][a-z0-9\._-]*@([a-z0-9]+([a-z0-9-]*[a-z0-9]+)*\.)+[a-z]+$/i";
				if(preg_match($pattern, $data)){

					return true;
				}
				break;

			case "phone":
				$pattern = "/^(\+?\d+)?\s*(\(\d+\))?[\s-]*([\d-]*)$/";
				if(preg_match($pattern, $data)){

					return true;
				}
				break;
		}

		return false;
	}

	// Узнаем предыдущую страницу
	$prev_page = $_SERVER["HTTP_REFERER"];
	// Наши сообщения
	$msg = "";
	// Статус письма
	$status_email = "";

	header("Content-Type: text/html; charset=utf-8");

	if($_SERVER["REQUEST_METHOD"] == "POST"){

		if(isset($_POST["number"], $_POST["email"], $_POST["question"])){

			$number 	= clear_data($_POST["number"]);
			$email 		= clear_data($_POST["email"]);
			$people 	= clear_data($_POST["people"]);

			if(check_format($number, "phone") && check_format($email, "email") && !empty($question)){

				// Формируем письмо
				$e_title = "Новое сообщение";
				$e_body  = "<html>";
					$e_body  .= "<body>";
					$e_body  .= "Телефон: ".$number;
					$e_body  .= "<br />";
					$e_body  .= "Почта: ".$email;
					$e_body  .= "<br />";
					$e_body  .= "Количество человек: ".$people;
					$e_body  .= "</body>";
				$e_body  .= "</html>";
				// END Формируем письмо

				if(send_mail($my_email, $e_title, $e_body, $prev_page)){
					$status_email = "success";
					$msg  = success_msg("Спасибо за ваш вопрос.<br />Мы ответим вам в ближайшее время.");
				}else{
					$status_email = "error";
					$msg  = error_msg("При отправке письма произошла ошибка.");
				}

				// Записываем в файл
				$str  = "Время: ".date("d-m-Y G:i:s")."\n\r";
				$str .= "Телефон: ".$number."\n\r";
				$str .= "Почта: ".$email."\n\r";
				$str .= "Количество человек: ".$people."\n\r";
				$str .= "Письмо: ".$status_email."\n\r";
				$str .= "==========================\n\r";
				file_put_contents($path_log, $str, FILE_APPEND);

			}else{

				$msg = error_msg("Заполните форму правильно!");
			}

		}else{

			$msg = error_msg("Произошла ошибка!");
		}
	}else{

		exit;
	}
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="refresh" content="<?php echo($time_back);?>; url=<?php echo($prev_page);?>" />
	<title>Обработчик формы обратной связи</title>
	<script type="text/javascript">
		function timeBack(){

			var time       = document.getElementById("time-back");
			time.innerText = parseInt(time.innerText) - 1;
			setTimeout("timeBack()", 1000);
		};
	</script>
</head>
<body>
	<div style="text-align: center;">
		<?php if($msg):?>
			<?php echo($msg);?>
		<?php endif;?>
		<p>
			Вы будете возвращены на страницу <b><?php echo($prev_page);?></b> через
		</p>
		<p id="time-back">
			<?php echo($time_back + 1);?>
		</p>
		<script type="text/javascript">timeBack();</script>
	</div>
</body>
</html>