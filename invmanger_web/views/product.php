<?php
include "../../connect.php";

$catid = isset($_GET['catid']) ? intval($_GET['catid']) : 0;

$stmt = $con->prepare("SELECT * FROM products WHERE products_catid = ?");
$stmt->execute([$catid]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="ar">
<head>
  <meta charset="UTF-8">
  <title>إدارة المنتجات</title>
  <style>
    body {
      font-family: "Cairo", sans-serif;
      background: #f5f6fa;
      margin: 0;
      padding: 30px;
    }
    h1 { text-align: center; }
    .back {
      display: block;
      margin: 10px auto 30px;
      text-align: center;
      color: #3498db;
      text-decoration: none;
    }
    .grid {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
    }
    .card {
      background: white;
      border-radius: 10px;
      width: 250px;
      padding: 15px;
      box-shadow: 0 3px 6px rgba(0,0,0,0.1);
      text-align: center;
      transition: 0.3s;
    }
    .card img {
      width: 100%;
      height: 150px;
      object-fit: cover;
      border-radius: 8px;
    }
    .card h3 { margin: 10px 0 5px; }
    .card p { color: #888; margin: 0; }
    .btn {
      margin-top: 10px;
      padding: 8px 12px;
      border: none;
      cursor: pointer;
      border-radius: 5px;
      font-size: 14px;
    }
    .btn-edit { background: #3498db; color: white; }
    .btn-delete { background: #e74c3c; color: white; }
    .edit-form input, .edit-form textarea {
      width: 100%;
      margin: 5px 0;
      padding: 6px;
      border: 1px solid #ccc;
      border-radius: 4px;
    }
    .save-btn {
      background: #2ecc71;
      color: white;
      border: none;
      padding: 7px 12px;
      margin-top: 8px;
      border-radius: 5px;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <h1>📦 منتجات الصنف</h1>
  <a href="home.php" class="back">⬅️ العودة إلى الأصناف</a>

  <div class="grid">
    <?php if (count($products) > 0): ?>
      <?php foreach ($products as $pro): ?>
        <div class="card" id="card-<?php echo $pro['products_id']; ?>">
          <img src="../../uploads/<?php echo $pro['products_image']; ?>" alt="">
          <h3><?php echo $pro['products_name']; ?></h3>
          <p>💰 السعر: <?php echo $pro['products_price']; ?></p>

          <!-- btn-delete -->
          <button class="btn btn-edit" onclick="enableEdit(<?php echo $pro['products_id']; ?>)">✏️ تعديل</button>
          <form action="../../products/delete.php" method="post" style="display:inline;">
            <input type="hidden" name="proid" value="<?php echo $pro['products_id']; ?>">
            <input type="hidden" name="imagename" value="<?php echo $pro['products_image']; ?>">
            <button type="submit" class="btn btn-delete" onclick="return confirm('هل أنت متأكد من الحذف؟')">🗑 حذف</button>
          </form>

          <!-- edit form -->
          <div class="edit-form" id="edit-<?php echo $pro['products_id']; ?>" style="display:none;">
            <form action="../../products/edit.php" method="post" enctype="multipart/form-data">
              <input type="hidden" name="proid" value="<?php echo $pro['products_id']; ?>">
              <input type="hidden" name="imagename" value="<?php echo $pro['products_image']; ?>">
              
              <label>الاسم:</label>
              <input type="text" name="name" value="<?php echo $pro['products_name']; ?>">

              <label>الوصف:</label>
              <textarea name="desc"><?php echo $pro['products_desc']; ?></textarea>

              <label>الكمية:</label>
              <input type="number" name="quantity" value="<?php echo $pro['products_quantity']; ?>">

              <label>السعر:</label>
              <input type="text" name="price" value="<?php echo $pro['products_price']; ?>">

              <label> رقم الصنف:</label>
              <input type="text" name="catid" value="<?php echo $pro['products_catid']; ?>">

              <label>تغيير الصورة:</label>
              <input type="file" name="files">

              <button type="submit" class="save-btn">💾 حفظ</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p style="text-align:center;">🚫 لا توجد منتجات في هذا الصنف حالياً</p>
    <?php endif; ?>
  </div>

  <script>
    function enableEdit(id) {
      const form = document.getElementById("edit-" + id);
      form.style.display = form.style.display === "none" ? "block" : "none";
    }
  </script>
</body>
</html>
