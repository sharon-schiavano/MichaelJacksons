document.addEventListener("DOMContentLoaded", function () {
    updateCartCount();
    loadCart();
    checkUserStatus(); // Controlla lo stato dell'utente al caricamento della pagina

    let cartIcon = document.getElementById("cart-icon");
    let cartMenu = document.getElementById("cart-menu");

    if (cartIcon && cartMenu) {
        cartIcon.addEventListener("click", toggleCart);
    } else {
        console.error("❌ Errore: Elementi cart-icon o cart-menu non trovati nel DOM.");
    }
});

// Controlla se l'utente è loggato e aggiorna la sessione nel frontend
function checkUserStatus() {
    fetch("../php_in_comune/getUser.php")
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.body.dataset.loggedIn = "true";
                document.body.dataset.mjc = data.valuta; // 🔥 Salva il saldo MJC nell'HTML
            } else {
                document.body.dataset.loggedIn = "false";
                localStorage.removeItem("cart"); // 🔥 Se l'utente è sloggato, svuota il carrello
                updateCartCount();
                loadCart();
            }
        })
        .catch(error => console.error("Errore nel controllo utente:", error));
}

//Funzione per verificare se l'utente è loggato
function isUserLoggedIn() {
    return document.body.dataset.loggedIn === "true";
}

// Aggiunta al carrello SOLO se l'utente è loggato
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

//Rimuove un prodotto dal carrello
function removeFromCart(id) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    cart = cart.filter(p => p.id !== id);
    localStorage.setItem("cart", JSON.stringify(cart));
    updateCartCount();
    loadCart();
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

//  Carica il carrello e mostra i prodotti
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
            cartItems.innerHTML += `<li>${item.name} - ${item.quantity} x ${item.price}€ 
                <button onclick="removeFromCart(${item.id})">❌</button></li>`;
        });

        totalElem.innerText = `Totale: ${total} €`;
    }
}

// Toggle del carrello (APRIRE/CHIUDERE)
function toggleCart() {
    let cartMenu = document.getElementById("cart-menu");

    if (!cartMenu) {
        console.error("❌ Errore: Elemento #cart-menu non trovato.");
        return;
    }

    //  Se il carrello ha la classe 'hidden', la rimuove e lo mostra
    if (cartMenu.classList.contains("hidden")) {
        cartMenu.classList.remove("hidden");
    }

    // Alterna la classe "show" per aprire o chiudere il carrello
    cartMenu.classList.toggle("show");

    // Se il carrello viene chiuso, riaggiunge la classe 'hidden' per nasconderlo
    if (!cartMenu.classList.contains("show")) {
        cartMenu.classList.add("hidden");
    }
}


// Acquisto canzoni con controllo MJC
function purchaseSong(songColumn) {
    if (!isUserLoggedIn()) {
        alert("❌ Devi essere loggato per acquistare una canzone.");
        return;
    }

    let userBalance = parseInt(document.body.dataset.mjc, 10);
    let prices = { "billie_jean": 10, "beat_it": 6, "smooth_criminal": 5, "thriller": 8 };
    let songPrice = prices[songColumn];

    if (userBalance < songPrice) {
        alert(`❌ Fondi insufficienti! Hai solo ${userBalance} MJC, ma servono ${songPrice} MJC.`);
        return;
    }

    if (!confirm(`Vuoi acquistare ${songColumn.replace(/_/g, ' ')} per ${songPrice} MJC?`)) {
        return;
    }

    fetch("purchase_song.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ song: songColumn })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(`✅ Acquisto completato! Nuovo saldo: ${data.new_balance} MJC`);
            document.body.dataset.mjc = data.new_balance; //  Aggiorna il saldo in tempo reale
        } else {
            alert(`❌ Errore: ${data.message}`);
        }
    })
    .catch(error => {
        alert("❌ Errore di connessione con il server.");
        console.error("Errore:", error);
    });
}

