<?php 
include('conn.php');

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['idtugas'])) {
    $idtugas_upd = $_GET['idtugas'];
    $query = "DELETE FROM tugas WHERE ID_Tugas = '$idtugas_upd'";
    $result = mysqli_query(connection(), $query);
    header('Location: desc.php');
    }
}
?>