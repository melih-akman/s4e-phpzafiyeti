<?php
// Oturum başlatılmamışsa başlat
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// 1. Temel cookie - Hiçbir güvenlik flag'i olmayan
setcookie("basit_cookie", "Bu bir basit cookie", time() + 3600);

// 2. HttpOnly flag'i olan cookie - JavaScript ile erişilemez
setcookie("httponly_cookie", "Bu bir HttpOnly cookie", time() + 3600, "/", "", false, true);

// 3. Tüm güvenlik flag'leri olan cookie (HttpOnly + Secure + SameSite)
setcookie("cookie", "Bu güvenli bir cookie", ['secure' => true,]);
//httpOnly
setcookie('httponly_cookie_secure', 'Bu bir HttpOnly cookie', ['httponly' => true]);
//Domain
setcookie('domain_cookie', 'Bu bir domain cookie', ['domain' => 'localhost:8000']);
//Path
setcookie('path_cookie', 'Bu bir path cookie', ['path' => '/']);
// Expires
setcookie('expires_cookie', 'Bu bir expires cookie', ['expires' => time() + 3600]);
//Max-Age
setcookie('maxage_cookie', 'Bu bir max-age cookie', ['max-age' => 3600]);
//SameSite
setcookie('samesite_cookie', 'Bu bir SameSite cookie', options: ['samesite' => 'Strict']);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookie Örnekleri</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        pre {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        .cookie-info {
            margin-top: 20px;
            padding: 15px;
            border-radius: 5px;
            background-color: #f8f9fa;
            border-left: 4px solid #007bff;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Cookie Örnekleri ve Özellikleri</h1>
        <p>Bu sayfada 3 farklı cookie oluşturulmuştur:</p>
        
        <div class="cookie-info">
            <h3>1. Basit Cookie</h3>
            <p>Bu cookie, herhangi bir güvenlik flag'i içermeyen temel bir cookie'dir.</p>
            <p><strong>Tanım:</strong> <code>setcookie("basit_cookie", "Bu bir basit cookie", time() + 3600);</code></p>
            <p><strong>Özellikleri:</strong></p>
            <ul>
                <li>JavaScript ile erişilebilir</li>
                <li>HTTP veya HTTPS üzerinden gönderilebilir</li>
                <li>Cross-site isteklerde gönderilir</li>
            </ul>
        </div>
        
        <div class="cookie-info">
            <h3>2. HttpOnly Cookie</h3>
            <p>Bu cookie, JavaScript ile erişilemeyen HttpOnly flag'i içeren bir cookie'dir.</p>
            <p><strong>Tanım:</strong> <code>setcookie("httponly_cookie", "Bu bir HttpOnly cookie", time() + 3600, "/", "", false, true);</code></p>
            <p><strong>Özellikleri:</strong></p>
            <ul>
                <li>JavaScript ile erişilemez (XSS saldırılarına karşı koruma)</li>
                <li>HTTP veya HTTPS üzerinden gönderilebilir</li>
                <li>Cross-site isteklerde gönderilir</li>
            </ul>
        </div>
        
        <div class="cookie-info">
            <h3>3. Tam Güvenli Cookie</h3>
            <p>Bu cookie, tüm güvenlik flag'lerini (HttpOnly, Secure, SameSite) içeren yüksek güvenlikli bir cookie'dir.</p>
            <p><strong>Tanım:</strong></p>
            <pre>setcookie(
    "guvenli_cookie", 
    "Bu tam güvenli bir cookie", 
    [
        'expires' => time() + 3600,
        'path' => '/',
        'domain' => '',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Strict'
    ]
);</pre>
            <p><strong>Özellikleri:</strong></p>
            <ul>
                <li>JavaScript ile erişilemez (HttpOnly)</li>
                <li>Sadece HTTPS üzerinden gönderilebilir (Secure)</li>
                <li>Cross-site isteklerde gönderilmez (SameSite=Strict)</li>
            </ul>
        </div>

        <h2 class="mt-4">Cookie'leri Göster</h2>
        <div class="row">
            <div class="col-md-6">
                <h4>PHP ile Erişilebilen Cookie'ler:</h4>
                <pre><?php print_r($_COOKIE); ?></pre>
            </div>
            <div class="col-md-6">
                <h4>JavaScript ile Erişilebilen Cookie'ler:</h4>
                <pre id="js-cookies">JavaScript çalıştırılıyor...</pre>
            </div>
        </div>

        <div class="mt-4">
            <h2>Tarayıcı Cookie'leri (Elle Kontrol)</h2>
            <p>Tarayıcı konsolunu açarak tüm cookie'leri görebilirsiniz:</p>
            <ol>
                <li>Tarayıcınızda F12 tuşuna basın veya sağ tıklayıp "İncele" seçeneğini seçin</li>
                <li>"Uygulamalar" veya "Storage" sekmesine tıklayın</li>
                <li>Sol menüden "Cookies" seçeneğini bulun</li>
                <li>Bu sayfanın adını (domain) seçin</li>
            </ol>
            <p>Not: HttpOnly cookie'ler JavaScript ile görüntülenemez ama tarayıcının geliştirici araçlarında görünür.</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const jsContainer = document.getElementById('js-cookies');
            
            // JavaScript ile cookie'leri okuma
            function getAllCookies() {
                const cookies = document.cookie.split('; ');
                let result = {};
                
                cookies.forEach(cookie => {
                    if (cookie) {
                        const parts = cookie.split('=');
                        result[parts[0]] = decodeURIComponent(parts[1] || '');
                    }
                });
                
                return result;
            }
            
            const jsCookies = getAllCookies();
            jsContainer.textContent = JSON.stringify(jsCookies, null, 2);
            
            // Not ekleme
            if (!jsCookies.httponly_cookie) {
                const note = document.createElement('div');
                note.className = 'alert alert-info mt-2';
                note.textContent = 'HttpOnly cookie JavaScript ile görüntülenemez! (Güvenlik özelliği çalışıyor)';
                jsContainer.parentNode.appendChild(note);
            }
        });
    </script>
</body>
</html>
