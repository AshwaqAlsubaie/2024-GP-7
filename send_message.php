<?php
// Get the POST data from the fetch call
$data = json_decode(file_get_contents('php://input'), true);

// Nexmo API credentials
$api_key = '38b6cf13';
$api_secret = 'A9RdstXE29ADshHw';
$to_number = $data['to']; // Get the number from the request
$message_text = $data['message'];

// Prepare the POST request
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://messages-sandbox.nexmo.com/v1/messages",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => json_encode([
      'from' => '14157386102',
      'to' => $to_number,
      'message_type' => 'text',
      'text' => $message_text,
      'channel' => 'whatsapp'
  ]),
  CURLOPT_HTTPHEADER => array(
    "Authorization: Basic " . base64_encode("$api_key:$api_secret"),
    "Content-Type: application/json",
    "Accept: application/json"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo json_encode(['status' => 'error', 'message' => $err]);
} else {
  echo $response;
}
?>
