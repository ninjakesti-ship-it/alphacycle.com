<?php include 'db_connect.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard - Cycle Store</title>
   <style>
  body {
    font-family: 'Segoe UI', sans-serif;
    background-color: #f9f9f9;
    padding: 30px;
    color: #333;
  }

  h1 {
    text-align: center;
    font-size: 32px;
    margin-bottom: 40px;
    color: #111;
  }

  h2 {
    font-size: 24px;
    margin-top: 40px;
    margin-bottom: 20px;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    background-color: white;
    box-shadow: 0 0 5px rgba(0,0,0,0.1);
  }

  th, td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #ddd;
  }

  th {
    background-color: #f0f0f0;
    font-weight: bold;
  }

  tr:hover {
    background-color: #f9f9f9;
  }

  form {
    display: flex;
    gap: 10px;
    align-items: center;
    margin-top: 5px;
  }

  input[type="text"],
  input[type="number"] {
    padding: 6px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 150px;
  }

  input[type="submit"] {
    background-color: #007bff;
    color: white;
    border: none;
    padding: 7px 12px;
    border-radius: 5px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s ease;
  }

  input[type="submit"]:hover {
    background-color: #0056b3;
  }

  .add-form label {
    display: block;
    margin-bottom: 10px;
  }

  .add-form input[type="text"],
  .add-form input[type="number"] {
    width: 300px;
  }

  .add-form {
    background-color: #fff;
    padding: 25px;
    border-radius: 8px;
    box-shadow: 0 0 5px rgba(0,0,0,0.1);
    width: 400px;
  }
  .form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 15px 20px;
  align-items: center;
}

.form-grid label {
  display: block;
  margin-bottom: 5px;
}

.form-grid input[type="text"],
.form-grid input[type="number"] {
  width: 100%;
  padding: 6px;
  border: 1px solid #ccc;
  border-radius: 5px;
}
h2 {
    font-size: 24px;
    margin-top: 40px;
    margin-bottom: 20px;
    text-align: center;   /* Center align table name */
    color: #222;
}

</style>

</head>
<body>
  <div style="position: absolute; top: 20px; left: 20px;">
    <a href="index.php" 
       style="background-color: #007bff; 
              color: white; 
              padding: 8px 15px; 
              border-radius: 5px; 
              text-decoration: none; 
              font-size: 14px;
              box-shadow: 0 2px 5px rgba(0,0,0,0.2);
              transition: background-color 0.3s ease;">
        ⬅ Logout
    </a>
</div>

<h1>Alpha Cycle - Admin Dashboard</h1>


<table>
    
   <?php
$tables = [
    "mountain"     => "Mountain Bikes",
    "ebikes"       => "ebikes Bikes",
    "road"         => "Road Bikes",
    "ch"           => "City & Hybrid",
    "ebikes"         => "ebikes Bikes",
    "ebikes"       => "Electric Bikes",
    "mountain"  => "mountain",
    "ebikes"     => "ebikes"
];

foreach ($tables as $table => $label) {
    echo "<h2>$label</h2>";
    echo "<table>
            <tr>
                <th>ID</th><th>Name</th><th>Price</th><th>Stock</th><th>Actions</th>
            </tr>";
    
    $result = $conn->query("SELECT * FROM `$table`");
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td>₹<?= $row['price'] ?></td>
                <td><?= $row['stock'] ?></td>
                <td>
                    <!-- Update Stock -->
                    <form action="update_stock.php" method="post" style="display:inline">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="table" value="<?= $table ?>">
                        <input type="number" name="stock" value="<?= $row['stock'] ?>" required>
                        <input type="submit" value="Update Stock">
                    </form>

                    <!-- Delete -->
                    <form action="delete_product.php" method="post" style="display:inline">
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="hidden" name="table" value="<?= $table ?>">
                        <input type="submit" value="Delete" onclick="return confirm('Are you sure?')">
                    </form>
                </td>
            </tr>
            <?php
        }
    } else {
        echo "<tr><td colspan='5'>No products found in $label</td></tr>";
    }
    echo "</table><br>";
}
?>


</table>

<!-- Add Product Button on Top Right -->
<div style="position: absolute; top: 20px; right: 20px;">
  <a href="add_product.php" 
     style="background-color: #28a745; 
            color: white; 
            padding: 10px 20px; 
            border-radius: 5px; 
            text-decoration: none; 
            font-size: 16px;
            font-weight: bold;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            transition: background-color 0.3s ease;">
    <span style="color: white;">➕</span> Add Product
  </a>
</div>




</body>
</html>
