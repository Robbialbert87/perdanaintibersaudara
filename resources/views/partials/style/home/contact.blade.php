<!-- Contact Section -->
<section id="contact" class="contact section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h1 style="font-family: 'Quicksand', sans-serif; font-weight: bold; color: #13447f;">Hubungi Kami</h1>
        <p style="color: #7f9ab7;">Jangan ragu untuk berkonsultasi mengenai alat kesehatan & kebutuhan teknis faskes Anda
        </p>
    </div><!-- End Section Title -->

    <div class="container" data-aos="fade-up" data-aos-delay="100">

        <div class="row gy-5">

            <!-- Contact Info Box -->
            <div class="col-lg-5" data-aos="fade-right" data-aos-delay="200">
                <div class="info-box p-4 rounded-4 shadow-sm bg-white" style="border: 1px solid rgba(0,0,0,0.05);">
                    <h3
                        style="font-family: 'Quicksand', sans-serif; font-weight: bold; color: #13447f; margin-bottom: 25px; font-size: 1.5rem;">
                        Informasi Kontak</h3>

                    <div class="info-item d-flex align-items-start mb-4">
                        <div class="icon-wrapper me-3 d-flex align-items-center justify-content-center bg-light rounded-circle"
                            style="width: 50px; height: 50px; font-size: 1.3rem; color: #065cc2; flex-shrink: 0; background-color: rgba(6, 92, 194, 0.08) !important;">
                            <i class="bi bi-geo-alt"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 1.1rem; font-weight: bold; color: #13447f; margin-bottom: 5px;">Alamat
                                Kantor</h4>
                            <p style="margin: 0; font-size: 0.92rem; color: #5c7694; line-height: 1.6;">
                                Jalan Kepodang I No. 205 RT.24, Kelurahan Handil Jaya, Kec. Jelutung, Kota Jambi,
                                Provinsi Jambi
                            </p>
                        </div>
                    </div>

                    <div class="info-item d-flex align-items-start mb-4">
                        <div class="icon-wrapper me-3 d-flex align-items-center justify-content-center bg-light rounded-circle"
                            style="width: 50px; height: 50px; font-size: 1.3rem; color: #065cc2; flex-shrink: 0; background-color: rgba(6, 92, 194, 0.08) !important;">
                            <i class="bi bi-telephone"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 1.1rem; font-weight: bold; color: #13447f; margin-bottom: 5px;">
                                Telepon / WhatsApp</h4>
                            <p style="margin: 0; font-size: 0.92rem; color: #5c7694;">
                                <a href="https://wa.me/6285263056505" target="_blank"
                                    style="color: #5c7694; text-decoration: none; font-weight: 500;">
                                    +62 852-6305-6505
                                </a>
                            </p>
                        </div>
                    </div>

                    <div class="info-item d-flex align-items-start">
                        <div class="icon-wrapper me-3 d-flex align-items-center justify-content-center bg-light rounded-circle"
                            style="width: 50px; height: 50px; font-size: 1.3rem; color: #065cc2; flex-shrink: 0; background-color: rgba(6, 92, 194, 0.08) !important;">
                            <i class="bi bi-envelope"></i>
                        </div>
                        <div>
                            <h4 style="font-size: 1.1rem; font-weight: bold; color: #13447f; margin-bottom: 5px;">Email
                                Resmi</h4>
                            <p style="margin: 0; font-size: 0.92rem; color: #5c7694;">
                                <a href="mailto:perdanaintibersaudara@gmail.com"
                                    style="color: #5c7694; text-decoration: none; font-weight: 500;">
                                    perdanaintibersaudara@gmail.com
                                </a>
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-7" data-aos="fade-left" data-aos-delay="300">
                <form action="#" method="POST" class="php-email-form p-4 rounded-4 shadow-sm bg-white"
                    style="border: 1px solid rgba(0,0,0,0.05);">
                    @csrf
                    <div class="row gy-4">

                        <div class="col-md-6">
                            <label for="name-field" class="pb-2" style="font-weight: 600; color: #314862;">Nama
                                Lengkap</label>
                            <input type="text" name="name" id="name-field" class="form-control rounded-3"
                                style="padding: 10px 15px; border: 1px solid #d3dfed;" placeholder="Nama Anda" required>
                        </div>

                        <div class="col-md-6">
                            <label for="email-field" class="pb-2" style="font-weight: 600; color: #314862;">Alamat
                                Email</label>
                            <input type="email" class="form-control rounded-3" name="email" id="email-field"
                                style="padding: 10px 15px; border: 1px solid #d3dfed;" placeholder="Email Anda"
                                required>
                        </div>

                        <div class="col-md-12">
                            <label for="subject-field" class="pb-2"
                                style="font-weight: 600; color: #314862;">Subjek</label>
                            <input type="text" class="form-control rounded-3" name="subject" id="subject-field"
                                style="padding: 10px 15px; border: 1px solid #d3dfed;"
                                placeholder="Pertanyaan / Pengadaan / Jasa" required>
                        </div>

                        <div class="col-md-12">
                            <label for="message-field" class="pb-2" style="font-weight: 600; color: #314862;">Pesan
                                Anda</label>
                            <textarea class="form-control rounded-3" name="message" rows="5" id="message-field"
                                style="padding: 10px 15px; border: 1px solid #d3dfed;"
                                placeholder="Tuliskan kebutuhan fasilitas kesehatan Anda di sini..." required></textarea>
                        </div>

                        <div class="col-md-12 text-center mt-4">
                            <div class="loading">Memuat...</div>
                            <div class="error-message"></div>
                            <div class="sent-message">Pesan Anda telah terkirim. Terima kasih!</div>

                            <button type="submit" class="btn btn-primary"
                                style="background-color: #065cc2; border-color: #065cc2; padding: 12px 35px; border-radius: 50px; font-weight: 600; font-family: 'Quicksand', sans-serif;">
                                Kirim Pesan
                            </button>
                        </div>

                    </div>
                </form>
            </div>

        </div>

    </div>

</section><!-- /Contact Section -->
