<?php
class CSRF {
  public static function generateToken() {
    // Generate a new CSRF token or retrieve the existing one
    session_start();
    if (!isset($_SESSION['csrf_token'])) {
      $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
  }

  public static function verifyToken($token) {
    // Verify the CSRF token
    session_start();
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
      // CSRF token is invalid or missing
        return false;
    }
    return true;
  }
}
?>