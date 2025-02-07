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

    <title>MJ IS ALIVE: Area Riservata</title>
    <meta charset="utf-8">
    <link rel="stylesheet"	href="file.css">

    <script type="text/javascript" src="login.js">
    </script> 


</head>



<body>



        <header>

                    


                    <nav>

                        <a href="#HOME">HOME</a>
                        <a href="#GIOCA">GIOCA</a>
                        <a href="#SHOP">SHOP</a>
                        <a href="#CLASSIFICA">CLASSIFICA</a>
                    
                    </nav>  
            

        </header>


        <section id="content-login">

            <div class="register-completed">

                <?php 

                        if(isset($_SESSION['registered']) && $_SESSION['registered'] == true) {
                            
                            echo "L'utente $username è stato registrato correttamente!";
                            unset($_SESSION['registered']);

                        }
                            
                ?>

            </div>

            <h1>Accedi</h1>


            <form action="login-manager.php" method="POST">
                <div class="input-login">

                    <label for="username-login">Username o Indirizzo e-mail</label>    
                        <input type="text" name="usernamelogin" onchange="verificaUsername(this)" value='<?php echo $oldinput ?>' placeholder="Inserisci username o indirizzo e-mail..." required>   
                    
                    <label for="password">Password</label>    
                        <input type="password" name="passwordlogin" placeholder="Inserisci password..." minlength="8" required>
                    
                </div>

                <input type="submit" name="login" value="Login">    
            </form>
            
            <p>
                Nuovo utente? <a href="javascript:void(0)" onclick="visualizzaRegistrazione()">Registrati!</a>
            </p>

            <div class="error">

                <?php 
                        if(isset($_SESSION['badusername']) && $_SESSION['badusername'] == true) {
                            
                            echo "Login non riuscito: Username non trovato!";
                            unset($_SESSION['badusername']);

                        }
                            
                        else if (isset($_SESSION['bademail']) && $_SESSION['bademail'] == true) {

                            echo "Login non riuscito: Indirizzo e-mail non trovato!";
                            unset($_SESSION['bademail']);

                        }

                        else if (isset($_SESSION['badpassword']) && $_SESSION['badpassword'] == true) {

                            echo "Login non riuscito: Password errata!";
                            unset($_SESSION['badpassword']);

                        }

                        else if(isset($_SESSION['existentusername']) && $_SESSION['existentusername'] == true) {
                            
                            echo "Registrazione non riuscita: Username già utilizzato!";
                            unset($_SESSION['existentusername']);

                        }
                            
                        else if (isset($_SESSION['existentemail']) && $_SESSION['existentemail'] == true) {

                            echo "Registrazione non riuscita: Indirizzo e-mail già utilizzato!";
                            unset($_SESSION['bademail']);

                        }
                            
        
                ?>

            </div>

        </section>      <!--    fine login    -->

        <section id="content-register">

            <h1>Registrati</h1>


            <form action="register-manager.php" method="POST" onSubmit="return controlloPassword(this);">

                <div class="input-register">

                    <label for="username-register">Username</label>    
                        <input type="text" name="usernameregister" value='<?php echo $oldusername ?>' placeholder="Inserisci username..." required>   

                    <label for="email">Indirizzo e-mail</label>    
                        <input type="email" name="email" value='<?php echo $oldemail ?>' placeholder="Inserisci indirizzo e-mail..." required >   
                    
                    <label for="password-register">Password</label>    
                        <input type="password" name="passwordregister" placeholder="Inserisci password..." minlength="8" required>
                    
                    <label for="conferma-password">Conferma password</label>    
                        <input type="password" name="confermapassword" placeholder="Conferma password..." required>

                </div>

                <input type="submit" name="registrati" value="Registrati">   

            </form>

            <p>
                Sei già registrato? <a href="javascript:void(0)" onclick="visualizzaLogin()">Accedi!</a>
            </p>


        </section>
        

</body>



</html>