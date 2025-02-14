
        
        function verificaUsername(elementoInput) {

            if(controlloInput(elementoInput) > -1) {	//ho trovato una @: uso pattern delle e-mail

                verificaMail(elementoInput);

            }

        }

        function controlloInput(elementoInput) {

            nome = elementoInput.value;
            atPos = nome.indexOf("@",0);

            return atPos;       //se è >=0 allora c'è una @ e quindi va considerata come un possibile indirizzo e-mail; altrimenti va considerato come uno username
        }

        function verificaMail(elementoInput) {

            mail = elementoInput.value;

            const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;


            if ( !emailPattern.test(mail) ) {

                alert("Input non valido: Inserire una e-mail valida");
                elementoInput.select();
                elementoInput.value="";
            }
            
        }

        function controlloPassword(elementoModulo) {

            if (elementoModulo.passwordregister.value == "") {

                alert("Errore: inserisci password!");
                return false;

            }

            if (elementoModulo.passwordregister.value != elementoModulo.confermapassword.value) {

                alert("Errore: le due password non corrispondono!");
                return false;
            }

            return true;

        }

        function visualizzaRegistrazione() {
            
            var login = document.getElementById("content-login");

            var register = document.getElementById("content-register");

            var message = document.getElementById("message");

            login.style.display = "none";

            register.style.display = "flex";
            register.style.flexDirection = "column";

            message.style.display ="none";

        }

        function visualizzaLogin() {

            var login = document.getElementById("content-login");

            var register = document.getElementById("content-register");

            var message = document.getElementById("message");

            register.style.display = "none";

            login.style.display = "flex";
            login.style.flexDirection = "column";
            
            message.style.display="block";

        }



