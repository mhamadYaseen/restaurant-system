let orderItems = [];
let subtotal = 0;
const taxRate = 0.1; // 10%

// Add item to order
function addToOrder(id, name, price) {
    const quantityInput = document.getElementById(`quantity-${id}`);
    const quantity = parseInt(quantityInput.value);

    // Check if item already exists in the order
    const existingItem = orderItems.find((item) => item.id === id);

    if (existingItem) {
        existingItem.quantity += quantity;
    } else {
        orderItems.push({
            id: id,
            name: name,
            price: price,
            quantity: quantity,
        });
    }

    // Reset quantity input
    quantityInput.value = 1;

    // Update order display
    updateOrderDisplay();
}

// Remove item from order
function removeFromOrder(id) {
    orderItems = orderItems.filter((item) => item.id !== id);
    updateOrderDisplay();
}

// Update quantity of an item in order
function updateOrderQuantity(id, newQuantity) {
    const item = orderItems.find((item) => item.id === id);
    if (item) {
        if (newQuantity <= 0) {
            removeFromOrder(id);
        } else {
            item.quantity = newQuantity;
            updateOrderDisplay();
        }
    }
}

// Update the order display
function updateOrderDisplay() {
    const orderContainer = document.getElementById("order-items");
    const emptyMessage = document.querySelector(".empty-cart-message");
    const orderSummary = document.querySelector(".order-summary");
    const placeOrderBtn = document.getElementById("place-order-btn");
    const orderInputsContainer = document.getElementById("order-inputs");

    // Clear current content except the empty message and inputs container
    Array.from(orderContainer.children).forEach((child) => {
        if (
            !child.classList.contains("empty-cart-message") &&
            child.id !== "order-inputs"
        ) {
            child.remove();
        }
    });

    // Clear hidden inputs
    orderInputsContainer.innerHTML = "";

    if (orderItems.length === 0) {
        // Show empty message
        emptyMessage.classList.remove("d-none");
        orderSummary.classList.add("d-none");
        placeOrderBtn.disabled = true;
        return;
    }

    // Hide empty message and show order summary
    emptyMessage.classList.add("d-none");
    orderSummary.classList.remove("d-none");
    placeOrderBtn.disabled = false;

    // Calculate subtotal
    subtotal = 0;

    // Add each order item
    orderItems.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;

        // Create hidden inputs for form submission
        const idInput = document.createElement("input");
        idInput.type = "hidden";
        idInput.name = `items[${index}][id]`;
        idInput.value = item.id;
        orderInputsContainer.appendChild(idInput);

        const quantityInput = document.createElement("input");
        quantityInput.type = "hidden";
        quantityInput.name = `items[${index}][quantity]`;
        quantityInput.value = item.quantity;
        orderInputsContainer.appendChild(quantityInput);

        const orderItemElement = document.createElement("div");
        orderItemElement.className = "order-item";
        orderItemElement.innerHTML = `
<div class="d-flex justify-content-between">
<h6 class="mb-0">${item.name}</h6>
<button type="button" class="btn btn-sm text-danger p-0" onclick="removeFromOrder('${
            item.id
        }')">
<i class="fas fa-times"></i>
</button>
</div>
<div class="d-flex justify-content-between align-items-center mt-2">
<div class="input-group input-group-sm" style="width: 100px">
<button type="button" class="btn btn-outline-secondary btn-sm" 
    onclick="updateOrderQuantity('${item.id}', ${item.quantity - 1})">
    <i class="fas fa-minus"></i>
</button>
<input type="number" class="form-control text-center" value="${
            item.quantity
        }" min="1" readonly>
<button type="button" class="btn btn-outline-secondary btn-sm" 
    onclick="updateOrderQuantity('${item.id}', ${item.quantity + 1})">
    <i class="fas fa-plus"></i>
</button>
</div>
<span>$${itemTotal.toFixed(2)}</span>
</div>
`;

        orderContainer.insertBefore(orderItemElement, orderInputsContainer);
    });

    // Update summary amounts
    const tax = subtotal * taxRate;
    const total = subtotal + tax;

    document.getElementById(
        "subtotal-amount"
    ).textContent = `$${subtotal.toFixed(2)}`;
    document.getElementById("tax-amount").textContent = `$${tax.toFixed(2)}`;
    document.getElementById("total-amount").textContent = `$${total.toFixed(
        2
    )}`;
}

