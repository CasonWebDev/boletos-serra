<?php

function curl_fetch ($url, $postData = null, $cookie = null, $headers = null, $customOptions = Array(), $returnHeaders = false)
{
	$debug = defined('DEBUG_CURL_FETCH') && DEBUG_CURL_FETCH;

	$userAgent = 'Mozilla/5.0 (Windows NT 6.2; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36';

	$defaultOptions = Array(
		CURLOPT_URL => $url,
		CURLOPT_SSL_VERIFYHOST => 0,
		CURLOPT_SSL_VERIFYPEER => 0,
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => 'gzip',
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 60,
		CURLOPT_USERAGENT => $userAgent
	);

	$options = $customOptions + $defaultOptions;

	if ($postData !== null) {
		$options[CURLOPT_POST] = true;
		$options[CURLOPT_POSTFIELDS] = $postData;
	}

	if ($cookie !== null) {
		$options[CURLOPT_COOKIEJAR] = $cookie;
		$options[CURLOPT_COOKIEFILE] = $cookie;
	}

	if ($headers) {
		$options[CURLOPT_HTTPHEADER] = $headers;
	}

	if ($debug) {
		$options[CURLOPT_HEADER] = true;
		$options[CURLOPT_VERBOSE] = true;
		$verbose = fopen('php://temp', 'w+');
		$options[CURLOPT_STDERR] = $verbose;
	}else if ($returnHeaders) {
		$options[CURLOPT_VERBOSE] = true;
		$options[CURLOPT_HEADER] = true;
	}

	$ch = curl_init();
	curl_setopt_array($ch, $options);
	$response = curl_exec($ch);

	if ($debug) {
		rewind($verbose);
		$verboseLog = stream_get_contents($verbose);
		var_dump($verboseLog);
		var_dump($response);
	}

	if (empty($response) && !$debug) {
		throw new Exception("Erro: resposta vazia ao fazer requisição para a url {$url}:\n" . curl_error($ch));
	}

	// converte para utf-8
	$responseEncoding = mb_detect_encoding($response, Array('UTF-8', 'ISO-8859-1', 'ASCII'));
	if (strToUpper($responseEncoding) != 'UTF-8') {
		$response = utf8_encode($response);
	}

	if ($returnHeaders) {
		$headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$response = Array(
			'headers' => substr($response, 0, $headerSize),
			'body' => substr($response, $headerSize)
		);
	}

	curl_close($ch);

	return $response;
}