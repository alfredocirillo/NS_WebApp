<?php
require_once 'includes/db.php';
require_once 'includes/auth.php';

$error = '';

// Get product ID from URL parameter
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id === 0) {
    header('Location: index.php');
    exit;
}


// Fetch product details
$product_query = $conn->prepare('SELECT * FROM products WHERE id = ?');
$product_query->bind_param('i', $product_id);
$product_query->execute();
$product_result = $product_query->get_result();

if ($product_result->num_rows === 0) {
    header('Location: index.php');
    exit;
}

$product = $product_result->fetch_assoc();

// Handle review submission
$review_message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    if (!isset($_SESSION['user_id'])) {
        $review_message = '<div class="error">You must be logged in to submit a review.</div>';
    } else {
        $user_id = $_SESSION['user_id'];
        $rating = intval($_POST['rating']);
        $comment = trim($_POST['comment']);

        if (empty($comment)) {
            $review_message = '<div class="error">Please write a comment.</div>';
        } elseif ($rating < 1 || $rating > 5) {
            $review_message = '<div class="error">Rating must be between 1 and 5.</div>';
        } else {
            $insert_review = $conn->prepare('INSERT INTO reviews (product_id, user_id, rating, comment, created_at) VALUES (?, ?, ?, ?, NOW())');
            $insert_review->bind_param('iiis', $product_id, $user_id, $rating, $comment);

            if ($insert_review->execute()) {
                $review_message = '<div class="success">Review submitted successfully!</div>';
            } else {
                $review_message = '<div class="error">Error submitting review. Please try again.</div>';
            }
        }
    }
}

