import './bootstrap';
// ==========================
// Sidebar Active Link
// ==========================
document.addEventListener("DOMContentLoaded", function(){

    const links = document.querySelectorAll(".sidebar a");
    const currentUrl = window.location.href;

    links.forEach(link => {
        if(currentUrl.includes(link.getAttribute("href"))){
            link.classList.add("active-menu");
        }
    });

});

// ==========================
// Confirm Delete
// ==========================
function confirmDelete(){
    return confirm("Are you sure you want to delete this record?");
}

// ==========================
// Auto Hide Alert Messages
// ==========================
setTimeout(function(){
    let alerts = document.querySelectorAll(".alert");
    alerts.forEach(alert => {
        alert.style.display = "none";
    });
},3000);

// ==========================
// POS: Calculate Subtotal
// ==========================
function calculateSubtotal(price, qty){
    return price * qty;
}
window.calculateSubtotal = calculateSubtotal;