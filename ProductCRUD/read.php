<?php
include 'db.php';

function readProducts($pdo) {
    $sql = "SELECT * FROM tblproduct";
    $stmt = $pdo->query($sql);

    echo "<h2>Product List</h2>";
    echo "<table border='1'>";
    echo "<tr><th>ID</th><th>Name</th><th>Description</th><th>Price</th><th>Quantity</th><th>Barcode</th></tr>";

    while ($row = $stmt->fetch()) {
        echo "<tr>";
        echo "<td>{$row['ID']}</td>";
        echo "<td>{$row['Name']}</td>";
        echo "<td>{$row['Description']}</td>";
        echo "<td>{$row['Price']}</td>";
        echo "<td>{$row['Quantity']}</td>";
        echo "<td>{$row['Bar-code']}</td>";
        echo "<td>{$row['created_at']}</td>";
        echo "<td>{$row['updated_at']}</td>";
        echo "</tr>";
    }

    echo "</table>";
}
?>
