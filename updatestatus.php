<?php
include('conn.php');

if (isset($_POST['submit'])) {
    // Periksa apakah ada checkbox yang dipilih
    if (isset($_POST['taskIds']) && is_array($_POST['taskIds'])) {
        $taskIds = $_POST['taskIds'];

        // Loop melalui array taskIds untuk mengupdate status tugas
        foreach ($taskIds as $taskId) {
            $query = "UPDATE tugas SET status = 1 WHERE ID_Tugas = '$taskId'";
            mysqli_query(connection(), $query);
        }

        header('Location: history.php');
    } else {
        echo 'Tidak ada tugas yang dipilih.';
    }
}

?>