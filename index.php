<?php
// Include common header
include_once './includes/header.php';
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-8">
            <h1>Welcome to Our Website</h1>
            <p>This is a demo website that demonstrates various web security concepts.</p>
            
            
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    User Profile
                </div>
                <div class="card-body">
                    <div id="user-profile">
                        <p>Please log in to view your profile data.</p>
                        <a href="login.php" class="btn btn-success">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="./js/comments.js"></script>

<?php
// Include common footer
include_once './includes/footer.php';
?>
