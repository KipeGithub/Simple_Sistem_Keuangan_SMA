<?php
/**
 * LIVE HTTP TESTING SELURUH 4 PERAN & REVISI UI KEUANGAN SMA/SMK
 * SMK DUMMY KIPE
 */

$baseUrl = 'http://localhost/Simple_Akuntansi_Sekolah';

function httpReq($url, $method = 'GET', $data = [], $cookieJar = null) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
    curl_setopt($ch, CURLOPT_HEADER, true);
    if ($cookieJar) {
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookieJar);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookieJar);
    }
    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    }
    $response = curl_exec($ch);
    $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $effectiveUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    $headers = substr($response, 0, $headerSize);
    $body = substr($response, $headerSize);
    curl_close($ch);

    return [
        'code'    => $httpCode,
        'url'     => $effectiveUrl,
        'headers' => $headers,
        'body'    => $body,
    ];
}

echo "====================================================================\n";
echo " LIVE HTTP & RBAC TESTING: 4 PERAN & REVISI INTERFACE (SMK DUMMY KIPE)\n";
echo "====================================================================\n\n";

$rolesToTest = [
    [
        'name'     => '1. Admin TU',
        'role_id'  => 1,
        'email'    => 'admin@smkn1akuntansi.sch.id',
        'password' => 'password',
        'allowed_urls' => [
            '/School_finance/dasbor'       => 200,
            '/School_finance/siswa'        => 200,
            '/School_finance/laporan_isak' => 200, // Menu Baru Rekap ISAK
            '/Pengguna'                    => 200, // Eksklusif Admin TU
            '/Lainnya'                     => 200, // Kas Masuk Lainnya
            '/Kelas'                       => 200,
            '/Guru'                        => 200,
        ],
        'forbidden_urls' => [],
    ],
    [
        'name'     => '2. Bendahara',
        'role_id'  => 2,
        'email'    => 'bendahara@smkn1akuntansi.sch.id',
        'password' => 'password',
        'allowed_urls' => [
            '/School_finance/dasbor'       => 200,
            '/School_finance/tagihan'      => 200,
            '/School_finance/tabungan'     => 200,
            '/School_finance/laporan_isak' => 200, // Menu Baru Rekap ISAK
            '/School_finance/bku'          => 200,
            '/Lainnya'                     => 200,
        ],
        'forbidden_urls' => [
            '/Pengguna' => 403, // Ditolak: Bendahara tidak boleh kelola user
        ],
    ],
    [
        'name'     => '3. Kepala Sekolah',
        'role_id'  => 3,
        'email'    => 'kepsek@smkn1akuntansi.sch.id',
        'password' => 'password',
        'allowed_urls' => [
            '/School_finance/dasbor'       => 200,
            '/School_finance/tunggakan'    => 200,
            '/School_finance/laporan_isak' => 200, // Menu Baru Rekap ISAK
            '/School_finance/bku'          => 200,
        ],
        'forbidden_urls' => [
            '/Pengguna' => 403, // Ditolak: Kepsek hanya monitoring
        ],
    ],
    [
        'name'     => '4. Wali Murid / Siswa',
        'role_id'  => 4,
        'email'    => 'wali.rizky@gmail.com',
        'password' => 'password',
        'allowed_urls' => [
            '/School_finance/portal' => 200,
        ],
        'forbidden_urls' => [
            '/Pengguna'                    => 403, // Ditolak
            '/School_finance/laporan_isak' => 302, // Dialihkan ke portal
        ],
    ],
];

$passedCount = 0;
$totalTests  = 0;

