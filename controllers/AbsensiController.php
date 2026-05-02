<?php
require_once __DIR__ . '/../core/Auth.php';
require_once __DIR__ . '/../core/Database.php';

class AbsensiController {

    private Database $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function index(): void {
        // Cek jika tidak punya izin Dosen atau Mahasiswa
        if (!Auth::hasPermission('absensi.view_all') && !Auth::hasPermission('absensi.view_own')) {
            header('Location: ' . BASE_URL . '/index.php?page=dashboard');
            exit;
        }

        $userId = Auth::user()['id'];
        $canViewAll = Auth::hasPermission('absensi.view_all');

        if ($canViewAll) {
            // Jika Dosen/Admin: Lihat semua absensi
            $query = "SELECT a.*, u.name as user_name FROM attendances a JOIN users u ON a.user_id = u.id ORDER BY a.created_at DESC";
            $attendances = $this->db->fetchAll($query);
        } else {
            // Jika Mahasiswa: Hanya lihat punya sendiri
            $query = "SELECT a.*, u.name as user_name FROM attendances a JOIN users u ON a.user_id = u.id WHERE a.user_id = ? ORDER BY a.created_at DESC";
            $attendances = $this->db->fetchAll($query, [$userId]);
        }

        $pageTitle = 'Data Data Absensi';
        require __DIR__ . '/../views/absensi/index.php';
    }

    public function masuk(): void {
        Auth::requirePermission('absensi.create');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?page=dashboard');
            exit;
        }

        $userId = Auth::user()['id'];

        // Cek apakah hari ini sudah absen masuk
        $cek = $this->db->fetch('SELECT id FROM attendances WHERE user_id = ? AND DATE(check_in) = CURDATE()', [$userId]);

        if ($cek) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Anda sudah melakukan check-in hari ini.'];
        } else {
            $this->db->execute('INSERT INTO attendances (user_id, check_in, status) VALUES (?, NOW(), "Hadir")', [$userId]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Berhasil melakukan Check-in (Hadir) untuk hari ini.'];
        }

        header('Location: ' . BASE_URL . '/index.php?page=dashboard');
        exit;
    }

    public function pulang(): void {
        Auth::requirePermission('absensi.create');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?page=dashboard');
            exit;
        }

        $userId = Auth::user()['id'];

        // Pastikan harus ada absen masuk hari ini yang belum check_out
        $attendance = $this->db->fetch('SELECT id, check_out FROM attendances WHERE user_id = ? AND DATE(check_in) = CURDATE() ORDER BY id DESC LIMIT 1', [$userId]);

        if (!$attendance) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Anda belum melakukan check-in hari ini.'];
        } elseif ($attendance['check_out'] !== null) {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Anda sudah melakukan check-out hari ini.'];
        } else {
            $this->db->execute('UPDATE attendances SET check_out = NOW() WHERE id = ?', [$attendance['id']]);
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Berhasil melakukan Check-out (Pulang). Hati-hati di jalan!'];
        }

        header('Location: ' . BASE_URL . '/index.php?page=dashboard');
        exit;
    }
}
