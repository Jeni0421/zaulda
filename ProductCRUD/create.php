<?php
include 'db.php';
function createProduct($pdo, $name, $description, $price, $quantity, $barcode) {
    try {
        $sql = "INSERT INTO tblproduct (Name, Description, Price, Quantity, `Bar-code`) 
                VALUES (:name, :description, :price, :quantity, :barcode)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'quantity' => $quantity,
            'barcode' => $barcode
        ]);
        echo "<p>New product created successfully!</p>";
    } catch (PDOException $e) {
        echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>