// Update the existing code to use AJAX for form submission
document.addEventListener("DOMContentLoaded", function () {
    // Add form submission handler
    const orderForm = document.getElementById("orderForm");
    orderForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Prevent default form submission

        if (orderItems.length === 0) {
            return;
        }

        // Show loading state
        const placeOrderBtn = document.getElementById("place-order-btn");
        placeOrderBtn.innerHTML =
            '<i class="fas fa-spinner fa-spin"></i> Processing...';
        placeOrderBtn.disabled = true;

        // Collect form data
        const formData = new FormData(orderForm);

        // Send AJAX request
        fetch(orderForm.action, {
            method: "POST",
            body: formData,
            headers: {
                "X-Requested-With": "XMLHttpRequest",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.success) {
                    // Show receipt modal with order details
                    displayReceipt(data.receipt);

                    // Reset order
                    orderItems = [];
                    updateOrderDisplay();
                } else {
                    // Handle errors
                    alert(
                        "There was a problem with your order. Please try again."
                    );
                }

                // Reset button
                placeOrderBtn.innerHTML = "Place Order";
                placeOrderBtn.disabled = false;
            })
            .catch((error) => {
                console.error("Error:", error);
                alert(
                    "An error occurred while processing your order. Please try again."
                );

                // Reset button
                placeOrderBtn.innerHTML = "Place Order";
                placeOrderBtn.disabled = false;
            });
    });
});

// Function to display the receipt
function displayReceipt(receiptData) {
    // Set basic receipt info
    document.getElementById("receipt-order-number").textContent =
        receiptData.order_id;
    document.getElementById("receipt-date").textContent = receiptData.date;
    document.getElementById("receipt-time").textContent = receiptData.time;

    // Set financial data
    document.getElementById("receipt-subtotal").textContent =
        "$" + receiptData.subtotal.toFixed(2);
    document.getElementById("receipt-tax").textContent =
        "$" + receiptData.tax.toFixed(2);
    document.getElementById("receipt-total").textContent =
        "$" + receiptData.total.toFixed(2);

    // Clear and populate items table
    const itemsContainer = document.getElementById("receipt-items");
    itemsContainer.innerHTML = "";

    receiptData.items.forEach((item) => {
        const row = document.createElement("tr");
        row.innerHTML = `
            <td>${item.name}</td>
            <td class="text-center">${item.quantity}</td>
            <td class="text-end">$${parseFloat(item.price).toFixed(2)}</td>
            <td class="text-end">$${parseFloat(item.total).toFixed(2)}</td>
        `;
        itemsContainer.appendChild(row);
    });

    // Show the modal
    const receiptModal = new bootstrap.Modal(
        document.getElementById("orderReceiptModal")
    );
    receiptModal.show();
}

// Increase quantity on menu item
function increaseQuantity(id) {
    const quantityInput = document.getElementById(`quantity-${id}`);
    quantityInput.value = parseInt(quantityInput.value) + 1;
}

// Decrease quantity on menu item
function decreaseQuantity(id) {
    const quantityInput = document.getElementById(`quantity-${id}`);
    if (parseInt(quantityInput.value) > 1) {
        quantityInput.value = parseInt(quantityInput.value) - 1;
    }
}

// Filter menu by category
function filterCategory(categoryId) {
    // Update active button
    const buttons = document.querySelectorAll(".category-pills .btn");
    buttons.forEach((btn) => btn.classList.remove("active"));

    const clickedButton = event.currentTarget;
    clickedButton.classList.add("active");

    // Show/hide categories
    const categories = document.querySelectorAll(".category-section");

    if (categoryId === "all") {
        categories.forEach((cat) => (cat.style.display = "block"));
    } else {
        categories.forEach((cat) => {
            if (cat.id === `category-${categoryId}`) {
                cat.style.display = "block";
            } else {
                cat.style.display = "none";
            }
        });
    }
}

// Place order function
function placeOrder() {
    if (orderItems.length === 0) return;

    // Example: Send order data to server
    const orderData = {
        items: orderItems.map((item) => ({
            id: item.id,
            quantity: item.quantity,
        })),
        subtotal: subtotal,
        tax: subtotal * taxRate,
        total: subtotal + subtotal * taxRate,
    };

    console.log("Placing order:", orderData);

    // Show loading state
    const placeOrderBtn = document.getElementById("place-order-btn");
    const originalText = placeOrderBtn.innerHTML;
    placeOrderBtn.innerHTML =
        '<i class="fas fa-spinner fa-spin"></i> Processing...';
    placeOrderBtn.disabled = true;

    // Simulate API call (replace with actual Ajax request)
    setTimeout(() => {
        // Reset order
        orderItems = [];
        updateOrderDisplay();

        // Show success message
        alert("Your order has been placed successfully!");

        // Reset button
        placeOrderBtn.innerHTML = originalText;
        placeOrderBtn.disabled = false;
    }, 1500);
}
