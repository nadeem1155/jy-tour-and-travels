/**
 * Admin Panel JavaScript
 */
document.addEventListener('DOMContentLoaded', function () {
    // 1. Mobile Sidebar Toggle
    const sidebarToggle = document.getElementById('sidebarToggle');
    const adminSidebar = document.getElementById('adminSidebar');

    if (sidebarToggle && adminSidebar) {
        sidebarToggle.addEventListener('click', function () {
            adminSidebar.classList.toggle('show');
        });
    }

    // 2. Generic Confirmation on Delete actions
    document.querySelectorAll('.confirm-delete').forEach(btn => {
        btn.addEventListener('click', function (e) {
            const item = this.getAttribute('data-item') || 'this record';
            if (!confirm(`Are you sure you want to permanently delete ${item}? This action cannot be reversed.`)) {
                e.preventDefault();
            }
        });
    });

    // 3. Auto-hide alerts after 5 seconds
    document.querySelectorAll('.alert-dismissible').forEach(alert => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            if (bsAlert) {
                bsAlert.close();
            }
        }, 6000);
    });
});
