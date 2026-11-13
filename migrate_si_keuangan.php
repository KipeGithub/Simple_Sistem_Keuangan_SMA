<?php
/**
 * Script Migrasi Database & Seeding Sistem Keuangan SMA/SMK
 * Database: si_keuangan
 */

$host = '127.0.0.1';
$port = 3306;
$user = 'root';
$pass = '';
$dbname = 'si_keuangan';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    echo "=== MEMULAI MIGRASI DATABASE si_keuangan ===\n";

    // 1. Standarisasi Kolom Nominal pada tabel-tabel bawaan menjadi DECIMAL(15,2)
    $decimalUpdates = [
        'catering'    => 'nominal',
        'gaji'        => 'nominal',
        'pembayaran'  => 'nominal',
        'pendaftaran' => 'nominal',
        'pengeluaran' => 'nominal',
        'snack'       => 'nominal',
        'spp'         => 'nominal',
        'ujian'       => 'nominal',
        'lainnya'     => 'nominal',
    ];

    foreach ($decimalUpdates as $table => $column) {
        $check = $pdo->query("SHOW TABLES LIKE '$table'")->fetch();
        if ($check) {
            $pdo->exec("ALTER TABLE `$table` MODIFY `$column` DECIMAL(15,2) NOT NULL DEFAULT 0.00");
            echo "  [OK] Kolom `$table`.`$column` diubah menjadi DECIMAL(15,2)\n";
        }
    }

    // 2. Modifikasi Tabel users untuk Google OAuth & Peran Sekolah
    $userCols = $pdo->query("SHOW COLUMNS FROM `users`")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('google_id', $userCols)) {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `google_id` VARCHAR(100) NULL AFTER `password`");
        echo "  [OK] Kolom `users`.`google_id` ditambahkan\n";
    }
    if (!in_array('avatar', $userCols)) {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `avatar` VARCHAR(255) NULL AFTER `google_id`");
        echo "  [OK] Kolom `users`.`avatar` ditambahkan\n";
    }
    if (!in_array('auth_provider', $userCols)) {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `auth_provider` VARCHAR(50) NOT NULL DEFAULT 'local' AFTER `avatar`");
        echo "  [OK] Kolom `users`.`auth_provider` ditambahkan\n";
    }
    if (!in_array('parent_email', $userCols)) {
        $pdo->exec("ALTER TABLE `users` ADD COLUMN `parent_email` VARCHAR(100) NULL AFTER `auth_provider`");
        echo "  [OK] Kolom `users`.`parent_email` ditambahkan\n";
    }

    // 3. Modifikasi Tabel siswa untuk SMK (Jurusan, NISN, Email Ortu, Diskon)
    $siswaCols = $pdo->query("SHOW COLUMNS FROM `siswa`")->fetchAll(PDO::FETCH_COLUMN);
    if (!in_array('nisn', $siswaCols)) {
        $pdo->exec("ALTER TABLE `siswa` ADD COLUMN `nisn` VARCHAR(10) NULL AFTER `nis`");
        echo "  [OK] Kolom `siswa`.`nisn` ditambahkan\n";
    }
    if (!in_array('major_code', $siswaCols)) {
        $pdo->exec("ALTER TABLE `siswa` ADD COLUMN `major_code` VARCHAR(10) NOT NULL DEFAULT 'RPL' AFTER `nisn`");
        echo "  [OK] Kolom `siswa`.`major_code` ditambahkan\n";
    }
    if (!in_array('parent_email', $siswaCols)) {
        $pdo->exec("ALTER TABLE `siswa` ADD COLUMN `parent_email` VARCHAR(100) NULL AFTER `wali`");
        echo "  [OK] Kolom `siswa`.`parent_email` ditambahkan\n";
    }
    if (!in_array('scholarship_status', $siswaCols)) {
        $pdo->exec("ALTER TABLE `siswa` ADD COLUMN `scholarship_status` ENUM('REGULER','KIP_SEKOLAH','PRESTASI','YATIM_PIATU') NOT NULL DEFAULT 'REGULER' AFTER `parent_email`");
        echo "  [OK] Kolom `siswa`.`scholarship_status` ditambahkan\n";
    }
    if (!in_array('discount_percent', $siswaCols)) {
        $pdo->exec("ALTER TABLE `siswa` ADD COLUMN `discount_percent` DECIMAL(5,2) NOT NULL DEFAULT 0.00 AFTER `scholarship_status`");
        echo "  [OK] Kolom `siswa`.`discount_percent` ditambahkan\n";
    }
    if (!in_array('user_id', $siswaCols)) {
        $pdo->exec("ALTER TABLE `siswa` ADD COLUMN `user_id` INT(11) NULL AFTER `discount_percent`");
        echo "  [OK] Kolom `siswa`.`user_id` ditambahkan\n";
    }

    // 4. Tabel Jurusan SMK (4 Jurusan: RPL, TKJ, AKL, TKR)
    $pdo->exec("CREATE TABLE IF NOT EXISTS `jurusan` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `code` VARCHAR(10) NOT NULL UNIQUE,
        `name` VARCHAR(100) NOT NULL,
        `head_teacher` VARCHAR(100) NOT NULL,
        `monthly_spp` DECIMAL(15,2) NOT NULL DEFAULT 350000.00,
        `practice_fee` DECIMAL(15,2) NOT NULL DEFAULT 150000.00,
        `description` TEXT NULL,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "  [OK] Tabel `jurusan` siap\n";

    // 5. Tabel Tahun Ajaran
    $pdo->exec("CREATE TABLE IF NOT EXISTS `tahun_ajaran` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `year` VARCHAR(20) NOT NULL,
        `semester` ENUM('Ganjil','Genap') NOT NULL,
        `is_active` TINYINT(1) NOT NULL DEFAULT 0,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "  [OK] Tabel `tahun_ajaran` siap\n";

    // 6. Tabel Rekening Kas & Bank (Pemisahan Kas Bendahara, SPP/Yayasan, dan Dana BOS)
    $pdo->exec("CREATE TABLE IF NOT EXISTS `rekening_kas` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `code` VARCHAR(20) NOT NULL UNIQUE,
        `name` VARCHAR(100) NOT NULL,
        `bank_name` VARCHAR(100) NOT NULL,
        `account_number` VARCHAR(50) NOT NULL,
        `fund_type` ENUM('TUNAI','SPP_YAYASAN','DANA_BOS','TABUNGAN') NOT NULL,
        `balance` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `description` TEXT NULL,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "  [OK] Tabel `rekening_kas` siap\n";

    // 7. Tabel Anggaran RKAS (8 Standar Nasional Pendidikan)
    $pdo->exec("CREATE TABLE IF NOT EXISTS `rkas_anggaran` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `fiscal_year` VARCHAR(20) NOT NULL,
        `snp_code` VARCHAR(10) NOT NULL,
        `snp_name` VARCHAR(150) NOT NULL,
        `activity_code` VARCHAR(20) NOT NULL,
        `activity_name` VARCHAR(255) NOT NULL,
        `fund_source` ENUM('BOS_REGULER','BOS_KINERJA','YAYASAN_SPP','KOMITE') NOT NULL DEFAULT 'BOS_REGULER',
        `allocated_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `realized_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `remaining_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `status` ENUM('DRAFT','DISETUJUI','DITUTUP') NOT NULL DEFAULT 'DISETUJUI',
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "  [OK] Tabel `rkas_anggaran` siap\n";

    // 8. Tabel Realisasi Belanja RKAS & SPJ Dana BOS
    $pdo->exec("CREATE TABLE IF NOT EXISTS `rkas_belanja` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `expense_number` VARCHAR(50) NOT NULL UNIQUE,
        `budget_id` INT NOT NULL,
        `cash_account_code` VARCHAR(20) NOT NULL,
        `expense_date` DATE NOT NULL,
        `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `recipient` VARCHAR(150) NOT NULL,
        `description` TEXT NOT NULL,
        `proof_file` VARCHAR(255) NULL,
        `created_by` VARCHAR(100) NOT NULL,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "  [OK] Tabel `rkas_belanja` siap\n";

    // 9. Tabel Tabungan Siswa (Liabilitas Titipan ISAK 35)
    $pdo->exec("CREATE TABLE IF NOT EXISTS `tabungan_siswa` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `student_id` INT NOT NULL UNIQUE,
        `account_number` VARCHAR(30) NOT NULL UNIQUE,
        `balance` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `status` ENUM('AKTIF','DIBEKUKAN','DITUTUP') NOT NULL DEFAULT 'AKTIF',
        `opened_at` DATE NOT NULL,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "  [OK] Tabel `tabungan_siswa` siap\n";

    // 10. Tabel Mutasi Tabungan Siswa
    $pdo->exec("CREATE TABLE IF NOT EXISTS `tabungan_mutasi` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `savings_id` INT NOT NULL,
        `receipt_number` VARCHAR(50) NOT NULL UNIQUE,
        `transaction_type` ENUM('SETOR','TARIK') NOT NULL,
        `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `balance_after` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `cash_account_code` VARCHAR(20) NOT NULL,
        `description` TEXT NOT NULL,
        `transaction_date` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `created_by` VARCHAR(100) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "  [OK] Tabel `tabungan_mutasi` siap\n";

    // 11. Tabel Tagihan Siswa (SPP Massal, Praktik, Pendaftaran)
    $pdo->exec("CREATE TABLE IF NOT EXISTS `tagihan_siswa` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `bill_number` VARCHAR(50) NOT NULL UNIQUE,
        `student_id` INT NOT NULL,
        `academic_year` VARCHAR(20) NOT NULL,
        `bill_type` ENUM('SPP','PRAKTIK_SMK','PENDAFTARAN','LAINNYA') NOT NULL,
        `month` TINYINT(2) NULL,
        `year` INT(4) NULL,
        `title` VARCHAR(200) NOT NULL,
        `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `discount_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `net_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `paid_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `status` ENUM('UNPAID','PARTIAL','PAID') NOT NULL DEFAULT 'UNPAID',
        `due_date` DATE NOT NULL,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "  [OK] Tabel `tagihan_siswa` siap\n";

    // 12. Tabel Pembayaran Sekolah & Riwayat Kuitansi
    $pdo->exec("CREATE TABLE IF NOT EXISTS `pembayaran_sekolah` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `receipt_number` VARCHAR(50) NOT NULL UNIQUE,
        `bill_id` INT NOT NULL,
        `student_id` INT NOT NULL,
        `cash_account_code` VARCHAR(20) NOT NULL,
        `payment_date` DATE NOT NULL,
        `amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `payment_method` ENUM('TUNAI','TRANSFER_BANK','QRIS') NOT NULL DEFAULT 'TUNAI',
        `note` TEXT NULL,
        `received_by` VARCHAR(100) NOT NULL,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "  [OK] Tabel `pembayaran_sekolah` siap\n";

    // 13. Tabel Bagan Akun (CoA - ISAK 35 Entitas Nonlaba)
    $pdo->exec("CREATE TABLE IF NOT EXISTS `coa_akun` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `code` VARCHAR(20) NOT NULL UNIQUE,
        `name` VARCHAR(150) NOT NULL,
        `category` ENUM('ASET','LIABILITAS','ASET_NETO','PENDAPATAN','BEBAN') NOT NULL,
        `normal_balance` ENUM('DEBIT','KREDIT') NOT NULL,
        `is_active` TINYINT(1) NOT NULL DEFAULT 1,
        `description` TEXT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "  [OK] Tabel `coa_akun` siap\n";

    // 14. Tabel Jurnal Umum Double-Entry
    $pdo->exec("CREATE TABLE IF NOT EXISTS `jurnal_umum` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `entry_number` VARCHAR(50) NOT NULL UNIQUE,
        `transaction_date` DATE NOT NULL,
        `reference_type` VARCHAR(50) NOT NULL,
        `reference_id` INT NULL,
        `fund_source` ENUM('BOS','YAYASAN_SPP','TABUNGAN_SISWA','KOMITE') NOT NULL,
        `description` TEXT NOT NULL,
        `total_amount` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `created_by` VARCHAR(100) NOT NULL,
        `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "  [OK] Tabel `jurnal_umum` siap\n";

    // 15. Tabel Detail Jurnal Umum Berpasangan
    $pdo->exec("CREATE TABLE IF NOT EXISTS `jurnal_detail` (
        `id` INT AUTO_INCREMENT PRIMARY KEY,
        `journal_id` INT NOT NULL,
        `account_code` VARCHAR(20) NOT NULL,
        `debit` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `credit` DECIMAL(15,2) NOT NULL DEFAULT 0.00,
        `note` VARCHAR(255) NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "  [OK] Tabel `jurnal_detail` siap\n";

    // 16. SEED DATA AWAL: Jurusan SMK
    $pdo->exec("INSERT INTO `jurusan` (`code`, `name`, `head_teacher`, `monthly_spp`, `practice_fee`, `description`) VALUES
        ('RPL', 'Rekayasa Perangkat Lunak', 'Drs. Hendra Setiawan, M.Kom', 350000.00, 150000.00, 'Kompetensi Keahlian Pemrograman Web, Mobile, dan Cloud Computing'),
        ('TKJ', 'Teknik Komputer dan Jaringan', 'Ir. M. Ridwan, M.T', 350000.00, 150000.00, 'Kompetensi Keahlian Jaringan Komputer, Cisco, Mikrotik, dan Server Linux'),
        ('AKL', 'Akuntansi dan Keuangan Lembaga', 'Hj. Ratna Dewi, S.E., M.Ak', 300000.00, 100000.00, 'Kompetensi Keahlian Pembukuan, Komputer Akuntansi MYOB/Spreadsheet, dan Perpajakan'),
        ('TKR', 'Teknik Kendaraan Ringan Otomotif', 'Bambang Supriyanto, S.Pd', 350000.00, 200000.00, 'Kompetensi Keahlian Otomotif Roda 4, Electronic Fuel Injection, dan Servis Berkala')
        ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `monthly_spp`=VALUES(`monthly_spp`), `practice_fee`=VALUES(`practice_fee`)");
    echo "  [OK] Seeding Jurusan SMK selesai\n";

    // 17. SEED DATA AWAL: Tahun Ajaran
    $pdo->exec("INSERT INTO `tahun_ajaran` (`year`, `semester`, `is_active`) VALUES
        ('2026/2027', 'Ganjil', 1),
        ('2026/2027', 'Genap', 0)
        ON DUPLICATE KEY UPDATE `is_active`=VALUES(`is_active`)");
    echo "  [OK] Seeding Tahun Ajaran selesai\n";

    // 18. SEED DATA AWAL: Rekening Kas & Bank
    $pdo->exec("INSERT INTO `rekening_kas` (`code`, `name`, `bank_name`, `account_number`, `fund_type`, `balance`, `description`) VALUES
        ('1-1101', 'Kas Tunai Bendahara Sekolah', 'KAS TUNAI', '-', 'TUNAI', 18500000.00, 'Penerimaan harian tunai SPP dan titipan tabungan siswa'),
        ('1-1201', 'Bank Giro Operasional SPP & Yayasan', 'Bank BNI', '0234-5678-901', 'SPP_YAYASAN', 125000000.00, 'Rekening operasional SPP, Uang Pangkal, dan Gaji Pegawai'),
        ('1-1202', 'Bank Rekening Khusus Dana BOS', 'Bank BJB', '1002-3344-556', 'DANA_BOS', 275000000.00, 'Rekening penyaluran resmi Dana BOS Reguler Kemendikbudristek')
        ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `balance`=VALUES(`balance`)");
    echo "  [OK] Seeding Rekening Kas selesai\n";

    // 19. SEED DATA AWAL: Bagan Akun (CoA - ISAK 35)
    $accounts = [
        ['1-1101', 'Kas Tunai Bendahara', 'ASET', 'DEBIT'],
        ['1-1201', 'Bank Operasional SPP & Yayasan', 'ASET', 'DEBIT'],
        ['1-1202', 'Bank Penampung Dana BOS Reguler', 'ASET', 'DEBIT'],
        ['1-1301', 'Piutang SPP & Biaya Sekolah Siswa', 'ASET', 'DEBIT'],
        ['1-1501', 'Peralatan & Laboratorium Komputer SMK', 'ASET', 'DEBIT'],
        ['2-1101', 'Utang Operasional & Belanja Barang', 'LIABILITAS', 'KREDIT'],
        ['2-1201', 'Liabilitas Titipan Tabungan Siswa', 'LIABILITAS', 'KREDIT'],
        ['3-1101', 'Aset Neto Tanpa Pembatasan (Yayasan/SPP)', 'ASET_NETO', 'KREDIT'],
        ['3-1201', 'Aset Neto Dengan Pembatasan (Dana BOS Pemerintah)', 'ASET_NETO', 'KREDIT'],
        ['4-1101', 'Pendapatan SPP Bulanan Siswa', 'PENDAPATAN', 'KREDIT'],
        ['4-1201', 'Pendapatan Biaya Praktik Kejuruan SMK', 'PENDAPATAN', 'KREDIT'],
        ['4-1301', 'Pendapatan Pendaftaran / Uang Pangkal', 'PENDAPATAN', 'KREDIT'],
        ['4-2101', 'Pendapatan Dana BOS Reguler Pemerintah', 'PENDAPATAN', 'KREDIT'],
        ['5-1101', 'Beban Praktik Kejuruan & Workshop SMK', 'BEBAN', 'DEBIT'],
        ['5-1201', 'Beban Pemeliharaan Sarpras & Komputer', 'BEBAN', 'DEBIT'],
        ['5-1301', 'Beban Honor & Gaji Pendidik / Tenaga Kependidikan', 'BEBAN', 'DEBIT'],
        ['5-1401', 'Beban Kegiatan Pembelajaran & Ekstrakurikuler', 'BEBAN', 'DEBIT'],
        ['5-1501', 'Beban Administrasi Bank & Operasional Kantor', 'BEBAN', 'DEBIT'],
    ];

    $stmtCoa = $pdo->prepare("INSERT INTO `coa_akun` (`code`, `name`, `category`, `normal_balance`) VALUES (?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `category`=VALUES(`category`), `normal_balance`=VALUES(`normal_balance`)");
    foreach ($accounts as $acc) {
        $stmtCoa->execute($acc);
    }
    echo "  [OK] Seeding Bagan Akun (CoA ISAK 35) selesai\n";

    // 20. SEED DATA AWAL: Pagu RKAS (8 SNP)
    $rkas = [
        ['2026/2027', 'SNP-01', 'Standar Kompetensi Lulusan', 'RKAS-01-01', 'Uji Kompetensi Keahlian (UKK) Siswa SMK RPL & TKJ', 'BOS_REGULER', 45000000.00, 15000000.00, 30000000.00],
        ['2026/2027', 'SNP-02', 'Standar Isi', 'RKAS-02-01', 'Pengembangan Kurikulum Merdeka & Modul Ajar SMK', 'BOS_REGULER', 20000000.00, 5000000.00, 15000000.00],
        ['2026/2027', 'SNP-03', 'Standar Proses', 'RKAS-03-01', 'Pengadaan Bahan Praktik Kejuruan Otomotif & Jaringan', 'BOS_REGULER', 75000000.00, 28000000.00, 47000000.00],
        ['2026/2027', 'SNP-04', 'Standar Penilaian Pendidikan', 'RKAS-04-01', 'Pelaksanaan Asesmen Sumatif & Ujian Semester Berbasis Komputer', 'BOS_REGULER', 35000000.00, 12000000.00, 23000000.00],
        ['2026/2027', 'SNP-05', 'Standar Pendidik & Tenaga Kependidikan', 'RKAS-05-01', 'Peningkatan Kompetensi Guru & Sertifikasi BNSP', 'BOS_REGULER', 30000000.00, 10000000.00, 20000000.00],
        ['2026/2027', 'SNP-06', 'Standar Sarana & Prasarana', 'RKAS-06-01', 'Upgrade PC Lab Komputer & Server Jaringan SMK', 'BOS_REGULER', 95000000.00, 42000000.00, 53000000.00],
        ['2026/2027', 'SNP-07', 'Standar Pengelolaan', 'RKAS-07-01', 'Langganan Sistem Informasi Sekolah & Manajemen Keuangan', 'BOS_REGULER', 25000000.00, 8500000.00, 16500000.00],
        ['2026/2027', 'SNP-08', 'Standar Pembiayaan', 'RKAS-08-01', 'Operasional Listrik, Internet Dedicated, dan ATK Sekolah', 'BOS_REGULER', 50000000.00, 19500000.00, 30500000.00],
    ];

    $stmtRkas = $pdo->prepare("INSERT INTO `rkas_anggaran` (`fiscal_year`, `snp_code`, `snp_name`, `activity_code`, `activity_name`, `fund_source`, `allocated_amount`, `realized_amount`, `remaining_amount`, `status`)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'DISETUJUI')
        ON DUPLICATE KEY UPDATE `allocated_amount`=VALUES(`allocated_amount`), `realized_amount`=VALUES(`realized_amount`), `remaining_amount`=VALUES(`remaining_amount`)");
    foreach ($rkas as $r) {
        $stmtRkas->execute($r);
    }
    echo "  [OK] Seeding Pagu RKAS (8 SNP) selesai\n";

    // 21. SEED DATA AWAL: Pengguna Demo 4 Peran Sekolah
    $passwordHash = password_hash('password', PASSWORD_DEFAULT);
    $usersDemo = [
        [
            'id' => 101,
            'email' => 'admin@smkn1akuntansi.sch.id',
            'name'  => 'Bpk. Bambang Irawan, S.Kom (Admin TU & IT)',
            'password' => $passwordHash,
            'role' => 1, // Admin TU
            'active' => '1',
            'gambar' => 'user.png',
            'auth_provider' => 'local',
        ],
        [
            'id' => 102,
            'email' => 'bendahara@smkn1akuntansi.sch.id',
            'name'  => 'Ibu Siti Rahmawati, S.E (Bendahara Sekolah)',
            'password' => $passwordHash,
            'role' => 2, // Bendahara Sekolah
            'active' => '1',
            'gambar' => 'user.png',
            'auth_provider' => 'local',
        ],
        [
            'id' => 103,
            'email' => 'kepsek@smkn1akuntansi.sch.id',
            'name'  => 'Drs. H. Ahmad Fauzi, M.Pd (Kepala Sekolah)',
            'password' => $passwordHash,
            'role' => 3, // Kepala Sekolah
            'active' => '1',
            'gambar' => 'user.png',
            'auth_provider' => 'local',
        ],
        [
            'id' => 104,
            'email' => 'wali.rizky@gmail.com',
            'name'  => 'Bpk. Hendra Pratama (Wali Siswa)',
            'password' => $passwordHash,
            'role' => 4, // Wali Murid / Siswa
            'active' => '1',
            'gambar' => 'user.png',
            'auth_provider' => 'local',
            'parent_email' => 'wali.rizky@gmail.com',
        ],
    ];

    $stmtUser = $pdo->prepare("INSERT INTO `users` (`id`, `email`, `name`, `password`, `role`, `active`, `gambar`, `auth_provider`, `parent_email`)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `password`=VALUES(`password`), `role`=VALUES(`role`), `active`=VALUES(`active`), `auth_provider`=VALUES(`auth_provider`), `parent_email`=VALUES(`parent_email`)");
    foreach ($usersDemo as $u) {
        $stmtUser->execute([
            $u['id'], $u['email'], $u['name'], $u['password'], $u['role'], $u['active'], $u['gambar'], $u['auth_provider'], $u['parent_email'] ?? null
        ]);
    }
    echo "  [OK] Seeding 4 Pengguna Demo Sekolah selesai (Password: 'password')\n";

    // 22. SEED DATA AWAL: Siswa SMK & Kaitan Wali Murid
    $siswaDemo = [
        [
            'id' => 1001,
            'name' => 'Muhammad Rizky Pratama',
            'nis' => '242510001',
            'nisn' => '0061234567',
            'major_code' => 'RPL',
            'sex' => 'Pria',
            'status' => 'Aktif',
            'wali' => 'Bpk. Hendra Pratama',
            'parent_email' => 'wali.rizky@gmail.com',
            'scholarship_status' => 'REGULER',
            'discount_percent' => 0.00,
            'tempat' => 'Jakarta',
            'tanggal' => '2008-05-14',
            'alamat' => 'Jl. Pendidikan Kejuruan No. 12, Jakarta',
            'kelas' => 1,
            'user_id' => 104,
        ],
        [
            'id' => 1002,
            'name' => 'Aulia Salsabila Nurhaliza',
            'nis' => '242510002',
            'nisn' => '0067654321',
            'major_code' => 'AKL',
            'sex' => 'Wanita',
            'status' => 'Aktif',
            'wali' => 'Bpk. Ahmad Sobari',
            'parent_email' => 'wali.aulia@gmail.com',
            'scholarship_status' => 'KIP_SEKOLAH',
            'discount_percent' => 50.00,
            'tempat' => 'Bogor',
            'tanggal' => '2008-08-20',
            'alamat' => 'Jl. Pajajaran Indah No. 45, Bogor',
            'kelas' => 1,
            'user_id' => null,
        ],
        [
            'id' => 1003,
            'name' => 'Dimas Bagas Prakoso',
            'nis' => '242510003',
            'nisn' => '0069876543',
            'major_code' => 'TKJ',
            'sex' => 'Pria',
            'status' => 'Aktif',
            'wali' => 'Ibu Maria Handayani',
            'parent_email' => 'wali.dimas@gmail.com',
            'scholarship_status' => 'REGULER',
            'discount_percent' => 0.00,
            'tempat' => 'Depok',
            'tanggal' => '2008-03-10',
            'alamat' => 'Jl. Margonda Raya No. 88, Depok',
            'kelas' => 2,
            'user_id' => null,
        ],
        [
            'id' => 1004,
            'name' => 'Fajar Bayu Pratama',
            'nis' => '242510004',
            'nisn' => '0063456789',
            'major_code' => 'TKR',
            'sex' => 'Pria',
            'status' => 'Aktif',
            'wali' => 'Bpk. Agus Santoso',
            'parent_email' => 'wali.fajar@gmail.com',
            'scholarship_status' => 'REGULER',
            'discount_percent' => 0.00,
            'tempat' => 'Tangerang',
            'tanggal' => '2008-11-25',
            'alamat' => 'Jl. Daan Mogot KM 18, Tangerang',
            'kelas' => 2,
            'user_id' => null,
        ],
    ];

    $stmtSiswa = $pdo->prepare("INSERT INTO `siswa` (`id`, `name`, `nis`, `nisn`, `major_code`, `sex`, `status`, `wali`, `parent_email`, `scholarship_status`, `discount_percent`, `tempat`, `tanggal`, `alamat`, `kelas`, `user_id`)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE `name`=VALUES(`name`), `nisn`=VALUES(`nisn`), `major_code`=VALUES(`major_code`), `wali`=VALUES(`wali`), `parent_email`=VALUES(`parent_email`), `scholarship_status`=VALUES(`scholarship_status`), `discount_percent`=VALUES(`discount_percent`), `user_id`=VALUES(`user_id`)");
    foreach ($siswaDemo as $s) {
        $stmtSiswa->execute([
            $s['id'], $s['name'], $s['nis'], $s['nisn'], $s['major_code'], $s['sex'], $s['status'], $s['wali'], $s['parent_email'], $s['scholarship_status'], $s['discount_percent'], $s['tempat'], $s['tanggal'], $s['alamat'], $s['kelas'], $s['user_id']
        ]);
    }
    echo "  [OK] Seeding Data Siswa SMK selesai\n";

    // 23. SEED DATA AWAL: Tabungan Siswa (Muhammad Rizky Pratama)
    $pdo->exec("INSERT INTO `tabungan_siswa` (`student_id`, `account_number`, `balance`, `status`, `opened_at`) VALUES
        (1001, 'TAB-SMK-2026-0001', 1250000.00, 'AKTIF', '2026-07-15')
        ON DUPLICATE KEY UPDATE `balance`=VALUES(`balance`)");
    $savingsId = $pdo->query("SELECT id FROM `tabungan_siswa` WHERE `student_id` = 1001")->fetchColumn();
    $pdo->exec("INSERT INTO `tabungan_mutasi` (`savings_id`, `receipt_number`, `transaction_type`, `amount`, `balance_after`, `cash_account_code`, `description`, `transaction_date`, `created_by`) VALUES
        ($savingsId, 'SETOR-TAB-001', 'SETOR', 1250000.00, 1250000.00, '1-1101', 'Setoran tabungan awal siswa TA 2026/2027', '2026-07-15 09:30:00', 'Bendahara Sekolah')
        ON DUPLICATE KEY UPDATE `amount`=VALUES(`amount`)");
    echo "  [OK] Seeding Tabungan Siswa selesai\n";

    // 24. SEED DATA AWAL: Tagihan & Pembayaran SPP
    $tagihanDemo = [
        ['TAG-2026-07-001', 1001, '2026/2027', 'SPP', 7, 2026, 'SPP Bulan Juli 2026', 350000.00, 0.00, 350000.00, 350000.00, 'PAID', '2026-07-10'],
        ['TAG-2026-08-001', 1001, '2026/2027', 'SPP', 8, 2026, 'SPP Bulan Agustus 2026', 350000.00, 0.00, 350000.00, 350000.00, 'PAID', '2026-08-10'],
        ['TAG-2026-09-001', 1001, '2026/2027', 'SPP', 9, 2026, 'SPP Bulan September 2026', 350000.00, 0.00, 350000.00, 0.00, 'UNPAID', '2026-09-10'],
        ['TAG-2026-10-001', 1001, '2026/2027', 'SPP', 10, 2026, 'SPP Bulan Oktober 2026', 350000.00, 0.00, 350000.00, 0.00, 'UNPAID', '2026-10-10'],
        ['TAG-2026-PRK-001', 1001, '2026/2027', 'PRAKTIK_SMK', null, 2026, 'Biaya Praktik Bengkel & Cloud RPL Semester Ganjil', 150000.00, 0.00, 150000.00, 150000.00, 'PAID', '2026-07-20'],
        // Aulia (KIP 50%)
        ['TAG-2026-07-002', 1002, '2026/2027', 'SPP', 7, 2026, 'SPP Bulan Juli 2026 (KIP Potongan 50%)', 300000.00, 150000.00, 150000.00, 150000.00, 'PAID', '2026-07-10'],
        ['TAG-2026-08-002', 1002, '2026/2027', 'SPP', 8, 2026, 'SPP Bulan Agustus 2026 (KIP Potongan 50%)', 300000.00, 150000.00, 150000.00, 0.00, 'UNPAID', '2026-08-10'],
    ];

    $stmtTagihan = $pdo->prepare("INSERT INTO `tagihan_siswa` (`bill_number`, `student_id`, `academic_year`, `bill_type`, `month`, `year`, `title`, `amount`, `discount_amount`, `net_amount`, `paid_amount`, `status`, `due_date`)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE `paid_amount`=VALUES(`paid_amount`), `status`=VALUES(`status`)");
    foreach ($tagihanDemo as $t) {
        $stmtTagihan->execute($t);
    }

    // Riwayat Pembayaran & Kuitansi untuk Tagihan Juli 2026 (Muhammad Rizky Pratama)
    $billJuliId = $pdo->query("SELECT id FROM `tagihan_siswa` WHERE `bill_number` = 'TAG-2026-07-001'")->fetchColumn();
    if ($billJuliId) {
        $pdo->exec("INSERT INTO `pembayaran_sekolah` (`receipt_number`, `bill_id`, `student_id`, `cash_account_code`, `payment_date`, `amount`, `payment_method`, `note`, `received_by`) VALUES
            ('KWT/SMK/2026/07/0001', $billJuliId, 1001, '1-1101', '2026-07-08', 350000.00, 'TUNAI', 'Pembayaran SPP Bulan Juli 2026 Lunas', 'Ibu Siti Rahmawati, S.E')
            ON DUPLICATE KEY UPDATE `amount`=VALUES(`amount`)");
    }
    echo "  [OK] Seeding Tagihan & Kuitansi Pembayaran SPP selesai\n";

    // 25. SEED DATA AWAL: Jurnal Umum Double-Entry Berimbang (ISAK 35)
    // Transaksi 1: Penerimaan SPP Juli Tunai (Kas Debit 350.000, Pendapatan SPP Kredit 350.000)
    $pdo->exec("INSERT INTO `jurnal_umum` (`entry_number`, `transaction_date`, `reference_type`, `reference_id`, `fund_source`, `description`, `total_amount`, `created_by`) VALUES
        ('JUR-2026-07-001', '2026-07-08', 'PEMBAYARAN_SPP', 1, 'YAYASAN_SPP', 'Penerimaan SPP Bulan Juli 2026 a.n Muhammad Rizky Pratama', 350000.00, 'Bendahara Sekolah')
        ON DUPLICATE KEY UPDATE `total_amount`=VALUES(`total_amount`)");
    $j1 = $pdo->query("SELECT id FROM `jurnal_umum` WHERE `entry_number` = 'JUR-2026-07-001'")->fetchColumn();
    $pdo->exec("DELETE FROM `jurnal_detail` WHERE `journal_id` = $j1");
    $pdo->exec("INSERT INTO `jurnal_detail` (`journal_id`, `account_code`, `debit`, `credit`, `note`) VALUES
        ($j1, '1-1101', 350000.00, 0.00, 'Kas Tunai Bendahara'),
        ($j1, '4-1101', 0.00, 350000.00, 'Pendapatan SPP Siswa')");

    // Transaksi 2: Setoran Tabungan Siswa (Kas Debit 1.250.000, Liabilitas Titipan Kredit 1.250.000)
    $pdo->exec("INSERT INTO `jurnal_umum` (`entry_number`, `transaction_date`, `reference_type`, `reference_id`, `fund_source`, `description`, `total_amount`, `created_by`) VALUES
        ('JUR-2026-07-002', '2026-07-15', 'TABUNGAN_SISWA', 1, 'TABUNGAN_SISWA', 'Penerimaan Setoran Tabungan Siswa a.n Muhammad Rizky Pratama', 1250000.00, 'Bendahara Sekolah')
        ON DUPLICATE KEY UPDATE `total_amount`=VALUES(`total_amount`)");
    $j2 = $pdo->query("SELECT id FROM `jurnal_umum` WHERE `entry_number` = 'JUR-2026-07-002'")->fetchColumn();
    $pdo->exec("DELETE FROM `jurnal_detail` WHERE `journal_id` = $j2");
    $pdo->exec("INSERT INTO `jurnal_detail` (`journal_id`, `account_code`, `debit`, `credit`, `note`) VALUES
        ($j2, '1-1101', 1250000.00, 0.00, 'Kas Tunai Bendahara'),
        ($j2, '2-1201', 0.00, 1250000.00, 'Liabilitas Titipan Tabungan Siswa')");

    // Transaksi 3: Realisasi Belanja Bahan Praktik BOS (Beban Debit 28.000.000, Bank BOS Kredit 28.000.000)
    $pdo->exec("INSERT INTO `jurnal_umum` (`entry_number`, `transaction_date`, `reference_type`, `reference_id`, `fund_source`, `description`, `total_amount`, `created_by`) VALUES
        ('JUR-2026-08-001', '2026-08-05', 'BELANJA_BOS', 3, 'BOS', 'Pengadaan Bahan Praktik Kejuruan Otomotif & Jaringan RKAS SNP-03', 28000000.00, 'Bendahara Sekolah')
        ON DUPLICATE KEY UPDATE `total_amount`=VALUES(`total_amount`)");
    $j3 = $pdo->query("SELECT id FROM `jurnal_umum` WHERE `entry_number` = 'JUR-2026-08-001'")->fetchColumn();
    $pdo->exec("DELETE FROM `jurnal_detail` WHERE `journal_id` = $j3");
    $pdo->exec("INSERT INTO `jurnal_detail` (`journal_id`, `account_code`, `debit`, `credit`, `note`) VALUES
        ($j3, '5-1101', 28000000.00, 0.00, 'Beban Praktik Kejuruan SMK'),
        ($j3, '1-1202', 0.00, 28000000.00, 'Rekening Khusus Dana BOS')");

    echo "  [OK] Seeding Jurnal Umum Double-Entry berimbang selesai\n";
    echo "\n=== MIGRASI & SEEDING BERHASIL 100%! ===\n";

} catch (Exception $e) {
    echo "ERROR MIGRASI: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
}

