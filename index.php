<?php
require_once "koneksi.php";

// Ambil data status 9 kamar langsung dari MySQL
$stmt = $pdo->query("SELECT * FROM kamar ORDER BY id ASC");
$kamar_list = $stmt->fetchAll();

// Hitung kamar kosong
$kamar_kosong = 0;
foreach ($kamar_list as $k) {
    if ($k['status'] === 'tersedia') {
        $kamar_kosong++;
    }
}
?>

<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
  <title>Debang Guest - Kost Putri Ciputat Timur</title>
  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
  <!-- Google Font Plus Jakarta Sans -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
  <!-- AOS Animation on Scroll CSS -->
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <script>
    tailwind.config = {
      theme: {
        extend: {
          fontFamily: {
            sans: ['"Plus Jakarta Sans"', 'sans-serif'],
          },
          colors: {
            adem: {
              50: '#f4f7f4',
              100: '#e7ede7',
              200: '#d1ddd1',
              300: '#adc4ae',
              400: '#83a585',
              500: '#608663',
              600: '#4a6b4c',
              700: '#3c553e',
              800: '#324534',
              900: '#1b261c',
            }
          }
        }
      }
    }
  </script>

  <style>
    html, body {
      max-width: 100%;
      overflow-x: hidden;
    }

    ::-webkit-scrollbar {
      width: 6px;
    }
    ::-webkit-scrollbar-track {
      background: #f4f6f4;
    }
    ::-webkit-scrollbar-thumb {
      background: #c3d4c5;
      border-radius: 4px;
    }
    ::-webkit-scrollbar-thumb:hover {
      background: #9ab49d;
    }

    .bg-ambient-pattern {
      background-color: #f8faf8;
      background-image: radial-gradient(rgba(96, 134, 99, 0.08) 1px, transparent 1px);
      background-size: 24px 24px;
    }
  </style>
