document.getElementById('sidebar-toggle').addEventListener('click', function() {
    const sidebar = document.querySelector('.sidebar');
    const toggleIcon = this.querySelector('i');

    sidebar.classList.toggle('collapsed');

    // Toggle icon class between "fa-circle-chevron-right" and "fa-circle-chevron-left"
    if (sidebar.classList.contains('collapsed')) {
        toggleIcon.classList.remove('fa-circle-chevron-left');
        toggleIcon.classList.add('fa-circle-chevron-right');
    } else {
        toggleIcon.classList.remove('fa-circle-chevron-right');
        toggleIcon.classList.add('fa-circle-chevron-left');
    }
});


// Get the current page URL and normalize it
const currentUrl = window.location.pathname.replace(/\/$/, "");

// Loop through all menu links
document.querySelectorAll('.menu-link').forEach(link => {
    // Normalize the href attribute
    const linkUrl = new URL(link.getAttribute('href'), window.location.origin).pathname.replace(/\/$/, "");

    // Check if the normalized href matches the current normalized URL
    if (linkUrl === currentUrl) {
        // If it matches, add the active class to the parent menu item
        link.closest('.menu-item').classList.add('active');
    } else {
        // Otherwise, remove the active class
        link.closest('.menu-item').classList.remove('active');
    }
});

// Add event listener for manual clicks to toggle active state
document.querySelectorAll('.menu-link').forEach(link => {
    link.addEventListener('click', function(e) {
        // Remove active class from all menu items
        document.querySelectorAll('.menu-item').forEach(item => {
            item.classList.remove('active');
        });

        // Add active class to the clicked menu item
        this.closest('.menu-item').classList.add('active');
    });
});


          document.addEventListener('DOMContentLoaded', function() {
            const dropdownToggle = document.querySelector('.dropdown-toggle');
            const dropdownContent = document.querySelector('.dropdown-content-dash');
            const dropdownIcon = document.querySelector('.dropdown-icon');

            dropdownToggle.addEventListener('click', function(e) {
                e.preventDefault();
                dropdownContent.classList.toggle('show');
                dropdownIcon.classList.toggle('fa-chevron-down');
                dropdownIcon.classList.toggle('fa-chevron-up');
            });
        });