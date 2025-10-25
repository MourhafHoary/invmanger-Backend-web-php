<?php
include "../../connect.php";

// قراءة بيانات JSON من Thunder Client
$input = json_decode(file_get_contents("php://input"), true);

// إذا لم تصل البيانات بشكل صحيح
if (!$input || !isset($input["items"])) {
    echo json_encode(["status" => "error", "message" => "البيانات المرسلة غير صحيحة"]);
    exit;
}

$worker_id = $input["worker_id"];
$items = $input["items"]; // يجب أن تكون مصفوفة

$total_amount = 0;
$total_quantity = 0;

// حساب الإجمالي
foreach ($items as $item) {
    $total_amount += $item['price'] * $item['quantity'];
    $total_quantity += $item['quantity'];
}

// إدخال الطلب الرئيسي
$orderData = [
    "orders_workerid" => $worker_id,
    "orders_quantity" => $total_quantity,
    "orders_totalamount" => $total_amount,
];

$order_id = insertData("orders", $orderData, true);

// إدخال تفاصيل كل منتج
foreach ($items as $item) {
    $itemData = [
        "order_id" => $order_id,
        "product_id" => $item['product_id'],
        "quantity" => $item['quantity'],
        "item_price" => $item['price'],
    ];
    insertData("order_items", $itemData);
}

echo json_encode([
    "status" => "success",
    "message" => "تم إنشاء الطلب بنجاح",
    "order_id" => $order_id
]);
?>
