<?php $title = " - Change Password"; ?>
<?php require 'layout/top.php'; ?>
<?php require 'layout/navbar.php'; ?>

<section id="changepassword">
  <div class="container py-4">
    <div class="row">
        <!-- Change Password -->
        <div class="py-5">
            <div class="d-flex justify-content-center">
                <div class="row">
                    <div class="px-5 py-4 text-center">
                        <h1>CHANGE PASSWORD</h1>
                        <p>
                            <small>
                            To ensure the security of your account, please enter your new password below. Make sure it’s unique and contains a combination of letters, numbers, and symbols for added strength.
                            </small>
                        </p>
                    </div>
                    <!-- Form -->
                    <div class="d-flex row justify-content-center">
                        <div class="py-2 col-lg-6">
                            <form class="needs-validation" id="changePasswordForm" novalidate>
                                <div class="form-group col-lg-12 mb-3">
                                    <label for="currentpassword" class="form-label">CURRENT PASSWORD</label>
                                    <input type="password" class="form-control rounded-3" id="currentpassword" name="currentpassword" placeholder="Enter Current Password!" required>
                                </div>

                                <div class="form-group col-lg-12 mb-3">
                                    <label for="newpassword" class="form-label">NEW PASSWORD</label>
                                    <input type="password" class="form-control" id="newpassword" name="newpassword"  pattern="(?=.*\d)(?=.*[A-Z]).{8,}" placeholder="Enter New Password!" required>
                                    <div class="invalid-feedback">
                                        Password must be at least 8 characters long, contain at least one uppercase letter, and one number.
                                    </div>  
                                </div>

                                <div class="form-group col-lg-12 mb-3">
                                    <label for="confirmpassword" class="form-label">CONFIRM PASSWORD</label>
                                    <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Confirm Password!" required>
                                    <div class="invalid-feedback">
                                        Passwords must match.
                                    </div>
                                </div>

                                <div class="form-group col-lg-12 mb-3 text-center">
                                    <button type="submit" class="btn bg-success-subtle btn-sm px-4 py-1">UPDATE</button>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                </div>             
            </div>
        </div>
    </div>
  </div>
</section>


<script>
$(document).ready(function () {

    // formValidation();
    // passwordValidation();

    $('#changePasswordForm input').on('input', function () {
        formValidation();
        passwordValidation();
    });

    $('#changePasswordForm').submit(function (e) { 
        e.preventDefault();  

        formValidation();
        passwordValidation();

        var formData = new FormData(this);

        if (this.checkValidity()) {
            $.ajax({
                url: 'backend/user/profile-server.php?action=profilePassword',
                method: 'POST',
                data: formData,
                dataType: 'json',
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.status === "success") {
                        swal({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Password update successfully!',
                        })
                        $('#changePasswordForm')[0].reset();
                    } else {
                        swal({
                            icon: 'warning',
                            title: 'Opps!',
                            text: response.message,
                        })
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        } else {
            $(this).addClass('was-validated');
        }
        
    });

});

function formValidation() { 
    var isEmpty = false; 
    var form = $('#changePasswordForm');   

    form.find('input').each(function () {
        var inputField = $(this);  
        var fieldValue = inputField.val().trim(); 

        if (fieldValue === "") {
            inputField.addClass('is-invalid');  
            isEmpty = true;
        } else {
            inputField.removeClass('is-invalid');  
        }
    });
}

function passwordValidation(){
    var newpass = $('#newpassword').val();
    var confirmpass = $('#confirmpassword').val();

    if (newpass !== confirmpass) {
        // Show the "Passwords must match" message
        $('#confirmpassword').addClass('is-invalid');
        $('#confirmpassword').next('.invalid-feedback').show();
    } else {
        // Hide the "Passwords must match" message
        $('#confirmpassword').removeClass('is-invalid');
        $('#confirmpassword').next('.invalid-feedback').hide();
    }
}

</script>

<?php require 'layout/bottom.php'; ?>
