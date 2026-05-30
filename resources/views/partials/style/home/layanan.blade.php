<!-- Services Section -->
<section id="layanan" class="services section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h1 style="font-family: 'Quicksand', sans-serif; font-weight: bold; color: #13447f;">Layanan Kami</h1>
        <p style="color: #7f9ab7;">Layanan unggulan teknik medis dan pengadaan alat kesehatan</p>
    </div><!-- End Section Title -->

    <div class="container">
        <div class="swiper layanan-swiper" data-aos="fade-up" data-aos-delay="100">
            <div class="swiper-wrapper">
                @forelse($services as $service)
                    <div class="swiper-slide">
                        <div class="service-card bg-white rounded-4 overflow-hidden shadow-sm d-flex flex-column h-100"
                            style="border: 1px solid rgba(0,0,0,0.05);">

                            <!-- Image -->
                            <div style="aspect-ratio: 4/3; overflow: hidden; background-color: #f0f4f9;">
                                @if($service->image)
                                    <img src="{{ Storage::url($service->image) }}"
                                        alt="{{ $service->title }}"
                                        class="w-100 h-100 object-fit-cover"
                                        style="transition: transform 0.3s;"
                                        loading="lazy" decoding="async">
                                @else
                                    <img src="{{ asset('style/assets/img/portfolio/portfolio-1.webp') }}"
                                        alt="{{ $service->title }}"
                                        class="w-100 h-100 object-fit-cover"
                                        style="transition: transform 0.3s;"
                                        loading="lazy" decoding="async">
                                @endif
                            </div>

                            <!-- Content -->
                            <div class="card-body p-4 d-flex flex-column flex-grow-1">
                                <h4 style="font-family: 'Quicksand', sans-serif; font-weight: bold; color: #13447f; font-size: 1.2rem; margin-bottom: 16px;">
                                    {{ $service->title }}
                                </h4>

                                <div class="mt-auto">
                                    <a href="{{ route('layanan.detail', $service->id) }}"
                                        class="btn btn-primary w-100 d-flex justify-content-between align-items-center"
                                        style="background-color: #2973cc; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 600; font-size: 0.9rem;">
                                        <span>Selengkapnya</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div><!-- End Slide -->
                @empty
                    <div class="swiper-slide">
                        <div class="col-12 text-center py-5">
                            <p class="text-muted">Layanan akan segera ditampilkan.</p>
                        </div>
                    </div>
                @endforelse
            </div>
            <div class="swiper-pagination layanan-pagination mt-4 position-relative"></div>
        </div>
    </div>

    @push('styles')
        <style>
            #layanan .service-card:hover img {
                transform: scale(1.05);
            }

            #layanan .service-card {
                transition: box-shadow 0.3s;
            }

            #layanan .service-card:hover {
                box-shadow: 0 8px 25px rgba(6, 92, 194, 0.15) !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                new Swiper('.layanan-swiper', {
                    loop: true,
                    speed: 800,
                    autoplay: {
                        delay: 3500,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: true
                    },
                    pagination: {
                        el: '.layanan-pagination',
                        type: 'bullets',
                        clickable: true
                    },
                    breakpoints: {
                        320: { slidesPerView: 1, spaceBetween: 20 },
                        768: { slidesPerView: 2, spaceBetween: 20 },
                        1200: { slidesPerView: 3, spaceBetween: 30 }
                    }
                });
            });
        </script>
    @endpush

</section><!-- /Services Section -->
