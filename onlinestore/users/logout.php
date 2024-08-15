
<?php
session_start();
session_unset();
session_destroy(); // Destroy all sessions

// Redirect to home page after logout
header("Location: ../index.html");
exit();
?>



