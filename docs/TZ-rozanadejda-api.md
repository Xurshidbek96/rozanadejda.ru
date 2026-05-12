# Texnik topshiriq (TZ) — Rozanadejda.ru backend API

Hujjat loyihaning REST API, admin oqimi, mahsulot media va **dinamik watermark** modulini qamrab oladi. Swagger UI manzili va OpenAPI manbasi alohida ko‘rsatilgan.

---

## 1. Maqsad va qamrov

- **Maqsad:** do‘kon (info) va admin panel uchun JSON API; fayl yuklash (mahsulot rasmlari, slaydlar); buyurtmalar boshqaruvi.
- **Texnologiya:** Laravel 10, PHP, MySQL, Laravel Sanctum (Bearer token), Intervention Image.
- **Global prefix:** barcha API marshrutlari `RouteServiceProvider` orqali `/api` ostida.

---

## 2. Autentifikatsiya

| Mexanizm | Tavsif |
|----------|--------|
| Ro‘yxatdan o‘tish / kirish | `POST /api/auth/register`, `POST /api/auth/login` — javobda `token` (Sanctum `plainTextToken`). |
| Himoyalangan zonalar | `Authorization: Bearer {token}` sarlavhasi. |
| Foydalanuvi | `GET /api/user` — `auth:sanctum`. |

Admin guruh (`/api/admin/*` asosiy CRUD, buyurtmalar, **watermark**) — `auth:sanctum` ostida.

---

## 3. API modullari (qisqa)

| Modul | Prefix | Eslatma |
|-------|--------|---------|
| Auth | `/api/auth/*` | Auto-route; amalda POST ishlatiladi. |
| Admin CRUD | `/api/admin/categories`, `products`, `slides` | REST + maxsus `productUpdate`, `slideUpdate`, `searchProduct`. |
| Admin orders | `/api/admin/order/*` | Ro‘yxat, ko‘rish, status yangilash. |
| Public info | `/api/info/*` | Do‘kon: kategoriyalar, mahsulotlar, qidiruv, buyurtma yaratish. |

Batafsil kontrakt: repodagi `openapi.yaml`.

---

## 4. Mahsulot media (rasmlar, GIF, video)

- **Maydon:** `files` — ixtiyoriy massiv (yoki bitta fayl).
- **Ruxsat etilgan turlar:** `jpeg, jpg, png, gif, webp, mp4, webm, mov, avi` (hajm cheklovi FormRequestda).
- **Suv belgisi:** faqat statik rasm formatlarida (`jpeg`, `png`, `webp`) qo‘llanadi; **GIF** va **video** o‘zgartirilmasdan saqlanadi.
- **Suv belgisi manbasi:** quyidagi bo‘lim 5 bo‘yicha dinamik fayl.

---

## 5. Dinamik watermark (alohida modul)

Bu bo‘lim **yangi qo‘shilgan** watermark funksionalligini to‘liq qamrab oladi.

### 5.1 Maqsad

- Oldingi holatda suv belgisi doim `public/images/products/water.png` dan olingan.
- **Yangi talab:** admin API orqali yangi watermark yuklansa, keyingi mahsulot **JPEG/PNG/WebP** yuklashlarida aynan shu rasm suv belgisi sifatida ishlatiladi.

### 5.2 Fayl joylashuvi va ustuvorlik

| Holat | Fayl yo‘li (public ichida) | Izoh |
|-------|---------------------------|------|
| Custom (API orqali) | `images/watermark/current.png` | Mavjud bo‘lsa, **faqat shu** ishlatiladi. |
| Legacy (zaxira) | `images/products/water.png` | Custom yo‘q bo‘lsa, agar legacy fayl bo‘lsa, u ishlatiladi. |
| Hech biri yo‘q | — | Mahsulot rasmi suv belgisiz saqlanadi (xatolik emas). |

Yuklangan fayl serverda **PNG** ko‘rinishida `current.png` nomiga yoziladi (Intervention orqali — keyingi `insert` uchun bir xil format). **Yangi yuklash yoki tahrirlash (PUT)** dan oldin avvalgi `current.png` diskdan o‘chiriladi — fayllar yig‘ilmaydi.

### 5.3 REST API (alohida endpointlar)

Barcha so‘rovlar: `Authorization: Bearer {token}`.

