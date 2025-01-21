<?php require 'layout/top.php'; ?>
<main id="main" class="main">

<div class="pagetitle">
  <h1>Manage Category</h1>
  <nav class="d-flex justify-content-between">
  <div>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
        <li class="breadcrumb-item active">Manage Category</li>
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
              <table class="table w-100" id="categoryTable">
              <thead>
                <tr>
                  <th>Category Name</th>
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
                <h1 class="modal-title fs-5 fw-bold" id="myTableModalLabel">ADD CATEGORY</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick="$('.text-danger').remove();"></button>
            </div>
            <div class="modal-body">
                <form id="addForm" class="row" enctype="multipart/form-data">
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="CategoryName" class="form-label">Category Name</label>
                            <input type="text" class="form-control" name="category_name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100">Add Category</button>
                    </div>
                    
                </form>
            </div>
        </div>
    </div>
</div>


<!-- edit modals -->
<div class="modal fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5 fw-bold" id="editModalLabel">Edit Category</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    onclick="$('.text-danger').remove();"></button>
            </div>
            <div class="modal-body">
                <form id="editForm" class="row">
                  <div class="col-12">
                        <div class="mb-3">
                            <label for="CategoryName" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="editCategoryName" name="category_name" required>
                        </div>
                    </div>
                    <input type="hidden" id="editCategoryId" name="category_id">
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary w-100">Update Category</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</main>
<?php require 'layout/bottom.php'; ?>

<script>
  $(document).ready(function(){
    var table = $('#categoryTable').DataTable({
      "ajax": {
        type: 'POST',
        url: 'backend/admin/category-server.php?action=fetchData',
        dataSrc: ''
      },
      "columns": [
        { "data": "category_name" },
        { "data": "category_id", 
          "render": function(data){
                    return `<a type="button" onclick="editCategory(${data})" class="btn btn-sm btn-success">Edit</a>
                  `;
          }
        }
      ]
    });

    // forms
    $('#addForm').submit(function(e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            type: 'POST',
            url: 'backend/admin/category-server.php?action=insertData',
            data: formData,
            contentType: false,  
            processData: false, 
            success: function(response) {
                if (response.status === 'success') {
                    swal('Success!',response.message,'success');
                    $('#addModal').modal('hide');
                    table.ajax.reload();
                    $('#addForm')[0].reset();
                } else {
                    swal('Oops',response.message,'warning');
                }
               
            },
            error: function(xhr, status, error) {
                console.error(error);
            }
        });
    });
    $('#editForm').submit(function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: 'backend/admin/category-server.php?action=updateData',
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status === 'success') {
                    swal('Success!',response.message,'success');
                    $('#editModal').modal('hide');
                    table.ajax.reload();
                } else {
                    swal('Oops!',response.message,'warning');
                }
            },
            error: function(xhr, status, error) {
                console.error("Error updating category:", xhr.responseText);
            }
        });
    });
  });



function editCategory(id) {
  $('#editModal').modal('show');
    $.ajax({
        url: 'backend/admin/category-server.php?action=fetchDetails',
        method: 'POST',
        data: { category_id: id },
        dataType: 'json',
        success: function(response) {
        if (response) {
            $('#editCategoryName').val(response.category_name);
            $('#editCategoryId').val(response.category_id);
        }
        },
        error: function(xhr, status, error) {
        console.error(error);
        alert('An error occurred while fetching category details');
        }
    });
}
</script>