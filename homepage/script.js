//barra di ricerca

function searchMenu() {
    let input = document.getElementById("search").value.toLowerCase();
    let items = document.querySelectorAll(".card h4");

    items.forEach(item => {
        let text = item.textContent.toLowerCase();
        let card = item.closest(".card");
        card.style.display = text.includes(input) ? "block" : "none";
    });
}

//immagine del profilo

const dropArea = document.getElementById('drop-area');
const inputFile = document.getElementById('input-file');
const imgView = document.getElementById('img-view');
const profileImage = document.getElementById('profileImage');
const uploadForm = document.getElementById('uploadForm');
const browseLink = document.getElementById('browse-link');


function uploadImage(file) {
    console.log("Upload file:", file);
    if (!file || !file.type.startsWith("image/")) {
        alert("Seleziona un file immagine valido.");
        return;
    }

    let imgLink = URL.createObjectURL(file);
    profileImage.src = imgLink;
    imgView.innerHTML = "";
    imgView.appendChild(profileImage);
}


// Eventi per il drag & drop
dropArea.addEventListener("dragover", function(e) {
e.preventDefault();
dropArea.style.border = "2px dashed #bfb22b";
});

dropArea.addEventListener("dragleave", function() {
dropArea.style.border = "2px solid #ccc";
});

dropArea.addEventListener("drop", function(e) {
    e.preventDefault();
    dropArea.style.border = "2px solid #ccc";

    let file = e.dataTransfer.files[0];
    if (file) {
        let dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        inputFile.files = dataTransfer.files;

        uploadImage(file);
        inputFile.dispatchEvent(new Event("change")); // Forza il trigger dell'evento change
    }
});

browseLink.addEventListener("click", function () {
    inputFile.click();
});

// Evento per il cambio di file tramite input
inputFile.addEventListener("change", function() {
    if (inputFile.files.length > 0) {
        uploadImage(inputFile.files[0]);
        uploadForm.submit(); // Invia il form automaticamente
    }
});


//cancella l'immagine del profilo
const deleteButton = document.getElementById('deleteButton');

deleteButton.addEventListener("click", function() {

/*
// Recupera l'immagine di default
const defaultImagePath = '../uploads/profile_images/default.jpg';
*/

// Resetta il campo di input file
inputFile.value = '';

// Invia una richiesta al server per eliminare l'immagine
fetch('delete_profile_image.php', {
method: 'POST',
headers: {
    'Content-Type': 'application/json',
}
})
.then(response => response.json())
.then(data => {
if (data.success) {
    alert('Immagine eliminata con successo');
    const profilepic = document.getElementById("profilepic");
} else {
    alert('Errore durante l\'eliminazione dell\'immagine');
}
})
.catch(error => {
console.error('Errore:', error);
alert('Errore durante l\'eliminazione dell\'immagine');
});
});

//classifica

document.addEventListener("DOMContentLoaded", function () {
    const leaderboard = document.getElementById("leaderboard");
    const sortCriteria = document.getElementById("sortCriteria");

    function fetchLeaderboard(sort) {
        fetch(`classifica.php?sort=${sort}`)
            .then(response => response.json())
            .then(data => {
                if (data.success === false) {
                    leaderboard.innerHTML = `<p>Errore nel caricamento della classifica.</p>`;
                    return;
                }
                
                leaderboard.innerHTML = ""; // Pulisce la classifica

                data.forEach((user, index) => {
                    const userElement = document.createElement("div");
                    userElement.classList.add("leaderboard-entry");

                    

                    userElement.innerHTML = `
                        <span class="rank">#${index + 1}</span>
                        <img src="${user.immagine_profilo || 'default.jpg'}" alt="Immagine Profilo" class="profile-pic">
                        <span class="username">${user.username}</span>
                        <span class="score">${user[sort]}</span>
                    `;

                    userElement.addEventListener("click", () => showUserDetails(user.id));

                    leaderboard.appendChild(userElement);
                });
            })
            .catch(error => {
                console.error("Errore nel recupero della classifica:", error);
            });
    }

    function showUserDetails(userId) {
        fetch(`dettagli_utente.php?id=${userId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success === false) {
                    alert("Errore nel caricamento dei dettagli utente.");
                    return;
                }

                alert(`Username: ${data.username}\nEmail: ${data.email}\nMJC: ${data.mjc}`);
            })
            .catch(error => {
                console.error("Errore nel recupero dei dettagli utente:", error);
            });
    }

    sortCriteria.addEventListener("change", function () {
        fetchLeaderboard(this.value);
    });

    fetchLeaderboard(sortCriteria.value);
});
