<?php 
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $title; ?></title>
    <?php
	echo '<link rel="stylesheet" type="text/css" href="../styles/home.css">';
    echo '<link rel="stylesheet" type="text/css" href="../styles/header.css">';
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">';
    echo '<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>';
    echo '<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-beta1/js/bootstrap.min.js"></script>';
    echo '<link href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-beta1/css/bootstrap.min.css" rel="stylesheet">';
    echo '<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css">';
    echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">';
    echo '<link rel="stylesheet" href="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.css">';
    echo '<link rel="stylesheet" type="text/css" href="../balade/balade.css"/>';
    echo '<link rel="stylesheet" type="text/css" href="../styles/footer.css"/>';
    echo '<link rel="stylesheet" type="text/css" href="../styles/cookie.css"/>';
    echo '<link rel="stylesheet" type="text/css" href="../styles/dark.css"/>';
    
    ?>
    <script src="js/cookie.js" defer></script>
</head>
