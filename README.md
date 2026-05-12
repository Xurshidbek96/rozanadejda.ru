<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 2000 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## API Docs

Quyidagi fayllar **bir-biriga mos** saqlanadi: `openapi.yaml` (manba), Postman, TZ va README.

| Fayl | Vazifa |
|------|--------|
| [openapi.yaml](openapi.yaml) | OpenAPI 3 — barcha path, sxema, `bearerAuth` |
| [docs/TZ-rozanadejda-api.md](docs/TZ-rozanadejda-api.md) | To‘liq texnik topshiriq (TZ), watermark va media batafsil |
| [postman/rozanadejda-api.postman_collection.json](postman/rozanadejda-api.postman_collection.json) | Import qilinadigan kolleksiya |
| [postman/rozanadejda-local.postman_environment.json](postman/rozanadejda-local.postman_environment.json) | `base_url`, `token`, id lar |

### Loyihani ishga tushirish (qisqa)

1. `composer install` — `scripts/ensure-app-key.php` orqali `.env` va bo‘sh `APP_KEY` avtomatik tuzatiladi (batafsil pastda).
2. `.env` da `APP_URL`, ma’lumotlar bazasi va `DB_SOCKET` (MAMP) ni tekshiring.
3. `php artisan migrate` (kerak bo‘lsa).
4. `php artisan serve` yoki MAMP orqali `public` ni oching — ilova ildizi `APP_URL` bilan mos bo‘lsin.

### Swagger UI — qaysi URL?

Swagger **web** marshruti (`/api` emas):

| Resurs | Manzil |
|--------|--------|
| **Swagger UI** | `{APP_URL}/docs` — masalan `http://127.0.0.1:8000/docs` yoki `http://localhost:8888/docs` |
| **OpenAPI YAML** | `{APP_URL}/docs/openapi.yaml` |

`.env` dagi `APP_URL` qaysi host/port bo‘lsa, `/docs` shu domen ostida ochiladi. Postman `base_url` odatda `.../api` bo‘ladi — Swagger uchun alohida brauzer URL kerak (yuqoridagi `APP_URL`).

### Swagger/OpenAPI (tashqi editor)

1. [openapi.yaml](openapi.yaml) ni [Swagger Editor](https://editor.swagger.io/) ga yuklash mumkin.
2. `servers` dagi `url` ni o‘z API bazangizga moslang (masalan `http://127.0.0.1:8000/api`).

### Postman import

1. Postman: **Import** → `postman/rozanadejda-api.postman_collection.json`
2. Keyin `postman/rozanadejda-local.postman_environment.json`
3. Environment tanlang: `base_url` — API (`http://127.0.0.1:8000/api` kabi); `web_base_url` — Swagger uchun ilova ildizi (`http://127.0.0.1:8000`, `/api` siz); `token` (Login dan keyin)

### MissingAppKeyException («No application encryption key»)

1. Loyiha ildizida `.env` bor-yo‘qligi; yo‘q bo‘lsa: `cp .env.example .env`
2. `php artisan key:generate`
3. `php artisan config:clear`

`composer install` dan keyin `post-install-cmd` → `scripts/ensure-app-key.php` ishlaydi.

### `Class "Route" not found` (welcome sahifa)

`config/app.php` da Laravel standart facade aliaslari `Facade::defaultAliases()` bilan qayta ulandi. Agar xato qaytsa: `php artisan config:clear`.

### Marshrutlar yangilanganda

Agar yangi API marshrutlari `route:list` da ko‘rinmasa: `php artisan route:clear` (yoki `route:cache` ni qayta yig‘ish).

### Token olish oqimi

1. `Auth > Login` yoki `Auth > Register` request yuboring.
2. `Login` requestidagi test script `token` o‘zgaruvchisiga yozadi.
3. Admin so‘rovlar: `GET/POST/PUT/PATCH/DELETE .../admin/*` (jumladan **watermark** — `openapi.yaml` va TZ bo‘lim 5).

### Mahsulot media tartibi (Front / User UI)

`openapi.yaml` **v1.2.0**: mahsulot `images` elementlarida `url`, `media_type` (`image`, `gif`, `video`), `sort_order` — admin `files` yuborgan **ketma-ketlik** bilan mos. Front massivni tartib bo‘yicha chiqaradi: rasm/gif uchun `<img>`, video uchun `<video controls>`. Batafsil: [docs/TZ-rozanadejda-api.md](docs/TZ-rozanadejda-api.md) → §4.
