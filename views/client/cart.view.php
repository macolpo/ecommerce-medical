<?php require 'layout/top.php'; ?>
<?php 
    if(!isset($_SESSION['user_data'])){
        header('Location: /403');
    }
?>
<?php require 'layout/navbar.php'; ?>


<section class="py-5">
  <div class="container">
    <div class="row d-flex justify-content-center align-items-center">
      <div class="col-12">
        <div class="card shadow">
          <div class="card-body p-0">
            <div class="row g-0">

                <!-- Cart Section -->
                <div class="col-lg-8">
                  <div class="p-5">
                    <div class="d-flex justify-content-between align-items-center mb-5">
                      <h5 class="fw-bold mb-0">Shopping Cart</h5>
                      <h6 class="mb-0 text-muted">3 items</h6>
                    </div>
                    <hr class="my-4">
                    
                    <!-- Cart Items -->
                    <div style="max-height:45vh; overflow:auto;">
                      <div id="cartItem"></div>
                    </div>
                    <!-- End Cart Items -->

                    <div class="py-3">
                      <hr>
                      <h6 class="mb-0"><a href="/product" class="text-body"><i class="fas fa-long-arrow-alt-left"></i> Back to shop</a></h6>
                    </div>
                  </div>
                </div>

                <!-- Summary Section -->
                <div class="col-lg-4 bg-body-tertiary">
                  <div class="p-5">
                    <h3 class="fw-bold mb-5 mt-2 pt-1">Summary</h3>
                    <hr class="my-4">

                    <div class="d-flex justify-content-between mb-4">
                      <h5 class="text-uppercase">Items (3)</h5>
                      <h5>€ 132.00</h5>
                    </div>

                    <h5 class="text-uppercase mb-3">Shipping</h5>
                    <div class="d-flex justify-content-between mb-4">
                      <h5 class="text-uppercase">Shipping cost</h5>
                      <h5>€ 5.00</h5>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between mb-5">
                      <h5 class="text-uppercase">Total price</h5>
                      <h5>€ 137.00</h5>
                    </div>

                    <button type="button" data-mdb-button-init data-mdb-ripple-init class="btn btn-dark btn-block btn-lg" data-mdb-ripple-color="dark">
                      CHECKOUT
                    </button>
                  </div>
                </div>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<?php require('layout/bottom.php') ?>

<script>
    $(document).ready(function () {
        cartItem();
        countCartItem();
    });


function cartItem() {
    $.ajax({
        url: 'backend/user/product-server.php?action=cartList',
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            var productListHtml = '';
            var overallTotalPrice = 0; 

            response.products.forEach(function(product) {
              if (product.product_name != null && product.product_price != null && product.cart_quantity != null) {
                  productListHtml += `
                    <div class="d-flex justify-content-center align-items-center">
                        <div class="col-lg-3 col-md-3 mb-3">
                            <img src="img/products/${product.product_img}" class="img-fluid rounded-3 w-100" alt="Cotton T-shirt">
                        </div>
                        <div class="col-lg-3 col-md-3 text-center">
                            <h6 class="mb-0 fw-semibold">${product.product_name}</h6>
                            <h6 class="text-muted">${product.product_color}</h6>
                            <h6 class="text-muted">Size: ${product.product_size}</h6>
                        </div>
                        <div class="col-lg-2 col-md-2 d-flex align-items-center justify-content-center">
                            <button onclick="addItem('${product.product_id}')" class="btn"><i class="bi bi-plus-lg"></i></button> 
                                ${product.cart_quantity} 
                            <button onclick="minusItem('${product.product_id}')" class="btn"><i class="bi bi-dash-lg"></i></button>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <h6>₱ ${parseFloat(product.product_price).toFixed(2)}</h6>
                        </div>
                        <div class="col-lg-2 col-md-2">
                            <a href="#" onclick="deleteBtn('${product.product_id}')" class="text-muted"><i class="bi bi-x-lg text-danger"></i></a>
                        </div>
                    </div>
                    <hr>
                  `;

                  // Calculate the overall total price
                  overallTotalPrice += parseFloat(product.product_price) * parseInt(product.cart_quantity);
              }
            });

            // Update the HTML of the cart list
            $('#cartItem').html(productListHtml);

            // Update total price and checkout button visibility
            if (overallTotalPrice === 0 || isNaN(overallTotalPrice)) {
              $('#cartBtn').html(``);
              $('#totalPrice').html('');
            } else {
              $('#totalPrice').html('Total: ₱ ' + overallTotalPrice.toFixed(2));
              $('#cartBtn').html(`
                <a href="/cart" class="btn btn-warning rounded-5 text-dark  text-decoration-none w-100 fw-semibold">View Cart</a>
              `);
            }
        },
        error: function(xhr, status, error) {
            console.log(error);
        }
    });
}

function countCartItem() { 
  $.ajax({
      type: "POST",
      url: "backend/user/product-server.php?action=countCartList",
      dataType: "json",
      success: function (response) {
        $('#countOrder').text(response.countOrder)
      },
      error: function(xhr, status, error) {
          console.log(error);
      }
    });
}

function addItem(productId) { 
    $.ajax({
      type: "POST",
      url: "backend/user/product-server.php?action=addQuantity&product_id=" + productId,
      dataType: "json",
      success: function (response) {
        cartList();
        cartItem();
      },
      error: function(xhr, status, error) {
          console.log(error);
      }
    });
}

function minusItem(productId) { 
  $.ajax({
      type: "POST",
      url: "backend/user/product-server.php?action=minusQuantity&product_id=" + productId,
      dataType: "json",
      success: function (response) {
        cartList();
        cartItem();
      },
      error: function(xhr, status, error) {
          console.log(error);
      }
      
    });
}
function removeItem(productId){
  $.ajax({
      type: "POST",
      url: "backend/user/product-server.php?action=deleteCart&product_id=" + productId,
      dataType: "json",
      success: function (response) {
        cartList();
        countCartList();
      },
      error: function(xhr, status, error) {
          console.log(error);
      }
  });

}
</script>