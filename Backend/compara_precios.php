<?php
header('Content-Type: application/json');

// URL de ejemplo: debemos cambiarlo por una real de suplemento similar
$url = 'https://ejemplo.com/producto/omega3';

// Inicializar cURL
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$html = curl_exec($ch);
curl_close($ch);

// Verificar contenido
if (!$html) {
    echo json_encode(['error' => 'No se pudo obtener información.']);
    exit;
}

// Extraer datos con DOMDocument
$doc = new DOMDocument();
libxml_use_internal_errors(true); // evitar warnings HTML mal formado
$doc->loadHTML($html);
libxml_clear_errors();

$xpath = new DOMXPath($doc);

// Ajusta los selectores según el sitio web externo
$precioNode = $xpath->query('//span[@class="price"]')->item(0);
$nombreNode = $xpath->query('//h1')->item(0);

$data = [
    'nombre' => $nombreNode ? trim($nombreNode->textContent) : 'No disponible',
    'precio' => $precioNode ? trim($precioNode->textContent) : 'No disponible',
    'fuente' => $url
];

echo json_encode($data);
?>
