# Project Guidelines & Rules

## UI & Dialog Standards
- **Standard Modal & Notification**: Seluruh konfirmasi hapus data, peringatan, dan alert di aplikasi web (semua Blade views) **WAJIB** menggunakan SweetAlert2.
- **Dilarang**: Menggunakan `confirm(...)` atau `alert(...)` bawaan browser.
- **Form Delete Standard**: Gunakan `class="confirm-delete"` dan `data-confirm="Pesan konfirmasi..."` pada elemen `<form>` agar ditangani oleh listener SweetAlert2 global.
