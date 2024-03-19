<?php
session_start();
include 'connection.php';


if(isset($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];

    $sql = "SELECT r.*, p.* FROM river_ride.reservations AS r 
            JOIN river_ride.packs AS p ON r.pack_id = p.id 
            WHERE r.user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            echo "Reservation ID: " . $row['id'] . "<br>";
            echo "Pack ID: " . $row['pack_id'] . "<br>";
            echo "Pack Name: " . $row['pack_name'] . "<br>";  // Assuming packs table has a 'pack_name' column
            echo "<hr>";
        }
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "You need to be logged in to view reserved packs.";
}

$conn->close();
?>


