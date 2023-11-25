<?php
    $err = "";
    if(!isset($_GET['map'])){
        include('audio.php');
        exit;
    }
    if(!empty($_POST['code'])){
        if(is_dir('maps/'.$_POST['code'])){
            header('Location: /map.php?p='.$_POST['code']);
            exit;
        }
        $err = "Dieser Code existiert nicht";
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sdat</title>
    <style>
        .html,
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }

        html{
            font-family: sans-serif;
        }

        form{
            display: flex;
            justify-content: center;
            margin: 24px auto 0;
        }

        .logo{
            text-align: center;
        }

        .logo img{
            max-width: 400px;
            margin: 40px auto 0;
        }

        .content{
            max-width: 800px;
            text-align: center;
            margin: 40px auto;
        }

        .error {
            color: #ee0000;
        }
    </style>
</head>
<body>
    <div class="logo"><img src="logo.png" alt="logo"></div>
    <div class="content">
        <p>Bitte geben Sie den erhaltenen Code ein, um die Aufgabe zu laden.</p>
        <form action="/index.php?map" method="post">
            <input type="text" name="code" id="code">
            <input type="submit" value="Start">
        </form>
        <?php if(!empty($err)){echo '<p class="error">Dieser Code existiert nicht</p>'; } ?>
        <p>Um zu testen, ob ihr Computer/Browser kompatibel ist, können Sie die Funktion hier testen:</p>
        <a href="/map.php?p=test"><button>Test starten</button></a>
    </div>
</body>
</html>