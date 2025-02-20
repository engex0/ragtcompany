<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_var($_POST["email"], FILTER_SANITIZE_EMAIL);

    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // الاتصال بقاعدة البيانات
        $conn = new mysqli("localhost", "root", "", "newsletter_db");

        if ($conn->connect_error) {
            die("فشل الاتصال بقاعدة البيانات: " . $conn->connect_error);
        }

        // إدخال البريد الإلكتروني إلى قاعدة البيانات
        $stmt = $conn->prepare("INSERT INTO subscribers (email) VALUES (?)");
        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            echo "success"; // استجابة AJAX
        } else {
            echo "error";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "invalid";
    }
} else {
    echo "no-data";
}
?>
