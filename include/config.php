<?php
session_start();

/**
 * client_id приложения
 */
define('CLIENT_ID', 'local.565ec55fb6b3c3.75244299');
/**
 * client_secret приложения
 */
define('CLIENT_SECRET', '235f154afe7a8666ae10221e2a7a7d73');
/**
 * относительный путь приложения на сервере
 */
define('PATH', '/formupload/');
/**
 * полный адрес к приложения
 */
define('REDIRECT_URI', 'http://oookbrenessans.bitrix24.ru'.PATH);
/**
 * scope приложения
 */
define('SCOPE', 'crm,log,user');

/**
 * протокол, по которому работаем. должен быть https
 */
define('PROTOCOL', "https");

/**
 * Производит перенаправление пользователя на заданный адрес
 *
 * @param string $url адрес
 */
function redirect($url)
{
	Header("HTTP 302 Found");
	Header("Location: ".$url);
	die();
}

/**
 * Совершает запрос с заданными данными по заданному адресу. В ответ ожидается JSON
 *
 * @param string $method GET|POST
 * @param string $url адрес
 * @param array|null $data POST-данные
 *
 * @return array
 */
function query($method, $url, $data = null)
{
	$query_data = "";

	$curlOptions = array(
		CURLOPT_RETURNTRANSFER => true
	);

	if($method == "POST")
	{
		$curlOptions[CURLOPT_POST] = true;
		$curlOptions[CURLOPT_POSTFIELDS] = http_build_query($data);
	}
	elseif(!empty($data))
	{
		$url .= strpos($url, "?") > 0 ? "&" : "?";
		$url .= http_build_query($data);
	}

	$curl = curl_init($url);
	curl_setopt_array($curl, $curlOptions);
	$result = curl_exec($curl);

	return json_decode($result, 1);
}

/**
 * Вызов метода REST.
 *
 * @param string $domain портал
 * @param string $method вызываемый метод
 * @param array $params параметры вызова метода
 *
 * @return array
 */
function call($domain, $method, $params)
{
	return query("POST", PROTOCOL."://".$domain."/rest/".$method, $params);
}
