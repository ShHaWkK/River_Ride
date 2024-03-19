<?php
    include('api/add_capcha.php')
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload et découpage d'image</title>
    <style>
       body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
        }

        .upload-container {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            max-width: 400px;
            width: 100%;
        }

        .upload-container h2 {
            text-align: left;
            margin-bottom: 30px;
            color: #138d75;
        }

        .input-field {
            margin-bottom: 30px;
            border-color: #138d75;
            padding: 15px;
        }

        .input-field i {
            font-size: 20px;
            color: #138d75;
        }

        .input-field input {
            border: none;
            border-radius: 0;
            border-bottom: 1px solid #138d75;
            background-color: transparent;
            height: 40px;
            font-size: 16px;
        }

        .input-field input::placeholder {
            color: #999;
        }

        .input-field button {
            background-color: #138d75;
            border: none;
            border-radius: 5px;
            padding: 15px 20px;
            font-size: 16px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease-in-out;
        }

        .input-field button:hover {
            background-color: #117e64;
        }
    </style>
</head>
<body>
    <div class="upload-container">
        <h2>Upload et découpage d'image</h2>
        <form method="POST" enctype="multipart/form-data">
            <div class="input-field">
                <label for="image"><i class="fa fa-image"></i> Choisir une image :</label>
                <input type="file" name="image" id="image">
            </div>
            <button type="submit" name="submit">Upload</button>
        </form>
    </div>
</body>
</html>
