<?php
session_start();

include 'Connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Itineraries</title>
    <style>
/* Global styles for the elementor container */
.elementor-container {
    max-width: 1200px; 
    margin: 0 auto;
    padding: 20px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

/* Column styles */
.elementor-column {
    box-sizing: border-box;
    padding: 15px;
}

/* Basic widget styling */
.elementor-widget {
    margin-bottom: 20px; 
}

/* Header Styles */
.elementor-widget-container h3 {
    font-size: 24px;
    margin-bottom: 10px;
    color: #006400; /* Dark Green */
    border-bottom: 2px solid #006400;
}

.elementor-widget-container h4 {
    font-size: 20px;
    margin-bottom: 8px;
    color: #006400;
}

/* Content styles */
.elementor-widget-container {
    font-size: 16px;
    color: #333;
    line-height: 1.5;
}

/* Special styles for the "Disponible en 2024" section */
.elementor-widget-container span {
    color: #228B22; /* Forest Green */
    display: block;
    padding: 10px;
    border: 2px solid #228B22;
    border-radius: 5px;
}

/* If you want the entire column background to have a slight green tint */
.elementor-col-50 {
    background-color: #f5fff5; /* Very light green for a subtle background */
}

/* Add transitions for hover effects */
.elementor-widget-container span:hover {
    background-color: #228B22;
    color: #fff;
    transition: background-color 0.3s ease, color 0.3s ease;
}
    </style>
<div class="elementor-container">
    <?php foreach($itineraries as $itinerary): ?>
    <div class="elementor-column elementor-col-50">
        <div class="elementor-widget-wrap">
            <div class="elementor-widget elementor-widget-text-editor">
                <div class="elementor-widget-container">
                    <h3><?php echo htmlspecialchars($itinerary['nom']); ?></h3>
                </div>
            </div>
            <div class="elementor-widget elementor-widget-text-editor">
                <div class="elementor-widget-container">
                    <h4>Date de Début: <?php echo htmlspecialchars($itinerary['date_debut']); ?></h4>
                    <h4>Date de Fin: <?php echo htmlspecialchars($itinerary['date_fin']); ?></h4>
                </div>
            </div>
            <div class="elementor-widget elementor-widget-text-editor">
                <div class="elementor-widget-container">
                    Notes: <?php echo htmlspecialchars($itinerary['notes']); ?>
                </div>
            </div>
            <br>
            <?php if($itinerary['est_public'] == 0): ?>
            <div class="elementor-widget elementor-widget-text-editor">
                <div class="elementor-widget-container">
                    <span style="color: red; font-weight: bold;">Cet itinéraire n'est pas encore public.</span>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>
</body>
</html>
