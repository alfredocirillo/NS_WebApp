<?php
session_start();

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function login_user($user_id, $username) {
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $username;
}

function logout_user() {
    session_destroy();
    header("Location: index.php");
    exit();
}
?>