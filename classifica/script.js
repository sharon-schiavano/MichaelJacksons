
document.addEventListener("DOMContentLoaded", function () {
    const sortCriteriaDropdown = document.getElementById("sortCriteria");
    const leaderboard = document.getElementById("leaderboard");

    // Funzione per caricare la classifica
    function loadLeaderboard(sortCriteria = "mjc") {
        fetch(`classifica.php?sort=${sortCriteria}`)
            .then(response => response.json())
            .then(data => {
                leaderboard.innerHTML = ""; // Pulisce la classifica prima di riempirla

                data.forEach((utente, index) => {
                    // Creazione del rettangolo utente
                    const userDiv = document.createElement("div");
                    userDiv.classList.add("utente");

                    // Assegna colore ai primi tre
                    if (index === 0) userDiv.style.backgroundColor = "gold";
                    else if (index === 1) userDiv.style.backgroundColor = "#888888";
                    else if (index === 2) userDiv.style.backgroundColor = "#CD7F32";

                    if(sortCriteria != "mjc"){
                    userDiv.innerHTML = `
                        <div style="display: flex; align-items: center;">
                            <img src="${utente.immagine_profilo}" class="profilo">
                            <span>${utente.username} - ${utente[sortCriteria]} punti</span>
                        </div>
                        <div class="tendina"></div>
                    `;
                    } else {
                        userDiv.innerHTML = `
                        <div style="display: flex; align-items: center;">
                            <img src="${utente.immagine_profilo}" class="profilo">
                            <span> ${index+1}. ${utente.username} - ${utente[sortCriteria]} MJC</span>
                        </div>
                        <div class="tendina"></div>
                    `; 
                    }

                    leaderboard.appendChild(userDiv);

                    userDiv.addEventListener("click", function () {
                        const tendina = this.querySelector(".tendina");

                        // Alterna la visibilità della tendina
                        const isOpen = tendina.classList.contains("aperta");

                        // Chiude tutte le altre tendine
                        document.querySelectorAll(".tendina").forEach(t => {
                            t.classList.remove("aperta");
                            t.style.display = "none"; // Nasconde
                            t.innerHTML = ""; // Pulisce il contenuto
                        });

                        // Se non era aperta, la apre e carica i dati
                        if (!isOpen) {
                            fetch(`dettagli_utente.php?id=${utente.id}`)
                                .then(response => response.json())
                                .then(dati => {
                                    tendina.innerHTML = `
                                        <div class="info-box">Punteggio massimo Billie Jean: ${dati.billie_jean}</div>
                                        <div class="info-box">Punteggio massimo Beat It: ${dati.beat_it}</div>
                                        <div class="info-box">Punteggio massimo Rock with you: ${dati.rock_with_you}</div>
                                        <div class="info-box">Punteggio massimo Smooth criminal: ${dati.smooth_criminal}</div>
                                        <div class="info-box">Punteggio massimo Thriller: ${dati.thriller}</div>
                                        <div class="info-box">Utente iscritto dal: ${dati.data_iscrizione}</div>
                                    `;
                                    tendina.style.display = "flex"; // Mostra la tendina
                                    tendina.classList.add("aperta");
                                });
                        }
                    });
                });
            });
    }

    // Ricarica la classifica quando cambia il criterio di ordinamento
    sortCriteriaDropdown.addEventListener("change", function () {
        loadLeaderboard(this.value);
    });

    // Carica la classifica iniziale con il criterio predefinito
    loadLeaderboard();
});
