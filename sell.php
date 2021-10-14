<?php

require_once('config/db_connection.php');

$error = '';
$stmt = $pdo->query('SELECT * FROM products');
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_POST['products'])) {
    $items = $_POST['products'];

    try {
        foreach ($items as $id => $value) {
            if ($value > 0) {
                $key = array_search($id, array_column($products, 'product_id'));
                $item = $products[$key];
                $stock = $item['stock'] - $value;

                $stmt = $pdo->prepare('INSERT INTO transactions (product_id, sell_stock, sell_price) VALUES(?, ?, ?)');
                $stmt->execute(array($id, $value, $item['price']));

                $stmt = $pdo->prepare('UPDATE products SET stock = ? WHERE product_id = ?');
                $stmt->execute(array($stock, $item['product_id']));
            }
        }

        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        $error = $e->getMessage();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management System - Sell</title>

    <link href="assets/style.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <?php if (!empty($error)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <form method="post" action="sell.php" class="m-3">
                    <table class="table table-hover sell">
                        <thead class="thead-light">
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Current Stock</th>
                                <th>Sell Quantity</th>
                            </tr>
                        </thead>
                        <?php foreach ($products as $product) : ?>
                            <?php if ($product['stock'] > 0) : ?>
                                <tr>
                                    <td><?php echo $product['name']; ?></td>
                                    <td><?php echo $product['price']; ?> <input type="hidden"></td>
                                    <td><?php echo $product['stock']; ?></td>
                                    <td><input type="number" name="products[<?php echo $product['product_id']; ?>]" class="form-control" placeholder="Sell" min="0" max="<?php echo $product['stock']; ?>" value="0" required /></td>
                                </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </table>

                    <div class="btn-group" role="group" aria-label="Basic example">
                        <button type="submit" class="btn btn-danger">Sell</button>
                        <a href="index.php" class="btn btn-light">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>