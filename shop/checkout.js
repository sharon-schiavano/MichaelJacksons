document.addEventListener("DOMContentLoaded", () => {
    const stripe = Stripe("pk_test_51QuwTx08nTLkC4pOcMo1haispr4wVI5QUDv7gaiiHzP0FDFx5Ml99wqE2dmkbx85CHtZkrovrZf6uTmYJbGTbk4d00QacxHsIS"); // Chiave pubblica

    const elements = stripe.elements();
    const cardElement = elements.create("card");
    cardElement.mount("#card-element");

    const cardErrors = document.getElementById("card-errors");
    const form = document.getElementById("payment-form");

    form.addEventListener("submit", async (event) => {
        event.preventDefault();

        let totalAmount = getCartTotal() * 100; // Convertire in centesimi

        if (totalAmount <= 0) {
            alert("❌ Il carrello è vuoto.");
            return;
        }

        document.getElementById("submit-button").disabled = true; // Evita doppi invii

        try {
            const response = await fetch("payment.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ amount: totalAmount }),
            });

            const data = await response.json();
            if (data.error) {
                cardErrors.textContent = "Errore durante il pagamento: " + data.error;
                document.getElementById("submit-button").disabled = false;
                return;
            }

            const { error, paymentIntent } = await stripe.confirmCardPayment(data.clientSecret, {
                payment_method: { card: cardElement },
            });

            if (error) {
                cardErrors.textContent = "Errore: " + error.message;
                document.getElementById("submit-button").disabled = false;
            } else if (paymentIntent.status === "succeeded") {
                alert("✅ Pagamento completato con successo!");
                localStorage.removeItem("cart"); // Svuota il carrello
                window.location.href = "success.php"; // Reindirizza alla pagina di successo
            }
        } catch (error) {
            console.error("Errore:", error);
            cardErrors.textContent = "Errore sconosciuto, riprova.";
            document.getElementById("submit-button").disabled = false;
        }
    });
});

// Funzione per ottenere il totale del carrello
function getCartTotal() {
    let cart = JSON.parse(localStorage.getItem("cart")) || [];
    return cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
}
