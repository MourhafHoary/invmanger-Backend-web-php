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
  <title>الصفحة الرئيسية</title>
  <style>
    body {
      font-family: "Cairo", sans-serif;
      background: #f5f6fa;
      margin: 0;
      padding: 0;
      direction: rtl;
    }
    h1 {
      text-align: center;
      margin: 20px;
    }
    .grid {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin: 30px;
    }
    .card {
      background: white;
      border-radius: 10px;
      width: 220px;
      padding: 15px;
      box-shadow: 0 3px 6px rgba(0,0,0,0.1);
      text-align: center;
      cursor: pointer;
      transition: 0.3s;
    }
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 5px 10px rgba(0,0,0,0.15);
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
      color: #777;
      margin: 0;
    }
  </style>
</head>
<body>

  <h1>📦 قائمة التصنيفات</h1>

  <div class="grid">
    <?php if (!empty($categories)) : ?>
      <?php foreach ($categories as $cat) : ?>
        <div class="card" onclick="window.location.href='product.php?catid=<?php echo $cat['categories_id']; ?>'">
          <img src="../../uploads/<?php echo $cat['categories_image']; ?>" alt="">
          <h3><?php echo $cat['categories_name']; ?></h3>
          <p><?php echo $cat['categories_section']; ?></p>
        </div>
      <?php endforeach; ?>
    <?php else : ?>
      <p>لا توجد تصنيفات لعرضها.</p>
    <?php endif; ?>
  </div>

</body>
</html>
