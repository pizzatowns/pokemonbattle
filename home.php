<?php  
    session_start();
    if (isset($_SESSION['user_data'])) {
        // $user_data = $_SESSION['user_data'];
        $user_data = $_SESSION['user_data'];
        $user_id = $user_data['name'];
        echo $user_id;
        echo "test";
    }
?>