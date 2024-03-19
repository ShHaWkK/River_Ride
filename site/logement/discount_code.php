<?php
session_start();

require 'vendor/autoload.php'; // Inclure le fichier d'autoloader de PHPMailer

$message = "";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'Connection.php';

$email = $_SESSION['email'];

$stmt = $conn->prepare("SELECT * FROM river_ride.users WHERE email = :email");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $email && !$user['code_de_reduction'] && !$user['has_previous_reservations']) {
    $discountCode = bin2hex(random_bytes(5));
    $stmt = $conn->prepare("UPDATE river_ride.users SET code_de_reduction = :code WHERE email = :email");
    $stmt->execute(['code' => $discountCode, 'email' => $email]);

    $mail = new PHPMailer\PHPMailer\PHPMailer();
    try {
        // Configurations SMTP
        $mail->SMTPDebug = 3;
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'riverride573@gmail.com';
        $mail->Password   = 'usywmwwlkehtdbbw';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        $mail->setFrom('riverride573@gmail.com', 'River Ride');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Votre code de réduction River Ride';
        $mail->Body    = "Félicitations! Votre code de réduction est : <b>$discountCode</b>";

        $mail->send();
        $message = "Code de réduction envoyé par e-mail!";
    } catch (Exception $e) {
        $message = "Le message n'a pas pu être envoyé. Erreur de messagerie: {$mail->ErrorInfo}";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <!-- Vos autres métadonnées et liens ici -->
    <title>Code de Réduction </title>
</head>
<body>
<div class="container">
    <h2>Appliquer un code de réduction</h2>
    <?php if ($message) : ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
    <form action="verify_discount.php" method="post">
        <div class="form-group">
            <label for="email">E-mail:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="code">Code de réduction:</label>
            <input type="text" class="form-control" id="code" name="code" required>
        </div>
        <button type="submit" class="btn btn-primary">Appliquer</button>
    </form>
</div>
</body>
</html>