</head>
<body class="bg-ambient-pattern text-slate-700 font-sans antialiased selection:bg-adem-600 selection:text-white relative overflow-x-hidden">

  <!-- Efek Gradasi Latar Adem -->
  <div class="fixed top-0 left-0 w-72 sm:w-96 md:w-[500px] h-72 sm:h-96 md:h-[500px] bg-gradient-to-br from-emerald-100/40 via-teal-50/20 to-transparent rounded-full blur-3xl pointer-events-none -z-10"></div>
  <div class="fixed bottom-0 right-0 w-80 sm:w-[500px] md:w-[600px] h-80 sm:h-[500px] md:h-[600px] bg-gradient-to-tl from-adem-100/50 via-slate-100/30 to-transparent rounded-full blur-3xl pointer-events-none -z-10"></div>

  <!-- ========================================== -->
  <!-- NAVBAR UTAMA PUBLIK (RESPONSIF PENUH)      -->
  <!-- ========================================== -->
  <header class="sticky top-0 z-40 bg-white/90 backdrop-blur-md border-b border-adem-200/60 shadow-sm transition-all duration-300">
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between gap-2">
      
      <!-- Logo & Identitas Kost -->
      <a href="#" class="flex items-center gap-2 sm:gap-3 group shrink min-w-0">
        <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl sm:rounded-2xl bg-gradient-to-tr from-adem-700 to-adem-500 flex items-center justify-center text-white text-xs sm:text-base font-black shadow-md shadow-adem-600/25 shrink-0">
          DG
        </div>
        <div class="min-w-0">
          <div class="flex items-center gap-1.5">
            <h1 class="text-sm sm:text-lg font-extrabold text-slate-800 tracking-tight leading-none truncate">Debang Guest</h1>
            <span class="bg-adem-100 text-adem-800 text-[9px] sm:text-[10px] font-bold px-1.5 py-0.5 rounded-full border border-adem-200/80 shrink-0">Putri</span>
          </div>
          <p class="text-[10px] sm:text-xs text-adem-700 font-medium mt-0.5 truncate">Kost Mahasiswi</p>
        </div>
      </a>

      <!-- Menu Navigasi Desktop / iPad -->
      <nav class="hidden lg:flex items-center gap-1.5 bg-adem-50/80 p-1.5 rounded-2xl border border-adem-200/70 shadow-inner">
        <a href="#galeri" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-adem-800 hover:bg-white hover:shadow-sm transition-all duration-200 flex items-center gap-1.5">
          <i class="fa-solid fa-camera text-adem-500 text-xs"></i>
          <span>Dokumentasi</span>
        </a>
        <a href="#lokasi" class="px-4 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-adem-800 hover:bg-white hover:shadow-sm transition-all duration-200 flex items-center gap-1.5">
          <i class="fa-solid fa-location-dot text-adem-500 text-xs"></i>
          <span>Lokasi</span>
        </a>
        <a href="#daftar" class="px-4 py-2 rounded-xl text-xs font-bold text-adem-700 hover:text-adem-900 hover:bg-white hover:shadow-sm transition-all duration-200 flex items-center gap-1.5">
          <i class="fa-solid fa-file-pen text-adem-500 text-xs"></i>
          <span>Form Pendaftaran</span>
        </a>
      </nav>

      <!-- Tombol WhatsApp & Menu Hamburger -->
      <div class="flex items-center gap-1.5 sm:gap-2 shrink-0">
        <!-- Bu Salamah -->
        <a href="https://wa.me/6282213159737?text=Halo%20Bu%20Salamah,%20saya%20ingin%20tanya%20kost%20Debang%20Guest" target="_blank" class="bg-adem-600 hover:bg-adem-700 text-white p-2 sm:px-3 sm:py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-sm transition active:scale-95" title="Chat Bu Salamah">
          <i class="fa-brands fa-whatsapp text-sm sm:text-base"></i>
          <span class="hidden md:inline">Bu Salamah</span>
        </a>

        <!-- Bu Yuyun -->
        <a href="https://wa.me/6281377028099?text=Halo%20Bu%20Yuyun,%20saya%20ingin%20tanya%20kost%20Debang%20Guest" target="_blank" class="bg-adem-600 hover:bg-adem-700 text-white p-2 sm:px-3 sm:py-2 rounded-xl text-xs font-bold flex items-center gap-1.5 shadow-sm transition active:scale-95" title="Chat Bu Yuyun">
          <i class="fa-brands fa-whatsapp text-sm sm:text-base"></i>
          <span class="hidden md:inline">Bu Yuyun</span>
        </a>

        <!-- Hamburger Button untuk Layar Mobile -->
        <button onclick="toggleMobileMenu()" class="lg:hidden p-2 rounded-xl text-slate-700 hover:bg-adem-100 border border-adem-200 text-sm transition" aria-label="Buka Menu">
          <i id="hamburgerIcon" class="fa-solid fa-bars text-base"></i>
        </button>
      </div>
    </div>

    <!-- Dropdown Mobile Menu Melayang (Tidak Mendorong Konten) -->
    <div id="mobileMenu" class="hidden lg:hidden absolute top-full left-0 w-full bg-white/95 backdrop-blur-lg border-b border-adem-200 shadow-xl px-4 py-3 space-y-2 z-50">
      <a href="#galeri" onclick="toggleMobileMenu()" class="flex items-center gap-2.5 py-2.5 px-3 rounded-xl text-xs font-semibold text-slate-700 hover:bg-adem-50 hover:text-adem-700 transition">
        <i class="fa-solid fa-camera text-adem-500 w-4 text-center"></i>
        <span>Dokumentasi Kost</span>
      </a>
      <a href="#lokasi" onclick="toggleMobileMenu()" class="flex items-center gap-2.5 py-2.5 px-3 rounded-xl text-xs font-semibold text-slate-700 hover:bg-adem-50 hover:text-adem-700 transition">
        <i class="fa-solid fa-location-dot text-adem-500 w-4 text-center"></i>
        <span>Lokasi & Peta</span>
      </a>
      <a href="#daftar" onclick="toggleMobileMenu()" class="flex items-center gap-2.5 py-2.5 px-3 rounded-xl text-xs font-bold text-adem-700 hover:bg-adem-50 transition">
        <i class="fa-solid fa-file-pen text-adem-500 w-4 text-center"></i>
        <span>Form Pendaftaran Calon Kost</span>
      </a>
    </div>
  </header>

  <!-- ========================================== -->
  <!-- HERO SECTION                               -->
  <!-- ========================================== -->
  <section class="py-10 sm:py-16 md:py-20 border-b border-adem-200/50 overflow-hidden relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid lg:grid-cols-12 gap-8 sm:gap-10 items-center">
        <div class="lg:col-span-7 space-y-4 sm:space-y-6 text-center lg:text-left" data-aos="fade-right" data-aos-duration="800">
          <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-adem-100/90 text-adem-800 text-xs font-semibold border border-adem-200">
            <span class="w-2 h-2 rounded-full bg-adem-500 animate-pulse"></span>
            Hunian Kost Putri Adem & Eksklusif
          </div>
          <h2 class="text-2xl sm:text-4xl md:text-5xl font-black text-slate-800 tracking-tight leading-tight">
            Selamat Datang di <span class="text-adem-600">Debang Guest</span>
          </h2>
          <p class="text-sm sm:text-base md:text-lg text-slate-600 max-w-2xl mx-auto lg:mx-0 font-normal leading-relaxed">
            Kost putri ideal untuk mahasiswi dengan lingkungan sejuk, tenang, bersih, dan aman. Silakan isi formulir pendaftaran untuk menanyakan ketersediaan kamar, pilihan tipe kamar, tarif sewa bulanan, dan jadwal survei lokasi langsung via WhatsApp pengelola kami.[cite: 3]
          </p>

          <div class="pt-2 flex flex-col sm:flex-row items-center justify-center lg:justify-start gap-3 sm:gap-4">
            <a href="#daftar" class="w-full sm:w-auto text-center bg-adem-600 hover:bg-adem-700 text-white px-7 py-3.5 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 shadow-lg shadow-adem-600/25 transition active:scale-95">
              <i class="fa-solid fa-file-signature"></i>
              <span>Isi Form Calon Kost</span>
            </a>
            <a href="#lokasi" class="w-full sm:w-auto text-center bg-white/90 hover:bg-white text-slate-700 border border-adem-200 px-6 py-3.5 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 shadow-sm transition">
              <i class="fa-solid fa-map-location-dot text-adem-600"></i>
              <span>Lihat Lokasi</span>
            </a>
          </div>
        </div>

        <div class="lg:col-span-5" data-aos="fade-left" data-aos-duration="800" data-aos-delay="200">
          <div class="bg-white/95 backdrop-blur p-5 sm:p-6 rounded-3xl border border-adem-200/80 shadow-xl shadow-slate-200/50 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-adem-100">
              <span class="text-xs font-bold uppercase tracking-wider text-slate-400">Kontak WhatsApp Host</span>
              <span class="text-xs bg-adem-100 text-adem-800 px-2.5 py-0.5 rounded-full font-bold">Respon Cepat</span>
            </div>
            
            <p class="text-xs text-slate-500">
              Punya pertanyaan seputar fasilitas dan biaya? Hubungi langsung salah satu pengelola kost kami:[cite: 3]
            </p>

            <div class="space-y-3">
              <a href="https://wa.me/6282213159737?text=Halo%20Bu%20Salamah,%20saya%20ingin%20tanya%20info%20kamar%20kost%20Debang%20Guest" target="_blank" class="flex items-center justify-between p-3.5 rounded-2xl bg-adem-50/70 hover:bg-adem-100/80 border border-adem-200 transition group">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-adem-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-brands fa-whatsapp"></i>
                  </div>
                  <div>
                    <p class="text-sm font-bold text-slate-800 group-hover:text-adem-700">Bu Salamah</p>
                    <p class="text-xs text-slate-500 font-mono">+62 822-1315-9737</p>
                  </div>
                </div>
                <span class="text-xs font-bold text-adem-700 flex items-center gap-1">Chat WA <i class="fa-solid fa-arrow-right text-[10px]"></i></span>
              </a>

              <a href="https://wa.me/6281377028099?text=Halo%20Bu%20Yuyun,%20saya%20ingin%20tanya%20info%20kamar%20kost%20Debang%20Guest" target="_blank" class="flex items-center justify-between p-3.5 rounded-2xl bg-adem-50/70 hover:bg-adem-100/80 border border-adem-200 transition group">
                <div class="flex items-center gap-3">
                  <div class="w-10 h-10 rounded-xl bg-adem-600 text-white flex items-center justify-center text-lg shadow-sm">
                    <i class="fa-brands fa-whatsapp"></i>
                  </div>
                  <div>
                    <p class="text-sm font-bold text-slate-800 group-hover:text-adem-700">Bu Yuyun</p>
                    <p class="text-xs text-slate-500 font-mono">+62 813-7702-8099</p>
                  </div>
                </div>
                <span class="text-xs font-bold text-adem-700 flex items-center gap-1">Chat WA <i class="fa-solid fa-arrow-right text-[10px]"></i></span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========================================== -->
  <!-- SECTION GALERI FOTO (3 FOTO ADAPTIF)       -->
  <!-- ========================================== -->
  <section id="galeri" class="py-12 sm:py-16 md:py-20 border-b border-adem-200/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center max-w-2xl mx-auto mb-8 sm:mb-12" data-aos="fade-up">
        <span class="text-adem-600 font-bold text-xs uppercase tracking-wider">Dokumentasi Kost</span>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 mt-1">Galeri Debang Guest</h2>
        <p class="text-slate-500 text-xs sm:text-sm mt-2">Gambaran lingkungan kamar, bangunan luar, dan fasilitas tempat tinggal.</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- FOTO 1: TAMPANG LUAR -->
        <div class="bg-white rounded-3xl overflow-hidden border border-adem-200/80 shadow-md shadow-slate-100 flex flex-col justify-between" data-aos="zoom-in" data-aos-delay="100">
          <div class="relative h-56 sm:h-64 overflow-hidden bg-slate-200">
            <!-- (ISI SENDIRI) Ganti src dengan path file Anda, misal: ./foto_depan.jpg -->
            <img src="https://images.unsplash.com/photo-1541971875076-8f970d573be6?auto=format&fit=crop&w=800&q=80" alt="(ISI SENDIRI) Foto Luar Debang Guest" class="w-full h-full object-cover hover:scale-105 transition duration-500" loading="lazy">
            <div class="absolute top-3 left-3 bg-adem-900/80 backdrop-blur text-white text-[11px] px-3.5 py-1 rounded-full font-bold shadow-sm">
              Foto 1: Area Luar & Gerbang
            </div>
          </div>
          <div class="p-4 sm:p-5">
            <h3 class="font-bold text-slate-800 text-sm">(ISI SENDIRI) Tampak Bangunan Depan & Parkiran</h3>
            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">(ISI SENDIRI) Lingkungan aman dengan pagar dan akses parkir motor yang luas.</p>
          </div>
        </div>

        <!-- FOTO 2: INTERIOR KAMAR -->
        <div class="bg-white rounded-3xl overflow-hidden border border-adem-200/80 shadow-md shadow-slate-100 flex flex-col justify-between" data-aos="zoom-in" data-aos-delay="200">
          <div class="relative h-56 sm:h-64 overflow-hidden bg-slate-200">
            <!-- (ISI SENDIRI) Ganti src dengan foto kamar Anda, misal: ./foto_kamar.jpg -->
            <img src="https://images.unsplash.com/photo-1555854877-bab0e564b8d5?auto=format&fit=crop&w=800&q=80" alt="(ISI SENDIRI) Foto Dalam Kamar Debang Guest" class="w-full h-full object-cover hover:scale-105 transition duration-500" loading="lazy">
            <div class="absolute top-3 left-3 bg-adem-900/80 backdrop-blur text-white text-[11px] px-3.5 py-1 rounded-full font-bold shadow-sm">
              Foto 2: Interior Kamar
            </div>
          </div>
          <div class="p-4 sm:p-5">
            <h3 class="font-bold text-slate-800 text-sm">(ISI SENDIRI) Kamar Siap Huni</h3>
            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">(ISI SENDIRI) Kasur bersih, meja belajar, ventilasi jendela sehat dan lemari pakaian.</p>
          </div>
        </div>

        <!-- FOTO 3: FASILITAS UMUM -->
        <div class="bg-white rounded-3xl overflow-hidden border border-adem-200/80 shadow-md shadow-slate-100 flex flex-col justify-between md:col-span-2 lg:col-span-1" data-aos="zoom-in" data-aos-delay="300">
          <div class="relative h-56 sm:h-64 overflow-hidden bg-slate-200">
            <!-- (ISI SENDIRI) Ganti src dengan foto fasilitas Anda, misal: ./foto_fasilitas.jpg -->
            <img src="https://images.unsplash.com/photo-1584622650111-993a426fbf0a?auto=format&fit=crop&w=800&q=80" alt="(ISI SENDIRI) Foto Fasilitas Bersama" class="w-full h-full object-cover hover:scale-105 transition duration-500" loading="lazy">
            <div class="absolute top-3 left-3 bg-adem-900/80 backdrop-blur text-white text-[11px] px-3.5 py-1 rounded-full font-bold shadow-sm">
              Foto 3: Fasilitas Umum
            </div>
          </div>
          <div class="p-4 sm:p-5">
            <h3 class="font-bold text-slate-800 text-sm">(ISI SENDIRI) Kamar Mandi & Area Bersama</h3>
            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">(ISI SENDIRI) Air mengalir lancar, bersih, serta area jemur dan dapur bersama yang nyaman.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========================================== -->
  <!-- SECTION LOKASI & MAPS                      -->
  <!-- ========================================== -->
  <section id="lokasi" class="py-12 sm:py-16 md:py-20 border-b border-adem-200/50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="grid lg:grid-cols-12 gap-8 items-center">
        <div class="lg:col-span-5 space-y-4" data-aos="fade-right">
          <span class="text-adem-600 font-bold text-xs uppercase tracking-wider">Akses & Alamat</span>
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800">Lokasi Debang Guest</h2>
          <div class="p-4 sm:p-5 rounded-3xl bg-white border border-adem-200 shadow-sm space-y-2">
            <p class="text-xs font-bold text-adem-700 uppercase">Alamat Lengkap:</p>
            <p class="text-xs sm:text-sm font-semibold text-slate-800 leading-relaxed">
              JL. Poncol Indah VII No.2-88, Cirendeu, Kec. Ciputat Timur, Kota Tangerang Selatan, Banten 15419
            </p>
          </div>

          <div class="pt-2">
            <!-- Tautan Langsung ke Koordinat Presisi Debang Guest -->
            <a 
              href="https://www.google.com/maps?q=-6.299950105174705,106.76828763823924" 
              target="_blank" 
              rel="noopener noreferrer" 
              class="inline-flex items-center gap-2 text-adem-600 hover:text-adem-700 font-extrabold text-sm sm:text-base tracking-wide hover:underline transition"
            >
              <i class="fa-solid fa-map-location-dot text-base sm:text-lg"></i>
              <span>TEMUKAN LOKASI</span>
              <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
            </a>
            <p class="text-[11px] sm:text-xs text-slate-500 mt-1">Klik link di atas untuk membuka peta rute via Google Maps secara langsung.</p>
          </div>
        </div>

        <div class="lg:col-span-7" data-aos="fade-left">
          <div class="rounded-3xl overflow-hidden border border-adem-200 shadow-lg h-72 sm:h-80 md:h-96 bg-white">
            <!-- Iframe Embed Langsung Mengarah ke Titik Koordinat -->
            <iframe 
              title="Peta Lokasi Debang Guest" 
              class="w-full h-full border-0" 
              loading="lazy" 
              allowfullscreen 
              referrerpolicy="no-referrer-when-downgrade" 
              src="https://maps.google.com/maps?q=-6.299950105174705,106.76828763823924&hl=id&z=17&output=embed">
            </iframe>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- ========================================== -->
  <!-- SECTION PENDAFTARAN CALON ANAK KOST        -->
  <!-- ========================================== -->
  <section id="daftar" class="py-12 sm:py-16 md:py-20 border-b border-adem-200/50">
    <div class="max-w-3xl mx-auto px-4 sm:px-6" data-aos="fade-up">
      <div class="text-center mb-8">
        <span class="text-adem-600 font-bold text-xs uppercase tracking-wider">Formulir Pendaftaran Kost</span>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 mt-1">Pendaftaran Calon Anak Kost</h2>
        <p class="text-slate-500 text-xs sm:text-sm mt-2">
          Isi formulir berikut. Setelah formulir terkirim, Anda dapat langsung menghubungi WhatsApp Bu Salamah atau Bu Yuyun untuk menanyakan ketersediaan kamar, pilihan tipe, dan tarif sewa.[cite: 3]
        </p>
      </div>

      <div class="bg-white rounded-3xl p-5 sm:p-8 border border-adem-200 shadow-xl shadow-slate-200/40">
        <form id="publicRegisterForm" onsubmit="handlePublicRegister(event)" class="space-y-4 text-xs sm:text-sm">
          <div>
            <label class="block font-bold text-slate-700 mb-1.5">Nama Lengkap <span class="text-rose-500">*</span></label>
            <input type="text" id="regFullName" required placeholder="Contoh: Siti Aisyah" class="w-full px-3.5 sm:px-4 py-3 rounded-xl border border-adem-200 focus:outline-none focus:border-adem-500 focus:ring-2 focus:ring-adem-200/50 font-medium transition">
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-bold text-slate-700 mb-1.5">No WhatsApp Kamu <span class="text-rose-500">*</span></label>
              <div class="relative">
                <span class="absolute left-3.5 top-3 text-adem-600 font-bold">+62</span>
                <input type="tel" id="regWhatsApp" required placeholder="8123456789" class="w-full pl-12 pr-4 py-3 rounded-xl border border-adem-200 focus:outline-none focus:border-adem-500 focus:ring-2 focus:ring-adem-200/50 font-medium transition">
              </div>
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1.5">No WhatsApp Orang Tua <span class="text-rose-500">*</span></label>
              <div class="relative">
                <span class="absolute left-3.5 top-3 text-adem-600 font-bold">+62</span>
                <input type="tel" id="regParentWhatsApp" required placeholder="8139876543" class="w-full pl-12 pr-4 py-3 rounded-xl border border-adem-200 focus:outline-none focus:border-adem-500 focus:ring-2 focus:ring-adem-200/50 font-medium transition">
              </div>
            </div>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1.5">Tempat Tinggal (Kota / Alamat Asal) <span class="text-rose-500">*</span></label>
            <input type="text" id="regOriginAddress" required placeholder="Contoh: Bandung, Jawa Barat" class="w-full px-3.5 sm:px-4 py-3 rounded-xl border border-adem-200 focus:outline-none focus:border-adem-500 focus:ring-2 focus:ring-adem-200/50 font-medium transition">
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1.5">Jurusan / Kampus Kuliah <span class="text-rose-500">*</span></label>
            <input type="text" id="regMajor" required placeholder="Contoh: Farmasi - UNIVERSITAS MUHAMMADIYAH JAKARTA" class="w-full px-3.5 sm:px-4 py-3 rounded-xl border border-adem-200 focus:outline-none focus:border-adem-500 focus:ring-2 focus:ring-adem-200/50 font-medium transition">
          </div>

          <div class="pt-2">
            <button id="btnSubmitRegister" type="submit" class="w-full py-3.5 px-6 rounded-2xl bg-adem-600 hover:bg-adem-700 text-white font-bold text-sm flex items-center justify-center gap-2 shadow-lg shadow-adem-600/25 transition active:scale-95">
              <i class="fa-solid fa-paper-plane"></i>
              <span id="btnSubmitText">Kirim Formulir Pendaftaran</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </section>

  <!-- ============================================================== -->
  <!-- MODAL NOTIFIKASI SUKSES & CHAT WA (RESPONSIF POPUP)           -->
  <!-- ============================================================== -->
  <div id="userSuccessModal" class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-sm hidden flex items-center justify-center p-4 overflow-y-auto">
    <div class="bg-white rounded-3xl max-w-lg w-full p-5 sm:p-8 shadow-2xl border border-adem-200 text-center animate-in fade-in zoom-in duration-200 my-8">
      <div class="w-14 h-14 sm:w-16 sm:h-16 bg-adem-100 text-adem-600 rounded-full flex items-center justify-center mx-auto mb-4 text-xl sm:text-2xl shadow-inner">
        <i class="fa-solid fa-check"></i>
      </div>
      <h3 class="text-lg sm:text-xl font-black text-slate-800">Formulir Berhasil Terkirim!</h3>
      <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
        Data Anda sudah tersimpan di database Debang Guest. Silakan langsung lanjutkan obrolan ke salah satu kontak pengelola di bawah ini untuk konfirmasi ketersediaan kamar dan harga:[cite: 3]
      </p>

      <div class="mt-6 space-y-3 text-left">
        <a id="modalWASalamah" href="#" target="_blank" class="flex items-center justify-between p-3 sm:p-3.5 rounded-2xl bg-adem-50/70 hover:bg-adem-100 border border-adem-200 transition group">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-adem-600 text-white flex items-center justify-center text-lg shadow-sm">
              <i class="fa-brands fa-whatsapp"></i>
            </div>
            <div>
              <p class="text-xs sm:text-sm font-extrabold text-slate-800">Hubungi Bu Salamah</p>
              <p class="text-[11px] sm:text-xs text-adem-700 font-mono">+62 822-1315-9737</p>
            </div>
          </div>
          <span class="text-xs font-bold text-white bg-adem-600 px-3 sm:px-3.5 py-1.5 rounded-xl group-hover:bg-adem-700 transition shadow-sm whitespace-nowrap">
            Chat WA <i class="fa-solid fa-arrow-right ml-1"></i>
          </span>
        </a>

        <a id="modalWAYuyun" href="#" target="_blank" class="flex items-center justify-between p-3.5 rounded-2xl bg-adem-50/70 hover:bg-adem-100 border border-adem-200 transition group">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-adem-600 text-white flex items-center justify-center text-lg shadow-sm">
              <i class="fa-brands fa-whatsapp"></i>
            </div>
            <div>
              <p class="text-xs sm:text-sm font-extrabold text-slate-800">Hubungi Bu Yuyun</p>
              <p class="text-[11px] sm:text-xs text-adem-700 font-mono">+62 813-7702-8099</p>
            </div>
          </div>
          <span class="text-xs font-bold text-white bg-adem-600 px-3 sm:px-3.5 py-1.5 rounded-xl group-hover:bg-adem-700 transition shadow-sm whitespace-nowrap">
            Chat WA <i class="fa-solid fa-arrow-right ml-1"></i>
          </span>
        </a>
      </div>

      <div class="mt-6 pt-4 border-t border-slate-100">
        <button onclick="closeUserSuccessModal()" class="w-full py-2.5 rounded-xl text-xs font-bold text-slate-500 hover:text-slate-800 hover:bg-adem-50 transition">
          Tutup Halaman Ini
        </button>
      </div>
    </div>
  </div>

  <!-- FOOTER -->
  <footer class="bg-adem-900 text-white py-10 sm:py-12 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-xs text-adem-200 space-y-2">
      <p class="font-bold text-white text-sm sm:text-base tracking-wide">Debang Guest - Kost Putri Ciputat Timur</p>
      <p class="text-adem-300">JL. Poncol Indah VII No.2-88, Cirendeu, Kec. Ciputat Timur, Tangerang Selatan</p>
      <p class="pt-4 text-adem-400">&copy; <?= date('Y'); ?> Debang Guest. Seluruh hak cipta dilindungi.</p>
    </div>
  </footer>

  <!-- AOS Script -->
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script>
    AOS.init({
      once: true,
      duration: 700,
      offset: 60
    });

    const HOST_SALAMAH = '6282213159737';
    const HOST_YUYUN = '6281377028099';

    // Toggle menu navigasi pada layar HP / Android
    function toggleMobileMenu() {
      const menu = document.getElementById('mobileMenu');
      const icon = document.getElementById('hamburgerIcon');
      menu.classList.toggle('hidden');
      
      if (menu.classList.contains('hidden')) {
        icon.classList.remove('fa-xmark');
        icon.classList.add('fa-bars');
      } else {
        icon.classList.remove('fa-bars');
        icon.classList.add('fa-xmark');
      }
    }

    async function handlePublicRegister(e) {
      e.preventDefault();
      const btn = document.getElementById('btnSubmitRegister');
      const btnText = document.getElementById('btnSubmitText');

      const fullName = document.getElementById('regFullName').value.trim();
      let phone = document.getElementById('regWhatsApp').value.trim().replace(/[^0-9]/g, '');
      let parentPhone = document.getElementById('regParentWhatsApp').value.trim().replace(/[^0-9]/g, '');
      const originAddress = document.getElementById('regOriginAddress').value.trim();
      const major = document.getElementById('regMajor').value.trim();

      if (phone.startsWith('0')) phone = phone.substring(1);
      if (phone.startsWith('62')) phone = phone.substring(2);

      if (parentPhone.startsWith('0')) parentPhone = parentPhone.substring(1);
      if (parentPhone.startsWith('62')) parentPhone = parentPhone.substring(2);

      const formattedPhone = '62' + phone;
      const formattedParentPhone = '62' + parentPhone;

      btn.disabled = true;
      btnText.innerText = "Menyimpan ke Database...";

      try {
        const response = await fetch('simpan_pendaftar.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            nama_lengkap: fullName,
            no_wa: formattedPhone,
            no_wa_ortu: formattedParentPhone,
            tempat_tinggal: originAddress,
            jurusan: major
          })
        });

        const result = await response.json();

        if (result.status) {
          const waText = encodeURIComponent(
            `Halo Bu, saya sudah mengisi formulir pendaftaran calon kost Debang Guest di website.\n\n` +
            `• Nama: ${fullName}\n` +
            `• Asal: ${originAddress}\n` +
            `• Jurusan/Kampus: ${major}\n\n` +
            `Saya ingin menanyakan informasi ketersediaan kamar, tipe, harga sewa, dan jadwal survei lokasi. Terima kasih!`
          );

          document.getElementById('modalWASalamah').href = `https://wa.me/${HOST_SALAMAH}?text=${waText}`;
          document.getElementById('modalWAYuyun').href = `https://wa.me/${HOST_YUYUN}?text=${waText}`;
          document.getElementById('userSuccessModal').classList.remove('hidden');
          document.getElementById('publicRegisterForm').reset();
        } else {
          alert('Gagal menyimpan formulir: ' + result.message);
        }
      } catch (err) {
        alert('Gagal menghubungi server database. Pastikan modul Apache & MySQL di XAMPP aktif.');
      } finally {
        btn.disabled = false;
        btnText.innerText = "Kirim Formulir Pendaftaran";
      }
    }

    function closeUserSuccessModal() {
      document.getElementById('userSuccessModal').classList.add('hidden');
    }
  </script>
</body>
</html>