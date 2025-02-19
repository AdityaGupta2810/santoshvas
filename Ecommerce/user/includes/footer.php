<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Toggle sidebar for small screens
const sidebar = document.getElementById("sidebar");
const sidebarToggle = document.getElementById("sidebarToggle");

sidebarToggle.addEventListener("click", () => {
    sidebar.classList.toggle("-translate-x-full");
});

// Toggle user dropdown on hover for larger screens
const userMenuButton = document.getElementById("userMenuButton");
const userMenuDropdown = document.getElementById("userMenuDropdown");

// Show dropdown when hovering over the button
userMenuButton.addEventListener("mouseenter", () => {
    userMenuDropdown.classList.remove("hidden");
});

// Keep dropdown open when hovering over it
userMenuDropdown.addEventListener("mouseenter", () => {
    userMenuDropdown.classList.remove("hidden");
});

// Hide dropdown when leaving both button and dropdown
userMenuButton.addEventListener("mouseleave", () => {
    setTimeout(() => {
        if (!userMenuDropdown.matches(":hover")) {
            userMenuDropdown.classList.add("hidden");
        }
    }, 200); // Small delay to allow cursor transition
});

userMenuDropdown.addEventListener("mouseleave", () => {
    userMenuDropdown.classList.add("hidden");
});

// Toggle user dropdown on click for mobile
const userMenuButtonMobile = document.getElementById("userMenuButtonMobile");
const userMenuDropdownMobile = document.getElementById("userMenuDropdownMobile");

userMenuButtonMobile.addEventListener("click", () => {
    userMenuDropdownMobile.classList.toggle("hidden");
});

</script>

<script> 
// Swal.fire({
//   title: "Good job!",
//   text: "You clicked the button!",  // when page is refreshed it will displayed
//   icon: "success"
// });
<?php
$fn->error();
$fn->alert();
?>
</script>
</body>
</html>