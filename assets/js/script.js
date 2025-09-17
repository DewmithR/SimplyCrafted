document.addEventListener("DOMContentLoaded", function () {
  const qtyInputs = document.querySelectorAll(".qty-input");

  qtyInputs.forEach(input => {
    input.addEventListener("change", function () {
      let row = this.closest("tr");
      let price = parseFloat(row.dataset.price);
      let qty = parseInt(this.value);
      let subtotalCell = row.querySelector(".subtotal");

      if (qty < 1) qty = 1;
      let subtotal = price * qty;
      subtotalCell.textContent = "Rs." + subtotal.toFixed(2);

      // Update total
      updateTotal();

      // Update database (AJAX)
      let cartId = this.dataset.cartid;
      fetch("update_cart.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: "cart_id=" + cartId + "&quantity=" + qty
      });
    });
  });

  function updateTotal() {
    let total = 0;
    document.querySelectorAll("#cartTable tr").forEach(row => {
      let subtotal = row.querySelector(".subtotal");
      if (subtotal) {
        total += parseFloat(subtotal.textContent.replace("Rs.",""));
      }
    });
    document.getElementById("cartTotal").textContent = "Rs." + total.toFixed(2);
  }
});
