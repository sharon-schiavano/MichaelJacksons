<?php
    
    session_start();

    $oldinput = "";
    $oldusername = "";
    $oldemail = "";


    if (isset($_SESSION['oldinput']))
        $oldinput = $_SESSION['oldinput'];

    if (isset($_SESSION['oldusername']))
        $oldusername = $_SESSION['oldusername'];

    if (isset($_SESSION['oldemail']))
        $oldemail = $_SESSION['oldemail'];

    

?>

<html lang="it" dir="ltr">

<head>

    <title>MJStillAlive: Area Riservata</title>
    <meta charset="utf-8">
    <link rel="icon" href="../favicon.ico" type="image/x-icon">

    <link rel="stylesheet"	href="file.css">
    <link rel="stylesheet" href="../sidebar/sidebar.css">
    <link rel="stylesheet" href="../footer/footer.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <script type="text/javascript" src="login.js">
    </script> 


</head>



<body>

    <?php include '../sidebar/sidebar.html'; ?>

    <main>

            <section id="content-login">

                <h1>Accedi</h1>

                    <form action="login-manager.php" method="POST" id="login">
                
                        <div class="input-box">

                            <input type="text" name="usernamelogin" id="usernamelogin" onchange="verificaUsername(this)" value="<?php echo $oldinput ?>" placeholder="Inserisci username o indirizzo e-mail..." required>
                            <i class='bx bx-user'></i>

                        </div>

                        <div class="input-box">
   
                            <input type="password" name="passwordlogin" id="passwordlogin" placeholder="Inserisci password..." minlength="8" required>
                            <i class='bx bx-lock-alt'></i>

                        </div>

                    

                        <div class="button">
                            <input type="submit" name="login" id="submitlogin" value="Login"> 
                        </div>  

                    </form>
                
                <p>
                    Nuovo utente? <a href="javascript:void(0)" onclick="visualizzaRegistrazione()">Registrati!</a>
                </p>

            </section>      <!--    fine login    -->

            <section id="message">           

               <?php
               

                        if(isset($_SESSION['badusername']) && $_SESSION['badusername'] == true) {
                            
                            echo '<div class="message-error">';
                            echo "<i class='bx bx-error-circle'></i>";
                            echo "<p>Login non riuscito: Username non trovato!</p>";
                            echo '</div>';
                            unset($_SESSION['badusername']);

                        }
                            
                        else if (isset($_SESSION['bademail']) && $_SESSION['bademail'] == true) {

                            echo '<div class="message-error">';
                            echo "<i class='bx bx-error-circle'></i>";
                            echo "<p>Login non riuscito: Indirizzo e-mail non trovato!</p>";
                            echo '</div>';
                            unset($_SESSION['bademail']);

                        }

                        else if (isset($_SESSION['badpassword']) && $_SESSION['badpassword'] == true) {

                            echo '<div class="message-error">';
                            echo "<i class='bx bx-error-circle'></i>";
                            echo "<p>Login non riuscito: Password errata!</p>";
                            echo '</div>';
                            unset($_SESSION['badpassword']);

                        }

                        else if(isset($_SESSION['existentusername']) && $_SESSION['existentusername'] == true) {
                            
                            echo '<div class="message-error">';
                            echo "<i class='bx bx-error-circle'></i>";
                            echo "<p>Registrazione non riuscita: Username già utilizzato!</p>";
                            echo '</div>';
                            unset($_SESSION['existentusername']);

                        }
                            
                        else if (isset($_SESSION['existentemail']) && $_SESSION['existentemail'] == true) {

                            echo '<div class="message-error">';
                            echo "<i class='bx bx-error-circle'></i>";
                            echo "<p>Registrazione non riuscita: Indirizzo e-mail già utilizzato!</p>";
                            echo '</div>';
                            unset($_SESSION['bademail']);

                        }

                        else if (isset($_SESSION['registered']) && $_SESSION['registered'] == true) {

                            echo '<div class="message-correct">';
                            echo "<i class='bx bx-check-circle'></i>";
                            echo "<p>L'utente $oldusername è stato registrato con successo!</p>";
                            echo '</div>';
                            unset($_SESSION['registered']);

                        }
                            
                        
                ?>

                    

            </section>

            <section id="content-register">

                <h1>Registrati</h1>


                <form action="register-manager.php" method="POST" onSubmit="return controlloPassword(this);" id="register">

                        <div class="input-box">
                              
                                <input type="text" name="usernameregister" id="usernameregister" onchange="verificaUsername(this)" value="<?php echo $oldusername ?>" placeholder="Inserisci username..." required>
                                <i class='bx bx-user'></i>

                        </div>

                        <div class="input-box">
   
                                <input type="email" name="email" id="email" value="<?php echo $oldemail ?>" placeholder="Inserisci indirizzo e-mail..." required >   
                                <i class='bx bx-envelope'></i>

                        </div>

                        <div class="input-box">

                                <input type="password" name="passwordregister" id="passwordregister" placeholder="Inserisci password..." minlength="8" required>
                                <i class='bx bx-lock-alt'></i>

                        </div>

                        <div class="input-box">  
                                <input type="password" name="confermapassword" id="confermapassword" placeholder="Conferma password..." required>
                                <i class='bx bx-check-shield'></i>

                        </div>

                    
                        <div class="button">
                            <input type="submit" name="registrati" id="submitregister" value="Registrati"> 
                        </div>  

                </form>

                <p>
                    Sei già registrato? <a href="javascript:void(0)" onclick="visualizzaLogin()">Accedi!</a>
                </p>


            </section>

        </main>

       <?php include '../footer/footer.html'; ?>


</body>



</html>