<!-- Hero Section -->
<section id="hero" class="hero section">

    <div class="container">
        <div class="row g-0 align-items-center">

            <div class="col-lg-6 hero-content" data-aos="fade-right" data-aos-delay="100">
                <div class="content-wrapper">
                    <h1 class="hero-title"
                        style="font-family: 'Quicksand', sans-serif; font-weight: bold; color: #13447f;">
                        Solusi Digital <br>
                        <span class="typed" style="color: #065cc2;"
                            data-typed-items="Radiography Modern, Alat Kesehatan, Layanan Teknis Medis, Maintenance & Repair"></span>
                    </h1>
                    <p class="lead" style="font-size: 1.1rem; line-height: 1.7; color: #314862;">
                        <strong>(PIB) Perdana Inti Bersaudara</strong> adalah perusahaan yang bergerak di bidang
                        pengadaan alat kesehatan khususnya radiologi. Kami melayani jasa instalasi profesional,
                        perbaikan ahli, dan pemeliharaan berkala untuk peralatan radiologi seperti DR (Digital
                        Radiography) dan CR (Computed Radiography) di seluruh Indonesia.
                    </p>

                    <style>
                        .hero-stats {
                            display: flex;
                            flex-wrap: wrap;
                            gap: 1rem;
                            align-items: center;
                        }

                        .hero-stats .stat-item {
                            flex: 1 1 30%;
                            text-align: center;
                        }

                        @media (min-width: 768px) {
                            .hero-stats {
                                gap: 0;
                            }

                            .hero-stats .stat-item {
                                flex: 0 1 auto;
                                text-align: left;
                                padding-right: 1.5rem;
                            }

                            .hero-stats .stat-item:not(:last-child) {
                                border-right: 1px solid rgba(0, 0, 0, 0.1);
                                padding-left: 1.5rem;
                            }

                            .hero-stats .stat-item:first-child {
                                padding-left: 0;
                            }
                        }

                        .hero-actions {
                            display: flex;
                            flex-wrap: wrap;
                            gap: 10px;
                            justify-content: center;
                        }

                        @media (min-width: 768px) {
                            .hero-actions {
                                justify-content: flex-start;
                            }
                        }

                        .hero-services-swiper .swiper-pagination-bullet {
                            background: white;
                            opacity: 0.5;
                        }

                        .hero-services-swiper .swiper-pagination-bullet-active {
                            opacity: 1;
                            background: #065cc2;
                        }

                        @media (max-width: 767.98px) {
                            .hero-title {
                                font-size: 2rem;
                            }
                        }
                    </style>

                    <div class="hero-stats" data-aos="fade-up" data-aos-delay="200" style="margin-bottom: 2.5rem;">
                        <div class="stat-item">
                            <span class="purecounter" data-purecounter-start="0" data-purecounter-end="6"
                                data-purecounter-duration="2"
                                style="font-size: 2.5rem; font-weight: bold; color: #065cc2;">0</span>
                            <span class="stat-label"
                                style="font-size: 0.85rem; font-weight: 600; color: #7f9ab7; display: block;">Tahun
                                Pengalaman</span>
                        </div>
                        <div class="stat-item">
                            <span class="purecounter" data-purecounter-start="0" data-purecounter-end="100"
                                data-purecounter-duration="2"
                                style="font-size: 2.5rem; font-weight: bold; color: #065cc2;">0</span>
                            <span class="stat-label"
                                style="font-size: 0.85rem; font-weight: 600; color: #7f9ab7; display: block;">%
                                Legalitas Resmi</span>
                        </div>
                        <div class="stat-item">
                            <span class="purecounter" data-purecounter-start="0" data-purecounter-end="100"
                                data-purecounter-duration="2"
                                style="font-size: 2.5rem; font-weight: bold; color: #065cc2;">0</span>
                            <span class="stat-label"
                                style="font-size: 0.85rem; font-weight: 600; color: #7f9ab7; display: block;">%
                                Terpercaya</span>
                        </div>
                    </div>

                    <div class="hero-actions" data-aos="fade-up" data-aos-delay="300">
                        <a href="{{ route('about') }}" class="btn btn-primary"
                            style="background-color: #065cc2; border-color: #065cc2; padding: 12px 30px; border-radius: 50px;">Tentang
                            Kami</a>
                        <a href="#contact" class="btn btn-outline"
                            style="border-color: #065cc2; color: #065cc2; padding: 12px 30px; border-radius: 50px;">Hubungi
                            Kami</a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 hero-image mt-5 mt-lg-0" data-aos="fade-left" data-aos-delay="200">
                <div class="image-container position-relative">
                    <div class="swiper hero-services-swiper rounded-4 overflow-hidden"
                        style="border: 6px solid white; box-shadow: 0 20px 40px rgba(0,0,0,0.15);">
                        <div class="swiper-wrapper">
                            @forelse($services as $service)
                                <div class="swiper-slide position-relative">
                                    @if($service->image)
                                        <img src="{{ Storage::url($service->image) }}"
                                            alt="{{ $service->title }}"
                                            class="w-100 object-fit-cover"
                                            style="aspect-ratio: 4/3; display: block;">
                                    @else
                                        <img src="{{ asset('style/assets/img/health/Gemini_Generated_Image_mnrhe1mnrhe1mnrh.png') }}"
                                            alt="{{ $service->title }}"
                                            class="w-100 object-fit-cover"
                                            style="aspect-ratio: 4/3; display: block;">
                                    @endif
                                    <div class="position-absolute bottom-0 start-0 w-100 p-3"
                                        style="background: linear-gradient(transparent, rgba(0,0,0,0.7));">
                                        <h6 class="text-white mb-0 fw-semibold" style="font-family: 'Quicksand', sans-serif; text-shadow: 0 1px 3px rgba(0,0,0,0.3);">
                                            {{ $service->title }}
                                        </h6>
                                    </div>
                                </div>
                            @empty
                                <div class="swiper-slide">
                                    <img src="{{ asset('style/assets/img/health/Gemini_Generated_Image_mnrhe1mnrhe1mnrh.png') }}"
                                        alt="Layanan" class="w-100 object-fit-cover"
                                        style="aspect-ratio: 4/3; display: block;">
                                </div>
                            @endforelse
                        </div>
                        <div class="swiper-pagination hero-services-pagination"></div>
                    </div>
                    <div class="image-overlay position-absolute top-0 start-0 w-100 h-100"
                        style="border-radius: 24px; background: linear-gradient(45deg, #065cc2, rgba(6, 92, 194, 0.4)); opacity: 0.1; pointer-events: none;">
                    </div>
                </div>
            </div>

            @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        new Swiper('.hero-services-swiper', {
                            loop: true,
                            speed: 800,
                            autoplay: {
                                delay: 3500,
                                disableOnInteraction: false,
                                pauseOnMouseEnter: true
                            },
                            pagination: {
                                el: '.hero-services-pagination',
                                type: 'bullets',
                                clickable: true
                            },
                            effect: 'fade',
                            fadeEffect: {
                                crossFade: true
                            }
                        });
                    });
                </script>
            @endpush

        </div>
    </div>

</section><!-- /Hero Section -->
