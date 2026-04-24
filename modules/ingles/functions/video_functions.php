<?php
/**
 * Funciones para manejar videos de YouTube en el módulo de inglés
 */

/**
 * Extraer ID de video de YouTube desde URL
 * @param string $url URL de YouTube
 * @return string|false ID del video o false si no es válido
 */
function extraerYouTubeID($url) {
    $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i';
    preg_match($pattern, $url, $matches);
    return $matches[1] ?? false;
}

/**
 * Obtener thumbnail de YouTube
 * @param string $videoId ID del video de YouTube
 * @param string $quality Calidad (default, mqdefault, hqdefault, sddefault, maxresdefault)
 * @return string URL del thumbnail
 */
function obtenerYouTubeThumbnail($videoId, $quality = 'hqdefault') {
    return "https://img.youtube.com/vi/{$videoId}/{$quality}.jpg";
}

/**
 * Obtener duración del video desde API de YouTube
 * @param string $videoId ID del video
 * @param string $apiKey API Key de YouTube Data API
 * @return int|false Duración en segundos o false si falla
 */
function obtenerDuracionYouTube($videoId, $apiKey) {
    $url = "https://www.googleapis.com/youtube/v3/videos?id={$videoId}&key={$apiKey}&part=contentDetails";
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    
    if (isset($data['items'][0]['contentDetails']['duration'])) {
        return convertirDuracionISO8601($data['items'][0]['contentDetails']['duration']);
    }
    
    return false;
}

/**
 * Convertir duración ISO 8601 a segundos
 * @param string $duration Duración en formato ISO 8601 (ej: PT1H2M10S)
 * @return int Duración en segundos
 */
function convertirDuracionISO8601($duration) {
    $interval = new DateInterval($duration);
    return ($interval->h * 3600) + ($interval->i * 60) + $interval->s;
}

/**
 * Generar embed code para YouTube
 * @param string $videoId ID del video
 * @param array $options Opciones de embed
 * @return string HTML del embed
 */
function generarYouTubeEmbed($videoId, $options = []) {
    $defaults = [
        'width' => 560,
        'height' => 315,
        'autoplay' => 0,
        'controls' => 1,
        'subtitles' => 1,
        'rel' => 0,
        'modestbranding' => 1
    ];
    
    $params = array_merge($defaults, $options);
    $query = http_build_query([
        'autoplay' => $params['autoplay'],
        'controls' => $params['controls'],
        'cc_load_policy' => $params['subtitles'],
        'rel' => $params['rel'],
        'modestbranding' => $params['modestbranding']
    ]);
    
    return "<iframe 
        width=\"{$params['width']}\" 
        height=\"{$params['height']}\" 
        src=\"https://www.youtube.com/embed/{$videoId}?{$query}\" 
        frameborder=\"0\" 
        allow=\"accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture\" 
        allowfullscreen
        style=\"border-radius: 12px;\">
    </iframe>";
}

/**
 * Validar si un video de YouTube existe
 * @param string $videoId ID del video
 * @return bool True si existe, false si no
 */
function validarYouTubeVideo($videoId) {
    $url = "https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v={$videoId}&format=json";
    $headers = @get_headers($url);
    return strpos($headers[0], '200') !== false;
}

/**
 * Obtener información del video desde oEmbed
 * @param string $videoId ID del video
 * @return array|false Información del video o false si falla
 */
function obtenerInfoYouTube($videoId) {
    $url = "https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v={$videoId}&format=json";
    $response = @file_get_contents($url);
    
    if ($response) {
        return json_decode($response, true);
    }
    
    return false;
}
?>