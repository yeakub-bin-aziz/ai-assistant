<?php
header('Content-Type: application/json');

// Gemini Tuned API endpoint and API key
$apiUrl = "https://generativelanguage.googleapis.com/v1beta/[MODEL_NAME]:generateContent?key=[YOUR_API_KEY]";

// Get the incoming message
$data = json_decode(file_get_contents("php://input"), true);
$message = $data['message'] ?? '';

// Validate the input
if (empty(trim($message))) {
    echo json_encode(['response' => 'Please enter a valid message.']);
    exit;
}

// Prepare the request payload
$requestData = [
    'contents' => [
        [
            'parts' => [
                ['text' => $message]
            ]
        ]
    ]
];

// Initialize cURL session
$ch = curl_init($apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));

// Execute the request
$response = curl_exec($ch);
if ($response === false) {
    echo json_encode(['response' => 'API error: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}
curl_close($ch);

// Decode and process the response
$apiResponse = json_decode($response, true);
if (isset($apiResponse['candidates'][0]['content']['parts'][0]['text'])) {
    $reply = $apiResponse['candidates'][0]['content']['parts'][0]['text'];
    echo json_encode(['response' => mb_convert_encoding($reply, 'UTF-8', 'UTF-8')]);
} else {
    echo json_encode(['response' => 'Unexpected API response.']);
}
?>
