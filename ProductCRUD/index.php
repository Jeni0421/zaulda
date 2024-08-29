<?php
include 'db.php'; 

// CRUD
function handleRequest($pdo) {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'] ?? null;
        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';
        $price = $_POST['price'] ?? '';
        $quantity = $_POST['quantity'] ?? '';
        $barcode = $_POST['barcode'] ?? '';
        $action = $_POST['action'] ?? '';

        // required 
        if (empty($name) || empty($description) || empty($price) || empty($quantity) || empty($barcode)) {
            echo "<p>Error: All fields are required.</p>";
            return;
        }

        //  price and quantity 
        if (!is_numeric($price) || !is_numeric($quantity)) {
            echo "<p>Error: Price and Quantity must be numeric.</p>";
            return;
        }

        // action
        if ($action == 'create') {
            createProduct($pdo, $name, $description, $price, $quantity, $barcode);
        } elseif ($action == 'update') {
            if ($id) {
                updateProduct($pdo, $id, $name, $description, $price, $quantity, $barcode);
            } else {
                echo "<p>Error: ID is required for update.</p>";
            }
        } elseif ($action == 'delete') {
            deleteProductByName($pdo, $name);
        }
    }
}

// Create 
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

// Update 
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

// Delete 
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

// Read 
// Read 
function readProducts($pdo) {
    $sql = "SELECT * FROM tblproduct";
    $stmt = $pdo->query($sql);

    echo "<h2>Product List</h2>";
    echo "<table border='1'>";
    echo "<tr>
            <th>ID</th>
            <th>Name</th>
            <th>Description</th>
            <th>Price</th>
            <th>Quantity</th>
            <th>Barcode</th>
            <th>Created At</th>
            <th>Updated At</th>
          </tr>";

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

// Handle the request, read products
handleRequest($pdo);
readProducts($pdo);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product CRUD</title>
</head>
<body>
    <h1>Product CRUD</h1>

    <!-- Form -->
    <form action="index.php" method="POST">
        <label for="id">ID:</label><br>
        <input type="number" id="id" name="id" min="1"><br><br>

        <label for="name">Name:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description" required></textarea><br><br>

        <label for="price">Price:</label><br>
        <input type="number" id="price" name="price" step="0.01" required><br><br>

        <label for="quantity">Quantity:</label><br>
        <input type="number" id="quantity" name="quantity" required><br><br>

        <label for="barcode">Barcode:</label><br>
        <input type="text" id="barcode" name="barcode" required><br><br>

        <input type="hidden" id="action" name="action" value="">

        <button type="button" onclick="handleAction('create')">Create</button>
        <button type="button" onclick="handleAction('update')">Update</button>
        <button type="button" onclick="handleAction('delete')">Delete</button>
    </form>

    <script>
        function handleAction(action) {
            document.getElementById('action').value = action;
            document.querySelector('form').submit();
        }
    </script>
</body>
</html>
