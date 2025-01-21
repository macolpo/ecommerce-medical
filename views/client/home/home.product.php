<section class="py-5 bg-white d-flex justify-content-center align-items-center" id="Product" style="min-height: 50vh;">

    <div class="container">

        <div class="row">
            <!-- header title -->
            <div class="text-center mb-5">
                <h1 class="fw-semi mb-3"> OUR PRODUCTS</h1>
                <span>
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Alias minima quod hic blanditiis quibusdam, atque nulla laudantium id soluta adipisci cupiditate, modi consequatur et optio commodi ratione voluptas. Dolorem, officiis.
                </span>
            </div>
            <!-- carousel btn -->
            <div class="d-flex justify-content-end gap-2 px-2">
                <a type="button" data-bs-target="#carouselProduct" data-bs-slide="prev">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <a type="button" data-bs-target="#carouselProduct" data-bs-slide="next">
                    <i class="bi bi-arrow-right fs-5"></i>
                </a>
            </div>
            <!-- carousel -->
            <div id="carouselProduct" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner" id="carouselItems">
                    <!-- Dynamic items will be appended here -->
                </div>
            </div>
            
            <!-- bottom -->
            <div class="py-5">
                <div class="text-center">
                    <a class="btn btn-outline-dark<?= urlIs('/product') ? 'active' : '' ?>" href="/product">View All Products</a>
                </div>
            </div>
        </div>
        
    </div>

</section>
<script>
    $(document).ready(function () {
        $.ajax({
            type: "POST",
            url: "backend/user/home-server.php?action=homeProduct",
            dataType: "json",
            success: function (response) {
                if (response.products && response.products.length > 0) {
                    let carouselItems = '';
                    let activeClass = 'active';

                    for (let i = 0; i < response.products.length; i += 6) {
                        carouselItems += `<div class="carousel-item ${activeClass}">
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 row-cols-xl-6 g-4">`;

                        for (let j = i; j < i + 6 && j < response.products.length; j++) {
                            const product = response.products[j];
                            carouselItems += `
                                <div class="col">
                                    <div class="card rounded-3 border-0">
                                        <div class="product-carousel">
                                            <img src="img/products/${product.product_img}" height="250" class="mx-auto w-100" alt="${product.product_name}">
                                        </div>
                                        <div class="card-body text-center">
                                            <h6 class="card-title">${product.product_name}</h6>
                                            <p class="card-text"><small>₱${product.product_price}</small></p>

                                            <a class="text-dark" href="/product-details?id=${product.product_id}"><small>View Details</small></a>
                                        </div>
                                    </div>
                                </div>`;
                        }

                        carouselItems += `</div></div>`;  
                        activeClass = '';
                    }

                    // Append the generated carousel items to the carousel
                    $('#carouselItems').html(carouselItems);
                }
            }
        });
    });



</script>