<?php
include "../../connect.php";

// Get category name
$catid = isset($_GET['catid']) ? intval($_GET['catid']) : 0;

$catStmt = $con->prepare("SELECT categories_name FROM categories WHERE categories_id = ?");
$catStmt->execute([$catid]);
$category = $catStmt->fetch(PDO::FETCH_ASSOC);
$categoryName = $category ? $category['categories_name'] : "صنف غير معروف";
// Fetch products for the given category
$catid = isset($_GET['catid']) ? intval($_GET['catid']) : 0;

$stmt = $con->prepare("SELECT * FROM products WHERE products_catid = ?");
$stmt->execute([$catid]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>إدارة المنتجات | نظام إدارة المخزون</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/home.css">
  <!-- Removed css/product.css to prevent body padding pushing navbar down -->
</head>
<body>
  <!-- Navbar (same as home.php) -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="home.php"><i class="bi bi-box-seam me-2"></i>نظام إدارة المخزون</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item d-lg-none">
            <a class="nav-link" href="#" id="mobileSidebarToggle"><i class="bi bi-list me-1"></i>القائمة الجانبية</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="home.php"><i class="bi bi-house-door me-1"></i>الرئيسية</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="product.php?catid=<?php echo $catid; ?>"><i class="bi bi-box me-1"></i>المنتجات</a>
          </li>
        </ul>
        <div class="d-flex">
          <a href="login.html" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right me-1"></i>تسجيل الخروج</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <h5><i class="bi bi-grid-fill me-2"></i>لوحة التحكم</h5>
    </div>
    <ul class="sidebar-menu">
      <li>
        <a href="home.php">
          <i class="bi bi-house-door-fill menu-icon"></i>
          <span class="menu-text">الرئيسية</span>
        </a>
      </li>
      <li>
        <a href="#" class="active">
          <i class="bi bi-box menu-icon"></i>
          <span class="menu-text">المنتجات</span>
        </a>
      </li>
      <li>
        <a href="#">
          <i class="bi bi-tags menu-icon"></i>
          <span class="menu-text">التصنيفات</span>
        </a>
      </li>
      <li>
        <a href="#">
          <i class="bi bi-people menu-icon"></i>
          <span class="menu-text">العملاء</span>
        </a>
      </li>
      <li>
        <a href="#">
          <i class="bi bi-truck menu-icon"></i>
          <span class="menu-text">الشحن</span>
        </a>
      </li>
      <li>
        <a href="#">
          <i class="bi bi-graph-up menu-icon"></i>
          <span class="menu-text">التقارير</span>
        </a>
      </li>
      <li>
        <a href="#">
          <i class="bi bi-gear menu-icon"></i>
          <span class="menu-text">الإعدادات</span>
        </a>
      </li>
    </ul>
  </div>

  <!-- Sidebar Toggle Button -->
  <button class="sidebar-toggle" id="sidebarToggle">
    <i class="bi bi-chevron-right" id="toggleIcon"></i>
  </button>

  <!-- Main Content -->
  <div class="main-content" id="mainContent">
    <!-- Page Header -->
    <div class="page-header">
      <div class="container d-flex align-items-center justify-content-between">
        <div>
          <h1 class="page-title"><i class="bi bi-box me-2"></i>قائمة المنتجات</h1>
          <p class="text-muted mb-0">إدارة منتجات هذا التصنيف</p>
        </div>
        <a href="home.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> الرجوع</a>
      </div>
    </div>
    <!-- Top Actions -->
  <div class="container mt-3 d-flex justify-content-end">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductsModal">
      <i class="bi bi-plus-circle me-1"></i> إضافة منتج
    </button>
  </div>

    <!-- Products Grid -->
    <div class="container my-4">
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
        <?php if (count($products) > 0): ?>
          <?php foreach ($products as $pro): ?>
            <div class="col">
              <div class="card h-100">
                <img src="../../uploads/<?php echo $pro['products_image']; ?>" class="card-img-top" alt="<?php echo $pro['products_name']; ?>">
                <div class="card-body">
                  <h5 class="card-title d-flex align-items-center justify-content-between">
                    <span><?php echo $pro['products_name']; ?></span>
                    <span class="badge bg-primary"><?php echo $pro['products_price']; ?> ر.س</span>
                  </h5>
                  <?php if (!empty($pro['products_desc'])): ?>
                    <p class="card-text text-muted" style="min-height: 2.5rem;">
                      <?php echo $pro['products_desc']; ?>
                    </p>
                  <?php endif; ?>
                </div>
                <div class="card-footer bg-transparent border-0 d-flex justify-content-end gap-2">
                  <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editProductModal"
                          onclick="prepareProductEditModal(<?php echo $pro['products_id']; ?>, '<?php echo htmlspecialchars($pro['products_name'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($pro['products_desc'], ENT_QUOTES); ?>', '<?php echo $pro['products_quantity']; ?>', '<?php echo $pro['products_price']; ?>', '<?php echo $pro['products_catid']; ?>', '<?php echo $pro['products_image']; ?>')">
                    <i class="bi bi-pencil-square me-1"></i>تعديل
                  </button>
                  <form action="../../products/delete.php" method="post" onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                    <input type="hidden" name="proid" value="<?php echo $pro['products_id']; ?>">
                    <input type="hidden" name="imagename" value="<?php echo $pro['products_image']; ?>">
                    <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash me-1"></i>حذف</button>
                  </form>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="col-12">
            <div class="text-center text-muted py-5">
              <i class="bi bi-emoji-frown" style="font-size: 2rem;"></i>
              <p class="mt-2">🚫 لا توجد منتجات في هذا الصنف حالياً</p>
              <a href="home.php" class="btn btn-primary mt-2"><i class="bi bi-arrow-left me-1"></i> الرجوع إلى التصنيفات</a>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer mt-5" id="footer">
    <div class="container">
      <p>© <?php echo date('Y'); ?> نظام إدارة المخزون | جميع الحقوق محفوظة</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Sidebar Toggle Script -->
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const sidebar = document.getElementById('sidebar');
      const mainContent = document.getElementById('mainContent');
      const footer = document.getElementById('footer');
      const sidebarToggle = document.getElementById('sidebarToggle');
      const toggleIcon = document.getElementById('toggleIcon');
      const mobileSidebarToggle = document.getElementById('mobileSidebarToggle');

      function toggleSidebar() {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
        footer.classList.toggle('expanded');
        sidebarToggle.classList.toggle('collapsed');

        if (sidebar.classList.contains('collapsed')) {
          toggleIcon.classList.remove('bi-chevron-right');
          toggleIcon.classList.add('bi-chevron-left');
        } else {
          toggleIcon.classList.remove('bi-chevron-left');
          toggleIcon.classList.add('bi-chevron-right');
        }
      }

      sidebarToggle.addEventListener('click', toggleSidebar);

      if (mobileSidebarToggle) {
        mobileSidebarToggle.addEventListener('click', function(e) {
          e.preventDefault();
          sidebar.classList.toggle('mobile-open');
        });
      }

      document.addEventListener('click', function(e) {
        const windowWidth = window.innerWidth;
        if (windowWidth < 992) {
          if (!sidebar.contains(e.target) && 
              e.target !== mobileSidebarToggle && 
              !mobileSidebarToggle.contains(e.target) &&
              sidebar.classList.contains('mobile-open')) {
            sidebar.classList.remove('mobile-open');
          }
        }
      });

      window.addEventListener('resize', function() {
        const windowWidth = window.innerWidth;
        if (windowWidth < 992) {
          sidebar.classList.remove('collapsed');
          mainContent.classList.remove('expanded');
          footer.classList.remove('expanded');
        }
      });
    });
  </script>

  <!-- Edit Product Modal -->
  <div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editProductModalLabel">تعديل المنتج</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editProductForm" action="../../products/edit.php" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
            <input type="hidden" id="proid" name="proid">
            <input type="hidden" id="imagename" name="imagename">

            <div class="row g-3">
              <div class="col-md-6">
                <label class="form-label" for="prod_name">الاسم</label>
                <input type="text" class="form-control" id="prod_name" name="name" required>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="prod_price">السعر</label>
                <input type="text" class="form-control" id="prod_price" name="price" required>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="prod_quantity">الكمية</label>
                <input type="number" class="form-control" id="prod_quantity" name="quantity" required>
              </div>
              <div class="mb-3">
              <label for="add_cate" class="form-label">اسم الصنف</label>
              <input type="text" class="form-control" id="add_cate" name="categories" value="<?php echo htmlspecialchars($categoryName); ?>" readonly>
              <input type="hidden" name="catid" value="<?php echo $catid; ?>">
            </div>
              <div class="col-12">
                <label class="form-label" for="prod_desc">الوصف</label>
                <textarea class="form-control" id="prod_desc" name="desc" rows="3"></textarea>
              </div>
              <div class="col-md-6">
                <label class="form-label" for="prod_file">تغيير الصورة</label>
                <input type="file" class="form-control" id="prod_file" name="files" accept="image/*">
              </div>
              <div class="col-md-6">
                <label class="form-label">الصورة الحالية</label>
                <img id="prod_currentImage" src="" alt="الصورة الحالية" class="img-thumbnail" style="max-height: 160px;">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            <button type="submit" class="btn btn-primary">حفظ</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function prepareProductEditModal(id, name, desc, quantity, price, catid, image) {
      document.getElementById('proid').value = id;
      document.getElementById('prod_name').value = name || '';
      document.getElementById('prod_desc').value = desc || '';
      document.getElementById('prod_quantity').value = quantity || '';
      document.getElementById('prod_price').value = price || '';
      document.getElementById('prod_catid').value = catid || '';
      document.getElementById('imagename').value = image || '';
      document.getElementById('prod_currentImage').src = '../../uploads/' + image;
      event.stopPropagation();
    }
  </script>
  <!-- Add Category Modal -->
  <div class="modal fade" id="addProductsModal" tabindex="-1" aria-labelledby="addProductsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addProductsModalLabel">إضافة منتج جديد</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addProductsForm" action="../../products/add.php" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="mb-3">
              <label for="add_name" class="form-label">اسم المنتج</label>
              <input type="text" class="form-control" id="add_name" name="name" required>
            </div>
            <div class="mb-3">
              <label for="add_name" class="form-label">وصف المنتج</label>
              <input type="text" class="form-control" id="add_desc" name="desc" required>
            </div>
            <div class="mb-3">
              <label for="add_quantity" class="form-label">الكمية</label>
              <input type="number" class="form-control" id="add_quantity" name="quantity" required>
            </div>
            <div class="mb-3">
              <label for="add_price" class="form-label">السعر</label>
              <input type="text" class="form-control" id="add_price" name="price" required>
            </div>
            <div class="mb-3">
              <label for="add_cate" class="form-label">اسم الصنف</label>
              <input type="text" class="form-control" id="add_cate" name="categories" value="<?php echo htmlspecialchars($categoryName); ?>" readonly>
              <input type="hidden" name="catid" value="<?php echo $catid; ?>">
            </div>

            <div class="mb-3">
              <label for="add_image" class="form-label">صورة المنتج</label>
              <input type="file" class="form-control" id="add_image" name="image" accept="image/*" required>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            <button type="submit" class="btn btn-primary">إضافة</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    // Reset add form when the modal opens
    document.addEventListener('DOMContentLoaded', function() {
      var addModalEl = document.getElementById('addCategoryModal');
      if (addModalEl) {
        addModalEl.addEventListener('show.bs.modal', function () {
          var form = document.getElementById('addCategoryForm');
          if (form) { form.reset(); }
        });
      }
    });
  </script>
</body>
</html>