// Fetch reviews for this product
$reviews_query = $conn->prepare('SELECT r.*, u.name FROM reviews r JOIN users u ON r.user_id = u.id WHERE r.product_id = ? ORDER BY r.created_at DESC');
$reviews_query->bind_param('i', $product_id);
$reviews_query->execute();
$reviews_result = $reviews_query->get_result();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - Product Details</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* Product page specific styles */
        .product-header {
            margin-bottom: 30px;
        }

        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .back-link:hover {
            color: #2980b9;
        }

        .product-section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
            margin-bottom: 40px;
        }

        .product-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            align-items: start;
        }

        .product-image-wrapper {
            width: 100%;
        }

        .product-image {
            width: 100%;
            aspect-ratio: 1;
            background-color: #e0e0e0;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            color: #999;
            overflow: hidden;
        }

        .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .product-info h1 {
            margin-bottom: 15px;
            font-size: 28px;
        }

        .product-price {
            font-size: 26px;
            color: #27ae60;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .product-description {
            margin-bottom: 20px;
            color: #555;
            line-height: 1.6;
        }

        .product-details {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e0e0e0;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            font-size: 14px;
        }

        .detail-label {
            font-weight: 600;
            color: #2c3e50;
        }

        .detail-value {
            color: #666;
        }

        .stock-available {
            color: #27ae60;
            font-weight: 600;
        }

        .stock-unavailable {
            color: #e74c3c;
            font-weight: 600;
        }

        .add-to-cart-btn {
            width: 100%;
            padding: 12px 20px;
            background: #3498db;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease, transform 0.2s ease;
        }

        .add-to-cart-btn:hover:not(:disabled) {
            background: #2980b9;
            transform: translateY(-2px);
        }

        .add-to-cart-btn:disabled {
            background: #95a5a6;
            cursor: not-allowed;
            opacity: 0.6;
        }

        .reviews-section {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        }

        .reviews-section h2 {
            margin-bottom: 30px;
        }

        .login-prompt {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            color: #856404;
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 30px;
            font-size: 14px;
        }

        .login-prompt a {
            color: #3498db;
            text-decoration: none;
            font-weight: 600;
        }

        .login-prompt a:hover {
            text-decoration: underline;
        }

        .review-form {
            background: #f9f9f9;
            padding: 25px;
            border-radius: 8px;
            margin-bottom: 40px;
            border: 1px solid #e0e0e0;
        }

        .review-form h3 {
            margin-bottom: 20px;
        }

        .reviews-list {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .review-card {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #3498db;
        }

        .review-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .review-author {
            font-weight: 600;
            font-size: 15px;
            color: #2c3e50;
        }

        .review-date {
            color: #999;
            font-size: 13px;
        }

        .review-rating {
            color: #f39c12;
            font-size: 16px;
            margin-bottom: 10px;
            letter-spacing: 2px;
        }

        .review-comment {
            color: #555;
            line-height: 1.6;
            font-size: 14px;
        }

        .no-reviews {
            text-align: center;
            color: #999;
            padding: 40px 20px;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .product-content {
                grid-template-columns: 1fr;
                gap: 25px;
            }

            .product-section,
            .reviews-section {
                padding: 20px;
            }

            .product-info h1 {
                font-size: 22px;
            }

            .product-price {
                font-size: 22px;
            }

            .review-header {
                flex-direction: column;
                gap: 5px;
            }
        }

        @media (max-width: 480px) {
            .product-section,
            .reviews-section {
                padding: 15px;
            }

            .product-content {
                gap: 15px;
            }

            .product-info h1 {
                font-size: 20px;
            }

            .product-price {
                font-size: 20px;
            }

            .detail-item {
                font-size: 13px;
            }

            .review-card {
                padding: 15px;
            }
        }
    </style>
</head>
<body>
    <nav>
        <div class="nav-brand">ShopHub</div>
        <div>
            <a href="index.php">Home</a>
            <a href="products.php">Products</a>
            <?php if (isset($_SESSION['user_id'])): ?>
                <span><a href="profile.php">Profile</a></span>
                <span><a href="logout.php">Logout</a></span>
            <?php else: ?>
                <span><a href="login.php">Login</a></span>
            <?php endif; ?>
        </div>
    </nav>

    <div class="page-wrapper">
        <div class="container">
            <div class="product-header">
                <a href="index.php" class="back-link">← Back to Products</a>
            </div>

            <!-- Product Details Section -->
            <section class="product-section">
                <div class="product-content">
                    <div class="product-image-wrapper">
                        <div class="product-image">
                            <?php if (!empty($product['image'])): ?>
                                <img src="<?php echo htmlspecialchars($product['image']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>">
                            <?php else: ?>
                                <span>No image available</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="product-info">
                        <h1><?php echo htmlspecialchars($product['name']); ?></h1>
                        <div class="product-price">$<?php echo number_format($product['price'], 2); ?></div>
                        <p class="product-description"><?php echo htmlspecialchars($product['description']); ?></p>
                        
                        <div class="product-details">
                            <div class="detail-item">
                                <span class="detail-label">Stock:</span>
                                <span class="detail-value">
                                    <?php 
                                    $stock = intval($product['stock']);
                                    if ($stock > 0) {
                                        echo '<span class="stock-available">' . $stock . ' units available</span>';
                                    } else {
                                        echo '<span class="stock-unavailable">Out of stock</span>';
                                    }
                                    ?>
                                </span>
                            </div>
                        </div>

                        <button class="add-to-cart-btn" <?php echo $product['stock'] <= 0 ? 'disabled' : ''; ?>>
                            <?php echo $product['stock'] > 0 ? 'Add to Cart' : 'Out of Stock'; ?>
                        </button>
                    </div>
                </div>
            </section>

            <!-- Reviews Section -->
            <section class="reviews-section">
                <h2>Customer Reviews</h2>

                <?php if (!isset($_SESSION['user_id'])): ?>
                    <div class="login-prompt">
                        <strong>Want to leave a review?</strong> <a href="login.php">Log in</a> to share your experience.
                    </div>
                <?php else: ?>
                    <div class="review-form">
                        <h3>Add Your Review</h3>
                        <?php echo $review_message; ?>
                        <form method="POST">
                            <div class="form-group">
                                <label for="rating">Rating:</label>
                                <select id="rating" name="rating" required>
                                    <option value="">Select a rating</option>
                                    <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                                    <option value="4">⭐⭐⭐⭐ Good</option>
                                    <option value="3">⭐⭐⭐ Average</option>
                                    <option value="2">⭐⭐ Poor</option>
                                    <option value="1">⭐ Terrible</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="comment">Your Review:</label>
                                <textarea id="comment" name="comment" placeholder="Share your thoughts about this product..." required></textarea>
                            </div>
                            <button type="submit" name="submit_review" class="btn">Submit Review</button>
                        </form>
                    </div>
                <?php endif; ?>

                <div class="reviews-list">
                    <?php if ($reviews_result->num_rows > 0): ?>
                        <?php while ($review = $reviews_result->fetch_assoc()): ?>
                            <div class="review-card">
                                <div class="review-header">
                                    <span class="review-author"><?php echo htmlspecialchars($review['name']); ?></span>
                                    <span class="review-date"><?php echo date('M d, Y', strtotime($review['created_at'])); ?></span>
                                </div>
                                <div class="review-rating">
                                    <?php echo str_repeat('⭐', intval($review['rating'])); ?>
                                </div>
                                <p class="review-comment"><?php echo htmlspecialchars($review['comment']); ?></p>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="no-reviews">
                            <p>No reviews yet. Be the first to review this product!</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
