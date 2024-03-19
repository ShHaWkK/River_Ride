<?php
include ('Connection.php');
$response = ["success" => false, "message" => "", "newTotal" => ""];

if(isset($_POST['promo_code'])) {
    $code = $_POST['promo_code'];

    // Vérifiez si le code est valide dans la base de données
    $stmt = $conn->prepare("SELECT * FROM promo_codes WHERE code = :code");
    $stmt->bindParam(':code', $code);
    $stmt->execute();

    if($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Appliquer la réduction
        $originalTotal = 100; // Exemple, ceci devrait être votre total original
        $discountedTotal = $originalTotal - $row['discount_amount'];
        
        $response["success"] = true;
        $response["newTotal"] = $discountedTotal;
    } else {
        $response["message"] = "Code de réduction invalide!";
    }
}

echo json_encode($response);
?>