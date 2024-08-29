<?php
include 'db.php';

function deleteProductByName($pdo, $name) {
    if (empty($name)) {
        echo "<p>Error: Product name is required.</p>";
        return;
    }

    try {
        // Find the product ID by name
        $checkSql = "SELECT ID FROM tblproduct WHERE Name = :name";
        $stmt = $pdo->prepare($checkSql);
        $stmt->execute(['name' => $name]);
        $product = $stmt->fetch();

        if ($product) {
            $id = $product['ID'];
            $sql = "DELETE FROM tblproduct WHERE ID = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            echo "<p>Product deleted successfully!</p>";
        } else {
            echo "<p>Error: Product with the name '$name' does not exist.</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Error: " . htmlspecialchars($e->getMessage()) . "</p>";
    }
}

?>
