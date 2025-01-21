<?php require 'layout/top.php'; ?>
<main id="main" class="main">

<div class="pagetitle">
  <h1>Manage Products</h1>
  <nav class="d-flex justify-content-between">
    <div>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
        <li class="breadcrumb-item active">Manage Products</li>
      </ol>
    </div>
    <div class="mb-1">
      <button type="button"
          class="btn btn-secondary text-light p-0 px-2 py-1 align-items-center"
          onclick="$('#addModal').modal('show')">
          <i class="bi bi-plus"></i>
      </button>
    </div>
  </nav>
</div>


<section class="section dashboard">
      <div class="row">

        <div class="col-lg-12">
          <div class="card p-3">
            <div class="table-responsive">
              <table class="table w-100 text-nowrap" id="productsTable">
              <thead>
                <tr>
                  <th>Products Image</th>
                  <th>Products Name</th>
                  <th>Products Price</th>
                  <th>Products Quantity</th>
                  <th>Action</th>
                </tr>
                </thead>
                <tbody>
              
                </tbody>
              </table>

            </div>
           
          </div>
        </div>
      </div>
</section>


<!-- modal -->
<div class="modal fade" id="addModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="myTableModalLabel">ADD PRODUCT</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick="$('.text-danger').remove();"></button>
            </div>
            <div class="modal-body">
                <form id="ProductForm" class="row" enctype="multipart/form-data">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="ProductName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" name="product_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="ProductPrice" class="form-label">Product Price</label>
                            <input type="text" class="form-control"  name="product_price" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                        </div>
                        <div class="mb-3">
                            <label for="ProductQuantity" class="form-label">Product Quantity</label>
                            <input type="text" class="form-control"  name="product_quantity" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                        </div>

                        <div class="mb-3">
                            <label for="ProductCategory" class="form-label">Category</label>
                            <select class="form-select" name="product_category" id="productCategory" required></select>
                        </div>

                    </div>

                    <div class="col-6">
                
                        <div class="col-lg-12 mb-3">
                            <label for="inputImage" class="form-label">Image</label>
                            <input type="file" class="form-control" id="inputImage" name="product_image"
                                accept="image/jpeg, image/png" required>
                        </div>

                        <div class="col-sm-12">
                            <div id="img-preview">
                                <!-- <img src="../img/about-img-01.jpg" alt="..." class="img-fluid"> -->
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100">Add Product</button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Edit Modal -->
<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="editModalLabel">Edit Product</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick="$('.text-danger').remove();"></button>
            </div>
            <div class="modal-body">
                <form id="editProductForm" class="row">
                <div class="col-6">
                        <div class="mb-3">
                            <label for="ProductName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="editProductName" name="product_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="ProductPrice" class="form-label">Product Price</label>
                            <input type="text" class="form-control"  name="product_price" id="editProductPrice" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                        </div>
                        <div class="mb-3">
                            <label for="ProductQuantity" class="form-label">Product Quantity</label>
                            <input type="text" class="form-control"  name="product_quantity" id="editProductQuantity" oninput="this.value = this.value.replace(/[^0-9]/g, '')" required>
                        </div>

                        <div class="mb-3">
                            <label for="ProductCategory" class="form-label">Category</label>
                            <select class="form-select" name="product_category" id="editproductCategory" required></select>
                        </div>

                    </div>

                    <div class="col-6">
                
                        <div class="col-lg-12 mb-3">
                            <label for="inputImage" class="form-label">Image</label>
                            <input type="file" class="form-control" id="editinputImage" name="editproduct_image"
                                accept="image/jpeg, image/png">
                        </div>

                        <div class="col-sm-12">
                            <div id="edit-img-preview">
                                <!-- <img src="../img/about-img-01.jpg" alt="..." class="img-fluid"> -->
                            </div>
                        </div>

                    </div>
                    <input type="hidden" id="editProductId" name="product_id">
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100">Update Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</main>
<?php require 'layout/bottom.php'; ?>

