<?php
    function show_login($log, $failed = false){
    ?>
        <html>
            <head>
                <meta charset="utf-8" />
                <meta http-equiv="X-UA-Compatible" content="IE=edge">
                <title>Πρωτέας</title>
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <link rel="stylesheet" type="text/css" media="screen" href="../css/login.css" />
            </head>
            <body>   
            <?php
            $log->loginform("login", "login-form", "");
            if ($failed) {
                echo "<center><h2>H είσοδος απέτυχε...</h2>";
                echo "<p>Παρακαλώ δοκιμάστε με έναν έγκυρο συνδυασμό ονόματος χρήστη - κωδικού...</p></center>";
            }
            ?>
            </body>
        </html>
    <?php
    }
    header('Content-type: text/html; charset=utf-8'); 
    require_once("../config.php");
    include("class.login.php");
    $log = new logmein();     //Instentiate the class
    $log->dbconnect();        //Connect to the database

    if ($_GET['logout'])
    {
        $log->logout();
        header("Location: login.php");
    }

    if (!isset($_REQUEST['action']))
    {
        show_login($log);
    }

    if($_REQUEST['action'] == "login"){
        if($log->login("logon", $_REQUEST['username'], $_REQUEST['password']) == true)
        {        
          header("Location: ../index.php");
        }
        else {
            show_login($log, true);
        }
    }
?>    
