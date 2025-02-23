document.addEventListener("DOMContentLoaded", function () {
    updateCartCount();
    loadCart();
    checkUserStatus(); // Controlla se l'utente è loggato

    let cartIcon = document.getElementById("cart-icon");
    let cartMenu = document.getElementById("cart-menu");

    if (cartIcon && cartMenu) {
        cartIcon.addEventListener("click", toggleCart);
    } else {
        console.error("❌ Errore: Elementi cart-icon o cart-menu non trovati nel DOM.");
    }
});

// Controlla se l'utente è loggato
function checkUserStatus() {
    fetch("../php_in_comune/getUser.php")
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.body.dataset.loggedIn = "true";
                document.body.dataset.mjc = data.valuta; //  Salva il saldo MJC nell'HTML
            } else {
                document.body.dataset.loggedIn = "false";
                localStorage.removeItem("cart"); //  Se l'utente sloggato, svuota il carrello
                updateCartCount();
                loadCart();
            }
        })
        .catch(error => console.error("Errore nel controllo utente:", error));
}

// Verifica se l'utente è loggato
function isUserLoggedIn() {
    return document.body.dataset.loggedIn === "true";
}

// Aggiunge un prodotto al carrello solo se loggato
function addToCart(id, name, price) {
    if (!isUserLoggedIn()) {
        alert("❌ Devi essere loggato per aggiungere prodotti al carrello.");
        return;
    }

    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let item = cart.find(p => p.id === id);

    if (item) {
        item.quantity += 1;
    } else {
        cart.push({ id, name, price, quantity: 1 });
    }

    localStorage.setItem("cart", JSON.stringify(cart));
    updateCartCount();
    loadCart();
}

// Rimuove un prodotto dal carrello
function removeFromCart(id) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    cart = cart.filter(p => p.id !== id);
    localStorage.setItem("cart", JSON.stringify(cart));
    updateCartCount();
    loadCart();
}

// Aumenta la quantità di un prodotto nel carrello
function increaseQuantity(id) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let item = cart.find(p => p.id === id);

    if (item) {
        item.quantity += 1;
        localStorage.setItem("cart", JSON.stringify(cart));
        updateCartCount();
        loadCart();
    }
}

// Diminuisce la quantità di un prodotto nel carrello (se arriva a 0, lo rimuove)
function decreaseQuantity(id) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let item = cart.find(p => p.id === id);

    if (item) {
        if (item.quantity > 1) {
            item.quantity -= 1;
        } else {
            cart = cart.filter(p => p.id !== id);
        }
        localStorage.setItem("cart", JSON.stringify(cart));
        updateCartCount();
        loadCart();
    }
}

// Aggiorna il numero di prodotti nel carrello
function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let count = cart.reduce((sum, item) => sum + item.quantity, 0);
    let cartCount = document.getElementById("cart-count");
    if (cartCount) {
        cartCount.innerText = count;
    }
}

// Carica il carrello e mostra i prodotti con la gestione della quantità
function loadCart() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let cartItems = document.getElementById("cart-items");
    let totalElem = document.getElementById("total");

    if (!cartItems || !totalElem) return;

    cartItems.innerHTML = "";

    if (cart.length === 0) {
        cartItems.innerHTML = "<li>Il carrello è vuoto.</li>";
        totalElem.innerText = "Totale: 0 €";
    } else {
        let total = 0;
        cart.forEach(item => {
            total += item.price * item.quantity;
            cartItems.innerHTML += `
                <li>
                    ${item.name} - ${item.quantity} x ${item.price}€ 
                    <button onclick="decreaseQuantity(${item.id})">➖</button>
                    <button onclick="increaseQuantity(${item.id})">➕</button>
                    <button onclick="removeFromCart(${item.id})">❌</button>
                </li>`;
        });

        totalElem.innerText = `Totale: ${total} €`;
    }
}

function toggleCart() {
    let cartMenu = document.getElementById("cart-menu");

    if (!cartMenu) {
        console.error("❌ Errore: Elemento #cart-menu non trovato.");
        return;
    }

    // Se il carrello è nascosto, lo mostra
    if (cartMenu.classList.contains("hidden")) {
        cartMenu.classList.remove("hidden");
    }

    // Alterna la classe "show" per far apparire il carrello
    cartMenu.classList.toggle("show");

    // Se il carrello viene chiuso, lo nasconde di nuovo
    if (!cartMenu.classList.contains("show")) {
        cartMenu.classList.add("hidden");
    }
}

function purchaseSong(songName, songPrice) {
    // Formatta il nome della canzone
    const formattedSongName = songName.toLowerCase().replace(/\s+/g, "_");

    // Mostra un alert di conferma
    const confirmPurchase = confirm(`Sei sicuro di voler acquistare "${songName}" per ${songPrice} MJC?`);

    if (!confirmPurchase) {
        alert("Acquisto annullato."); // L'utente ha annullato l'acquisto
        return;
    }

    // Procedi con l'acquisto
    fetch("purchase_song.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ song: formattedSongName, price: songPrice })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`✅ Acquisto completato! Nuovo saldo: ${data.new_balance} MJC`);
            document.body.dataset.mjc = data.new_balance; // Aggiorna il saldo MJC nell'HTML

            // Aggiorna il valore di "Valuta" nella sidebar
            const mjcSpan = document.getElementById("mjc");
            if (mjcSpan) {
                mjcSpan.innerHTML = "Valuta: " + data.new_balance + '<i class="bx bxs-coin"></i>';
            }

            // Aggiorna i pulsanti delle canzoni sbloccate solo se esistono
            const playButton1 = document.getElementById("playButton1");
            const playButton2 = document.getElementById("playButton2");
            const playButton4 = document.getElementById("playButton4");
            const playButton5 = document.getElementById("playButton5");

            if (playButton1 && data.unlocked_billiejean === 't') playButton1.style.display = "block";
            if (playButton2 && data.unlocked_beatit === 't') playButton2.style.display = "block";
            if (playButton4 && data.unlocked_smoothcriminal === 't') playButton4.style.display = "block";
            if (playButton5 && data.unlocked_thriller === 't') playButton5.style.display = "block";
        } else {
            throw new Error(data.message);
        }
    })
    .catch(error => {
        alert("❌ Errore: " + error.message);
    });
}