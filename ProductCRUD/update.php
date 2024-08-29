<?php
include 'db.php';

function updateProduct($pdo, $id, $name, $description, $price, $quantity, $barcode) {
    try {
        $sql = "UPDATE tblproduct SET Name = :name, Description = :description, Price = :price, 
                Quantity = :quantity, `Bar-code` = :barcode WHERE ID = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'quantity' => $quantity,
            'barcode' => $barcode,
            'id' => $id
        ]);
        echo "<p>Product updated successfully!</p>";
    } catch (PDOException $e) {
        echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}
?>

