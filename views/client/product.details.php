<?php require 'layout/top.php'; ?>
<?php require 'layout/navbar.php'; ?>

<section class="py-5">
    <div class="container">
        <div class="card shadow-lg rounded-3 shadow py-3 px-4">
            <div class="row p-3">
                <div class="col-md-12 col-lg-4">
                    <div class="product-carousel img-thumbnail">
                        <img src="img/products/<?php echo htmlspecialchars($product['product_img']); ?>" class="img-fluid w-100" alt="<?php echo htmlspecialchars($product['product_id']); ?>">
                    </div>
                </div>
                <div class="col-md-12 col-lg-8">
                    <h1 class="fw-bold" id="productname"><?php echo htmlspecialchars($product['category_name']); ?></h1>
                    <h3 class="fw-bold" id="productname"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                    <h4 class="fw-bold" id="productPrice">₱<?php echo intval($product['product_price']); ?></h4>
                    <form id="cartForm">
                    <p class="text-muted" id="productQuantity">Available Quantity: <?php echo $product['product_quantity']; ?></p>
                        <?php if(!isset($_SESSION['user_data'])): ?>
                            <hr>
                            <a href="/product" class="btn bg-secondary-subtle w-100 mb-3 py-1">Continue Shopping</i> </a>
                            <span>Don't have account? <a href="/login"></i>Create an account </a></span>
                        <?php else: ?>
                            <button type="submit" class="btn bg-success-subtle w-100 mb-3 py-1">Add to Cart</button>
                            <a href="/product" class="btn bg-secondary-subtle w-100 py-1">Continue Shopping</i> </a>
                    <?php endif ?>
                        <input type="hidden" name="product_id" value="<?php echo $_GET['id']; ?>">
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
$(document).ready(function () {
    cart();
});

// Cart form 
function cart(){
    $('#cartForm').submit(function (event) {
        event.preventDefault();
    
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
