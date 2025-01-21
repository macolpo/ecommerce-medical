<?php require 'layout/top.php'; ?>
<?php require 'layout/navbar.php'; ?>


<section id="profileview">
  <div class="container py-4">
    <div class="row">
        <!-- profile -->
        <div>
            <h2 class="mb-3 fw-bolder">PROFILE</h2>
        </div>
        <!-- left -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body p-3 text-center">
                    <div class="p-5">
                        <img src="img/user/home-bg.png" alt="avatar" class="rounded-circle img-fluid w-100">
                    </div>
                    <h5 class="fw-bold" id="name"><?= $row['firstname'] ?> <?= $row['lastname'] ?></h5>
                </div>
            </div>
        </div>
        <!-- right -->
        <div class="col-lg-8">
            <div class="card">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <div class="col-6">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active w-100" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal-tab-pane" type="button" role="tab" aria-controls="personal-tab-pane" aria-selected="true">My Details</button>
                        </li>
                    </div>
                    <div class="col-6">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link w-100" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Settings</button>
                        </li>
                    </div>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- My Details -->
                    <div class="tab-pane fade show active" id="personal-tab-pane" role="tabpanel" aria-labelledby="personal-tab" tabindex="0">
                        <div class="my-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Full Name</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0"><?= $row['firstname'] ?> <?= $row['lastname'] ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Email</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0"><?= $row['email'] ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Phone</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0"><?= $row['phone_number'] ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Address</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0"><?= $row['address'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- settings -->
                    <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                        <form id="profileForm">
                            <!-- Form Body -->
                            <div class=" p-4">
                                <div class="row">
                                    <div class="form-group col-md-6 mb-3">
                                        <label for="firstName" class="form-label">First Name</label>
                                        <input type="text" id="firstName" class="form-control form-control-sm" placeholder="Enter your first name" value="<?= htmlspecialchars($row['firstname']) ?>">
                                    </div>
                                    
                                    <div class="form-group col-md-6 mb-3">
                                        <label for="lastName" class="form-label">Last Name</label>
                                        <input type="text" id="lastName" class="form-control form-control-sm" placeholder="Enter your last name" value="<?= htmlspecialchars($row['lastname']) ?>">
                                    </div>
                                    
                                    <div class="form-group col-md-6 mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" id="address" class="form-control form-control-sm" placeholder="Enter your address" value="<?= htmlspecialchars($row['address']) ?>">
                                    </div>

                                    <div class="form-group col-md-6 mb-3">
                                        <label for="phonenumber" class="form-label">Phone Number</label>
                                        <input type="text" id="phonenumber" class="form-control form-control-sm" placeholder="Enter your phone number" value="<?= htmlspecialchars($row['phone_number']) ?>">
                                    </div>
                                </div>
                                
                                <!-- Submit Button -->
                                <div class="text-end">
                                    <button type="submit" class="btn bg-success-subtle btn-sm px-4 py-1">
                                        <small>UPDATE</small>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
</section>

<script>
    $(document).ready(function () {
        
    });
</script>




<?php require 'layout/bottom.php'; ?>
