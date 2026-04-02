<?php
session_start();
require_once 'includes/db.php'; // ✅ reuse connection

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$message = $_SESSION['message'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['message'], $_SESSION['error']);

// Fetch user
function getUser($conn, $user_id) {
    $stmt = $conn->prepare("SELECT id, username, name, email, profile_picture FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc();
}

$user = getUser($conn, $user_id);

// Handle POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $action = $_POST['action'] ?? '';

    // ✅ PROFILE UPDATE
    if ($action === "update_profile") {
        $username = trim($_POST['username']);
        $name = trim($_POST['name']);

        if (!$username || !$name) {
            $_SESSION['error'] = "All fields required.";
        } else {
            $stmt = $conn->prepare("SELECT id FROM users WHERE username=? AND id!=?");
            $stmt->bind_param("si", $username, $user_id);
            $stmt->execute();

            if ($stmt->get_result()->num_rows > 0) {
                $_SESSION['error'] = "Username already taken.";
            } else {
                $stmt = $conn->prepare("UPDATE users SET username=?, name=? WHERE id=?");
                $stmt->bind_param("ssi", $username, $name, $user_id);
                $stmt->execute();

                $_SESSION['message'] = "Profile updated!";
            }
        }
    }

    // ✅ PASSWORD UPDATE
    if ($action === "update_password") {
        $current = $_POST['current_password'];
        $new = $_POST['new_password'];
        $confirm = $_POST['confirm_password'];

        $stmt = $conn->prepare("SELECT password FROM users WHERE id=?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $hash = $stmt->get_result()->fetch_assoc()['password'];

        if (!password_verify($current, $hash)) {
            $_SESSION['error'] = "Wrong current password.";
        } elseif ($new !== $confirm) {
            $_SESSION['error'] = "Passwords don't match.";
        } elseif (strlen($new) < 6) {
            $_SESSION['error'] = "Password too short.";
        } else {
            $newHash = password_hash($new, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE users SET password=? WHERE id=?");
            $stmt->bind_param("si", $newHash, $user_id);
            $stmt->execute();

            $_SESSION['message'] = "Password updated!";
        }
    }

    // ✅ PROFILE PICTURE
    if ($action === "update_picture") {
        if (!empty($_FILES['profile_picture']['tmp_name'])) {

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $_FILES['profile_picture']['tmp_name']);

            $allowed = ['image/jpeg', 'image/png', 'image/gif'];

            if (!in_array($mime, $allowed)) {
                $_SESSION['error'] = "Invalid image type.";
            } else {
                //$file = 'uploads/' . uniqid() . '.jpg';
                $file = 'uploads/' . $_FILES['profile_picture']['name'];

                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $file)) {
                    $stmt = $conn->prepare("UPDATE users SET profile_picture=? WHERE id=?");
                    $stmt->bind_param("si", $file, $user_id);
                    $stmt->execute();

                    $_SESSION['message'] = "Picture updated!";
                } else {
                    $_SESSION['error'] = "Failed to upload picture.";
                }
            }
        }
    }

    header("Location: user_profile.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav>
        <div class="nav-brand">WebStore</div>
        <div>
            <a href="index.php">Home</a>
            <a href="logout.php">Logout</a>
        </div>
    </nav>    


    <div class="container" style="max-width: 800px;">
        <div class="profile-wrapper">
            <h1>My Profile</h1>

            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="profile-header">
                <div class="profile-picture-section">
                    <img src="<?php echo htmlspecialchars($user['profile_picture'] ?? 'uploads/default.png'); ?>" 
                         alt="Profile Picture" class="profile-picture">
                </div>

                <div class="user-info">
                    <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                </div>
            </div>
            <div>
                <button class="btn-change-picture" onclick="toggleModal()">Change Picture</button>  
            </div>
            <hr>
            <div class="tabs">
                <button class="tab-button" onclick="openTab(event, 'edit-profile')">Edit Personal Info</button>
                <button class="tab-button" onclick="openTab(event, 'change-password')">Change Password</button>
            </div>

            <!-- Edit Profile Tab -->
            <div id="edit-profile" class="tab-content active">
                <form method="POST" action="">
                    <input type="hidden" name="action" value="update_profile">
                    
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" id="username" name="username" 
                               value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="name">Full Name:</label>
                        <input type="text" id="name" name="name" 
                               value="<?php echo htmlspecialchars($user['name']); ?>" required>
                    </div>

                    <button type="submit" class="btn-submit">Update Profile</button>
                </form>
            </div>

            <!-- Change Password Tab -->
            <div id="change-password" class="tab-content">
                <form method="POST" action="">
                    <input type="hidden" name="action" value="update_password">
                    
                    <div class="form-group">
                        <label for="current_password">Current Password:</label>
                        <input type="password" id="current_password" name="current_password" required>
                    </div>

                    <div class="form-group">
                        <label for="new_password">New Password:</label>
                        <input type="password" id="new_password" name="new_password" required>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>

                    <button type="submit" class="btn-submit">Update Password</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Profile Picture Modal -->
    <div id="pictureModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="document.getElementById('pictureModal').style.display='none'">&times;</span>
            <h2>Upload Profile Picture</h2>
            <form method="POST" action="" enctype="multipart/form-data">
                <input type="hidden" name="action" value="update_picture">
                <div class="form-group">
                    <input type="file" id="profile_picture" name="profile_picture" accept="image/*" required>
                    <small>Supported formats: JPG, PNG, GIF (Max 5MB)</small>
                </div>
                <button type="submit" class="btn-submit">Change Picture</button>
            </form>
        </div>
    </div>

    <script src="js/user_profile.js"></script>
</body>
</html>
