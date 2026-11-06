<?php
session_start();

// ==========================================
// 1. SISTEM AUTENTIKASI LOGIN KHUSUS HOST
// ==========================================
$login_user = "BintangAFR";
$login_pass = "211218200908";
$error_login = "";

// Aksi Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: host.php");
    exit();
}

// Proses Login
if (isset($_POST['login_submit'])) {
    $u = trim($_POST['username'] ?? '');
    $p = trim($_POST['password'] ?? '');

    if ($u === $login_user && $p === $login_pass) {
        $_SESSION['host_logged_in'] = true;
        header("Location: host.php");
        exit();
    } else {
        $error_login = "Username atau password salah! Silakan coba lagi.";
    }
}

// Tampilkan Form Login jika belum terautentikasi
if (!isset($_SESSION['host_logged_in'])) {
    ?>
    <!DOCTYPE html>
    <html lang="id">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Login Host - Debang Guest</title>
      <script src="https://cdn.tailwindcss.com"></script>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
      <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
      <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
    </head>
    <body class="bg-[#f2f5f2] flex items-center justify-center min-h-screen p-4 selection:bg-emerald-600 selection:text-white">
      <div class="bg-white p-7 sm:p-9 rounded-3xl shadow-xl shadow-slate-200/60 max-w-sm w-full border border-emerald-100 relative overflow-hidden">
        <div class="absolute -top-12 -right-12 w-28 h-28 bg-emerald-100/60 rounded-full blur-xl pointer-events-none"></div>
        
        <div class="w-12 h-12 bg-emerald-700 text-white rounded-2xl flex items-center justify-center mx-auto mb-4 text-xl shadow-md shadow-emerald-700/20">
          <i class="fa-solid fa-house-lock"></i>
        </div>
        
        <h2 class="text-xl font-black text-slate-800 text-center tracking-tight">Portal Host Debang Guest</h2>
        <p class="text-xs text-slate-500 text-center mb-6 mt-0.5">Akses khusus manajemen & data kost</p>

        <?php if (!empty($error_login)): ?>
          <div class="mb-4 p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-700 text-xs font-semibold flex items-center gap-2 animate-bounce">
            <i class="fa-solid fa-circle-exclamation text-rose-500"></i>
            <span><?= $error_login; ?></span>
          </div>
        <?php endif; ?>

        <form method="POST" class="space-y-4 text-xs">
          <div>
            <label class="block font-bold text-slate-700 mb-1.5">Username Pengelola</label>
            <div class="relative">
              <span class="absolute left-3.5 top-3 text-slate-400"><i class="fa-regular fa-user"></i></span>
              <input type="text" name="username" required placeholder="Masukkan username" class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 font-medium">
            </div>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1.5">Password</label>
            <div class="relative">
              <span class="absolute left-3.5 top-3 text-slate-400"><i class="fa-solid fa-lock"></i></span>
              <input type="password" name="password" required placeholder="••••••••••••" class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 font-medium font-mono">
            </div>
          </div>

          <button type="submit" name="login_submit" class="w-full py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl shadow-md shadow-emerald-700/20 transition active:scale-95 flex items-center justify-center gap-2 mt-2">
            <i class="fa-solid fa-right-to-bracket"></i>
            <span>Masuk ke Dashboard</span>
          </button>
        </form>

        <p class="text-[11px] text-slate-400 text-center mt-6">&copy; <?= date('Y'); ?> Debang Guest Management</p>
      </div>
    </body>
    </html>
    <?php
    exit();
}

// ==========================================
// 2. LOGIKA DATABASE & OPERASIONAL HOST
// ==========================================
require_once "koneksi.php";

$pesan = "";
$tipe_pesan = "sukses";

