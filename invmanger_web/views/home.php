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
    }
    
    .navbar {
      background-color: #2C7BE5;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .navbar-brand {
      font-weight: 700;
      color: white;
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
    }
    
    .footer p {
      color: #6C757D;
      margin-bottom: 0;
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
          <li class="nav-item">
            <a class="nav-link active" href="#"><i class="bi bi-house-door me-1"></i>الرئيسية</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="bi bi-box me-1"></i>المنتجات</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#"><i class="bi bi-tags me-1"></i>التصنيفات</a>
          </li>
        </ul>
        <div class="d-flex">
          <a href="login.html" class="btn btn-outline-light btn-sm"><i class="bi bi-box-arrow-right me-1"></i>تسجيل الخروج</a>
        </div>
      </div>
    </div>
  </nav>

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
            <div class="card category-card h-100" onclick="window.location.href='product.php?catid=<?php echo $cat['categories_id']; ?>'">
              <div class="position-relative">
                <img src="../../uploads/<?php echo $cat['categories_image']; ?>" class="card-img-top" alt="<?php echo $cat['categories_name']; ?>">
                <span class="category-badge"><?php echo $cat['categories_id']; ?>#</span>
              </div>
              <div class="card-body">
                <h5 class="card-title"><?php echo $cat['categories_name']; ?></h5>
                <p class="card-text"><?php echo $cat['categories_section']; ?></p>
              </div>
              <div class="card-footer bg-transparent border-0 text-end">
                <button class="btn btn-sm btn-primary"><i class="bi bi-eye me-1"></i>عرض المنتجات</button>
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

  <!-- Footer -->
  <footer class="footer mt-5">
    <div class="container">
      <p>© <?php echo date('Y'); ?> نظام إدارة المخزون | جميع الحقوق محفوظة</p>
    </div>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
