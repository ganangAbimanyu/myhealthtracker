<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $food = $_POST['food'];

    // Nutritionix API credentials
    $api_url = "https://trackapi.nutritionix.com/v2/natural/nutrients";
    $app_id = "ffb03a0b"; 
    $app_key = "01980ecf8709a0d0db15f491bdad7a5a";

    $headers = [
        "Content-Type: application/json",
        "x-app-id: $app_id",
        "x-app-key: $app_key"
    ];

    $data = json_encode(["query" => $food]);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $api_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    echo $response;
}
?>