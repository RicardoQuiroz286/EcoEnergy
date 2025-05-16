<?php
// Mostrar errores para depuración
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Encabezado para indicar que la respuesta es JSON
header('Content-Type: application/json');

// Leer el contenido crudo JSON que envía el cliente
$inputJSON = file_get_contents('php://input');

// Guardar el JSON recibido para depuración
file_put_contents('debug_input.txt', $inputJSON);

// Decodificar el JSON a un array asociativo
$input = json_decode($inputJSON, true);

// Validar parámetros
if (!isset($input['text']) || !isset($input['target_lang']) || !is_array($input['text'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Parámetros incompletos o inválidos.']);
    exit;
}

$texts = $input['text'];
$targetLang = strtoupper($input['target_lang']); // Ejemplo: "EN", "ES"
$apiKey = 'd3d0044d-8d86-4fa9-9d96-b901938bc738:fx'; // Reemplaza con tu clave si es necesario

// Construir los datos POST
$postFields = [];
foreach ($texts as $text) {
    $postFields[] = "text=" . urlencode($text);
}
$postFields[] = "target_lang=" . urlencode($targetLang);
$postFields[] = "auth_key=" . urlencode($apiKey);

// Convertir array a cadena tipo "text=...&text=...&target_lang=...&auth_key=..."
$postFieldsString = implode("&", $postFields);

// 🔍 Guardar lo que se enviará para depuración
file_put_contents("debug_request.txt", print_r($postFields, true));

// OPCIONAL: guardar lo que se envía para depuración
// file_put_contents("debug_request.txt", http_build_query($postFields));

// Usar cURL para enviar la solicitud a la API de DeepL
$ch = curl_init("https://api-free.deepl.com/v2/translate");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postFieldsString);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/x-www-form-urlencoded"
]);

$response = curl_exec($ch);
file_put_contents("debug_response.txt", $response);


// Verifica errores de conexión
if (curl_errno($ch)) {
    echo json_encode([
        'error' => 'Error al conectar con DeepL.',
        'detalle' => curl_error($ch)
    ]);
    curl_close($ch);
    exit;
}

$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Verifica que la respuesta sea exitosa
if ($httpCode !== 200) {
    echo json_encode([
        'error' => 'Respuesta no exitosa desde DeepL.',
        'status_code' => $httpCode,
        'raw_response' => $response
    ]);
    exit;
}

// Decodificar y retornar la respuesta JSON
$json = json_decode($response, true);
if ($json === null) {
    echo json_encode([
        'error' => 'Respuesta inválida de DeepL.',
        'raw_response' => $response
    ]);
    exit;
}

echo json_encode($json);
