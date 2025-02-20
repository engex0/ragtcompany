<?php
// ✅ تفعيل عرض الأخطاء لمعرفة المشكلة
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// ✅ تحقق من مفتاح API
$apiKey = "ضع_مفتاح_API_هنا"; // استبدل بمفتاح OpenAI API الخاص بك
if (empty($apiKey)) {
    die(json_encode(["response" => "❌ خطأ: مفتاح API غير موجود!"]));
}

// ✅ استقبال البيانات القادمة من JavaScript
$data = json_decode(file_get_contents("php://input"), true);
$userMessage = $data["message"] ?? "";

// ✅ تأكد من وجود رسالة من المستخدم
if (empty($userMessage)) {
    die(json_encode(["response" => "❌ الرجاء إدخال رسالة!"]));
}

// ✅ إعداد طلب OpenAI API
$apiURL = "https://api.openai.com/v1/chat/completions";
$postData = [
    "model" => "gpt-4",
    "messages" => [["role" => "user", "content" => $userMessage]],
    "max_tokens" => 200
];

$options = [
    "http" => [
        "header"  => "Content-Type: application/json\r\n" .
                     "Authorization: Bearer $apiKey\r\n",
        "method"  => "POST",
        "content" => json_encode($postData),
    ]
];

// ✅ إرسال الطلب إلى OpenAI API
$context  = stream_context_create($options);
$response = file_get_contents($apiURL, false, $context);

// ✅ التحقق من استجابة API
if ($response === FALSE) {
    die(json_encode(["response" => "❌ فشل الاتصال بـ OpenAI API!"]));
}

$responseData = json_decode($response, true);
$chatbotResponse = $responseData["choices"][0]["message"]["content"] ?? "❌ خطأ في معالجة الرد!";

echo json_encode(["response" => $chatbotResponse]);
?>
