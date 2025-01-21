<?php require 'layout/top.php'; ?>
<?php 
    if(!isset($_SESSION['user_data'])){
        header('Location: /403');
    }
    // unset when back to shop
    unset($_SESSION['transactionId']);
    unset($_SESSION['checkoutSessionId']);
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
                    <div class="d-flex align-items-center row" style="max-height:45vh; overflow:auto;">
                      <div id="cartItem"></div>
                    </div>
                    <!-- End Cart Items -->

                    <div class="py-3">
                      <h6 class="mb-0"><a href="/product" class="text-body"><i class="fas fa-long-arrow-alt-left"></i> Back to shop</a></h6>
                    </div>
                  </div>
                </div>

                <!-- Summary Section -->
                <div class="col-lg-4 bg-body-tertiary">
                  <div class="p-5">
                    <h3 class="fw-bold mb-5 mt-2 pt-1">Summary</h3>
                    <hr class="my-4">

                    <!-- summary -->
                    <div class="d-flex align-items-center mb-4 row" id="cartSummary">
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between mb-5">
                      <h5 class="text-uppercase">Total price</h5>
                      <h5 class="totalPrice"></h5>
                    </div>
                    <form id="paymentForm" class="mb-5">
                        <div class="form-check d-flex justify-content-between align-items-center mb-3">
                            <input class="form-check-input" type="radio" name="paymentMethod"
                                id="gcash" value="paymongo" required>
                            <label class="form-check-label" for="gcash">
                                <img src="https://ui.paymongo.com/logo.png"
                                    alt="GCASH" class="img-fluid w-100 rounded-5 ms-2" style="cursor: pointer;">
                            </label>
                        </div>
                      <button type="button" class="btn btn-success btn-block btn-lg mb-3 w-100" onclick="checkout()">Checkout</button>
                    </form>

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
            var productSummaryHtml = '';
            var overallTotalPrice = 0; 

            response.products.forEach(function(product) {
              if (product.product_name != null && product.product_price != null && product.cart_quantity != null) {
                  productListHtml += `
                      <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12 mb-3">
                            <img src="img/products/${product.product_img}" class="img-fluid rounded-3 w-100" alt="">
                        </div>
                        <div class="col-lg-3 col-md-3 col-sm-12 d-flex align-items-center justify-content-center">
                            <h6 class="mb-0 fw-semibold">${product.product_name}</h6>
                        </div>
                        <div class="col-lg-2 col-md-2 d-flex align-items-center justify-content-center">
                            <button onclick="addItem('${product.product_id}')" class="btn"><i class="bi bi-plus-lg"></i></button> 
                                ${product.cart_quantity} 
                            <button onclick="minusItem('${product.product_id}')" class="btn"><i class="bi bi-dash-lg"></i></button>
                        </div>
                        <div class="col-lg-2 col-md-2 d-flex align-items-center justify-content-center">
                            <h6>₱ ${parseFloat(product.product_price).toFixed(2)}</h6>
                        </div>
                        <div class="col-lg-2 col-md-2 d-flex align-items-center justify-content-center">
                            <a href="#" onclick="removeItem('${product.product_id}')" class="text-muted"><i class="bi bi-x-lg text-danger"></i></a>
                        </div>
                      </div>
                    <hr>
                  `;
                  
                  productSummaryHtml += `
                    <div class="col-12 mb-3">
                      <div class="d-flex justify-content-between">
                        <div class="col-lg-6 col-md-6 col-sm-6">
                          <h6 class="text-uppercase">${product.product_name} (${product.cart_quantity})</h6>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 text-end">
                          <h6>₱ ${(parseFloat(product.product_price) * parseInt(product.cart_quantity)).toFixed(2)}</h6>
                        </div>
                      </div>
                    </div>
                  `;

                  // Calculate the overall total price
                  overallTotalPrice += parseFloat(product.product_price) * parseInt(product.cart_quantity);
              }
            });

            $('#cartItem').html(productListHtml);
            $('#cartSummary').html(productSummaryHtml);

            if (overallTotalPrice === 0 || isNaN(overallTotalPrice)) {
              $('#paymentForm').hide();
              $('.totalPrice').html('₱ 0.00');
              $('.btn-dark').attr('disabled', true); 
            } else {
              $('.totalPrice').html('₱ ' + overallTotalPrice.toFixed(2));
              $('.btn-dark').attr('disabled', false); 
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
        cartItem();
        cartList();
        countCartList();
      },
      error: function(xhr, status, error) {
          console.log(error);
      }
  });

}


function checkout() {

  var paymentMethod = $('input[name="paymentMethod"]:checked').val();

  if (!paymentMethod) {
      swal('Oops..','Please select a payment method before proceeding!','error');
      return;
  }

  $.ajax({
      type: 'POST',
      url: 'backend/user/checkout-server.php?action=checkout',
      data: {
          paymentMethod: paymentMethod
      },
      success: function(response) {
          console.log(response);
          window.location.href = response.direct;
      },
      error: function(error) {
          console.error('Error:', error);
      }
  });

}
</script>