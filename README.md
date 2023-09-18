# upload-big-file-laravel
**Рішення для завантаження великих файлів.**

**JavaScript** з боку клієнта і код **php** з боку сервера,
рішення дозволяє завантажити "на сервер" великий файл.
Враховуючи ситуацію низької якості інтернету на стороні клієнта.

Використано бібліотеку **resumable.js** на стороні клієнта та **Laravel** фреймворк на стороні сервера

Сервіс для завантаження файлів - [app/Services/ChunkUploadService.php](https://github.com/Polishchyk/upload-big-file-laravel/blob/113d6828a2f3bfb8a84f733cd98d07e5e60b985d/app/Services/ChunkUploadService.php)

Контролер - [app/Http/Controllers/UploadController.php](https://github.com/Polishchyk/upload-big-file-laravel/blob/113d6828a2f3bfb8a84f733cd98d07e5e60b985d/app/Http/Controllers/UploadController.php)

JavaScript - [public/js/custom.js](https://github.com/Polishchyk/upload-big-file-laravel/blob/113d6828a2f3bfb8a84f733cd98d07e5e60b985d/public/js/custom.js)

Використав js бібліотеку - [resumable.js](https://github.com/Polishchyk/upload-big-file-laravel/blob/113d6828a2f3bfb8a84f733cd98d07e5e60b985d/public/js/resumable.min.js)

---
**A solution for uploading large files.**

**JavaScript** on the client side and **php** code on the server side,
the solution allows you to upload a large file "to the server".
Considering the situation of low quality of the Internet on the client's side.

Service for upload big files - [app/Services/ChunkUploadService.php](https://github.com/Polishchyk/upload-big-file-laravel/blob/113d6828a2f3bfb8a84f733cd98d07e5e60b985d/app/Services/ChunkUploadService.php)

Controller - [app/Http/Controllers/UploadController.php](https://github.com/Polishchyk/upload-big-file-laravel/blob/113d6828a2f3bfb8a84f733cd98d07e5e60b985d/app/Http/Controllers/UploadController.php)

JavaScript - [public/js/custom.js](https://github.com/Polishchyk/upload-big-file-laravel/blob/113d6828a2f3bfb8a84f733cd98d07e5e60b985d/public/js/custom.js)

Used js libryary - [resumable.js](https://github.com/Polishchyk/upload-big-file-laravel/blob/113d6828a2f3bfb8a84f733cd98d07e5e60b985d/public/js/resumable.min.js)

The **resumable.js** library is used on the client side and the **Laravel framework** on the server side
