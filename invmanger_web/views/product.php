<?php
include "../../connect.php"; 


$stmt = $con->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>الصفحة الرئيسية</title>
  <style>
    body {
      font-family: "Cairo", sans-serif;
      background: #f5f6fa;
      margin: 0;
      padding: 0;
    }
    h1 {
      text-align: center;
      margin-top: 20px;
    }
    .grid {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin: 40px;
    }
    .card {
      background: white;
      border-radius: 10px;
      width: 220px;
      padding: 15px;
      box-shadow: 0 3px 6px rgba(0,0,0,0.1);
      text-align: center;
    }
    .card img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      border-radius: 8px;
    }
    .card h3 {
      margin: 10px 0 5px;
    }
    .card p {
      color: #888;
      margin: 0;
    }
  </style>
</head>
<body>
  <h1>📦 قائمة المنتجات</h1>

  <div class="grid">
    <?php if (!empty($products)) : ?>
      <?php foreach ($products as $pro) : ?>
        <div class="card">
          <img src="../../uploads/<?php echo $pro['products_image']; ?>" alt="">
          <h3><?php echo $pro['products_name']; ?></h3>
          <p><?php echo $pro['products_price']; ?></p>
        </div>
      <?php endforeach; ?>
    <?php else : ?>
      <p>لا توجد تصنيفات لعرضها.</p>
    <?php endif; ?>
  </div>
</body>
</html>