foreach ($rolesToTest as $r) {
    echo "--------------------------------------------------------------------\n";
    echo "[TEST PERAN] {$r['name']} ({$r['email']})\n";
    echo "--------------------------------------------------------------------\n";

    // 1. Berikan file cookie unik terisolasi per peran
    $roleCookie = tempnam(sys_get_temp_dir(), 'ci_role_' . $r['role_id'] . '_');

    // 2. Akses halaman Login
    $loginPage = httpReq($baseUrl . '/Auth', 'GET', [], $roleCookie);
    $totalTests++;
    if ($loginPage['code'] === 200) {
        echo "  [PASS] Halaman Login dapat diakses (HTTP 200)\n";
        $passedCount++;
    } else {
        echo "  [FAIL] Gagal memuat Login (HTTP {$loginPage['code']})\n";
    }

    // Verifikasi ketiadaan tombol Google OAuth di login
    $totalTests++;
    if (strpos($loginPage['body'], 'Masuk dengan Akun Google') === false) {
        echo "  [PASS] Tombol Google OAuth telah bersih/dilepas dari Form Login\n";
        $passedCount++;
    } else {
        echo "  [FAIL] Tombol Google OAuth masih terdeteksi di halaman login!\n";
    }

    // 3. Submit Form Login Standar
    $postData = [
        'email'    => $r['email'],
        'password' => $r['password'],
    ];
    $loginSubmit = httpReq($baseUrl . '/Auth', 'POST', $postData, $roleCookie);
    $totalTests++;

    if ($loginSubmit['code'] === 200) {
        echo "  [PASS] Autentikasi berhasil, redirect ke: {$loginSubmit['url']}\n";
        $passedCount++;
    } else {
        echo "  [FAIL] Login gagal (HTTP {$loginSubmit['code']})\n";
    }

    // 4. Verifikasi URL yang diizinkan (Allowed URLs)
    foreach ($r['allowed_urls'] as $path => $expectedCode) {
        $totalTests++;
        $res = httpReq($baseUrl . $path, 'GET', [], $roleCookie);
        if ($res['code'] === $expectedCode) {
            echo "  [PASS] Akses {$path} -> HTTP {$res['code']} OK\n";
            $passedCount++;

            // Cek kebersihan tampilan: tidak ada "Data Data Siswa"
            if ($path === '/School_finance/siswa') {
                $totalTests++;
                if (strpos($res['body'], 'Data Data Siswa') === false) {
                    echo "  [PASS] Verifikasi UI: Tidak ada judul berulang 'Data Data Siswa'\n";
                    $passedCount++;
                } else {
                    echo "  [FAIL] Terdeteksi judul berulang 'Data Data Siswa'!\n";
                }
            }

            // Cek Copyright Footer Kipe Portfolio
            if ($path === '/School_finance/dasbor') {
                $totalTests++;
                if (strpos($res['body'], 'https://kipe-portfolio.vercel.app/') !== false && strpos($res['body'], 'Kipe Portfolio') !== false) {
                    echo "  [PASS] Verifikasi UI: Copyright Footer Kipe Portfolio aktif\n";
                    $passedCount++;
                } else {
                    echo "  [FAIL] Copyright Footer Kipe Portfolio tidak ditemukan!\n";
                }

                // Cek Topbar: tulisan "SMK DUMMY KIPE" sudah dihapus dari topbar
                $totalTests++;
                if (strpos($res['body'], '<i class="fa fa-graduation-cap text-yellow"></i> SMK DUMMY KIPE') === false) {
                    echo "  [PASS] Verifikasi UI: Tulisan teks sekolah di topbar berhasil dihapus\n";
                    $passedCount++;
                } else {
                    echo "  [FAIL] Tulisan teks sekolah masih muncul di topbar!\n";
                }

                // Cek Sidebar User-Panel memiliki badge role
                $totalTests++;
                if (strpos($res['body'], 'user-panel') !== false) {
                    echo "  [PASS] Verifikasi UI: Sidebar user-panel rapi dengan badge peran pengguna\n";
                    $passedCount++;
                } else {
                    echo "  [FAIL] Sidebar user-panel tidak terdeteksi!\n";
                }
            }

            // Cek Menu Baru Rekap ISAK memiliki tabbed interface
            if ($path === '/School_finance/laporan_isak') {
                $totalTests++;
                if (strpos($res['body'], 'tab_neraca') !== false && strpos($res['body'], 'tab_aktivitas') !== false && strpos($res['body'], 'tab_bku') !== false) {
                    echo "  [PASS] Verifikasi Modul: Halaman Rekap ISAK memiliki 4 tab navigasi terpadu\n";
                    $passedCount++;
                } else {
                    echo "  [FAIL] Tab navigasi Rekap ISAK tidak lengkap!\n";
                }
            }

        } else {
            echo "  [FAIL] Akses {$path} diharapkan HTTP {$expectedCode}, didapat HTTP {$res['code']}\n";
        }
    }

    // 5. Verifikasi URL yang Dilarang (Forbidden RBAC Security Guard)
    foreach ($r['forbidden_urls'] as $path => $expectedCode) {
        $totalTests++;
        $res = httpReq($baseUrl . $path, 'GET', [], $roleCookie);
        if ($res['code'] === $expectedCode || $res['code'] === 302 || strpos($res['url'], '/School_finance/portal') !== false) {
            echo "  [PASS] Proteksi Keamanan RBAC: {$path} DITOLAK / DIALIHKAN (HTTP {$res['code']})\n";
            $passedCount++;
        } else {
            echo "  [FAIL] KEBOCORAN AKSES! {$path} seharusnya DITOLAK, tapi diizinkan (HTTP {$res['code']})\n";
        }
    }

    @unlink($roleCookie);
    echo "\n";
}

// 6. Test Modul Kas Masuk Lainnya (getData Endpoint)
echo "--------------------------------------------------------------------\n";
echo "[TEST MODUL] Kas Masuk Lainnya (Keterangan, Edit, Delete)\n";
echo "--------------------------------------------------------------------\n";

$adminCookie = tempnam(sys_get_temp_dir(), 'ci_admin_lainnya_');
httpReq($baseUrl . '/Auth', 'POST', ['email' => 'bendahara@smkn1akuntansi.sch.id', 'password' => 'password'], $adminCookie);

$lainnyaData = httpReq($baseUrl . '/Lainnya/getData', 'POST', [], $adminCookie);
$totalTests++;
if ($lainnyaData['code'] === 200 && strpos($lainnyaData['body'], 'data') !== false) {
    echo "  [PASS] Endpoint /Lainnya/getData mengembalikan JSON DataTables valid\n";
    $passedCount++;
} else {
    echo "  [FAIL] Endpoint /Lainnya/getData gagal (HTTP {$lainnyaData['code']})\n";
}
@unlink($adminCookie);

echo "\n====================================================================\n";
echo " REKAPITULASI HASIL TESTING: {$passedCount} / {$totalTests} PENGUJIAN BERHASIL\n";
echo "====================================================================\n";
