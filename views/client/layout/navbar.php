 <!-- header -->
 <nav class="navbar navbar-expand-lg sticky-top bg-white p-3" id="navbar">
    <div class="container">
      <a href="/" class="navbar-brand">
        <img src="img/logo.png" alt="logo" class="img-fluid" width="150">
      </a>
      <a class="navbar-toggler border border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasLeft" aria-controls="offcanvasLeft">
        <i class="bi bi-list" id="menu-icon"></i>
      </a>

      <!-- Offcanvas Menu -->
      <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasLeft" aria-labelledby="offcanvasLeftLabel">
        <div class="offcanvas-header">
          <h5 id="offcanvasLeftLabel">Details</h5>
          <a type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></a>
        </div>
        <div class="offcanvas-body">
          <ul class="navbar-nav m-auto gap-2">
            <li class="nav-item">
              <a class="nav-link fw-semi <?= urlIs('/') ? 'active' : '' ?>" href="/" title="Home" >HOME</a>
            </li>
            <li class="nav-item">
              <a class="nav-link fw-semi <?= urlIs('/about') ? 'active' : '' ?>" href="/about" title="About">ABOUT</a>
            </li>
            <li class="nav-item">
              <a class="nav-link fw-semi <?= urlIs('/contact') ? 'active' : '' ?>" href="/contact" title="Contact">CONTACT</a>
            </li>
            <li class="nav-item">
              <a class="nav-link fw-semi <?= urlIs('/product') ? 'active' : '' ?>" href="/product" title="Product">PRODUCT</a>
            </li>
            
          </ul>

          <div class="d-flex align-items-center gap-3 mt-2 mb-2">
            

            <?php if(!isset($_SESSION['user_data'])): ?>
            <a class="px-3 py-2 rounded-circle hvr-fade" href="/login"><i class="bi bi-person-circle fs-5"></i></a>
            <!-- cart btn -->
            <a class="" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
              <i class="bi bi-cart hvr-fade px-3 py-2 rounded-circle fs-5"></i>
            </a>
            <?php else: ?>
              <div class="dropdown">
                <a class="dropdown-toggle-he px-3 py-2 rounded-circle hvr-fade" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  <i class="bi bi-person-circle fs-5"></i>
                </a>
                <ul class="dropdown-menu">
                  <li><a class="dropdown-item py-2" href="/profile">Profile</a></li>
                  <li><a class="dropdown-item py-2" href="/change-password">Change Password</a></li>
                  <li><hr class="dropdown-divider"></li>
                  <li><a class="dropdown-item" href="/logout">Logout</a></li>
                </ul>
              </div>
              <!-- cart btn -->
              <a class="position-relative" type="button" data-bs-toggle="offcanvas" data-bs-target="#staticBackdrop" aria-controls="staticBackdrop">
                <i class="bi bi-cart hvr-fade px-3 py-2 rounded-circle fs-5"></i>  
                <span class="position-absolute top-0 start-50 badge rounded-pill bg-danger" id="countOrder"></span>
              </a>

              
              
            <?php endif ?>

            


          </div>
        </div>
      </div>
    </div>
  </nav>













    <!-- cart -->
    <div class="offcanvas offcanvas-end" data-bs-backdrop="static" tabindex="-2" id="staticBackdrop" aria-labelledby="staticBackdropLabel">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="staticBackdropLabel">Cart <i class="bi bi-cart"></i></h5>
        <a type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></a>
      </div>
      <div class="offcanvas-body over-flow">
        <?php if(!isset($_SESSION['user_data'])): ?>
        <div class="container py-5 mt-3">
          <div class="d-flex justify-content-center align-items-center py-5">
            <div class="text-center">
              <div class="py-5">
                <h5 class="text-muted">Join us to stay updated on new arrivals, exclusive offers, and more! Sign up now!</h5>
              </div>
              <div>
                <a href="/register" class="btn btn-outline-dark btn-sm">Register Here!</a>
              </div>
            </div>
          </div>
        </div>
        <?php endif ?>
        <!-- order list -->
        <div id="cartList">
        </div>
      </div>
      <div class="p-3 shadow">
        <div class="d-flex justify-content-between align-items-center">
            <div class="col-sm-8 totalPrice">
            </div>
            <div class="col-sm-4" id="cartBtn">
            </div>
        </div>  
      </div>
    </div>
    <!-- cart -->


  <!-- end of header -->
<script>

$(document).ready(function () {
  cartList();
  countCartList();
});
  
function cartList() {
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
                    <div class="d-flex mb-3">
                      <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-12">
                          <img src="img/products/${product.product_img}" class="img-fluid w-100">
                        </div> 
                        <div class="col-lg-8 col-md-8 col-sm-12">
                          <div><strong>Name:</strong> ${product.product_name}</div>
                          <div><strong>Price:</strong> ₱ ${parseFloat(product.product_price).toFixed(2)}</div>

                          <div><strong>Quantity:</strong>
                            <button onclick="addBtn('${product.product_id}')" class="btn"><i class="bi bi-plus"></i></button> 
                            ${product.cart_quantity} 
                            <button onclick="minusBtn('${product.product_id}')" class="btn"><i class="bi bi-dash"></i></button>
                            <button onclick="deleteBtn('${product.product_id}')" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash"></i></button>
                          </div>

                        </div>   
                      </div>                
                    </div>
                  `;

                  // Calculate the overall total price
                  overallTotalPrice += parseFloat(product.product_price) * parseInt(product.cart_quantity);
              }
            });

            // Update the HTML of the cart list
            $('#cartList').html(productListHtml);

            // Update total price and checkout button visibility
            if (overallTotalPrice === 0 || isNaN(overallTotalPrice)) {
              $('#cartBtn').html(``);
              $('.totalPrice').html('');
            } else {
              $('.totalPrice').html('Total: ₱ ' + overallTotalPrice.toFixed(2));
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

function countCartList() { 
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

function addBtn(productId) { 
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

function minusBtn(productId) { 
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

function deleteBtn(productId){
  $.ajax({
      type: "POST",
      url: "backend/user/product-server.php?action=deleteCart&product_id=" + productId,
      dataType: "json",
      success: function (response) {
        cartList();
        cartItem();
        countCartList();
      },
      error: function(xhr, status, error) {
          console.log(error);
      }
  });

}

</script>