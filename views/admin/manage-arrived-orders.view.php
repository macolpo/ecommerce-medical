<?php require 'layout/top.php'; ?>
<main id="main" class="main">

<div class="pagetitle">
  <h1>Manage Arrived Order</h1>
  <nav class="d-flex justify-content-between">
  <div>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/dashboard">Home</a></li>
        <li class="breadcrumb-item active">Manage Arrived Order</li>
      </ol>
    </div>
    <div class="mb-1">
    </div>
  </nav>
</div>


<section class="section">
      <div class="row">

        <div class="col-lg-12">
          <div class="card p-3">
            <div class="table-responsive">
              <table class="table w-100" id="orderTable">
              <thead>
                <tr>
                <th>Date</th>
                <th>Transaction#</th>
                <th>Name</th>
                <th>Status</th>
                <th>Ongoing</th>
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

<!-- view details -->
<div class="modal fade" id="viewModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
  aria-labelledby="viewModallLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
      <div class="modal-content">
          <div class="modal-header">
              <h1 class="modal-title fs-5 fw-bold" id="viewModallLabel">Receipt</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                  onclick="$('.text-danger').remove();"></button>
          </div>
          <div class="modal-body row">
              <div class="mb-3 col-6">
                <h5 class="fw-semibold mb-0">Transaction#: </h5>
                <span id="transactionNumber" class="mb-3"></span>
              </div>

              <div class="mb-3 col-6">
                <h5 class="fw-semibold mb-0">Date: </h5>
                <span id="created_at" class="mb-3"></span>
              </div>
              
              <div class="mb-3">
                <h5 class="fw-semibold mb-0">Overall Total: </h5>
                <span id="overalltotal" class="mb-3"></span>
                
              </div>
              
              <div class="table-responsive">
                  <table class="table w-100 text-nowrap" id="orderDetailsTable">
                      <thead>
                          <tr class="text-center">
                              <th class="border-bottom pb-2">Product</th>
                              <th class="border-bottom pb-2">Price</th>
                              <th class="border-bottom pb-2">Quantity</th>
                              <th class="border-bottom pb-2">Total</th>
                          </tr>
                      </thead>
                      <tbody>
                      </tbody>
                  </table>
              </div>
              
              <!-- Footer for receipt-style layout -->
              <div class="text-center mt-4">
                <hr>
                <p class="mb-0">Thank you for your purchase!</p>
                <p class="mb-0">Visit us again!</p>
              </div>
          </div>
      </div>
  </div>
</div>

</main>
<?php require 'layout/bottom.php'; ?>

<script>
  $(document).ready(function(){
    var table = $('#orderTable').DataTable({
      "ajax": {
        type: 'POST',
        url: 'backend/admin/order-server.php?action=fetchArrivedOrders',
        dataSrc: ''
      },
      "columns": [
        { "data": "created_at"
        },
        { "data": "transaction_number" },
        { "data": "firstname" },
        {
        "data": "status",
          "render": function(data) {
            return data === 1 ? '<span class="badge bg-success">Paid</span>' : 'Not Paid';
          }
        },
        {
        "data": "action",
          "render": function(data) {
            return data === 1 ? '<span class="badge bg-success">Arrived</span>' : '<span class="badge bg-warning">Ongoing</span>';
          }
        },
        {
            "data": "transaction_id",
            "render": function(data, type, row) {
                return `
                    ${row.action === 0 ? '<a onclick="updateOrder(' + data + ')" class="btn btn-sm btn-success py-0 px-3">Update</a>' : ''}
                    <a onclick="viewOrder(${data})" class="btn btn-sm btn-info py-0 px-3">View Details</a>
                `;
            }
        }
      ]
    });

  });


  function viewOrder(id){
    $('#viewModal').modal('show');
    $.ajax({
      type: 'POST',
      url: 'backend/admin/order-server.php?action=fetchDetails',
      data: {transaction_id: id},
      dataType: 'json',
      success: function(response){
        console.log(response)
        $('#transactionNumber').text(response[0].transaction_number);
        $('#created_at').text(response[0].created_at);
        $('#overalltotal').html('₱' + response[0].total_price);
        var table = $('#orderDetailsTable').DataTable({
          "searching": false,
          "paging": false,
          "data": response,
          "destroy": true,
          "columns": [
            { "data": "product_name" },
            { "data": "price" },
            { "data": "qty" },
            { "data": "price" }
          ]
        });
      }
    });
  }

</script>