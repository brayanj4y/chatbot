<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $apiKey = "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"; // Replace with your actual key
    $userInput = trim($_POST['message'] ?? '');

    if (empty($userInput)) {
        echo json_encode(["error" => "Message cannot be empty."]);
        exit;
    }

    $data = [
        "model" => "text-davinci-003",
        "prompt" => $userInput,
        "max_tokens" => 150,
    ];

    // Initialize cURL
    $ch = curl_init("https://api.openai.com/v1/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey",
    ]);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // OPTIONAL: Disable SSL Verification (For testing only)
    // Uncomment the following lines ONLY if you encounter SSL certificate issues
    // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    // curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

    $response = curl_exec($ch);

    // Handle cURL errors
    if (curl_errno($ch)) {
        echo json_encode(["error" => curl_error($ch)]);
        curl_close($ch);
        exit;
    }

    $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Check for non-200 HTTP status codes
    if ($httpStatus !== 200) {
        echo json_encode(["error" => "API returned status $httpStatus."]);
        exit;
    }

    // Return API response
    echo $response;
}
