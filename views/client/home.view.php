<?php require 'layout/top.php'; ?>
<?php require 'layout/navbar.php'; ?>

<!-- Hero -->
<section class="py-5 d-flex justify-content-center align-items-center" id="hero" style="min-height: 50vh;">
    <div class="container py-5">
        <div class="text-white py-5 text-center">
            <div class="py-5 d-flex justify-content-center align-items-center row">
                <div class="col-lg-7" data-aos="fade-down" data-aos-duration="3000">
                    <h1 class="fw-bold mb-5 display-5 text-primary-emphasis">
                        Your Quality Retailers Of <span class="text-success">Medical Supplies</span>
                    </h1>
                </div>
                <div class="col-lg-7"  data-aos="fade-up" data-aos-duration="3000">
                    <h5 class="text-primary-emphasis">
                        Explore a Wide Range of Essential Healthcare Products and Equipment in Our Online Store
                    </h5>
                </div>
            </div>
            <div class="py-5" data-aos="fade-right" data-aos-duration="3000">
                <a class="btn btn-outline-light<?= urlIs('/product') ? 'active' : '' ?>" href="/product">Shop Now</a>
            </div>
        </div>
    </div>
</section>

<!-- product -->
<?php require 'home/home.product.php'; ?>
<!-- end product -->

<?php require 'layout/footer.php'; ?>
<?php require 'layout/bottom.php'; ?>
