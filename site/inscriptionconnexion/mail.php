<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

include 'Connection.php'; // Cela suppose que vous avez déjà établi une connexion PDO dans Connection.php.

if (isset($_POST["register"])) {
    $name = $_POST["name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'riverride573@gmail.com';
        $mail->Password = 'lbxrbwyawplhamkh';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('riverride573@gmail.com', 'your_website_name'); // Remplacez 'your_website_name' par le nom de votre site si nécessaire.
        $mail->addAddress($email, $name);
        $mail->isHTML(true);

        $verification_code = substr(number_format(time() * rand(), 0, '', ''), 0, 6);

        $mail->Subject = 'Email verification';
        $mail->Body    = '<p>Le code de vérification est : <b style="font-size: 30px;">' . $verification_code . '</b></p>';

        $mail->send();

        $encrypted_password = password_hash($password, PASSWORD_DEFAULT);

        // Insertion dans la table users
        $sql = "INSERT INTO users(name, email, password, verification_code, email_verified VALUES (?, ?, ?, ?, NULL)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$name, $email, $encrypted_password, $verification_code]);

        header("Location: verifmail.php?email=" . $email);
        exit();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>
