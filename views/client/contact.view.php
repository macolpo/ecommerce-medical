<?php require 'layout/top.php'; ?>
<?php require 'layout/navbar.php'; ?>

<section>
    <div class="py-5 bg-dark bg-gradient d-flex justify-content-center align-items-center" style="min-height: 20vh;">
        <div class="py-3 text-center" data-aos="fade-down" data-aos-duration="3000">
            <h1 class="fw-semibold text-light py-3">CONTACT US</h1>
        </div>
    </div>
    <div class="container py-5 d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="row">
            <!-- details -->
            <div class="col-lg-12 col-md-12 col-lg-6 mb-3 p-5 d-flex justify-content-center row">
                <!-- Call us -->
                <div class="col-md-4 mb-3 row">
                    <div class="col-2 d-flex">
                        <h1 class="fw-bold"><i class="bi bi-telephone-fill text-warning"></i></h1>
                    </div>
                    <div class="col-10">
                        <div class="ms-1">
                            <h4 class="fw-bold">CALL US</h4>
                            <h6 class="fw-bold"><i>+ (63) 321 2131 312</i></h6>
                        </div>
                    </div>
                </div>

                <!-- Location -->
                <div class=" col-md-4 mb-3 row">
                    <div class="col-2 d-flex">
                        <h1 class="fw-bold"><i class="bi bi-geo-alt-fill text-success"></i></h1>
                    </div>
                    <div class="col-10">
                        <div class="ms-1">
                            <h4 class="fw-bold">LOCATION</h4>
                            <h6 class="fw-bold"><i>DASMARIÑAS CITY, CAVITE</i></h6>
                        </div>
                    </div>
                </div>
                <!-- Email -->
                <div class=" col-md-4 mb-3 row">
                    <div class="col-2 d-flex">
                        <h1 class="fw-bold"><i class="bi bi-envelope-fill text-info"></i></h1>
                    </div>
                    <div class="col-10">
                        <div class="ms-1">
                            <h4 class="fw-bold">EMAIL</h4>
                            <h6 class="fw-bold"><i>EXAMPLE@EXAMPLE.COM</i></h6>
                        </div>
                    </div>
                </div>
            </div>
            <!-- contact form -->
            <div class="col-lg-6 col-md-12 mb-5">
                <div class="card p-3 rounded-0 shadow">
                    <div class="card-body">
                        <form class="row">
                            <div class="col-12 mb-1">
                                <label class="form-label">Fullname <small class="text-danger">*</small></label>
                                <div class="row">
                                    <div class="col-6">
                                        <input type="text" class="form-control form-control-sm hvr-grow" placeholder="First Name">
                                        <div class="form-text"><small>First Name</small></div>
                                    </div>
                                    <div class="col-6">
                                        <input type="text" class="form-control form-control-sm hvr-grow" placeholder="Last Name">
                                        <div class="form-text"><small>Last Name</small></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 mb-3">
                                <label class="form-label">Email <small class="text-danger">*</small></label>
                                <input type="text" class="form-control form-control-sm hvr-grow" placeholder="Email">
                                <div class="form-text"><small>example@example.com</small></div>
                            </div>

                            <div class="col-12 mb-4">
                                <label class="form-label">Message <small class="text-danger">*</small></label>
                                <textarea type="text" class="form-control form-control-sm hvr-grow" placeholder="Message"></textarea>
                            </div>

                            <div class="col-12 text-end">
                                <button class="btn bg-success-subtle btn-sm">SUBMIT</button>

                            </div>
                        </form>

                    </div>
                    
                </div>
            </div>
            <!-- map -->
            <div class="col-lg-6 col-md-12">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d123712.00271308002!2d120.88300116920006!3d14.311427757298043!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397d5b87c111e25%3A0xab8cb698f840321f!2sDasmari%C3%B1as%2C%20Cavite!5e0!3m2!1sen!2sph!4v1736255935553!5m2!1sen!2sph" width="100%" height="398px" style="border-radius: 5px" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
                

                
        </div>
    </div>
</section>
<?php require 'layout/footer.php'; ?>
<?php require 'layout/bottom.php'; ?>
