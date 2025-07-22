<?php
function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}

function redirect_if_not_logged_in(): void {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function sanitize(string $str): string {
    return htmlspecialchars(trim($str), ENT_QUOTES, 'UTF-8');
}
