<?php

function handleGlobalError($exception) {
    // Handle the error, e.g., log it, display a user-friendly message, etc.
    echo 'Error: ' . $exception->getMessage();
}

?>
