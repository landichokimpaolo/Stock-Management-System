<?php

require_once('config/db_connection.php');

if (!isset($_GET['id'])) {
    die('Invalid Product');
}

$error = '';
$stmt = $pdo->prepare('SELECT * FROM products WHERE product_id = ?');
$stmt->execute(array($_GET['id']));
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (isset($_POST['stock'])) {
    try {
        $stmt = $pdo->prepare('UPDATE products SET stock = ?, price = ? WHERE product_id = ?');
        $stmt->execute(array($_POST['stock'], $_POST['price'], $_GET['id']));

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
    <title>Stock Management System - Update</title>

    <link href="assets/style.css" type="text/css" rel="stylesheet" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-4 offset-md-4">
                <?php if (!empty($error)) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error; ?>
                    </div>
                <?php endif; ?>

                <div class="card">
                    <h5 class="card-header"><?php echo $product['name']; ?></h5>
                    <form method="post" action="update.php?id=<?php echo $product['product_id']; ?>" class="m-3">
                        <div class="form-group">
                            <label>Stock</label>
                            <input type="number" name="stock" class="form-control" placeholder="Enter Stock" value="<?php echo $product['stock']; ?>" min="0" required />
                        </div>

                        <div class="form-group">
                            <label>Price</label>
                            <input type="text" name="price" class="form-control" placeholder="Enter Price" value="<?php echo $product['price'] ?>" />
                        </div>

                        <div class="btn-group" role="group" aria-label="Basic example">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="index.php" class="btn btn-light">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>