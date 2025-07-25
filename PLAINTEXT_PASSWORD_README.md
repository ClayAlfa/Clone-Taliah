# ⚠️ IMPLEMENTASI PLAIN TEXT PASSWORD

## PERINGATAN KEAMANAN
**SANGAT PENTING:** Implementasi ini menggunakan **plain text password** yang **SANGAT TIDAK AMAN** dan hanya boleh digunakan untuk:
- Keperluan development/testing
- Demo aplikasi
- **JANGAN PERNAH** digunakan di production!

## Perubahan yang Dilakukan

### 1. File yang Dimodifikasi
- `login.php` - Mengubah verifikasi password dari `password_verify()` ke `===` comparison
- `register.php` - Menghapus `password_hash()`, menyimpan password langsung
- `admin/user.php` - Mengubah create/update user untuk plain text password

### 2. Script Konversi
- `convert_passwords_to_plaintext.php` - Script untuk mengubah password yang sudah ter-hash menjadi plain text

## Cara Menggunakan

### 1. Konversi Password Existing
Buka browser dan akses:
```
http://localhost/clone-taliah/convert_passwords_to_plaintext.php
```
Klik tombol "🔓 Konversi ke Plain Text"

### 2. Akun Demo Setelah Konversi
```
• Admin: admin@shoebrand.com / admin123
• Customer: customer@example.com / customer123
• User lainnya: [email] / password123
```

### 3. Login Normal
- Registrasi user baru akan menyimpan password dalam plain text
- Login akan menggunakan string comparison biasa

## Kode yang Diubah

### login.php
```php
// SEBELUM (Aman):
if ($user && password_verify($password, $user['password'])) {

// SESUDAH (TIDAK AMAN):
if ($user && $password === $user['password']) {
```

### register.php
```php
// SEBELUM (Aman):
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$stmt->execute([$name, $email, $hashed_password]);

// SESUDAH (TIDAK AMAN):
$stmt->execute([$name, $email, $password]);
```

### admin/user.php
```php
// SEBELUM (Aman):
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// SESUDAH (TIDAK AMAN):
// Langsung gunakan $password tanpa hashing
```

## Risiko Keamanan

### 1. Password Terlihat di Database
- Semua password dapat dibaca langsung dari database
- Admin database dapat melihat password semua user

### 2. Tidak Ada Proteksi
- Jika database bocor, semua password terbaca
- Tidak ada enkripsi atau hashing

### 3. Log Files
- Password mungkin terekam di log files dalam plain text

## Cara Kembali ke Secure Password

Untuk mengembalikan ke sistem yang aman:

### 1. Ubah kode login.php
```php
if ($user && password_verify($password, $user['password'])) {
```

### 2. Ubah kode register.php
```php
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$stmt->execute([$name, $email, $hashed_password]);
```

### 3. Ubah kode admin/user.php
```php
$hashed_password = password_hash($password, PASSWORD_DEFAULT);
```

### 4. Hash ulang semua password di database
```sql
-- Script untuk hash ulang password (jalankan manual)
UPDATE users SET password = '$2y$10$hash_result_here' WHERE id = user_id;
```

## Kesimpulan

Implementasi plain text password ini **HANYA UNTUK DEVELOPMENT/DEMO**. 
Untuk aplikasi production, **WAJIB** menggunakan password hashing yang proper seperti `password_hash()` dan `password_verify()` di PHP.

---
*Dibuat pada: Januari 2025*
*Status: ⚠️ DEVELOPMENT ONLY - NOT FOR PRODUCTION*
