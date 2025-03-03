<?php
$current_page = basename($_SERVER['PHP_SELF']);

// ตรวจสอบว่าเป็น Admin หรือไม่
$isAdmin = (strtolower($_SESSION['group_name'] ?? '') === 'admin' || $_SESSION['is_admin'] ?? false);

// ถ้าเป็น Admin ให้เข้าถึงทุกหน้า
$permissions = $_SESSION['permissions'] ?? [];

function canAccess($page) {
  global $isAdmin, $permissions;
  
  // ถ้าเป็น Admin ให้เข้าถึงทุกหน้า
  if ($isAdmin) {
      return true;
  }

  // ตรวจสอบสิทธิ์เฉพาะหน้า
  return isset($permissions[$page]) && $permissions[$page]['access'] == 1;
}

?>

<!-- ======= Sidebar ======= -->
<aside id="sidebar" class="sidebar">

<ul class="sidebar-nav" id="sidebar-nav">

<?php if (canAccess('dashboard.php')): ?>
<li class="nav-item">
  <a class="nav-link <?= ($current_page == 'dashboard.php') ? 'active' : 'collapsed' ?>" href="dashboard.php">
    <i class="bi bi-pie-chart-fill nav-icon"></i>
    <span>Dashboard</span>
  </a>
</li><!-- End Dashboard Nav -->
<?php endif; ?>

<?php if (canAccess('all_product.php')): ?>
<li class="nav-item">
  <a class="nav-link <?= ($current_page == 'all_product.php') ? 'active' : 'collapsed' ?>" href="all_product.php">
    <i class="bi bi-archive-fill nav-icon"></i>
    <span>All Product</span>
  </a>
</li><!-- End All Product Nav -->
<?php endif; ?>

<?php
  $stock_pages = ['manage_stock.php', 'manage_balance.php','add_new_balance.php', 'add_product.php'];
  $is_stock_active = in_array($current_page, $stock_pages);
?>
<?php if (canAccess('manage_stock.php') || canAccess('manage_balance.php') || canAccess('add_new_balance.php') || canAccess('add_product.php')): ?>
<li class="nav-item <?= $is_stock_active ? 'menu-open' : '' ?>">
  <a class="nav-link <?= $is_stock_active ? 'active' : 'collapsed' ?>" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
    <i class="bi bi-clipboard-fill nav-icon"></i><span>Stock</span><i class="bi bi-chevron-down ms-auto"></i>
  </a>
  <ul id="components-nav" class="nav-content collapse <?= $is_stock_active ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
    <?php if (canAccess('manage_stock.php')): ?>
    <li>
      <a href="manage_stock.php" class="nav-link <?= ($current_page == 'manage_stock.php') ? 'active' : 'collapsed' ?>">
        <i class="bi bi-circle"></i><span>Manage Stock</span>
      </a>
    </li>
    <?php endif; ?>
    <?php if (canAccess('manage_balance.php')): ?>
    <li>
      <a href="manage_balance.php" class="nav-link <?= ($current_page == 'manage_balance.php') ? 'active' : 'collapsed' ?>">
        <i class="bi bi-circle"></i><span>Manage Balance</span>
      </a>
    </li>
    <?php endif; ?>
    <?php if (canAccess('add_new_balance.php')): ?>
    <li>
      <a href="add_new_balance.php" class="nav-link <?= ($current_page == 'add_new_balance.php') ? 'active' : 'collapsed' ?>">
        <i class="bi bi-circle"></i><span>Add Balance</span>
      </a>
    </li>
    <?php endif; ?>
    <?php if (canAccess('add_product.php')): ?>
    <li>
      <a href="add_product.php" class="nav-link <?= ($current_page == 'add_product.php') ? 'active' : 'collapsed' ?>">
        <i class="bi bi-circle"></i><span>Add & Delete Product</span>
      </a>
    </li>
    <?php endif; ?>
 
  </ul>
</li><!-- End Stock Nav -->
<?php endif; ?>

<?php if (canAccess('report.php')): ?>
<li class="nav-item">
  <a class="nav-link <?= ($current_page == 'report.php') ? 'active' : 'collapsed' ?>" href="report.php">
    <i class="bi bi-reception-4 nav-icon"></i>
    <span>Report</span>
  </a>
</li><!-- End Report Nav -->
<?php endif; ?>

<?php
  $user_master_pages = ['manage_user.php', 'manage_master.php'];
  $is_user_master_active = in_array($current_page, $user_master_pages);
?>
<?php if (canAccess('manage_user.php') || canAccess('manage_master.php')): ?>
<li class="nav-item <?= $is_user_master_active ? 'menu-open' : '' ?>">
  <a class="nav-link <?= $is_user_master_active ? 'active' : 'collapsed' ?>" data-bs-target="#forms-nav" data-bs-toggle="collapse" href="#">
    <i class="bi bi-clipboard-fill nav-icon"></i><span>User & Master</span><i class="bi bi-chevron-down ms-auto"></i>
  </a>
  <ul id="forms-nav" class="nav-content collapse <?= $is_user_master_active ? 'show' : '' ?>" data-bs-parent="#sidebar-nav">
    <?php if (canAccess('manage_user.php')): ?>
    <li>
      <a href="manage_user.php" class="nav-link <?= ($current_page == 'manage_user.php') ? 'active' : 'collapsed' ?>">
        <i class="bi bi-circle"></i><span>Manage Users</span>
      </a>
    </li>
    <?php endif; ?>
    <?php if (canAccess('manage_master.php')): ?>
    <li>
      <a href="manage_master.php" class="nav-link <?= ($current_page == 'manage_master.php') ? 'active' : 'collapsed' ?>">
        <i class="bi bi-circle"></i><span>Manage Master</span>
      </a>
    </li>
    <?php endif; ?>
  </ul>
</li><!-- End User & Master Nav -->
<?php endif; ?>

<?php if (canAccess('select_system.php')): ?>
<li class="nav-item">
  <a class="nav-link <?= ($current_page == 'select_system.php') ? 'active' : 'collapsed' ?>" href="select_system.php">
    <i class="bi bi-house-door-fill"></i>
    <span>Home</span>
  </a>
</li><!-- End Home Nav -->
<?php endif; ?>

</ul>

</aside><!-- End Sidebar-->