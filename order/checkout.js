$(document).ready(function() {
    $("#checkoutForm").submit(function(e) {
        e.preventDefault(); // Prevent default form submission
        var paymentMethod = $("input[name='payment_method']:checked").val();
        console.log("Payment Method:", paymentMethod); // Log payment method

        if (paymentMethod === 'online') {
            $("#qrPopup").show(); // Show QR code popup for online payment
            console.log("QR Popup Shown"); // Log when popup is shown
        } else {
            this.submit(); // Submit the form directly for Cash on Delivery
        }
    });

    $("#paymentDoneBtn").click(function() {
        var formData = $("#checkoutForm").serialize(); // Serialize form data
        $.ajax({
            type: "POST",
            url: "checkout.php", // This URL will handle the order confirmation
            data: formData, // Send form data
            dataType: "json", // Expect JSON response
            success: function(response) {
                if (response.order_id) {
                    // Redirect to confirmation page with the order ID
                    window.location.href = "order_confirmation.php?order_id=" + response.order_id; // Redirect to confirmation page
                } else {
                    alert("Order ID not found, try again.");
                }
            },
            error: function() {
                alert("Error processing your payment. Please try again."); // Error handling
            }
        });
    });

    $("#closePopup").click(function() {
        $("#qrPopup").hide(); // Hide QR code popup
    });
});