// Check-in Mahasiswa
if (isset($_POST['aksi']) && $_POST['aksi'] === 'checkin') {
    $nomor_kamar  = $_POST['nomor_kamar'];
    $jatuh_tempo  = $_POST['jatuh_tempo'];
    $status_bayar = $_POST['status_bayar'];
    $mode         = $_POST['mode_input'] ?? 'otomatis';

    $nama = "";
    $no_wa = "";

    if ($mode === 'otomatis') {
        $pendaftar_id = (int)$_POST['pendaftar_id'];
        $stmtP = $pdo->prepare("SELECT nama_lengkap, no_wa FROM pendaftar WHERE id = ?");
        $stmtP->execute([$pendaftar_id]);
        $calon = $stmtP->fetch();

        if ($calon) {
            $nama  = trim($calon['nama_lengkap']);
            $no_wa = trim($calon['no_wa']);
        }
    } else {
        $nama  = trim($_POST['nama_manual'] ?? '');
        $no_wa = trim($_POST['wa_manual'] ?? '');
    }

    if (!empty($nama) && !empty($no_wa)) {
        $no_wa = preg_replace('/[^0-9]/', '', $no_wa);
        if (strpos($no_wa, '0') === 0) {
            $no_wa = '62' . substr($no_wa, 1);
        } elseif (strpos($no_wa, '62') !== 0) {
            $no_wa = '62' . $no_wa;
        }

        $stmt = $pdo->prepare("UPDATE kamar SET status = 'terisi', nama_penghuni = ?, no_wa_penghuni = ?, jatuh_tempo = ?, status_bayar = ? WHERE nomor_kamar = ?");
        $stmt->execute([$nama, $no_wa, $jatuh_tempo, $status_bayar, $nomor_kamar]);
        $pesan = "Berhasil check-in mahasiswi '{$nama}' di {$nomor_kamar}!";
    } else {
        $pesan = "Gagal: Data nama atau nomor WhatsApp belum ditentukan.";
        $tipe_pesan = "error";
    }
}

// Check-out Mahasiswa
if (isset($_GET['checkout'])) {
    $id = (int)$_GET['checkout'];
    $stmt = $pdo->prepare("UPDATE kamar SET status = 'tersedia', nama_penghuni = NULL, no_wa_penghuni = NULL, jatuh_tempo = NULL, status_bayar = 'lunas' WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: host.php?msg=checkout_ok");
    exit();
}

// Reset Semua Kamar Kosong Sekaligus
if (isset($_GET['reset_semua_kamar'])) {
    $pdo->query("UPDATE kamar SET status = 'tersedia', nama_penghuni = NULL, no_wa_penghuni = NULL, jatuh_tempo = NULL, status_bayar = 'lunas'");
    header("Location: host.php?msg=reset_ok");
    exit();
}

// Hapus Data Pendaftar
if (isset($_GET['hapus_pendaftar'])) {
    $id = (int)$_GET['hapus_pendaftar'];
    $stmt = $pdo->prepare("DELETE FROM pendaftar WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: host.php?msg=hapus_ok");
    exit();
}

// Catat Keuangan
if (isset($_POST['aksi']) && $_POST['aksi'] === 'keuangan') {
    $tanggal    = $_POST['tanggal'];
    $keterangan = trim($_POST['keterangan']);
    $tipe       = $_POST['tipe'];
    $nominal    = (float)$_POST['nominal'];

    $stmt = $pdo->prepare("INSERT INTO keuangan (tanggal, keterangan, tipe, nominal) VALUES (?, ?, ?, ?)");
    $stmt->execute([$tanggal, $keterangan, $tipe, $nominal]);
    $pesan = "Transaksi kas berhasil dicatat!";
}

// Tangkap notifikasi URL
if (isset($_GET['msg'])) {
    if ($_GET['msg'] === 'checkout_ok') $pesan = "Penghuni berhasil di check-out. Kamar kini kosong kembali!";
    if ($_GET['msg'] === 'reset_ok')    $pesan = "Seluruh 9 kamar berhasil di-reset menjadi status KOSONG!";
    if ($_GET['msg'] === 'hapus_ok')    $pesan = "Data notifikasi pendaftar berhasil dihapus.";
}

// Ambil Data Terkini
$pendaftar = $pdo->query("SELECT * FROM pendaftar ORDER BY id DESC")->fetchAll();
$kamar     = $pdo->query("SELECT * FROM kamar ORDER BY id ASC")->fetchAll();
$keuangan  = $pdo->query("SELECT * FROM keuangan ORDER BY tanggal DESC, id DESC")->fetchAll();

// =========================================================================
// FITUR BARU: Ambil Daftar Kontak Penghuni yang Sedang Aktif di Kamar
// =========================================================================
$penghuni_aktif_map = []; // Map nomor_wa / nama -> nomor_kamar
foreach ($kamar as $km) {
    if ($km['status'] === 'terisi') {
        if (!empty($km['no_wa_penghuni'])) {
            $clean_wa = preg_replace('/[^0-9]/', '', $km['no_wa_penghuni']);
            $penghuni_aktif_map[$clean_wa] = $km['nomor_kamar'];
        }
        if (!empty($km['nama_penghuni'])) {
            $penghuni_aktif_map[strtolower(trim($km['nama_penghuni']))] = $km['nomor_kamar'];
        }
    }
}

