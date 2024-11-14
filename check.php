<?php

// API URL
$url = 'https://api.zerogpt.com/api/transform/paraphrase';

// Get the data from the POST request
$data = json_decode(file_get_contents('php://input'), true);

// Extract values from the request
$string = $data['string'] ?? '';  // Ensure the string is not empty
$tone = $data['tone'] ?? 'Fluent';  // Default tone to Fluent
$skipRealtime = isset($data['skipRealtime']) ? $data['skipRealtime'] : 0;  // Default skipRealtime to 0

// Debug: Log the request payload
error_log("Request Data: " . print_r($data, true));

// Prepare the data for the API request
$payload = json_encode([
    'string' => $string,
    'tone' => $tone,
    'skipRealtime' => $skipRealtime
]);

// Debug: Log the payload to check if it's properly formatted
error_log("Payload to ZeroGPT API: " . $payload);

// cURL Setup
$ch = curl_init($url);

// cURL options
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // To return the response as a string
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'ApiKey: 897bf9fe-99c8-4225-8fef-8c9118674900'  // Make sure this API key is correct
]);
curl_setopt($ch, CURLOPT_POST, true);  // Sending a POST request
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);  // Attach the JSON data to the request

// Execute the cURL request and capture the response
$response = curl_exec($ch);

// Check for cURL errors
if(curl_errno($ch)) {
    // Debug: log cURL error
    error_log("cURL Error: " . curl_error($ch));
    echo json_encode(['success' => false, 'message' => 'Error executing cURL request']);
    exit;
}

// Close the cURL session
curl_close($ch);

// Decode the response
$responseData = json_decode($response, true);

// Check for ZeroGPT API response errors
if ($responseData && isset($responseData['success']) && $responseData['success'] === true) {
    echo json_encode([
        'success' => true,
        'message' => $responseData['data']['message']
    ]);
} else {
    // Debug: log API error response
    error_log("API Error Response: " . print_r($responseData, true));

    // Output error if API response is not successful
    echo json_encode([
        'success' => false,
        'message' => 'Error from ZeroGPT API: ' . ($responseData['message'] ?? 'Unknown Error')
    ]);
}
?>
