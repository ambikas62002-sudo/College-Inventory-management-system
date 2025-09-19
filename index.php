<?php 
require_once 'includes/header.php';
require_once 'bootstrap.php'; 
?>

<style>
    body {
        background: url('images/your-background.jpg') no-repeat center center fixed;
        background-size: cover;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .login-wrapper {
        margin-top: 100px;
        max-width: 400px;
        padding: 30px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 10px;
        box-shadow: 0px 0px 10px 2px rgba(0,0,0,0.2);
    }

    h1.text-center {
        color: #fff;
        margin-top: 50px;
        font-weight: bold;
        text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
    }

    label {
        font-weight: 500;
    }

    .btn-success {
        width: 100%;
    }
</style>

<h1 class="text-center">COLLEGE INVENTORY SYSTEM</h1>

<div class="container d-flex justify-content-center">
    <div class="login-wrapper mt-5">
        <h3 class="text-center mb-4">Login Panel</h3>

        <form action="<?php echo URLROOT; ?>/actions/__login.php" method="post">
            
            <div>
                <?php flash(); ?>
            </div>

            <div class="form-group">
                <label for="id">ID: <sup>*</sup></label>
                <input type="text" name="id" class="form-control" placeholder="Enter Your ID" required>
            </div>

            <div class="form-group">
                <label for="password">Password: <sup>*</sup></label>
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <input type="hidden" name="token" value="<?php echo Token::generate(); ?>">

            <div class="form-group mt-3">
                <input type="submit" name="submit" value="LOGIN" class="btn btn-success">
            </div>

        </form>
    </div>
</div>

<?php require_once './includes/footer.php'; ?>
