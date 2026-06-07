@extends('layouts.style')

@section('title', 'About Us - (PIB) Perdana Inti Bersaudara')

@section('body-class', 'about-page')

@section('content')



    <!-- Main About Content Section -->
    <section class="about-detail section" style="padding-bottom: 80px;">
        <div class="container" data-aos="fade-up">
            <div class="row gy-5">

                <!-- Left Column: Company Profile Detail -->
                <div class="col-lg-8" data-aos="fade-right" data-aos-delay="100">
                    <div class="card border-0 shadow-sm rounded-4 h-100 bg-white" style="border: 1px solid rgba(0,0,0,0.05) !important;">
                        <div class="card-body p-4 p-lg-5">
                            <h2 style="font-family: 'Quicksand', sans-serif; font-weight: bold; color: #13447f; margin-bottom: 8px;">Company Profile</h2>
                            <h5 style="color: #065cc2; font-weight: 600; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 2px solid rgba(0,0,0,0.05);">(PIB) Perdana Inti Bersaudara</h5>

                            <p style="text-align: justify; font-size: 1.05rem; line-height: 1.8; color: #314862; margin-bottom: 20px;">
                                <strong>(PIB) Perdana Inti Bersaudara</strong> merupakan perusahaan nasional yang bergerak aktif di bidang pengadaan alat kesehatan, laboratorium, farmasi, serta penyediaan layanan teknis penunjang fasilitas kesehatan dan industri. Didirikan secara resmi sejak tahun 2020 di Provinsi Jambi, perusahaan hadir dengan komitmen tinggi untuk menghadirkan layanan yang profesional, terpercaya, dan berorientasi penuh pada standar kualitas tertinggi.
                            </p>

                            <p style="text-align: justify; font-size: 1.05rem; line-height: 1.8; color: #314862; margin-bottom: 30px;">
                                Berbekal dukungan tim engineering elektromedis berpengalaman dan jaringan distribusi sparepart orisinal, kami terus berkembang secara berkelanjutan demi memenuhi segala ekspektasi dan kebutuhan dari berbagai instansi pemerintah, rumah sakit daerah/swasta, laboratorium diagnostik, klinik pratama/utama, hingga sektor industri kesehatan di seluruh wilayah Indonesia.
                            </p>

                            <!-- Highlights Grid inside the profile card -->
                            <div class="row g-4 mb-4">
                                <div class="col-md-6">
                                    <div class="p-4 rounded-4 h-100" style="background-color: #f7faff; border-left: 4px solid #065cc2;">
                                        <h5 style="font-family: 'Quicksand', sans-serif; font-weight: bold; color: #13447f; margin-bottom: 10px;">Fokus Layanan</h5>
                                        <p class="mb-0 text-secondary" style="font-size: 0.9rem; line-height: 1.6;">
                                            Pengadaan alat laboratorium medis, peralatan kedokteran umum, jasa reparasi & pemeliharaan alat elektromedis, radiography (DR/CR), penyeimbang kelistrikan medis, serta penyediaan suku cadang asli.
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-4 rounded-4 h-100" style="background-color: #f7faff; border-left: 4px solid #2973cc;">
                                        <h5 style="font-family: 'Quicksand', sans-serif; font-weight: bold; color: #13447f; margin-bottom: 10px;">Layanan Penunjang</h5>
                                        <p class="mb-0 text-secondary" style="font-size: 0.9rem; line-height: 1.6;">
                                            Aktivitas pengujian teknis, kalibrasi faskes, aktivitas konstruksi sipil khusus ruangan pelayanan kesehatan (seperti pengerjaan Pb shielding ruangan X-Ray/radiasi rontgen standar BAPETEN).
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <p style="text-align: justify; font-size: 1.05rem; line-height: 1.8; color: #314862; margin-bottom: 30px;">
                                Sebagai entitas badan usaha yang berpayung hukum legalitas lengkap di Kementerian Hukum dan HAM Republik Indonesia, (PIB) Perdana Inti Bersaudara senantiasa memegang teguh integritas, transparansi administrasi, serta kemudahan dalam setiap kerja sama yang dirintis demi terwujudnya hubungan kemitraan yang berkelanjutan.
                            </p>

                            <div class="p-4 rounded-4" style="background-color: rgba(6, 92, 194, 0.05); border: 1px dashed rgba(6, 92, 194, 0.2);">
                                <p class="mb-0 fst-italic" style="color: #065cc2; line-height: 1.7; font-size: 0.98rem;">
                                    "Melalui kerja sama yang erat, inovasi berkelanjutan, serta komitmen peningkatan kapasitas teknisi elektromedis, kami terus berupaya menjadi pilar terpercaya demi mendukung keandalan pelayanan kesehatan terbaik di seluruh Indonesia."
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Legalities & Contact Card Sidebars -->
                <div class="col-lg-4" data-aos="fade-left" data-aos-delay="200">
                    
                    <!-- Legalities Sidebar Card -->
                    <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white" style="border: 1px solid rgba(0,0,0,0.05) !important;">
                        <div class="card-body p-4">
                            <h4 style="font-family: 'Quicksand', sans-serif; font-weight: bold; color: #13447f; margin-bottom: 20px; padding-bottom: 15px; border-bottom: 1px solid rgba(0,0,0,0.05);">
                                <i class="bi bi-shield-check text-primary me-2"></i> Legalitas Perusahaan
                            </h4>

                            <div class="d-flex align-items-start mb-3">
                                <div class="icon me-3" style="font-size: 1.5rem; color: #065cc2;">
                                    <i class="bi bi-file-earmark-text"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1" style="font-weight: bold; color: #314862;">NIB (Nomor Induk Berusaha)</h6>
                                    <p class="mb-0 text-secondary small">0220101661815</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-3">
                                <div class="icon me-3" style="font-size: 1.5rem; color: #065cc2;">
                                    <i class="bi bi-card-checklist"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1" style="font-weight: bold; color: #314862;">SIUP</h6>
                                    <p class="mb-0 text-secondary small">Terdaftar Resmi & Aktif</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-3">
                                <div class="icon me-3" style="font-size: 1.5rem; color: #065cc2;">
                                    <i class="bi bi-bank"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1" style="font-weight: bold; color: #314862;">Kemenkumham RI</h6>
                                    <p class="mb-0 text-secondary small">Terdaftar Resmi pada Sistem Administrasi Badan Usaha</p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start">
                                <div class="icon me-3" style="font-size: 1.5rem; color: #065cc2;">
                                    <i class="bi bi-building-gear"></i>
                                </div>
                                <div>
                                    <h6 class="mb-1" style="font-weight: bold; color: #314862;">Status Penanaman Modal</h6>
                                    <p class="mb-0 text-secondary small">PMDN (Penanaman Modal Dalam Negeri)</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Details Sidebar Card -->
                    <div class="card border-0 shadow-sm rounded-4" style="background: linear-gradient(135deg, #13447f 0%, #065cc2 100%); color: white;">
                        <div class="card-body p-4">
                            <h4 class="text-white mb-4 pb-3" style="font-family: 'Quicksand', sans-serif; font-weight: bold; border-bottom: 1px solid rgba(255,255,255,0.15);">
                                <i class="bi bi-headset me-2"></i> Kontak Hubung
                            </h4>

                            <div class="d-flex align-items-start mb-4">
                                <div class="me-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; flex-shrink: 0; background-color: rgba(255,255,255,0.2);">
                                    <i class="bi bi-geo-alt text-white" style="font-size: 1.25rem;"></i>
                                </div>
                                <div>
                                    <h6 class="text-white mb-1" style="font-weight: bold;">Alamat Kantor</h6>
                                    <p class="mb-0 small lh-base" style="color: rgba(255,255,255,0.8);">
                                        Jalan Kepodang I No. 205 RT.24<br>
                                        Kelurahan Handil Jaya, Kec. Jelutung<br>
                                        Kota Jambi, Provinsi Jambi
                                    </p>
                                </div>
                            </div>

                            <div class="d-flex align-items-start mb-4">
                                <div class="me-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; flex-shrink: 0; background-color: rgba(255,255,255,0.2);">
                                    <i class="bi bi-envelope text-white" style="font-size: 1.25rem;"></i>
                                </div>
                                <div>
                                    <h6 class="text-white mb-1" style="font-weight: bold;">Email Kantor</h6>
                                    <a href="mailto:perdanaintibersaudara@gmail.com" class="small text-decoration-none" style="color: rgba(255,255,255,0.8); font-weight: 500;">
                                        perdanaintibersaudara@gmail.com
                                    </a>
                                </div>
                            </div>

                            <div class="d-flex align-items-start">
                                <div class="me-3 rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; flex-shrink: 0; background-color: rgba(255,255,255,0.2);">
                                    <i class="bi bi-telephone text-white" style="font-size: 1.25rem;"></i>
                                </div>
                                <div>
                                    <h6 class="text-white mb-1" style="font-weight: bold;">Telepon / WA</h6>
                                    <a href="https://wa.me/6281274044912" target="_blank" class="small text-decoration-none" style="color: rgba(255,255,255,0.8); font-weight: 500;">
                                        +62 812-7404-4912
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </section>

@endsection
