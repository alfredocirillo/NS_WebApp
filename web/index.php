<?php
    require_once 'includes/auth.php';
    require_once 'includes/functions.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebStore - Home</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-brand">WebStore</div>
        <div>
            <a href="index.php">Home</a>
            <?php if (is_logged_in()): ?>
                <span><a href="user_profile.php">Profile</a></span>
                <span><a href="logout.php">Logout</a></span>
            <?php else: ?>
                <a href="login.php">Login</a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="container">
        <div class="page-title">Welcome to our WebStore</div>
        <p style="text-align: center; font-size: 18px; margin-bottom: 40px;">By yours truly, DoubleA</p>
    </div>

    <!-- Featured Products Section -->
    <div class="container">
        <h2 style="font-size: 28px; margin-bottom: 30px;">Featured Products</h2>
        <div class="products-grid">
            <?php
            $products = get_all_products($conn);
            $featured = array_slice($products, 0, 6);
            foreach ($featured as $product):
            ?>
                <div class="product-card">
                    <img src="<?php echo htmlspecialchars($product['image'] ?? 'https://via.placeholder.com/250'); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" style="width: 100%; height: 200px; object-fit: cover; border-radius: 4px; margin-bottom: 15px;">
                    <h3><?php echo htmlspecialchars($product['name']); ?></h3>
                    <p><?php echo htmlspecialchars(substr($product['description'], 0, 50)) . '...'; ?></p>
                    <div class="product-price">$<?php echo number_format($product['price'], 2); ?></div>
                    <a href="products.php?id=<?php echo $product['id']; ?>" class="btn">View Details</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer style="background: rgba(0, 0, 0, 0.8); color: white; text-align: center; padding: 15px;">
        <p>&copy; Double A</p>
    </footer>

    <script src="js/script.js"></script>
</body>
</html>
