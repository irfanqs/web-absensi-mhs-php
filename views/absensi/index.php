<?php
$pageTitle = 'Riwayat Absensi';
require __DIR__ . '/../layouts/header.php';
?>

<div class="page-header">
    <h2>Data Absensi</h2>
    <p>Log daftar kehadiran pengguna / mahasiswa.</p>
</div>

<div class="card">
    <div style="overflow-x: auto;">
        <table class="table" style="width: 100%; border-collapse: collapse; text-align: left;">
            <thead>
                <tr style="border-bottom: 2px solid #ecf0f1;">
                    <th style="padding: 12px 16px;">No</th>
                    <th style="padding: 12px 16px;">Tanggal</th>
                    <th style="padding: 12px 16px;">Nama Mahasiswa</th>
                    <th style="padding: 12px 16px;">Jam Masuk</th>
                    <th style="padding: 12px 16px;">Jam Pulang</th>
                    <th style="padding: 12px 16px;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($attendances as $row): ?>
                <tr style="border-bottom: 1px solid #ecf0f1;">
                    <td style="padding: 12px 16px;"><?= $no++ ?></td>
                    <td style="padding: 12px 16px;"><?= htmlspecialchars(date('d-m-Y', strtotime($row['check_in'] ?? $row['created_at']))) ?></td>
                    <td style="padding: 12px 16px;"><?= htmlspecialchars($row['user_name']) ?></td>
                    <td style="padding: 12px 16px;"><?= $row['check_in'] ? date('H:i', strtotime($row['check_in'])) : '-' ?></td>
                    <td style="padding: 12px 16px;"><?= $row['check_out'] ? date('H:i', strtotime($row['check_out'])) : '-' ?></td>
                    <td style="padding: 12px 16px;">
                        <?php if ($row['status'] === 'Hadir'): ?>
                            <span class="badge" style="background:#27ae60; color:#fff; padding:4px 8px; border-radius:4px; font-size:12px;">Hadir</span>
                        <?php elseif ($row['status'] === 'Izin'): ?>
                            <span class="badge" style="background:#3498db; color:#fff; padding:4px 8px; border-radius:4px; font-size:12px;">Izin</span>
                        <?php elseif ($row['status'] === 'Sakit'): ?>
                            <span class="badge" style="background:#f1c40f; color:#fff; padding:4px 8px; border-radius:4px; font-size:12px;">Sakit</span>
                        <?php else: ?>
                            <span class="badge" style="background:#e74c3c; color:#fff; padding:4px 8px; border-radius:4px; font-size:12px;">Alpa</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>

                <?php if (empty($attendances)): ?>
                <tr>
                    <td colspan="6" style="padding: 16px; text-align: center; color: #7f8c8d;">Belum ada riwayat absensi.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/../layouts/sidebar.php'; ?>
