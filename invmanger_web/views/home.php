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
  <style>
    body {
      font-family: "Cairo", sans-serif;
      background-color: #F8F9FA;
      margin: 0;
      padding: 0;
      direction: rtl;
      overflow-x: hidden;
    }
    
    .navbar {
      background-color: #2C7BE5;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
      position: fixed;
      width: 100%;
      z-index: 1030;
    }
    
    .navbar-brand {
      font-weight: 700;
      color: white;
    }
    
    /* Sidebar Styles */
    .sidebar {
      height: 100%;
      width: 250px;
      position: fixed;
      top: 56px;
      right: 0;
      background-color: #343A40;
      overflow-x: hidden;
      transition: 0.3s;
      padding-top: 20px;
      z-index: 1020;
    }
    
    .sidebar.collapsed {
      width: 60px;
    }
    
    .sidebar-header {
      padding: 0 15px 20px 15px;
      color: white;
      border-bottom: 1px solid rgba(255,255,255,0.1);
      margin-bottom: 15px;
      text-align: center;
    }
    
    .sidebar.collapsed .sidebar-header h5 {
      display: none;
    }
    
    .sidebar-menu {
      padding: 0;
      list-style: none;
    }
    
    .sidebar-menu li {
      margin-bottom: 5px;
    }
    
    .sidebar-menu a {
      padding: 10px 15px;
      color: rgba(255,255,255,0.8);
      display: flex;
      align-items: center;
      text-decoration: none;
      transition: 0.3s;
    }
    
    .sidebar-menu a:hover, .sidebar-menu a.active {
      color: white;
      background-color: rgba(255,255,255,0.1);
    }
    
    .sidebar-menu .menu-icon {
      margin-left: 10px;
      font-size: 1.2rem;
      min-width: 25px;
      text-align: center;
    }
    
    .sidebar-menu .menu-text {
      transition: opacity 0.3s;
    }
    
    .sidebar.collapsed .menu-text {
      display: none;
    }
    
    .sidebar-toggle {
      position: fixed;
      top: 70px;
      right: 250px;
      background-color: #343A40;
      color: white;
      border: none;
      border-radius: 50%;
      width: 30px;
      height: 30px;
      display: flex;
      align-items: center;
      justify-content: center;
      cursor: pointer;
      z-index: 1025;
      transition: 0.3s;
    }
    
    .sidebar-toggle.collapsed {
      right: 60px;
    }
    
    /* Main Content Adjustment */
    .main-content {
      margin-right: 250px;
      transition: 0.3s;
      padding-top: 56px;
    }
    
    .main-content.expanded {
      margin-right: 60px;
    }
    
    .page-header {
      background-color: white;
      padding: 1.5rem 0;
      margin-bottom: 2rem;
      box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    }
    
    .page-title {
      font-weight: 700;
      color: #343A40;
      margin-bottom: 0.5rem;
    }
    
    .category-card {
      border: none;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
      transition: all 0.3s ease;
      cursor: pointer;
      overflow: hidden;
      height: 100%;
    }
    
    .category-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }
    
    .category-card .card-img-top {
      height: 180px;
      object-fit: cover;
    }
    
    .category-card .card-body {
      padding: 1.25rem;
    }
    
    .category-card .card-title {
      font-weight: 600;
      margin-bottom: 0.5rem;
      color: #343A40;
    }
    
    .category-card .card-text {
      color: #6C757D;
      font-size: 0.9rem;
    }
    
    .category-badge {
      position: absolute;
      top: 10px;
      right: 10px;
      background-color: rgba(44, 123, 229, 0.9);
      color: white;
      padding: 0.25rem 0.75rem;
      border-radius: 50px;
      font-size: 0.8rem;
      font-weight: 500;
    }
    
    .empty-state {
      text-align: center;
      padding: 3rem;
      background-color: white;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    
    .empty-state i {
      font-size: 3rem;
      color: #6C757D;
      margin-bottom: 1rem;
    }
    
    .empty-state p {
      color: #6C757D;
      font-size: 1.1rem;
    }
    
    .footer {
      background-color: white;
      padding: 1.5rem 0;
      margin-top: 3rem;
      border-top: 1px solid #e0e0e0;
      text-align: center;
      transition: 0.3s;
      margin-right: 250px;
    }
    
    .footer.expanded {
      margin-right: 60px;
    }
    
    .footer p {
      color: #6C757D;
      margin-bottom: 0;
    }
    
    /* Responsive adjustments */
    @media (max-width: 992px) {
      .sidebar {
        width: 0;
        padding-top: 60px;
      }
      
      .sidebar.mobile-open {
        width: 250px;
      }
      
      .main-content, .footer {
        margin-right: 0;
      }
      
      .sidebar-toggle {
        right: 0;
      }
    }
  </style>
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
</body>
</html>
