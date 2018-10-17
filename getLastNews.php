<?php
/**
 * простой вывод последних пяти новостей с lenta.ru
 * запуск из командной строки: php getLastNews.php
 * author: ildar r. khasanshin .. 10021987.ru
 */
function fileGetContentsCurl($href) {
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $href);
	$data = curl_exec($ch);
	curl_close($ch);

	return $data;
}

$dat = fileGetContentsCurl("https://lenta.ru/rss");
if ( $dat && strlen($dat) > 0 ) {
	$xml = new SimpleXMLElement($dat);

	$count = 5;
	for ( $i = 0; $i <= $count; $i ++ ) {
		echo ($i + 1).') ';
		echo $xml->channel->item[$i]->title."\r\n";
		echo $xml->channel->item[$i]->guid."\r\n";
		echo trim($xml->channel->item[$i]->description)."\r\n\r\n";
	}
}