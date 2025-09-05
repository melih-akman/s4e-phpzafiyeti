# PHP Web Güvenlik Zafiyetleri Demo Projesi

Bu proje, web uygulamalarında karşılaşılabilecek yaygın güvenlik zafiyetlerini ve bunlara karşı alınabilecek önlemleri göstermek amacıyla hazırlanmış bir demo web uygulamasıdır. Eğitim amaçlı olarak tasarlanmıştır ve çeşitli güvenlik açıklarını içerir.

## Projenin Amacı

Bu proje, aşağıdaki web güvenlik zafiyetlerini ve önlemlerini göstermek için tasarlanmıştır:

1. SQL Injection (SQL Enjeksiyonu)
2. Cross-Site Scripting (XSS)
3. Cross-Origin Resource Sharing (CORS) Zafiyetleri
4. Cookies ve Güvenli Cookie Ayarları
5. Kimlik Doğrulama ve Yetkilendirme Sorunları

## Kurulum

1. Bu projeyi web sunucunuza kopyalayın (Apache/Nginx/PHP destekli bir sunucu).
2. Proje kök dizininde `setup.php` dosyasını çalıştırarak veritabanını oluşturun.
3. Web tarayıcınızda `index.php` dosyasını açarak uygulamayı başlatın.

### Gereksinimler

- PHP 7.0 veya üstü
- SQLite veritabanı (MySQL alternatif olarak kullanılabilir)
- Web sunucusu (Apache/Nginx)

## Proje Yapısı ve İşleyişi

### Ana Dosyalar

- `index.php`: Ana sayfa. Siteye genel bir giriş ve kullanıcı profiline erişim sağlar.
- `login.php`: Kullanıcı giriş sayfası. SQL Injection zafiyeti burada gösterilmiştir.
- `profile.php`: Kullanıcı profil sayfası. CORS ve API güvenlik konularını gösterir.
- `search.php`: Arama işlevi. XSS zafiyetlerini göstermek için kullanılır.
- `cookies.php`: Farklı cookie güvenlik ayarlarını gösteren sayfa.
- `setup.php`: Veritabanı kurulum dosyası.

### Klasör Yapısı

- `/api`: API endpoint'leri (`user_data.php`: Kullanıcı verilerine erişim sağlar)
- `/css`: CSS dosyaları
- `/database`: Veritabanı bağlantı ve kurulum dosyaları
- `/includes`: Ortak header ve footer dosyaları
- `/js`: JavaScript dosyaları (comments.js: XSS açığı gösterimi)
- `/other-domain`: CORS saldırılarını simüle eden HTML sayfası

## Güvenlik Açıkları ve Çözümleri

### 1. SQL Injection (login.php)

**Açık:**
```php
$query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
```

**Çözüm:**
```php
$query = "SELECT * FROM users WHERE username = ? AND password = ?";
$stmt = $pdo->prepare($query);
$stmt->execute([$username, $password]);
```

### 2. Cross-Site Scripting (XSS) (search.php ve comments.js)

**Açık:**
```php
<h5 class="card-title">Results for: <?php echo $search; ?></h5>
```

**Çözüm:**
```php
<h5 class="card-title">Results for: <?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?></h5>
```

### 3. CORS Güvenlik Açığı (api/user_data.php)

**Açık:**
```php
header("Access-Control-Allow-Origin: *");
```

**Çözüm:**
```php
header("Access-Control-Allow-Origin: https://localhost:8000");
header("Access-Control-Allow-Methods: GET");
```

### 4. Güvensiz Cookie Kullanımı (cookies.php)

**Açık:**
```php
setcookie("basit_cookie", "Bu bir basit cookie", time() + 3600);
```

**Çözüm:**
```php
setcookie("guvenli_cookie", "Bu tam güvenli bir cookie", [
    'expires' => time() + 3600,
    'path' => '/',
    'domain' => '',
    'secure' => true,
    'httponly' => true,
    'samesite' => 'Strict'
]);
```

### 5. API Güvenlik Sorunları (api/user_data.php)

**Açık:**
```php
$userId = isset($_GET['user_id']) ? (int) $_GET['user_id'] : 0;
```

**Çözüm:**
```php
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Unauthorized access']);
    exit;
}
$userId = $_SESSION['user_id']; // Sadece kendi verilerine erişim sağlanır
```

## Demo Hesapları

Aşağıdaki hesapları kullanarak sisteme giriş yapabilirsiniz:

- **Admin:** Kullanıcı adı: `admin` / Şifre: `admin123`
- **Normal Kullanıcı 1:** Kullanıcı adı: `john` / Şifre: `password123`
- **Normal Kullanıcı 2:** Kullanıcı adı: `jane` / Şifre: `jane123`

## Notlar

- Bu proje sadece eğitim amaçlıdır ve gerçek bir üretim ortamında kullanılmamalıdır.
- Güvenlik açıklarını göstermek için kasten zafiyetler eklenmiştir.
- Her zafiyet için güvenli çözüm örnekleri de kodda yorum satırları içinde verilmiştir.


