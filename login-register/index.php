<?php
    
    session_start();

    $input = "";
    $username = "";
    $email = "";

    if (isset($_SESSION['badusername'])) {
        $username = $_SESSION['badusername'];
        $input = $_SESSION['badusername'];
    }

    if (isset($_SESSION['bademail'])) {
        $email = $_SESSION['bademail'];
        $input = $_SESSION['bademail'];
    }
    

?>

<html lang="it" dir="ltr">

<head>

    <title>MJ IS ALIVE: Area Riservata</title>
    <meta charset="utf-8">
    <link rel="stylesheet"	href="prova.css">

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

            

            <h1>Accedi</h1>


            <form action="login-manager.php" method="POST">
                <div class="input-login">

                    <label for="username-login">Username o Indirizzo e-mail</label>    
                        <input type="text" name="usernamelogin" onchange="verificaUsername(this)" value='<?php echo $input ?>' placeholder="Inserisci username o indirizzo e-mail..." required>   

                    <?php 
                        if(isset($_SESSION['badusername']))
                            echo "<p>Username non trovato!</p>";
                        else if (isset($_SESSION['bademail']))
                            echo "<p>Indirizzo e-mail non trovato!</p>";
                    ?>
                    
                    <label for="password">Password</label>    
                        <input type="password" name="passwordlogin" placeholder="Inserisci password..." minlength="8" required>
                    
                </div>

                <?php 
                        if(isset($_SESSION['badpassword']))
                            echo "<p>Password errata!</p>";
                ?>

                <input type="submit" name="login" value="Login">    
            </form>
            
            <p>
                Nuovo utente? <a href="javascript:void(0)" onclick="visualizzaRegistrazione()">Registrati!</a>
            </p>

        </section>      <!--    fine login    -->

        <section id="content-register">

            

            <h1>Registrati</h1>


            <form action="register-manager.php" method="POST" onSubmit="return controlloPassword(this);">

                <div class="input-register">

                    <label for="username-register">Username</label>    
                        <input type="text" name="usernameregister" value='<?php echo $username ?>' placeholder="Inserisci username..." required>   

                    <label for="email">Indirizzo e-mail</label>    
                        <input type="email" name="email" value='<?php echo $email ?>' placeholder="Inserisci indirizzo e-mail..." required >   
                    
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