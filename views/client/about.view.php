<?php $title = " - About";  ?>
<?php require 'layout/top.php'; ?>
<?php require 'layout/navbar.php'; ?>

<section style="min-height: 100vh;">
    <div class="py-5 mb-5 bg-dark bg-gradient d-flex justify-content-center align-items-center" style="min-height: 20vh;">
        <div class="py-3" data-aos="fade-down" data-aos-duration="3000">
            <h1 class="fw-semibold text-light py-3">ABOUT US</h1>
        </div>
    </div>
    
    <div class="container d-flex justify-content-center align-items-center mb-4" style="min-height: 70vh;">
        <div class="row px-5">
            <div class="d-flex gap-3">
                <div class="col-lg-6 col-md-12 mb-3" data-aos="fade" data-aos-duration="3000">
                    <div class="rounded-3 shadow">
                        <img src="img/about/about-img-1.jpg" alt="" class="img-fluid w-100">
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 mb-3">
                    <div class="px-3">
                        <div class="mb-5" data-aos="fade-up" data-aos-duration="3000">
                            <h3>Your faithful partners Medical Goods</h3>
                            <p>Welcome to your faithful partner in medical goods, your go-to destination for reliable, affordable, and high-quality healthcare products. At our medical e-commerce website, we understand the crucial importance of having access to trustworthy medical supplies and equipment, and we're here to ensure that you have just that.</p>
                            <p><i class="bi bi-check-lg bg-success px-1 rounded-3"></i> We are committed to offering you a wide range of medical products that you can rely on.</p>
                            <p><i class="bi bi-check-lg bg-success px-1 rounded-3"></i> We believe that quality healthcare should be accessible to everyone.</p>
                            <p><i class="bi bi-check-lg bg-success px-1 rounded-3"></i> We offer a diverse range of medical products, from everyday essentials to specialized equipment.</p>
                            <p><i class="bi bi-check-lg bg-success px-1 rounded-3"></i> We leverage the power of e-commerce to connect you with the healthcare products you need, anytime, anywhere.</p>
                        </div>
                        <div data-aos="fade-right" data-aos-duration="3000">
                            <a class="btn btn-outline-success<?= urlIs('/product') ? 'active' : '' ?>" href="/product">Shop Now</a>
                        </div>
                    </div>
                </div>
            </div>
           
           

        </div>
       
    </div>
</section>


<?php require 'layout/footer.php'; ?>
<?php require 'layout/bottom.php'; ?>
