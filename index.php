<?php

require_once('config/db_connection.php');

$total = 0;

$stmt = $pdo->query('SELECT * FROM products');
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query('SELECT A.transaction_id, A.sell_price, A.sell_stock, A.product_id, B.name FROM transactions A LEFT JOIN products B ON A.product_id = B.product_id ORDER BY transaction_id');
$transactions = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management System</title>

    <link href="assets/style.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div class="container">
        <div class="mt-3 mb-3 text-right">
            <a href="sell.php" class="btn btn-danger">Sell</a>
        </div>

        <table class="table table-hover list">
            <thead class="thead-light">
                <tr>
                    <th>Product</th>
                    <th>Stock</th>
                    <th>Price</th>
                    <th></th>
                </tr>
            </thead>
            <?php foreach ($products as $product) : ?>
                <tr>
                    <td><?php echo $product['name']; ?></td>
                    <td><?php echo $product['stock']; ?></td>
                    <td><?php echo $product['price']; ?></td>
                    <td><a href="update.php?id=<?php echo $product['product_id']; ?>">update</a></td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="mt-5 mb-5">
            <hr />
        </div>

        <h3>Transaction History</h3>
        <div class="transaction">
            <table class="table table-hover transaction">
                <thead class="thead-light">
                    <tr>
                        <th>Transaction ID</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th></th>
                    </tr>
                </thead>
                <?php foreach ($transactions as $transaction) : ?>
                    <?php
                    $subtotal = $transaction['sell_stock'] * $transaction['sell_price'];
                    $total += $subtotal;
                    ?>
                    <tr>
                        <td><?php echo $transaction['transaction_id']; ?></td>
                        <td><?php echo $transaction['name']; ?></td>
                        <td><?php echo $transaction['sell_stock']; ?></td>
                        <td><?php echo $transaction['sell_price']; ?></td>
                        <td><?php echo number_format($subtotal, 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <th colspan="4" class="text-right">TOTAL SALE</th>
                    <th><u><?php echo number_format($total, 2); ?></u></th>
                </tr>
            </table>
        </div>
    </div>
</body>

</html>