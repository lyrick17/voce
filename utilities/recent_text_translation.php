<?php 
if (isset($_SESSION['text_time']) && (time() - $_SESSION['text_time'] > 3600)) {
    // last request was more than 1 hour ago
    unset($_SESSION['recent_text']);
    unset($_SESSION['text_time']);
}
?>