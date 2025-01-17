<html lang="it" dir="ltr">

<head>

    <title>MJ IS ALIVE: Accedi o Registrati</title>
    <meta charset="utf-8">
    <link rel="stylesheet"	href="login.css">

    <script language="javascript" type="text/javascript">
        
        function verifica(elementoInput) {

            nome = elementoInput.value;
            atPos = nome.indexOf("@",0);

            if(atPos > -1) {	//ho trovato una @: uso pattern delle e-mail

                const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

                
                if ( !emailPattern.test(nome) ) {
                    alert("Input non valido: Inserire una e-mail valida");
                    elementoInput.select();
                    elementoInput.value="";
                }

            }

        }


    </script>     


</head>



<body>


    <div class="header">

        <header>

                    


                    <div class="navbar">
                        <a href="#HOME">HOME</a>
                        <a href="#GIOCA">GIOCA</a>
                        <a href="#SHOP">SHOP</a>
                        <a href="#CLASSIFICA">CLASSIFICA</a>
                    
                    </div>  
            

        </header>

    </div>


    <div class="content-login">

        

        <h1>Accedi</h1>


        <form>
            <div class="input-login">

                <label for="username-login">Username o Indirizzo e-mail</label>    
                    <input type="text" name="username-login" onchange="verifica(this)" placeholder="Inserisci username o indirizzo e-mail..." required>   

                <label for="password">Password</label>    
                    <input type="password" name="password" placeholder="Inserisci password..." required>
                   
            </div>

             <input type="submit" name="login" value="Login">    
        </form>
        
        Nuovo utente? <a href="#registrati">Registrati!</a>


    </div>      <!--    fine login    -->


</body>



</html>
