<?php
// Дуртай газар нэмэх/хасах API
header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);
$resortId = $data['resort_id'] ?? null;

if (!$resortId) {
    echo json_encode(['success' => false, 'message' => 'Resort ID шаардлагатай']);
    exit;
}

// Cookie-с дуртай газруудыг авах
$favorites = isset($_COOKIE['favorites']) ? json_decode($_COOKIE['favorites'], true) : [];

// Дуртай жагсаалтад байгаа эсэхийг шалгах
$index = array_search($resortId, $favorites);

if ($index !== false) {
    // Хэрэв байвал устгах
    unset($favorites[$index]);
    $favorites = array_values($favorites); // Индексийг дахин тохируулах
} else {
    // Хэрэв байхгүй бол нэмэх
    $favorites[] = $resortId;
}

// Cookie-д хадгалах (30 хоногийн хугацаатай)
setcookie('favorites', json_encode($favorites), time() + (86400 * 30), '/');

echo json_encode([
    'success' => true,
    'favorites' => $favorites,
    'message' => $index !== false ? 'Дуртай жагсаалтаас хасагдлаа' : 'Дуртай жагсаалтад нэмэгдлээ'
]);
?>
