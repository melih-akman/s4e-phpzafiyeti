<?php
// Oturum başlatılmamışsa başlat
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

include_once './includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header">
                    User Profile
                </div>
                <div class="card-body">
                    <div id="profile-data">
                        Loading profile data...
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const profileData = document.getElementById('profile-data');
    
    fetch('/api/user_data.php?user_id=<?php echo $userId; ?>')
        .then(response => response.json())
        .then(data => {
            if (data.error) {
                profileData.innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
            } else {
                profileData.innerHTML = `
                    <h3>${data.username}</h3>
                    <p><strong>Email:</strong> ${data.email}</p>
                    <p><strong>Account Balance:</strong> $${data.balance}</p>
                    <div class="alert alert-info mt-3">
                        <strong>Note:</strong> For security reasons, some sensitive information is hidden.
                    </div>
                `;
            }
        })
        .catch(error => {
            profileData.innerHTML = `<div class="alert alert-danger">Error loading profile data</div>`;
            console.error('Error:', error);
        });
});
</script>

<?php
include_once './includes/footer.php';
?>
