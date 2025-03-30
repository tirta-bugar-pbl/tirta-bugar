function loadNotifications() {
    fetch('notifications.php')
        .then(response => response.json())
        .then(data => {
            const notificationList = document.getElementById('notification-list');
            const notificationBadge = document.querySelector('.notification-badge');
            
            // Clear existing notifications
            notificationList.innerHTML = '';
            
            if (!data.hasNotifications) {
                const li = document.createElement('li');
                li.textContent = 'Tidak ada notifikasi';
                notificationList.appendChild(li);
                notificationBadge.classList.add('hidden');
            } else {
                data.messages.forEach(message => {
                    const li = document.createElement('li');
                    li.textContent = message;
                    notificationList.appendChild(li);
                });
                notificationBadge.classList.remove('hidden');
                notificationBadge.textContent = data.count;
            }
        })
        .catch(error => console.error('Error:', error));
}

// Inisialisasi notifikasi saat halaman dimuat
document.addEventListener('DOMContentLoaded', () => {
    loadNotifications();

    // Toggle popup saat mengklik ikon notifikasi
    const notificationIcon = document.getElementById('notificationIcon');
    const notificationPopup = document.getElementById('notification-popup');
    const closePopup = document.getElementById('close-popup');

    notificationIcon.addEventListener('click', (e) => {
        e.stopPropagation();
        notificationPopup.classList.toggle('hidden');
    });

    // Tutup popup saat mengklik tombol close
    closePopup.addEventListener('click', () => {
        notificationPopup.classList.add('hidden');
    });

    // Tutup popup saat mengklik di luar
    document.addEventListener('click', (e) => {
        if (!notificationPopup.contains(e.target) && e.target !== notificationIcon) {
            notificationPopup.classList.add('hidden');
        }
    });
});