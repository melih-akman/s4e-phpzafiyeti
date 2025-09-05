<?php
include_once './includes/header.php';

$search = isset($_GET['q']) ? $_GET['q'] : '';

/* 
// GÜVENLİ VERSİYON: Content Security Policy (CSP) ile XSS saldırılarını önleme
header("Content-Security-Policy: default-src 'self'; style-src 'self' https://cdn.jsdelivr.net; script-src 'self' https://cdn.jsdelivr.net; img-src 'self' data:;");

// GÜVENLİ VERSİYON: XSS saldırılarını önlemek için htmlspecialchars kullanılır
$search = isset($_GET['q']) ? htmlspecialchars($_GET['q'], ENT_QUOTES, 'UTF-8') : '';
*/
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <h1>Search Our Site</h1>
            
            <form method="GET" action="search.php" class="mb-4">
                <div class="input-group">
                    <input type="text" class="form-control" name="q" placeholder="Search for something..." value="<?php echo $search; ?>">
                    <button class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>
            
            <?php if (!empty($search)): ?>
                <div class="card">
                    <div class="card-header">
                        Search Results
                    </div>
                    <div class="card-body">
                        
                        
                        <h5 class="card-title">Results for: <?php echo htmlspecialchars($search, ENT_QUOTES, 'UTF-8'); ?></h5>
                        
                        
                        <p>No results found for your search.</p>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
include_once './includes/footer.php';
?>
