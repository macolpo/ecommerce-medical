<?php require 'layout/top.php'; ?>
<?php require 'layout/navbar.php'; ?>


<section>
<div class="container py-5">
    <div class="row">


    <!-- filter -->
    <div class="col-lg-4 mb-3">
        <div class="card p-4 border-0 rounded-0 shadow-sm">
            <h4 class="fw-bolder card-title">Products</h4>
            <div class="mt-2">
                <input type="search" class="form-control" id="search" oninput="filterProducts()" placeholder="Search..">
            </div>
            <hr>
            <!-- categories -->
            <div class="mb-1">
                <small><a class="nav-link py-2" href="javascript:void(0)" id="category-all" onclick="selectCategory('all')"><i class="bi bi-chevron-right"></i>All</a></small>
                <hr class="mt-0">
            </div>
            <div id="productCategories"></div>
            
        </div>
    </div>

    <!-- products -->
    <div class="col-lg-8">
        <!-- product list -->
        <div class="d-flex" style="max-height:75vh; overflow-y:auto;">
            <div class="row w-100" id="productList"></div>
        </div>
    </div>


    </div>
</div>

</section>

<?php require 'layout/bottom.php'; ?>

<script>
    $(document).ready(function () {
        ProductList();
        ProductCategory();
    });

    function ProductCategory() {
        $.ajax({
            type: "POST",
            url: "backend/user/product-server.php?action=productCategories",
            dataType: "json",
                success: function (response) {
                if (response && response.length > 0) {
                    var categoryHtml = '';
                    $.each(response, function (index, category) {
                        categoryHtml += `
                        <div class="mb-1">
                            <small>
                                <a id="category-${category.category_id}" class="nav-link py-2" href="#" onclick="selectCategory('${category.category_id}')">
                                    <i class="bi bi-chevron-right"></i>${category.category_name}
                                </a>
                            </small>
                            <hr class="mt-0">
                        </div>
                        `;
                    });
                    $('#productCategories').html(categoryHtml);
                } else {
                    $('#productCategories').html('<p>No category found.</p>');
                    }
                }
            });
    }

    function ProductList(cat){
        $.ajax({
            type: "POST",
            url: "backend/user/product-server.php?action=productList", 
            data: {
                filter: cat || 'all',
            },
            dataType: "json",
            success: function (response) {
                if (response && response.length > 0) {
                    var productHTML = '';
                    $.each(response, function(index, product) {
                        productHTML += `
                            <div class="col-lg-3 col-md-4 col-sm-12 mb-3 card-product">
                                <div class="card border-0 shadow card-product-hover">
                                    <div class="product-carousel">
                                        <img src="img/products/${product.product_img}" height="250" class="mx-auto w-100" alt="${product.product_name}">
                                    </div>
                                    <div class="card-body text-center">
                                        <h6 class="card-title">${product.product_name}</h6>
                                        <p class="card-subtitle"><small>${product.product_color}</small></p>
                                        <p class="card-text"><small>₱${product.product_price}</small></p>
                                    </div>
                                </div>
                                <div class="middle">
                                    <a class="btn btn-dark btn-sm" href="/product-details?id=${product.product_id}">
                                        <small>View Details</small>
                                    </a>
                                </div>
                            </div>
                        `;
                    });

                    $('#productList').html(productHTML);
                } else {
                    $('#productList').html('<p>No products found.</p>'); 
                }
            },
            error: function(xhr, status, error) {
                console.error('Error fetching product list:', error);
                $('#productList').html('<p>Error fetching product list. Please try again.</p>');
            }
        });
    }

    function selectCategory(categoryId) {
        $('.nav-link').removeClass('text-danger'); 
        $('#category-' + categoryId).addClass('text-danger');
        ProductList(categoryId);
    }

    function filterProducts() {
        var search = $('#search').val().toLowerCase();  
        $('#productList .col-lg-3').each(function() {
            var productName = $(this).find('.card-title').text().toLowerCase();  
            var productColor = $(this).find('.card-subtitle').text().toLowerCase();  

            if (productName.indexOf(search) !== -1 || productColor.indexOf(search) !== -1) {
                $(this).show(); 
            } else {
                $(this).hide();
            }
        });
    }
</script>
