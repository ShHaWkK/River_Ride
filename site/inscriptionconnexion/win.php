<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>You won!</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #e6e6e6;
            font-family: Arial, sans-serif;
        }
        button {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }
    </style>
  </head>
  <body>
    <div>
        <h1>Vous avez réussi ! </h1>
        <p>Click below to go back to the main page.</p>
        <button onclick="window.location.href='../index.php';">Accueil</button>
    </div>
  </body>
</html>