| Metod | URL | Tavsif |
|-------|-----|--------|
| `GET` | `/api/admin/watermark` | Hozirgi effektiv watermark haqida ma’lumot: `configured`, `url`, `is_custom`, `updated_at` yoki `message`. |
| `POST` | `/api/admin/watermark` | `multipart/form-data`, maydon: **`image`**. Avvalgi custom fayl o‘chiriladi, keyin yangi `current.png` yoziladi. |
| `PUT` / `PATCH` | `/api/admin/watermark` | POST bilan bir xil — almashtirish (eski fayl diskdan olib tashlanadi). |
| `DELETE` | `/api/admin/watermark` | Faqat `public/images/watermark/current.png` ni o‘chiradi; **legacy** `images/products/water.png` tegilmaydi. |

**Muhim:** watermark yangilangandan keyin allaqachon yuklangan mahsulot fayllari **qayta ishlanmaydi** — faqat keyingi yuklamalar yangi suv belgisidan foydalanadi.

### 5.4 Biznes qoidalari

- Watermark faqat **rasm** (jpeg/png/webp) mahsulot yuklamalariga qo‘llanadi.
- GIF animatsiyasi va video **suv belgisiz** qoladi (texnik sabab: Intervention GIF/video ustida ishlamaydi).
- Suv belgisi o‘lchami: asosiy rasmning eni/balandligining taxminan **30%** gacha proporsional kichraytiriladi, **past-o‘ng** burchakda joylashadi (offset 15 px).

### 5.5 Xavfsizlik

- Watermark API faqat `auth:sanctum` ostida.
- Faqat rasm MIME tekshiruvi; hajm chegarasi.

### 5.6 Texnik implementatsiya (kod nuqtai nazaridan)

- `App\Http\Services\WatermarkService` — fayl yo‘lini aniqlash, custom saqlash.
- `App\Http\Controllers\Admin\WatermarkController` — `show`, `store`, `update`, `destroy`.
- `App\Http\Services\ProductAdminService` — `storeUploadedMedia` ichida `WatermarkService::resolveWatermarkAbsolutePath()` chaqiruvi.

---

## 6. Swagger / OpenAPI va to‘liq hujjat URL

| Resurs | URL (qoida) | Tavsif |
|--------|-------------|--------|
| **Swagger UI (to‘liq interaktiv hujjat)** | `{APP_URL}/docs` | Brauzerda ochiladi. `APP_URL` — `.env` dagi ilova asosiy manzili (**`/api` qo‘shilmaydi**). |
| **OpenAPI YAML (spec)** | `{APP_URL}/docs/openapi.yaml` | Swagger UI shu manbadan yuklaydi. Fayl diskda: repoda `openapi.yaml` (ildiz). |
| API bazaviy URL (Try it out) | `{APP_URL}/api` | `openapi.yaml` → `servers` bo‘limida ko‘rsatilgan; sinov so‘rovlari shu host ostida ketadi. |

**Misol (MAMP, lokal):**

- Agar sayt `http://localhost:8888` da ochilsa:
  - Swagger: `http://localhost:8888/docs`
  - Spec: `http://localhost:8888/docs/openapi.yaml`
  - API: `http://localhost:8888/api/...`

**Eslatma:** productionda `APP_URL` to‘g‘ri HTTPS manzil bo‘lishi kerak; Swagger ham shu domen ostida ishlaydi.

---

## 7. Postman

- Kolleksiya: `postman/rozanadejda-api.postman_collection.json`
- Muhit: `postman/rozanadejda-local.postman_environment.json`
- `Admin Watermark` papkasi: `GET`, `POST`, `PUT`, `DELETE` ... `/admin/watermark`

---

## 8. Migratsiya va deploy eslatmalari

- Watermark uchun alohida DB jadvali **talab qilinmaydi** (fayl tizimi).
- `public/images/watermark/` katalogi repoda `.gitkeep` bilan saqlanadi.
- Katta fayllar uchun PHP `upload_max_filesize` / `post_max_size` sozlamalari serverda mos bo‘lishi kerak.

---

## 9. Versiya

- Hujjat va watermark API: loyiha reposi bilan birga yangilanadi; aniq versiya `openapi.yaml` → `info.version` maydonida.
