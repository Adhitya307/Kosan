document.addEventListener('DOMContentLoaded', function() {
    // Add any interactive functionality here
    
    // Example: Confirmation for logout
    const logoutBtn = document.querySelector('.logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function(e) {
            if (!confirm('Apakah Anda yakin ingin logout?')) {
                e.preventDefault();
            }
        });
    }
    
    // Example: Add animation to menu items
    const menuItems = document.querySelectorAll('.menu-item');
    menuItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.1}s`;
    });
    
    // Example: Add hover effect to kamar items
    const kamarItems = document.querySelectorAll('.kamar-item');
    kamarItems.forEach((item, index) => {
        item.style.animationDelay = `${index * 0.1 + 0.2}s`;
    });
});

// Profile dropdown functionality
document.addEventListener('DOMContentLoaded', function() {
    const profileDropdown = document.querySelector('.profile-dropdown');
    const profileBtn = document.querySelector('.profile-btn');
    
    // Toggle dropdown
    profileBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        profileDropdown.classList.toggle('active');
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function() {
        profileDropdown.classList.remove('active');
    });
    
    // Prevent dropdown from closing when clicking inside
    const dropdownContent = document.querySelector('.dropdown-content');
    dropdownContent.addEventListener('click', function(e) {
        e.stopPropagation();
    });
});