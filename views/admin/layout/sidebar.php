<aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">

  <li class="nav-item">
    <a class="nav-link fw-semi <?= urlIs('/dashboard') ? '' : 'collapsed' ?>" href="/dashboard" title="Dashboard" >
      <i class="bi bi-grid"></i>
      <span>Dashboard</span>
    </a>
  </li>

  <li class="nav-item">
    <a class="nav-link <?= urlIs('/manage-products') || urlIs('/manage-category') ? '' : 'collapsed' ?>" data-bs-target="#product-nav" data-bs-toggle="collapse" href="#">
      <i class="bi bi-menu-button-wide"></i><span>Products</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="product-nav" class="nav-content collapse <?= urlIs('/manage-products') || urlIs('/manage-category') ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
      <li>
        <a href="/manage-products" class="<?= urlIs('/manage-products') ? 'active' : '' ?>">
          <i class="bi bi-circle"></i><span>Manage Products</span>
        </a>
      </li>
      <li>
        <a href="/manage-category" class="<?= urlIs('/manage-category') ? 'active' : '' ?>">
          <i class="bi bi-circle"></i><span>Manage Category</span>
        </a>
      </li>
    </ul>
  </li>

  <!-- orders -->
  <li class="nav-item">
    <a class="nav-link <?= urlIs('/manage-orders') || urlIs('/manage-arrived-orders') ? '' : 'collapsed' ?>" data-bs-target="#order-nav" data-bs-toggle="collapse" href="#">
      <i class="bi bi-cart-fill"></i><span>Orders</span><i class="bi bi-chevron-down ms-auto"></i>
    </a>
    <ul id="order-nav" class="nav-content collapse <?= urlIs('/manage-orders') || urlIs('/manage-arrived-orders') ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
      <li>
        <a href="/manage-orders" class="<?= urlIs('/manage-orders') ? 'active' : '' ?>">
          <i class="bi bi-circle"></i><span>Manage Orders</span>
        </a>
      </li>
      <li>
        <a href="/manage-arrived-orders" class="<?= urlIs('/manage-arrived-orders') ? 'active' : '' ?>">
          <i class="bi bi-circle"></i><span>Manage Arrived Orders</span>
        </a>
      </li>
    </ul>
  </li>

</ul>

</aside><!-- End Sidebar-->