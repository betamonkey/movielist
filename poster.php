<?php
	require_once(dirname(__FILE__) . '/functions.php');

	$movie = Movie::getFromID($_REQUEST['id']);
	//var_dump($movie);
	$remotePoster = true;

	/*if (isset($_REQUEST['fanart'])) {
		if (file_exists($movie->dir . '/fanart.jpg')) {
			$poster = $movie->dir . '/fanart.jpg';
		} else {
			foreach (glob($movie->dir . '/*-fanart.jpg') as $fanart) {
				$poster = $fanart;
				break;
			}
		}
	} else if (file_exists($movie->dir . '/movie.tbn')) {
		$poster = $movie->dir . '/movie.tbn';
	} else if (empty($movie->poster) || $movie->poster == 'N/A') {
		$poster = dirname(__FILE__) . '/inc/noposter.jpg';
	} else {
		$poster = $movie->poster;
		$remotePoster = true;
	}

	if (empty($poster)) { die(); }
*/

$poster = $movie->poster;
//echo "hello";
//var_dump($poster);

	if ($remotePoster) {
		$ch = curl_init($poster);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		$response = curl_exec($ch);
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($response, 0, $header_size);
		$body = substr($response, $header_size);

		foreach (explode("\n", $header) as $header) {
			header($header);
			//echo $header;
			//echo "</br>";
		}
		echo $body;
	} else {
		header('Content-type: image/jpeg');
		echo file_get_contents($poster);
	}
?>
