<?php
include "../../connect.php"; 

$stmt = $con->prepare("SELECT * FROM categories");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>الصفحة الرئيسية | نظام إدارة المخزون</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/home.css">
</head>
<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="#"><i class="bi bi-box-seam me-2"></i>نظام إدارة المخزون</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto">
          <li class="nav-item d-lg-none">
            <a class="nav-link" href="#" id="mobileSidebarToggle"><i class="bi bi-list me-1"></i>القائمة الجانبية</a>
          </li>
          <li class="nav-item">
            <a class="nav-link active" href="#"><i class="bi bi-house-door me-1"></i>الرئيسية</a>
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
        <a href="#" class="active">
          <i class="bi bi-house-door-fill menu-icon"></i>
          <span class="menu-text">الرئيسية</span>
        </a>
      </li>
      <li>
        <a href="#">
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
      <div class="container">
        <h1 class="page-title"><i class="bi bi-tags me-2"></i>قائمة التصنيفات</h1>
        <p class="text-muted">استعرض جميع التصنيفات المتاحة في النظام</p>
      </div>
  </div>

  <!-- Top Actions -->
  <div class="container mt-3 d-flex justify-content-end">
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
      <i class="bi bi-plus-circle me-1"></i> إضافة تصنيف
    </button>
  </div>

  <!-- Categories Grid -->
  <div class="container">
      <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
        <?php if (!empty($categories)) : ?>
          <?php foreach ($categories as $cat) : ?>
            <div class="col">
              <div class="card category-card h-100">
                <div class="position-relative" onclick="window.location.href='product.php?catid=<?php echo $cat['categories_id']; ?>'">
                  <img src="../../uploads/<?php echo $cat['categories_image']; ?>" class="card-img-top" alt="<?php echo $cat['categories_name']; ?>" style="cursor: pointer;">
                  <span class="category-badge"><?php echo $cat['categories_id']; ?>#</span>
                </div>
                <div class="card-body" onclick="window.location.href='product.php?catid=<?php echo $cat['categories_id']; ?>'" style="cursor: pointer;">
                  <h5 class="card-title"><?php echo $cat['categories_name']; ?></h5>
                  <p class="card-text"><?php echo $cat['categories_section']; ?></p>
                </div>
                <div class="card-footer bg-transparent border-0 text-end">
                  <button class="btn btn-sm btn-primary" onclick="window.location.href='product.php?catid=<?php echo $cat['categories_id']; ?>'"><i class="bi bi-eye me-1"></i>عرض المنتجات</button>
                  <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#editCategoryModal" 
                    onclick="prepareEditModal('<?php echo $cat['categories_id']; ?>', '<?php echo $cat['categories_name']; ?>', '<?php echo $cat['categories_image']; ?>')">
                    <i class="bi bi-pencil-square me-1"></i>تعديل
                  </button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else : ?>
          <div class="col-12">
            <div class="empty-state">
              <i class="bi bi-emoji-frown"></i>
              <p>لا توجد تصنيفات لعرضها حالياً</p>
              <button class="btn btn-primary mt-3"><i class="bi bi-plus-circle me-1"></i>إضافة تصنيف جديد</button>
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
      
      // Function to toggle sidebar
      function toggleSidebar() {
        sidebar.classList.toggle('collapsed');
        mainContent.classList.toggle('expanded');
        footer.classList.toggle('expanded');
        sidebarToggle.classList.toggle('collapsed');
        
        // Change toggle icon direction
        if (sidebar.classList.contains('collapsed')) {
          toggleIcon.classList.remove('bi-chevron-right');
          toggleIcon.classList.add('bi-chevron-left');
        } else {
          toggleIcon.classList.remove('bi-chevron-left');
          toggleIcon.classList.add('bi-chevron-right');
        }
      }
      
      // Toggle sidebar on button click
      sidebarToggle.addEventListener('click', toggleSidebar);
      
      // Mobile sidebar toggle
      if (mobileSidebarToggle) {
        mobileSidebarToggle.addEventListener('click', function(e) {
          e.preventDefault();
          sidebar.classList.toggle('mobile-open');
        });
      }
      
      // Close sidebar when clicking outside on mobile
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
      
      // Adjust on window resize
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

  <!-- Edit Category Modal -->
  <div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editCategoryModalLabel">تعديل التصنيف</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="editCategoryForm" action="../../categories/edit.php" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
            <input type="hidden" id="cateid" name="cateid">
            <input type="hidden" id="oldimage" name="oldimage">
            
            <div class="mb-3">
              <label for="name" class="form-label">اسم التصنيف</label>
              <input type="text" class="form-control" id="name" name="name" required>
            </div>
            
            <div class="mb-3">
              <label for="files" class="form-label">صورة التصنيف</label>
              <input type="file" class="form-control" id="files" name="files">
              <div class="form-text">اترك هذا الحقل فارغًا إذا كنت لا ترغب في تغيير الصورة</div>
            </div>
            
            <div class="mb-3">
              <label class="form-label">الصورة الحالية</label>
              <div>
                <img id="currentImage" src="" alt="الصورة الحالية" class="img-thumbnail" style="max-height: 150px;">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
            <button type="submit" class="btn btn-primary">حفظ التغييرات</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script>
    function prepareEditModal(categoryId, categoryName, categoryImage) {
      // Set values in the form
      document.getElementById('cateid').value = categoryId;
      document.getElementById('name').value = categoryName;
      document.getElementById('oldimage').value = categoryImage;
      document.getElementById('currentImage').src = '../../uploads/' + categoryImage;
      
      // Prevent the click from navigating to the products page
      event.stopPropagation();
    }
  </script>
  <!-- Add Category Modal -->
  <div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addCategoryModalLabel">إضافة تصنيف جديد</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <form id="addCategoryForm" action="../../categories/add.php" method="POST" enctype="multipart/form-data">
          <div class="modal-body">
            <div class="mb-3">
              <label for="add_name" class="form-label">اسم التصنيف</label>
              <input type="text" class="form-control" id="add_name" name="name" required>
            </div>
            <div class="mb-3">
              <label for="add_section" class="form-label">القسم</label>
              <input type="text" class="form-control" id="add_section" name="section" required>
            </div>
            <div class="mb-3">
              <label for="add_image" class="form-label">صورة التصنيف</label>
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
