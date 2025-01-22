<?php require 'layout/top.php'; ?>
<?php require 'layout/navbar.php'; ?>


<section id="profileview">
  <div class="container py-4">
    <div class="row">
        <!-- profile -->
        <div>
            <h2 class="mb-3 fw-bolder">PROFILE</h2>
        </div>
        <!-- left -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-body p-3 text-center">
                    <div class="p-5">
                        <img src="img/user/home-bg.png" alt="avatar" class="rounded-circle img-fluid w-100">
                    </div>
                    <h5 class="fw-bold" id="name"><?= $row['firstname'] ?> <?= $row['lastname'] ?></h5>
                </div>
            </div>
        </div>
        <!-- right -->
        <div class="col-lg-8">
            <div class="card">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <div class="col-6">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active w-100" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal-tab-pane" type="button" role="tab" aria-controls="personal-tab-pane" aria-selected="true">My Details</button>
                        </li>
                    </div>
                    <div class="col-6">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link w-100" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Settings</button>
                        </li>
                    </div>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <!-- My Details -->
                    <div class="tab-pane fade show active" id="personal-tab-pane" role="tabpanel" aria-labelledby="personal-tab" tabindex="0">
                        <div class="my-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Full Name</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0"><?= $row['firstname'] ?> <?= $row['lastname'] ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Email</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0"><?= $row['email'] ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Phone</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0"><?= $row['phone_number'] ?></p>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <p class="mb-0">Address</p>
                                    </div>
                                    <div class="col-sm-9">
                                        <p class="text-muted mb-0"><?= $row['address'] ?></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- settings -->
                    <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                        <form id="profileForm">
                            <!-- Form Body -->
                            <div class=" p-4">
                                <div class="row">
                                    <div class="form-group col-md-6 mb-3">
                                        <label for="firstName" class="form-label">First Name</label>
                                        <input type="text" id="firstName" class="form-control form-control-sm" placeholder="Enter your first name" value="<?= htmlspecialchars($row['firstname']) ?>">
                                    </div>
                                    
                                    <div class="form-group col-md-6 mb-3">
                                        <label for="lastName" class="form-label">Last Name</label>
                                        <input type="text" id="lastName" class="form-control form-control-sm" placeholder="Enter your last name" value="<?= htmlspecialchars($row['lastname']) ?>">
                                    </div>
                                    
                                    <div class="form-group col-md-6 mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <input type="text" id="address" class="form-control form-control-sm" placeholder="Enter your address" value="<?= htmlspecialchars($row['address']) ?>">
                                    </div>

                                    <div class="form-group col-md-6 mb-3">
                                        <label for="phonenumber" class="form-label">Phone Number</label>
                                        <input type="text" id="phonenumber" class="form-control form-control-sm" placeholder="Enter your phone number" value="<?= htmlspecialchars($row['phone_number']) ?>">
                                    </div>
                                </div>
                                
                                <!-- Submit Button -->
                                <div class="text-end">
                                    <button type="submit" class="btn bg-success-subtle btn-sm px-4 py-1">
                                        <small>UPDATE</small>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>



        <!-- cart -->
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-body">
                <h4 class="cart-title fw-bold">My Order</h4>
                <div class="table-responsive mt-4">
                    <table class="table  w-100" id="orderTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Transaction#</th>
                                <th>Status</th>
                                <th>Ongoing</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order) : ?>
                                <tr>
                                    <td><?= date('M d Y h:i', strtotime($order['created_at'])) ?></td>
                                    <td><?= $order['transaction_number'] ?></td>
                                    <td><?= $order['status'] === 1 ? '<span class="badge bg-success px-3">Paid</span>' : ''; ?></td>
                                    <td><?= $order['action'] === 1 ? '<span class="badge bg-success px-3">Arrvied</span>' : '<span class="badge bg-warning px-3">Ongoing</span>'; ?></td>
                                    <td>
                                    <a type="button" class="btn bg-primary-subtle btn-sm px-3 py-0" data-bs-toggle="modal" data-bs-target="#exampleModal<?= $order['transaction_number'] ?>">
                                        View Details
                                    </a>
                                    </td>
                                </tr>
                                <!-- modal -->
                                <div class="modal fade" id="exampleModal<?= $order['transaction_number'] ?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered modal-md">
                                        <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Order Summary</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="p-3">
                                            <h6><strong>Transaction Number:</strong> <?= htmlspecialchars($order['transaction_number']) ?></h6>
                                            <h6>
                                                <strong>Status:</strong> 
                                                <?= $order['status'] === 1 ? '<span class="badge bg-success">Paid</span>' : '<span class="badge bg-warning">Pending</span>'; ?>
                                            </h6>

                                            <div class="mt-5">
                                                <?php
                                                // Fetch product details for the current transaction
                                                $sqlDetails = "SELECT products.product_name, transaction_details.qty, transaction_details.price 
                                                            FROM transaction_details 
                                                            JOIN products ON transaction_details.product_id = products.product_id
                                                            WHERE transaction_id = ?";
                                                $stmtDetails = $conn->prepare($sqlDetails);
                                                $stmtDetails->bind_param("i", $order['transaction_id']);
                                                $stmtDetails->execute();
                                                $detailsResult = $stmtDetails->get_result();
                                                $total = 0;

                                                while ($detail = $detailsResult->fetch_assoc()) :
                                                    $subtotal = $detail['qty'] * $detail['price'];
                                                    $total += $subtotal;
                                                ?>
                                                <div class="border-top py-2">
                                                    <div class="d-flex justify-content-between">
                                                        <div><strong>Product:</strong> <?= htmlspecialchars($detail['product_name']) ?></div>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div><strong>Price:</strong> ₱<?= number_format($detail['price'], 2) ?></div>
                                                        <div><strong>Qty:</strong> <?= htmlspecialchars($detail['qty']) ?></div>
                                                    </div>
                                                    <div class="d-flex justify-content-between">
                                                        <div><strong>Subtotal:</strong> ₱<?= number_format($subtotal, 2) ?></div>
                                                    </div>
                                                </div>
                                                <?php endwhile; ?>
                                            </div>

                                            <div class="mt-3 border-top pt-3">
                                                <div class="d-flex justify-content-between">
                                                    <div><strong>Total:</strong></div>
                                                    <div class="fw-bold">₱<?= number_format($total, 2) ?></div>
                                                </div>
                                            </div>
                                        </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
                


                </div>
            </div>
        </div>
    </div>
  </div>
</section>

<script>
    $(document).ready(function () {
        $('#orderTable').DataTable();
    });
</script>




<?php require 'layout/bottom.php'; ?>
