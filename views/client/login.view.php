<?php require 'layout/top.php'; ?>
<?php 
    // if(isset($_SESSION['user_data'])){
    //     header('Location: /');
    // }
?>
<?php require 'layout/navbar.php'; ?>

<section class="py-5">
<div class="container py-5">
    <div class="row justify-content-center">
    <div class="col-lg-5 col-md-6">

        <div class="card mb-3 rounded-4">

            <div class="card-body">
                <div class="pt-4 pb-2 text-center">
                    <h5 class="card-title  pb-0 fs-4">Login to Your Account</h5>
                    <p class=" small">Enter your username & password to login</p>
                </div>

                <form id="loginForm" class="row g-3 needs-validation" novalidate>

                <div class="col-12">
                    <div class="form-floating">
                        <input type="email" class="form-control" id="email" name="email" placeholder="Email">
                        <label for="email">Email</label>
                    </div>
                </div>

                <div class="col-12">
                    <div class="form-floating">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                        <label for="password">Password</label>
                    </div>
                </div>

                <div class="col-12">
                    <button class="btn btn-primary w-100" type="submit">Login</button>
                </div>
                <div class="col-12">
                    <p class="small mb-0">Don't have account? <a href="/register">Create an account</a></p>
                </div>
                </form>

            </div>
        </div>
        
    </div>
    </div>
</div>

</section>

</main><!-- End #main -->



<script>
    $(document).ready(function () {

        $('#loginForm').submit(function (e) { 
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                method: "POST",
                url: "backend/auth/login-server.php?action=authLogin",
                data: formData,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function (response) {
                    console.log(response);
                    if (response.user_type === 'user') {
                        swal({
                            title: "Account Verified!",
                            text: "Login Successfully!",
                            icon: "success",
                            button: "Let's go!",
                        })
                        .then(function() {
                            window.location.href = '/';
                        });
                    }
                    else if (response.user_type === 'admin') {
                        swal({
                            title: "Account Verified!",
                            text: "Login Successfully!",
                            icon: "success",
                            button: "Let's go!",
                        })
                        .then(function() {
                            window.location.href = '/dashboard';
                        });
                    }
                   
                    else{
                        swal({
                            title: "Warning!",
                            text: response.message,
                            icon: "warning",
                            button: "Aww yiss!",
                        })
                        $('.text-danger').removeClass('d-none');
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });

            
        });
    });


</script>

<?php require('layout/bottom.php') ?>
