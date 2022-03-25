<?php

// ************************************
//			-- Partir.com --
//		-- HTML2PDF Web Interface --
// ************************************

$url = $_GET["url"];
$pageSize = $_GET["pageSize"];
$viewportSize = $_GET["viewportSize"];
$jsDelay = $_GET["jsDelay"];
$argArray = $_GET;
unset($argArray["url"]);
unset($argArray["pageSize"]);
unset($argArray["viewportSize"]);
unset($argArray["jsDelay"]);

$options = ['load-error-handling'=>'skip', 'viewport-size' => $viewportSize, 'javascript-delay' => $jsDelay, 'page-size' => $pageSize ];
$options = array_merge($options, $argArray);

function get_data($url) {
  $ch = curl_init();
  $timeout = 20;
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt( $ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; rv:1.7.3) Gecko/20041001 Firefox/0.10.1" );
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
  curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
}

$context = stream_context_create(
	[
		'http' => [
			'method'  => 'POST',
			"ssl"=>array(
			        "verify_peer"=>false,
			        "verify_peer_name"=>false,
			        'allow_self_signed' => true

			),
			'header'  => 'Content-Type: application/json',
			'content' => 
			 	json_encode( 
			 		[ 'contents' => base64_encode(get_data($url)),
			 		  'options' => $options
			 		] 
			 	)
		]
	]
);

header("Content-type: application/pdf");
header("Content-Disposition: inline; filename=filename.pdf");
echo file_get_contents("https://example.com/html2pdf", false, $context);

?>
