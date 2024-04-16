<?php
// EVE Online ESI base URL
$base_url = "https://esi.evetech.net/latest";

// Your application's client ID and secret key
$client_id = "133d439d1f864dbe9b7dc8fcc3cea648";
$client_secret = "YOUR_CLIENT_SECRET";

// Route to fetch materials in ship hangar
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["materials"])) {
    // Make a request to authenticate and get access token
    $auth_url = "https://login.eveonline.com/v2/oauth/token";
    $auth_data = array(
        "grant_type" => "client_credentials",
        "client_id" => $client_id,
        "client_secret" => $client_secret
    );
    $auth_response = json_decode(http_post($auth_url, $auth_data));
    $access_token = $auth_response->access_token;

    // Make a request to get materials in ship hangar
    $hangar_url = "{$base_url}/characters/character_id/ship";
    $headers = array("Authorization: Bearer {$access_token}");
    $hangar_response = json_decode(http_get($hangar_url, $headers));

    // Process the response and extract materials data
    $materials = $hangar_response;

    // Output materials data as JSON
    header("Content-Type: application/json");
    echo json_encode($materials);
    exit();
}

// Function to make a POST request
function http_post($url, $data) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}

// Function to make a GET request
function http_get($url, $headers) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}
?>
