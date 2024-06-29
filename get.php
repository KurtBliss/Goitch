<?php

$link = urldecode($_GET["url"]) . "\/download_url";

$ch = curl_init($link);

curl_setopt($ch, CURLOPT_POST, true);

// Execute the POST request
$response = curl_exec($ch);

// Check for errors
if ($response === false) {
    echo 'cURL Error: ' . curl_error($ch);
} else {
    // Decode JSON response (if applicable)
    $responseData = json_decode($response, true);
    print_r($responseData);
}

// Close cURL session
curl_close($ch);
