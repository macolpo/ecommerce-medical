<?php require 'layout/top.php'; ?>
<?php 
    if(!isset($_SESSION['transactionId'])){
        header('Location: /403');
    }
    unset($_SESSION['transactionId']);
?>
<section class="d-flex justify-content-center align-items-center" style="min-height: 100vh;">
<div class="container py-5 text-center">
    <div class="mb-5">
        <h1 class="display-2 fw-bold text-success">PAYMENT SUCCESSFULLY!</h1>
    </div>
    <div>
        <a href="/" class="btn btn-success px-5 rounded-5">GO HOME</a>
    </div>
</div>

</section>

</main>


<?php require('layout/bottom.php') ?>