<script>
$(document).ready(function() {
    var table = $('#productsTable').DataTable({
        "ajax": {
            "url": "backend/admin/product-server.php?action=fetchData",
            "type": "POST",
            "dataSrc": ""
        },
        "columns": [
            { 
                "data": "product_img",
                "render": function(data) {
                    return `<img src="img/products/${data}" class="img-fluid w-25 img-thumbnail" alt="Product Image">`;
                },
            },
            { "data": "product_name" },
            { "data": "product_price" },
            { "data": "product_quantity" },
            {
                "data": "product_id",
                "render": function(data) {
                    return `<a type="button" onclick="editProducts(${data})" class="btn btn-sm btn-success">Edit</a>
                            `;
                }
            }
        ]
    });

    // fetch categories
    $('#addModal').on('show.bs.modal', function() {
        $.ajax({
            type: "POST",
            url: 'backend/admin/product-server.php?action=fetchCategories',
            dataType: "json",
            success: function(categoryResponse) {
                $('#productCategory').empty();
                $('#productCategory').append($('<option>', {
                    value: '',
                    text: 'Select Category'
                }));
                $.each(categoryResponse, function(key, value) {
                    $('#productCategory').append($('<option>', {
                        value: value.category_id,
                        text: value.category_name
                    }));
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching category options:", xhr.responseText);
            }
        });

    });
    $('#editModal').on('show.bs.modal', function() {
        $.ajax({
            type: "POST",
            url: 'backend/admin/product-server.php?action=fetchCategories',
            dataType: "json",
            success: function(categoryResponse) {
                $('#editproductCategory').empty();
                $('#editproductCategory').append($('<option>', {
                    value: '',
                    text: 'Select Category'
                }));
                $.each(categoryResponse, function(key, value) {
                    $('#editproductCategory').append($('<option>', {
                        value: value.category_id,
                        text: value.category_name
                    }));
                });
            },
            error: function(xhr, status, error) {
                console.error("Error fetching category options:", xhr.responseText);
            }
        });

    });


    // forms
    $('#ProductForm').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: 'backend/admin/product-server.php?action=insertData',
            data: formData,
            contentType: false,  
            processData: false, 
            success: function(response) {
                if (response.status === 'success') {
                    swal('Success!',response.message,'success');
                    $('#addModal').modal('hide');
                    table.ajax.reload();
                    $('#ProductForm')[0].reset();
                } else {
                    swal('Oops',response.message,'warning');
                }
               
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });

    $('#editProductForm').submit(function(e) {
        e.preventDefault();

        var formData = $(this).serialize(); 

        $.ajax({
            url: 'backend/admin/product-server.php?action=updateData',
            method: 'POST',
            data: formData,
            success: function(response) {
                if (response.status === 'success') {
                swal('Success!',response.message,'success');
                $('#editModal').modal('hide');
                table.ajax.reload(); 
                $('#editProductForm')[0].reset();
                } else {
                    swal('Oops',response.message,'warning');
                }
            },
            error: function(xhr, status, error) {
                console.error(error);
                alert('An error occurred while updating the product');
            }
        });
    });
});

function editProducts(id) {
  $('#editModal').modal('show');
    $.ajax({
        url: 'backend/admin/product-server.php?action=fetchProductDetails',
        method: 'POST',
        data: { product_id: id },
        dataType: 'json',
        success: function(response) {
        console.log(response);
        if (response) {
            $('#editProductName').val(response.product_name);
            $('#editProductPrice').val(response.product_price);
            $('#editProductQuantity').val(response.product_quantity);
            $('#editProductId').val(response.product_id);
            $('#editproductCategory').val(response.category_id);
            var imgSrc = 'img/products/' + response.product_img;
            $('#edit-img-preview').html('<img src="' + imgSrc + '" class="img-fluid  w-50 img-thumbnail" alt="Product Image">');
        }
        },
        error: function(xhr, status, error) {
        console.error(error);
        alert('An error occurred while fetching product details');
        }
    });
}


// function deleteProducts(id) {
//     swal({
//         title: "Are you sure?",
//         text: "You want to delete this product?",
//         icon: "warning",
//         buttons: true,
//         dangerMode: true,
//     }).then((willDelete) => {
//         if (willDelete) {
//             $.ajax({
//                 url: 'backend/admin/product-server.php?action=deleteData',
//                 type: 'POST',
//                 data: { product_id: id },
//                 success: function(response) {
//                    console.log(response);
//                     // if (response.status === 'success') {
//                     //     swal('Success!',response.message,'success');
//                     //     table.ajax.reload();
//                     // } else {
//                     //     swal('Oops',response.message,'warning');
//                     // }
//                 },
//                 error: function(xhr, status, error) {
//                     console.error(error);
//                     swal('Error!', 'There was an issue with the request.', 'error');
//                 }
//             });
//         } else {
//             swal("Your imaginary file is safe!");
//         }
//     });
// }





//image
function validateImageType(input) {
    input.addEventListener('change', function() {
        const file = this.files[0];
        const acceptedTypes = ['image/jpeg', 'image/png'];

        if (file && !acceptedTypes.includes(file.type)) {
            showAlert('JPEG or PNG');
            this.value = '';
        }
    });
}
validateImageType(document.getElementById('inputImage'));
validateImageType(document.getElementById('editinputImage'));

$('#inputImage').on('change', function(e) {
    var files = e.target.files;
    $('#img-preview').empty();

    for (var i = 0; i < files.length; i++) {
        var file = files[i];

        // Check if the file is an image
        if (file.type.match('image.*')) {
            var reader = new FileReader();

            reader.onload = function(e) {
                var imgSrc = e.target.result;

                // Create image element and add to col-sm-4
                var imgElement = $('<div class="col-lg-12"><img src="' + imgSrc +
                    '" class="img-thumbnail img-fluid mb-3 w-50" alt="Preview"></div>');
                $('#img-preview').append(imgElement);
            };

            // Read the image file as a data URL
            reader.readAsDataURL(file);
        }
    }
});
$('#editinputImage').on('change', function(e) {
    var files = e.target.files;
    $('#edit-img-preview').empty();

    for (var i = 0; i < files.length; i++) {
        var file = files[i];

        // Check if the file is an image
        if (file.type.match('image.*')) {
            var reader = new FileReader();

            reader.onload = function(e) {
                var imgSrc = e.target.result;

                // Create image element and add to col-sm-4
                var imgElement = $('<div class="col-lg-12"><img src="' + imgSrc +
                    '" class="img-thumbnail img-fluid mb-3 w-50" alt="Preview"></div>');
                $('#edit-img-preview').append(imgElement);
            };

            // Read the image file as a data URL
            reader.readAsDataURL(file);
        }
    }
});

$('#editinputImage').on('change', function(e) {
    var files = e.target.files;
    $('#img-preview').empty();

    for (var i = 0; i < files.length; i++) {
        var file = files[i];

        // Check if the file is an image
        if (file.type.match('image.*')) {
            var reader = new FileReader();

            reader.onload = function(e) {
                var imgSrc = e.target.result;

                // Create image element and add to col-sm-4
                var imgElement = $('<div class="col-lg-12"><img src="' + imgSrc +
                    '" class="img-thumbnail img-fluid mb-3 w-50" alt="Preview"></div>');
                $('#img-preview').append(imgElement);
            };

            // Read the image file as a data URL
            reader.readAsDataURL(file);
        }
    }
});

</script>