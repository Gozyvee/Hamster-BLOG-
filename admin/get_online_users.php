<?php
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    
    //   $_SESSION['users_online'] = $count_user;

?>