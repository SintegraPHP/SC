<?php

require_once '../vendor/autoload.php';

use SintegraPHP\SC\SintegraSC;

if (isset($_POST['captcha']) && isset($_POST['cnpj']) && isset($_POST['__VIEWSTATE']) && isset($_POST['__VIEWSTATEGENERATOR']) && isset($_POST['__EVENTVALIDATION'])) {
	echo '<pre>';
	$result = SintegraSC::consulta($_POST['cnpj'], $_POST['captcha'], $_POST['__VIEWSTATE'], $_POST['__VIEWSTATEGENERATOR'], $_POST['__EVENTVALIDATION']);
	var_dump($result);
	die;
} else
	$params = SintegraSC::getParams();
?>

<img src="<?php echo $params['captchaBase64'] ?>"/>

<form method="POST">

	<input type="hidden" name="__VIEWSTATE" value="<?php echo $params['viewstate'] ?>">
	<input type="hidden" name="__VIEWSTATEGENERATOR" value="<?php echo $params['viewstategenerator'] ?>">
	<input type="hidden" name="__EVENTVALIDATION" value="<?php echo $params['eventvalidation'] ?>">

	<input type="text" name="captcha" placeholder="Captcha"/>
	<input type="text" name="cnpj" placeholder="CNPJ" value="83646984003710"/>

	<button type="submit">Consultar</button>
</form>