<?php require 'layout/top.php'; ?>
<?php require 'layout/navbar.php'; ?>

<section class="py-5">
    <div class="container">
        <div class="card shadow-lg rounded-3 shadow py-3 px-4">
            <div class="row p-3">
                <div class="col-md-12 col-lg-4">
                    <div class="product-carousel">
                        <img src="img/products/<?php echo htmlspecialchars($product['product_img']); ?>" class="img-fluid w-100" alt="<?php echo htmlspecialchars($product['product_id']); ?>">
                    </div>
                </div>
                <div class="col-md-12 col-lg-8">
                    <h3 class="fw-bold text-start text-lg-start" id="productname"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                    <h3 class="fw-bold" id="productPrice">₱<?php echo intval($product['product_price']); ?></h3>
                    <p class="text-muted">Available Colors:</p>
                    
                    <!-- Colors -->
                    <div class="d-flex flex-wrap gap-3">
                        <?php while ($color = $colors_result->fetch_assoc()) { ?>
                                <a href="/product-details?id=<?= encryptor('encrypt', htmlspecialchars($color['product_id']))?>"
                                    class="btn btn-sm <?= $product['product_color'] == $color['product_color'] ? 'btn-dark' : 'btn-outline-dark' ?>">
                                    <?php echo htmlspecialchars($color['product_color']); ?> 
                                </a>
                        <?php } ?>
                    </div>
                    <form id="cartForm">
                        <!-- Sizes -->
                        <div class="d-flex align-items-center gap-3"> 
                            <p class="text-muted mt-3">Sizes:</p>
                            <div class="d-flex flex-wrap">
                                <?php while ($size = $sizes_result->fetch_assoc()) { ?>
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="radio" name="product_size" value="<?php echo htmlspecialchars($size['product_size']); ?>">
                                        <label class="form-check-label">
                                            <?php echo htmlspecialchars($size['product_size']); ?>
                                        </label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                        <p class="text-muted" id="productQuantity">Available Quantity: <?php echo $product['product_quantity']; ?></p>
                            <?php if(!isset($_SESSION['user_data'])): ?>
                                <hr>
                                <a href="/product" class="btn bg-secondary-subtle w-100 mb-3 py-1">Continue Shopping</i> </a>
                                <span>Don't have account? <a href="/login"></i>Create an account </a></span>
                            <?php else: ?>
                                <button type="submit" class="btn bg-success-subtle w-100 mb-3 py-1">Add to Cart</button>
                                <a href="/product" class="btn bg-secondary-subtle w-100 py-1">Continue Shopping</i> </a>
                        <?php endif ?>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
$(document).ready(function () {
    cart();
    productSize();

   
});

// product size
function productSize() { 
    $("input[name='product_size']").on("change", function () {
        var productname = $("#productname").text();  
        var selectedColor = $(".btn.btn-dark").text().trim();
        var selectedSize = $("input[name='product_size']:checked").val(); 
        
        if (selectedColor && selectedSize && productname) {
            $.ajax({
                type: "POST",  
                url: "backend/user/product-server.php?action=productSize",  
                data: {
                    productname: productname,
                    color: selectedColor,
                    size: selectedSize    
                },
                dataType: "json", 
                success: function (response) {
                    if (response) {
                        $('#productPrice').text('₱' + response[0].product_price);
                        $('#productQuantity').text('Available Quantity: ' + response[0].product_quantity);

                        // Update or add hidden inputs
                        $('#cartForm').find("input[name='product_id']").remove();

                        $('#cartForm').append(`
                            <input type="hidden" name="product_id" value="${response[0].product_id}">
                        `);
                    } 
                },
                error: function (xhr, status, error) {
                    console.error("AJAX Error: " + error);
                }
            });
        }
    });
}

// Cart form 
function cart(){
    $('#cartForm').submit(function (event) {
        event.preventDefault();
        
        var productSize = $("input[name='product_size']:checked").val();
        if (!productSize) {
            return swal('Opps!', 'Please select a size!', 'warning');
        }
    
        $.ajax({
            type: "POST",
            url: "backend/user/product-server.php?action=formCart",  
            data: $(this).serialize(),  
            dataType: "json",
            success: function (response) {
                if(response.success){
                    swal('Success!',response.message,'success');
                }
                cartList();
                countCartList();
            },
            error: function (xhr, status, error) {
                console.error("Error: " + error);
            }
        });
    });
}


</script>

<?php require 'layout/bottom.php'; ?>
