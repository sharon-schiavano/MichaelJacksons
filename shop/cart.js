document.addEventListener("DOMContentLoaded", function() {
    updateCartCount();
    loadCart();
    loadUserBalance(); // Aggiorna il saldo MJC al caricamento della pagina

    let cartIcon = document.getElementById("cart-icon");
    if (cartIcon) {
        cartIcon.addEventListener("click", toggleCart);
    }
});

function toggleCart() {
    let cartMenu = document.getElementById("cart-menu");
    cartMenu.classList.toggle("hidden");
    cartMenu.classList.toggle("show");
}

//  GESTIONE CARRELLO (SOLO per i prodotti in EURO)
function addToCart(id, name, price) {
    if (!isUserLoggedIn()) {
        alert("❌ Devi essere loggato per aggiungere prodotti al carrello!");
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

// Funzione per verificare se l'utente è loggato
function isUserLoggedIn() {
    return document.body.dataset.loggedIn === "true"; // Verifica se il body ha un dataset con `loggedIn`
}

function removeFromCart(id) {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    cart = cart.filter(p => p.id !== id);
    localStorage.setItem("cart", JSON.stringify(cart));
    updateCartCount();
    loadCart();
}

function updateCartCount() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    let count = cart.reduce((sum, item) => sum + item.quantity, 0);
    let cartCount = document.getElementById("cart-count");
    if (cartCount) {
        cartCount.innerText = count;
    }
}

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

function purchaseSong(songColumn, price) {
    if (!confirm(`Vuoi acquistare ${songColumn.replace(/_/g, ' ')} per ${price} MJC?`)) {
        return;
    }

    fetch("purchase_song.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ song: songColumn, price: price })
    })
    .then(response => response.json())
    .then(data => {
        console.log(data);

        if (data.success) {
            alert(`✅ Acquisto completato! Hai sbloccato ${songColumn.replace(/_/g, ' ')}.\nNuovo saldo: ${data.new_balance} MJC`);
            document.getElementById("mjc-balance").innerText = `Saldo MJC: ${data.new_balance}`;
        } else {
            alert(`❌ Errore: ${data.message}`);
        }
    })
    .catch(error => {
        alert("❌ Errore di connessione con il server.");
        console.error("Errore:", error);
    });
}

// FUNZIONE PER AGGIORNARE IL SALDO MJC IN TEMPO REALE
function loadUserBalance() {
    fetch('get_balance.php') // 🔥 Nuovo file PHP per recuperare il saldo MJC
    .then(response => response.json())
    .then(data => {
        let balanceElem = document.getElementById("mjc-balance");
        if (balanceElem) {
            balanceElem.innerText = `Saldo MJC: ${data.saldo_mjc}`;
        }
    })
    .catch(error => console.error("Errore nel recupero del saldo:", error));
}