// Filter Pendaftar yang BELUM Check-in (Khusus untuk Dropdown Check-in)
$pendaftar_belum_checkin = [];
foreach ($pendaftar as $p) {
    $clean_wa_p = preg_replace('/[^0-9]/', '', $p['no_wa']);
    $lower_nama = strtolower(trim($p['nama_lengkap']));
    
    // Jika WA atau nama belum ada di kamar terisi, masukkan ke dropdown
    if (!isset($penghuni_aktif_map[$clean_wa_p]) && !isset($penghuni_aktif_map[$lower_nama])) {
        $pendaftar_belum_checkin[] = $p;
    }
}

// Hitung Kamar Kosong & Terisi
$total_terisi = 0;
foreach ($kamar as $km) {
    if ($km['status'] === 'terisi') $total_terisi++;
}
$total_kosong = count($kamar) - $total_terisi;

// Hitung Rekap Keuangan
$total_in = 0; $total_out = 0;
foreach ($keuangan as $k) {
    if ($k['tipe'] === 'in') $total_in += $k['nominal'];
    else $total_out += $k['nominal'];
}
$saldo = $total_in - $total_out;
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Portal Host - Debang Guest</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Plus Jakarta Sans', sans-serif; }
  </style>
</head>
<body class="bg-[#f4f7f4] text-slate-800 pb-16">

  <!-- NAVBAR ATAS HOST -->
  <header class="bg-white border-b border-emerald-100 sticky top-0 z-40 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-emerald-800 flex items-center justify-center text-amber-300 font-bold shadow-md shadow-emerald-900/10">
          <i class="fa-solid fa-house-chimney-user text-lg"></i>
        </div>
        <div>
          <div class="flex items-center gap-2">
            <h2 class="text-base font-extrabold text-slate-800">Portal Host - Debang Guest</h2>
            <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-md border border-emerald-300">ADMIN: BintangAFR</span>
          </div>
          <p class="text-xs text-slate-500">Kelola 9 unit kamar, pendaftar, dan pembukuan kas</p>
        </div>
      </div>

      <div class="flex items-center gap-2">
        <a href="index.php" target="_blank" class="px-3.5 py-2 text-xs font-bold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 rounded-xl transition flex items-center gap-1.5 border border-emerald-200">
          <i class="fa-solid fa-arrow-up-right-from-square"></i> <span class="hidden sm:inline">Web Publik</span>
        </a>
        <a href="host.php?logout=1" onclick="return confirm('Keluar dari sesi Host?')" class="px-3.5 py-2 text-xs font-bold text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-xl transition flex items-center gap-1.5 border border-rose-200">
          <i class="fa-solid fa-arrow-right-from-bracket"></i> <span>Keluar</span>
        </a>
      </div>
    </div>
  </header>

  <div class="max-w-7xl mx-auto px-4 pt-6 space-y-6">

    <!-- ALERT NOTIFIKASI AKSI -->
    <?php if (!empty($pesan)): ?>
      <div class="p-4 rounded-2xl <?= $tipe_pesan === 'error' ? 'bg-rose-50 border-rose-300 text-rose-800' : 'bg-emerald-50 border-emerald-300 text-emerald-800'; ?> border text-xs font-bold flex items-center justify-between shadow-sm">
        <div class="flex items-center gap-2">
          <i class="fa-solid <?= $tipe_pesan === 'error' ? 'fa-triangle-exclamation text-rose-600' : 'fa-circle-check text-emerald-600'; ?> text-base"></i>
          <span><?= htmlspecialchars($pesan); ?></span>
        </div>
        <button onclick="this.parentElement.remove()" class="text-slate-400 hover:text-slate-700 text-base">&times;</button>
      </div>
    <?php endif; ?>

    <!-- STATISTIK RINGKAS -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
      <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kamar Kosong</p>
        <h4 class="text-2xl font-black text-emerald-600 mt-1"><?= $total_kosong; ?> <span class="text-xs font-medium text-slate-400">/ 9 Kamar</span></h4>
      </div>
      <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Kamar Terisi</p>
        <h4 class="text-2xl font-black text-blue-600 mt-1"><?= $total_terisi; ?> <span class="text-xs font-medium text-slate-400">/ 9 Kamar</span></h4>
      </div>
      <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Pemasukan Kas</p>
        <h4 class="text-xl font-black text-emerald-700 mt-1">Rp <?= number_format($total_in, 0, ',', '.'); ?></h4>
      </div>
      <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <p class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Saldo Bersih</p>
        <h4 class="text-xl font-black text-indigo-700 mt-1">Rp <?= number_format($saldo, 0, ',', '.'); ?></h4>
      </div>
    </div>

    <!-- TABEL NOTIFIKASI DATA PENDAFTAR BARU -->
    <section class="bg-white rounded-2xl border-2 border-emerald-200/80 p-6 shadow-sm">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-4 border-b border-slate-200">
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center font-bold text-base">
            <i class="fa-solid fa-bell"></i>
          </div>
          <div>
            <div class="flex items-center gap-2">
              <h3 class="text-base font-bold text-slate-900">Notifikasi Data Calon Mahasiswa (Form Masuk)</h3>
              <span class="bg-emerald-700 text-white text-[11px] font-bold px-2.5 py-0.5 rounded-full"><?= count($pendaftar); ?> Data</span>
            </div>
            <p class="text-xs text-slate-500 mt-0.5">Data formulir yang diisi calon anak kost di website publik</p>
          </div>
        </div>
      </div>

      <div class="overflow-x-auto mt-4">
        <table class="w-full text-left text-xs text-slate-600">
          <thead class="bg-slate-50 uppercase text-[11px] font-bold text-slate-700 border-b border-slate-200">
            <tr>
              <th class="py-3 px-3">Tgl Masuk</th>
              <th class="py-3 px-3">Nama Lengkap</th>
              <th class="py-3 px-3">WhatsApp Mahasiswa</th>
              <th class="py-3 px-3">WhatsApp Orang Tua</th>
              <th class="py-3 px-3">Tempat Tinggal</th>
              <th class="py-3 px-3">Jurusan / Kampus</th>
              <th class="py-3 px-3 text-center">Status / Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-slate-100">
            <?php if (empty($pendaftar)): ?>
              <tr>
                <td colspan="7" class="py-8 text-center text-slate-400">
                  <i class="fa-solid fa-inbox text-2xl mb-2 block"></i>
                  Belum ada data calon mahasiswa yang mengisi formulir.
                </td>
              </tr>
            <?php else: ?>
              <?php foreach ($pendaftar as $p): ?>
                <?php 
                  $clean_wa_p = preg_replace('/[^0-9]/', '', $p['no_wa']);
                  $lower_nama = strtolower(trim($p['nama_lengkap']));
                  $kamar_huni = $penghuni_aktif_map[$clean_wa_p] ?? $penghuni_aktif_map[$lower_nama] ?? null;
                ?>
                <tr class="hover:bg-slate-50 transition">
                  <td class="py-3 px-3 text-slate-400 font-mono text-[11px]"><?= $p['tanggal_daftar']; ?></td>
                  <td class="py-3 px-3 font-bold text-slate-900"><?= htmlspecialchars($p['nama_lengkap']); ?></td>
                  <td class="py-3 px-3 font-mono font-semibold text-slate-700">
                    <a href="https://wa.me/<?= $clean_wa_p; ?>" target="_blank" class="text-emerald-700 hover:underline flex items-center gap-1">
                      <i class="fa-brands fa-whatsapp text-emerald-600"></i> +<?= htmlspecialchars($p['no_wa']); ?>
                    </a>
                  </td>
                  <td class="py-3 px-3 font-mono text-slate-500">+<?= htmlspecialchars($p['no_wa_ortu']); ?></td>
                  <td class="py-3 px-3"><?= htmlspecialchars($p['tempat_tinggal']); ?></td>
                  <td class="py-3 px-3 text-emerald-800 font-semibold"><?= htmlspecialchars($p['jurusan']); ?></td>
                  <td class="py-3 px-3 text-center">
                    <div class="flex items-center justify-center gap-2">
                      <?php if ($kamar_huni): ?>
                        <!-- Indikator Jika Sudah Menempati Kamar -->
                        <span class="px-2.5 py-1 rounded-lg bg-emerald-100 text-emerald-800 text-[10px] font-bold border border-emerald-300 inline-flex items-center gap-1">
                          <i class="fa-solid fa-check"></i> <?= $kamar_huni; ?>
                        </span>
                      <?php else: ?>
                        <!-- Tombol Pilih jika Masih Calon (Belum Check-in) -->
                        <button 
                          type="button" 
                          onclick="autoPilihCalon('<?= $p['id']; ?>')"
                          class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 hover:bg-emerald-100 font-bold border border-emerald-200 transition"
                          title="Pilih pendaftar ini untuk Check-in"
                        >
                          <i class="fa-solid fa-arrow-down mr-1"></i> Pilih
                        </button>
                      <?php endif; ?>

                      <a href="host.php?hapus_pendaftar=<?= $p['id']; ?>" onclick="return confirm('Hapus data pendaftar ini?')" class="text-rose-500 hover:text-rose-700 p-1" title="Hapus">
                        <i class="fa-regular fa-trash-can"></i>
                      </a>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </section>

    <!-- DASHBOARD VISUAL 9 KAMAR -->
    <section class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
      <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6 pb-4 border-b border-slate-200">
        <div>
          <h3 class="text-base font-bold text-slate-900">Manajemen 9 Kamar Debang Guest</h3>
          <p class="text-xs text-slate-500 mt-0.5">Status kamar bersifat manual. Anda sendiri yang mengatur check-in dan check-out.</p>
        </div>
        <div class="flex items-center gap-2">
          <a href="host.php?reset_semua_kamar=1" onclick="return confirm('Yakin ingin mereset SEMUA 9 kamar menjadi KOSONG?')" class="text-xs text-rose-600 hover:underline font-bold px-3 py-1.5 rounded-lg border border-rose-200 hover:bg-rose-50 transition">
            <i class="fa-solid fa-arrows-rotate mr-1"></i> Kosongkan Semua Kamar
          </a>
        </div>
      </div>

      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
        <?php foreach ($kamar as $km): ?>
          <?php 
            $terisi = ($km['status'] === 'terisi');
            $bg = $terisi ? ($km['status_bayar'] === 'jatuh_tempo' ? 'border-amber-300 bg-amber-50/50' : 'border-blue-200 bg-blue-50/40') : 'border-emerald-200 bg-emerald-50/40';
          ?>
          <div class="border-2 rounded-2xl p-4 transition-all duration-200 <?= $bg; ?>">
            <div class="flex justify-between items-center mb-2">
              <span class="font-extrabold text-sm text-slate-900"><?= $km['nomor_kamar']; ?></span>
              <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full <?= $terisi ? 'bg-blue-100 text-blue-800 border border-blue-300' : 'bg-emerald-100 text-emerald-800 border border-emerald-300'; ?>">
                <?= ucfirst($km['status']); ?>
              </span>
            </div>

            <?php if ($terisi): ?>
              <div class="text-xs space-y-1.5 my-3 pt-2 border-t border-slate-200/60">
                <p class="font-bold text-slate-800 truncate"><i class="fa-solid fa-user-graduate text-emerald-700 mr-1.5"></i> <?= htmlspecialchars($km['nama_penghuni']); ?></p>
                <p class="text-slate-500 font-mono text-[11px]"><i class="fa-brands fa-whatsapp text-emerald-600 mr-1.5"></i> +<?= htmlspecialchars($km['no_wa_penghuni']); ?></p>
                <p class="text-[11px] <?= $km['status_bayar'] === 'jatuh_tempo' ? 'text-amber-800 font-bold' : 'text-slate-500'; ?>">
                  <i class="fa-solid fa-calendar-day mr-1.5"></i> Jatuh Tempo: <?= $km['jatuh_tempo'] ?: '-'; ?>
                </p>
              </div>
              <div class="pt-2 border-t border-slate-200/60 flex items-center justify-between gap-2">
                <?php 
                  $tagihMsg = urlencode("Halo {$km['nama_penghuni']} ({$km['nomor_kamar']} Debang Guest),\n\nMengingatkan untuk konfirmasi pembayaran sewa kamar yang jatuh tempo pada {$km['jatuh_tempo']}.\n\nTerima kasih.");
                ?>
                <a href="https://wa.me/<?= preg_replace('/[^0-9]/', '', $km['no_wa_penghuni']); ?>?text=<?= $tagihMsg; ?>" target="_blank" class="text-[11px] font-bold text-emerald-700 bg-emerald-100 hover:bg-emerald-200 px-2.5 py-1.5 rounded-lg transition flex items-center gap-1">
                  <i class="fa-brands fa-whatsapp"></i> Tagih WA
                </a>
                <a href="host.php?checkout=<?= $km['id']; ?>" onclick="return confirm('Keluarkan penghuni dari kamar <?= $km['nomor_kamar']; ?>? Kamar akan berstatus kosong.')" class="text-[11px] font-semibold text-rose-600 hover:text-rose-800 hover:underline">
                  <i class="fa-solid fa-arrow-right-from-bracket"></i> Check-out
                </a>
              </div>
            <?php else: ?>
              <div class="py-5 text-center text-slate-400 text-xs">
                <i class="fa-regular fa-square-check text-emerald-500 text-lg mb-1 block"></i>
                Kamar Bersih & Kosong
              </div>
              <button onclick="setCheckInKamar('<?= $km['nomor_kamar']; ?>')" class="w-full py-2 bg-emerald-700 hover:bg-emerald-800 text-white rounded-xl font-bold text-xs shadow-sm transition active:scale-95">
                <i class="fa-solid fa-user-plus mr-1"></i> + Check-in Mahasiswa
              </button>
            <?php endif; ?>
          </div>
        <?php endforeach; ?>
      </div>
    </section>

    <!-- FORM CHECK-IN & REKAP KEUANGAN -->
    <div class="grid lg:grid-cols-12 gap-6" id="sectionFormCheckin">
      <!-- FORM CHECK-IN MAHASISWA -->
      <div class="lg:col-span-6 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="pb-3 border-b border-slate-200 mb-4 flex items-center justify-between">
          <div>
            <h3 class="text-base font-bold text-slate-900">Form Check-in Mahasiswa</h3>
            <p class="text-xs text-slate-500">Pilih calon mahasiswi atau input manual</p>
          </div>

          <!-- Toggle Mode Input -->
          <div class="bg-slate-100 p-1 rounded-xl flex items-center border border-slate-200 text-xs font-semibold">
            <button type="button" id="btnModeOtomatis" onclick="switchCheckinMode('otomatis')" class="px-2.5 py-1 rounded-lg bg-white text-emerald-800 shadow-sm transition">
              <i class="fa-solid fa-bolt mr-1 text-amber-500"></i> Dari Web
            </button>
            <button type="button" id="btnModeManual" onclick="switchCheckinMode('manual')" class="px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 transition">
              <i class="fa-solid fa-pen mr-1"></i> Manual
            </button>
          </div>
        </div>

        <form action="host.php" method="POST" class="space-y-4 text-xs">
          <input type="hidden" name="aksi" value="checkin">
          <input type="hidden" name="mode_input" id="inputMode" value="otomatis">
          
          <!-- Mode 1: Pilihan Otomatis (HANYA YANG BELUM CHECK-IN) -->
          <div id="wrapperOtomatis">
            <label class="block font-bold text-slate-700 mb-1 flex items-center gap-1.5">
              <i class="fa-solid fa-user-check text-emerald-700"></i>
              <span>Pilih Calon Mahasiswa (Belum Check-in) <span class="text-rose-500">*</span></span>
            </label>
            <select name="pendaftar_id" id="selectCalonPendaftar" onchange="tampilkanPreviewCalon(this)" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 font-semibold focus:outline-none focus:border-emerald-600 bg-white">
              <option value="">-- Klik untuk memilih calon mahasiswi --</option>
              <?php foreach ($pendaftar_belum_checkin as $cp): ?>
                <option 
                  value="<?= $cp['id']; ?>" 
                  data-nama="<?= htmlspecialchars($cp['nama_lengkap']); ?>" 
                  data-wa="<?= htmlspecialchars($cp['no_wa']); ?>"
                  data-jurusan="<?= htmlspecialchars($cp['jurusan']); ?>"
                  data-asal="<?= htmlspecialchars($cp['tempat_tinggal']); ?>"
                >
                  <?= htmlspecialchars($cp['nama_lengkap']); ?> - <?= htmlspecialchars($cp['jurusan']); ?>
                </option>
              <?php endforeach; ?>
            </select>

            <?php if (empty($pendaftar_belum_checkin)): ?>
              <p class="text-[11px] text-amber-700 font-semibold mt-1.5 bg-amber-50 p-2.5 rounded-xl border border-amber-200">
                <i class="fa-solid fa-circle-info mr-1"></i> Semua calon pendaftar sudah menempati kamar. Gunakan mode "Manual" jika ada mahasiswi baru yang datang langsung.
              </p>
            <?php endif; ?>

            <!-- Preview Data Terpilih -->
            <div id="previewCard" class="hidden mt-3 p-3.5 rounded-xl bg-emerald-50/60 border border-emerald-200 space-y-1 text-slate-600">
              <p class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider">Detail Data Terpilih:</p>
              <p class="font-bold text-slate-900 text-sm" id="prevNama">-</p>
              <p class="text-[11px] font-mono text-emerald-700 font-semibold" id="prevWa">-</p>
              <p class="text-[11px]" id="prevInfo">-</p>
            </div>
          </div>

          <!-- Mode 2: Input Manual (Hanya muncul jika mode manual aktif) -->
          <div id="wrapperManual" class="hidden space-y-3">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Nama Mahasiswa (Manual)</label>
              <input type="text" name="nama_manual" id="inputNamaManual" placeholder="Contoh: Siti Aisyah" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-emerald-600 font-medium">
            </div>
            <div>
              <label class="block font-bold text-slate-700 mb-1">Nomor WhatsApp (Manual)</label>
              <input type="tel" name="wa_manual" id="inputWaManual" placeholder="08123456789" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-emerald-600 font-mono">
            </div>
          </div>

          <!-- Pilihan Nomor Kamar -->
          <div>
            <label class="block font-bold text-slate-700 mb-1">Pilih Nomor Kamar <span class="text-rose-500">*</span></label>
            <select name="nomor_kamar" id="selectKamar" required class="w-full px-3 py-2.5 rounded-xl border border-slate-300 font-semibold focus:outline-none focus:border-emerald-600">
              <?php foreach ($kamar as $km): ?>
                <option value="<?= $km['nomor_kamar']; ?>" <?= $km['status'] === 'terisi' ? 'class="text-slate-400 bg-slate-50"' : 'class="font-bold text-emerald-700"'; ?>>
                  <?= $km['nomor_kamar']; ?> <?= $km['status'] === 'terisi' ? '(Sedang Terisi)' : '(Tersedia / Kosong)'; ?>
                </option>
              <?php endforeach; ?>
            </select>
          </div>

          <!-- Jatuh Tempo & Status Bayar -->
          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Tanggal Jatuh Tempo</label>
              <input type="date" name="jatuh_tempo" required value="<?= date('Y-m-d', strtotime('+1 month')); ?>" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:border-emerald-600 font-medium">
            </div>
            <div>
              <label class="block font-bold text-slate-700 mb-1">Status Pembayaran</label>
              <select name="status_bayar" class="w-full px-3 py-2.5 rounded-xl border border-slate-300 font-medium focus:outline-none focus:border-emerald-600">
                <option value="lunas">Lunas</option>
                <option value="jatuh_tempo">Jatuh Tempo (Belum Bayar)</option>
              </select>
            </div>
          </div>

          <button type="submit" class="w-full py-3 bg-emerald-700 hover:bg-emerald-800 text-white font-bold rounded-xl shadow-sm transition active:scale-95 mt-2">
            <i class="fa-solid fa-floppy-disk mr-1"></i> Simpan Data Check-in
          </button>
        </form>
      </div>

      <!-- PENCATATAN KEUANGAN KAS -->
      <div class="lg:col-span-6 bg-white rounded-2xl border border-slate-200 p-6 shadow-sm">
        <div class="pb-3 border-b border-slate-200 mb-4">
          <h3 class="text-base font-bold text-slate-900">Pencatatan Kas & Operasional</h3>
          <p class="text-xs text-slate-500">Catat penerimaan uang sewa atau beban operasional (Wi-Fi, listrik, perawatan)</p>
        </div>

        <form action="host.php" method="POST" class="space-y-3 text-xs mb-4">
          <input type="hidden" name="aksi" value="keuangan">
          <div class="grid grid-cols-2 gap-2">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Tanggal</label>
              <input type="date" name="tanggal" required value="<?= date('Y-m-d'); ?>" class="w-full p-2 border border-slate-300 rounded-xl">
            </div>
            <div>
              <label class="block font-bold text-slate-700 mb-1">Jenis Transaksi</label>
              <select name="tipe" class="w-full p-2 border border-slate-300 rounded-xl font-bold">
                <option value="in" class="text-emerald-600">Pemasukan (+)</option>
                <option value="out" class="text-rose-600">Pengeluaran (-)</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Keterangan</label>
            <input type="text" name="keterangan" required placeholder="Contoh: Sewa Kamar 01 / Tagihan Wi-Fi" class="w-full p-2 border border-slate-300 rounded-xl">
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Nominal (Rp)</label>
            <input type="number" name="nominal" required min="1000" step="1000" placeholder="850000" class="w-full p-2 border border-slate-300 rounded-xl font-mono">
          </div>

          <button type="submit" class="w-full py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl transition">
            + Tambah Catatan Transaksi
          </button>
        </form>

        <!-- DAFTAR HISTORI KAS TERAKHIR -->
        <div class="max-h-40 overflow-y-auto space-y-2 border-t border-slate-100 pt-3 text-xs">
          <?php if (empty($keuangan)): ?>
            <p class="text-center text-slate-400 py-3">Belum ada riwayat kas.</p>
          <?php else: ?>
            <?php foreach (array_slice($keuangan, 0, 5) as $ks): ?>
              <?php $is_in = ($ks['tipe'] === 'in'); ?>
              <div class="flex items-center justify-between p-2 rounded-xl bg-slate-50 border border-slate-100">
                <div>
                  <p class="font-bold text-slate-800"><?= htmlspecialchars($ks['keterangan']); ?></p>
                  <p class="text-[10px] text-slate-400"><?= $ks['tanggal']; ?></p>
                </div>
                <span class="font-bold font-mono <?= $is_in ? 'text-emerald-600' : 'text-rose-600'; ?>">
                  <?= $is_in ? '+' : '-'; ?> Rp <?= number_format($ks['nominal'], 0, ',', '.'); ?>
                </span>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
      </div>
    </div>

  </div>

  <script>
    // Memilih nomor kamar dari kartu visual
    function setCheckInKamar(nomorKamar) {
      const select = document.getElementById('selectKamar');
      if (select) {
        select.value = nomorKamar;
        document.getElementById('sectionFormCheckin').scrollIntoView({ behavior: 'smooth' });
      }
    }

    // Tampilkan pratinjau data calon mahasiswa saat dropdown dipilih
    function tampilkanPreviewCalon(selectEl) {
      const card = document.getElementById('previewCard');
      const opt = selectEl.options[selectEl.selectedIndex];

      if (selectEl.value === "") {
        card.classList.add('hidden');
        return;
      }

      document.getElementById('prevNama').innerText = opt.getAttribute('data-nama');
      document.getElementById('prevWa').innerText = 'WhatsApp: +' + opt.getAttribute('data-wa');
      document.getElementById('prevInfo').innerText = opt.getAttribute('data-jurusan') + ' • ' + opt.getAttribute('data-asal');
      card.classList.remove('hidden');
    }

    // Aksi tombol "Pilih" di tabel notifikasi langsung memilih ke dropdown
    function autoPilihCalon(pendaftarId) {
      switchCheckinMode('otomatis');
      const select = document.getElementById('selectCalonPendaftar');
      if (select) {
        select.value = pendaftarId;
        tampilkanPreviewCalon(select);
      }
      document.getElementById('sectionFormCheckin').scrollIntoView({ behavior: 'smooth' });
    }

    // Toggle Tab Mode Check-in (Otomatis vs Manual)
    function switchCheckinMode(mode) {
      const wOtomatis = document.getElementById('wrapperOtomatis');
      const wManual   = document.getElementById('wrapperManual');
      const btnOtomatis = document.getElementById('btnModeOtomatis');
      const btnManual   = document.getElementById('btnModeManual');
      const inputMode   = document.getElementById('inputMode');
      const selCalon    = document.getElementById('selectCalonPendaftar');
      const inNama      = document.getElementById('inputNamaManual');
      const inWa        = document.getElementById('inputWaManual');

      inputMode.value = mode;

      if (mode === 'otomatis') {
        wOtomatis.classList.remove('hidden');
        wManual.classList.add('hidden');
        btnOtomatis.className = "px-2.5 py-1 rounded-lg bg-white text-emerald-800 shadow-sm transition";
        btnManual.className   = "px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 transition";
        selCalon.required = true;
        inNama.required   = false;
        inWa.required     = false;
      } else {
        wOtomatis.classList.add('hidden');
        wManual.classList.remove('hidden');
        btnManual.className   = "px-2.5 py-1 rounded-lg bg-white text-emerald-800 shadow-sm transition";
        btnOtomatis.className = "px-2.5 py-1 rounded-lg text-slate-600 hover:text-slate-900 transition";
        selCalon.required = false;
        inNama.required   = true;
        inWa.required     = true;
      }
    }
  </script>
</body>
</html>