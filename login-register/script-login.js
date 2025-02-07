
        
        function verifica(elementoInput) {

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

            }
            
        }

        function controlloPassword(elementoModulo) {

            if (elementoModulo.password-register.value == "") {

                alert("Errore: inserisci password!");
                return false;

            }

            if (elementoModulo.password-register.value != elementoModulo.conferma-password.value) {

                alert("Errore: le due password non corrispondono!");
                return false;
            }

            return true;

        }

        function visualizzaRegistrazione() {
            
        }

        function visualizzaLogin() {

        }


