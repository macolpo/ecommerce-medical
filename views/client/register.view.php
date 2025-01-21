<?php require 'layout/top.php'; ?>
<?php 
    if(isset($_SESSION['user_data'])){
        header('Location: /403');
    }
?>
<?php require 'layout/navbar.php'; ?>

<section class="py-5">
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-6">

            <div class="card mb-3 rounded-4">

                <div class="card-body">
                    <div class="pt-4 pb-2 text-center">
                        <h5 class="card-title  pb-0 fs-4">Register</h5>
                        <p class="small">Create an account by entering your email & password</p>
                        </div>

                    <form id="registerForm" class="row g-3 needs-validation" novalidate>

                    <div class="col-6">
                        <div class="form-floating">
                            <input type="firstname" class="form-control form-control-sm" id="firstname" name="firstname" placeholder="First Name">
                            <label for="firstname">First name</label>
                        </div>
                    </div>

                    <div class="col-6">
                        <div class="form-floating">
                            <input type="lastname" class="form-control form-control-sm" id="lastname" name="lastname" placeholder="Last Name">
                            <label for="lastname">Last Name</label>
                        </div>
                    </div>


                    <div class="col-6">
                        <div class="form-floating">
                            <input type="address" class="form-control form-control-sm" id="address" name="address" placeholder="Address">
                            <label for="address">Address</label>
                        </div>
                    </div>


                    <div class="col-6">
                        <div class="form-floating">
                            <input type="phonenumber" class="form-control form-control-sm" id="phonenumber" name="phonenumber" placeholder="Phone Number">
                            <label for="phonenumber">Phone Number</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating">
                            <input type="email" class="form-control form-control-sm" id="email" name="email" placeholder="Email">
                            <label for="email">Email</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="form-floating">
                            <input type="password" class="form-control form-control-sm" id="password" name="password" placeholder="Password">
                            <label for="password">Password</label>
                        </div>
                    </div>

                    <div class="col-12">
                        <button class="btn btn-primary w-100" type="submit">Register</button>
                    </div>
                    <div class="col-12">
                        <p class="small mb-0">Already have an account? <a href="/login">Login here</a></p>
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

        $('#registerForm').submit(function (e) { 
            e.preventDefault();

            var formData = new FormData(this);

            $.ajax({
                method: "POST",
                url: "backend/auth/login-server.php?action=authRegister",
                data: formData,
                dataType: "json",
                processData: false,
                contentType: false,
                success: function (response) {
                    console.log(response)
                    if (response.status === true) {
                        swal({
                            title: "Success!",
                            text: response.message,
                            icon: "success",
                            button: "Let's go!",
                        })
                        .then(function() {
                            window.location.href = '/login';
                        });
                    }
                   
                    else{
                        swal({
                            title: "Warning!",
                            text: response.message,
                            icon: "warning",
                            button: "Aww yiss!",
                        })
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
