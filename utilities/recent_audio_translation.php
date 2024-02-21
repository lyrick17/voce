<?php 
if (isset($_SESSION['audio_time']) && (time() - $_SESSION['audio_time'] > 3600)) {
    // last request was more than 1 hour ago
    unset($_SESSION['recent_audio']);
    unset($_SESSION['audio_time']);
}
?>