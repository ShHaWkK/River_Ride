<?php
$servername = "localhost";
$dbname = "river_ride";
$username = "root";
$password = "root";
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = "
    SELECT 
        d.calendar_date,
        l.nom as logement_nom,
        COUNT(r.id) as reservations,
        MAX(l.capacite) as capacite
    FROM
        (
            SELECT ADDDATE((SELECT MIN(date_debut) FROM reservations), @num:=@num+1) as calendar_date
            FROM (SELECT @num:=-1) num, reservations
            WHERE ADDDATE((SELECT MIN(date_debut) FROM reservations), @num) <= (SELECT MAX(date_fin) FROM reservations)
        ) d
    CROSS JOIN
        logements l
    LEFT JOIN
        reservations r ON l.id = r.logement_id AND d.calendar_date BETWEEN r.date_debut AND r.date_fin
    WHERE 
        r.confirme = 'confirmé' OR r.confirme IS NULL
    GROUP BY
        d.calendar_date, l.nom;
    ";

    $stmt = $conn->prepare($query);
    $stmt->execute();

    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
} catch(PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
