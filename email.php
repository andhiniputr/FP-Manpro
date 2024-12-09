<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Pastikan PHPMailer terinstal melalui Composer
Dotenv\Dotenv::createImmutable(__DIR__)->load();
// Fungsi untuk mengirim email
function kirimEmailSMTP($emailPenerima, $subjek, $pesan)
{
    $mail = new PHPMailer(true);

    try {
        // Konfigurasi SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Ganti sesuai penyedia SMTP Anda
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['Username']; // Ambil Username dari .env
        $mail->Password = $_ENV['Password']; // Ambil Password dari .envPassword
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Pengaturan email
        $mail->setFrom('emailanda@gmail.com', 'Pengingat Tugas'); // Ganti dengan email dan nama pengirim
        $mail->addAddress($emailPenerima);

        // Konten email
        $mail->isHTML(true);
        $mail->Subject = $subjek;
        $mail->Body = $pesan;

        $mail->send();
        echo "Email berhasil dikirim ke $emailPenerima.<br>";
    } catch (Exception $e) {
        echo "Gagal mengirim email ke $emailPenerima. Error: {$mail->ErrorInfo}<br>";
    }
}

// Koneksi ke database
$conn = new mysqli("localhost", "root", "", "manajementugas");

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mendapatkan tugas yang memiliki sisa waktu 1 jam, 6 jam, atau 1 hari
$query = "
SELECT 
    tugas.ID_Tugas, 
    tugas.Judul, 
    tugas.Deadline, 
    Pengguna.Email,
    TIMESTAMPDIFF(MINUTE, NOW(), tugas.Deadline) AS Sisa_Waktu,
    tugas.Reminder_Sent
FROM 
    Tugas
JOIN 
    Pengguna ON tugas.ID_Pengguna = Pengguna.ID_Pengguna
WHERE 
    tugas.Status = 0
    AND (
        (TIMESTAMPDIFF(MINUTE, NOW(), tugas.Deadline) > 0 AND TIMESTAMPDIFF(MINUTE, NOW(), tugas.Deadline) <= 60) OR  -- 1 jam
        (TIMESTAMPDIFF(MINUTE, NOW(), tugas.Deadline) > 60 AND TIMESTAMPDIFF(MINUTE, NOW(), tugas.Deadline) <= 360) OR  -- 6 jam
        (TIMESTAMPDIFF(MINUTE, NOW(), tugas.Deadline) > 360 AND TIMESTAMPDIFF(MINUTE, NOW(), tugas.Deadline) <= 1440)   -- 1 hari
    )
";

// Jalankan query
$result = $conn->query($query);

// Cek apakah ada data yang ditemukan
if ($result->num_rows > 0) {
    echo "<h2>Tugas yang Mendekati Deadline</h2>";
    echo "<table border='1'>
            <tr>
                <th>ID Tugas</th>
                <th>Judul</th>
                <th>Deadline</th>
                <th>Email Penerima</th>
                <th>Sisa Waktu (Menit)</th>
            </tr>";

    // Menampilkan data tugas
    while ($row = $result->fetch_assoc()) {
        // Menampilkan data tugas
        echo "<tr>
                <td>" . $row['ID_Tugas'] . "</td>
                <td>" . $row['Judul'] . "</td>
                <td>" . $row['Deadline'] . "</td>
                <td>" . $row['Email'] . "</td>
                <td>" . $row['Sisa_Waktu'] . " menit</td>
              </tr>";

        // Cek apakah pengingat sudah dikirim untuk rentang waktu 1 jam, 6 jam, atau 1 hari
        $reminderSent = $row['Reminder_Sent'];
        $sendEmail = false;
        $reminderType = '';

        // Cek pengingat 1 jam
        if (strpos($reminderSent, '1') === false && $row['Sisa_Waktu'] <= 60) {
            $sendEmail = true;
            $reminderType = '1';
        }
        // Cek pengingat 6 jam
        elseif (strpos($reminderSent, '6') === false && $row['Sisa_Waktu'] > 60 && $row['Sisa_Waktu'] <= 360) {
            $sendEmail = true;
            $reminderType = '6';
        }
        // Cek pengingat 1 hari
        elseif (strpos($reminderSent, '24') === false && $row['Sisa_Waktu'] > 360 && $row['Sisa_Waktu'] <= 1440) {
            $sendEmail = true;
            $reminderType = '24';
        }

        // Kirim email jika perlu
        if ($sendEmail) {
            $emailPenerima = $row['Email'];
            $subjek = "Pengingat Tugas: " . $row['Judul'];
            $pesan = "
                <h2>Pengingat Tugas</h2>
                <p>Tugas Anda: <strong>" . $row['Judul'] . "</strong> akan jatuh tempo pada <strong>" . $row['Deadline'] . "</strong>.</p>
                <p>Mohon segera menyelesaikannya.</p>
            ";

            // Kirim email
            kirimEmailSMTP($emailPenerima, $subjek, $pesan);

            // Perbarui Reminder_Sent dengan menambahkan tipe pengingat yang sudah terkirim
            $newReminderSent = $reminderSent ? $reminderSent . ',' . $reminderType : $reminderType;
            $updateQuery = "
                UPDATE Tugas
                SET Reminder_Sent = '$newReminderSent'
                WHERE ID_Tugas = '" . $row['ID_Tugas'] . "'
            ";
            $conn->query($updateQuery);
        }
    }
    echo "</table>";
} else {
    echo "Tidak ada tugas yang mendekati deadline 1 jam, 6 jam, atau 1 hari.\n";
}

// Tutup koneksi
$conn->close();
?>