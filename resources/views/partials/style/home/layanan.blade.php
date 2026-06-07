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
                        <div class="portfolio-entry bg-white shadow-sm rounded-4 overflow-hidden"
                            style="border: 1px solid rgba(0,0,0,0.05);">
                            @php $layananImg = $service->images[0] ?? $service->image; @endphp
                            <div class="entry-image position-relative" style="aspect-ratio: 4/3; overflow: hidden; background-color: #f0f4f9;">
                                <a href="{{ route('layanan.detail', $service->id) }}">
                                    @if($layananImg)
                                        <img src="{{ Storage::url($layananImg) }}"
                                            alt="{{ $service->title }}"
                                            class="img-fluid w-100 h-100 object-fit-contain"
                                            style="transition: transform 0.3s;"
                                            loading="lazy" decoding="async">
                                    @else
                                        <img src="{{ asset('style/assets/img/portfolio/portfolio-1.webp') }}"
                                            alt="{{ $service->title }}"
                                            class="img-fluid w-100 h-100 object-fit-contain"
                                            style="transition: transform 0.3s;"
                                            loading="lazy" decoding="async">
                                    @endif
                                </a>
                            </div>
                            <div class="entry-title-wrapper p-3 d-flex flex-column">
                                <h4 class="entry-title mb-1" style="font-size: 1.1rem; font-weight: bold;">
                                    <a href="{{ route('layanan.detail', $service->id) }}"
                                        style="color: #13447f; text-decoration: none; transition: 0.3s;"
                                        class="hover-primary">
                                        {{ $service->title }}
                                    </a>
                                </h4>
                                <p class="entry-category text-secondary mb-2 small" style="font-weight: 500;">Layanan</p>
                                <a href="{{ route('layanan.detail', $service->id) }}"
                                    class="read-more-link"
                                    style="color: #065cc2; font-weight: 600; font-size: 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: color 0.2s; margin-top: auto;">
                                    Selengkapnya &rarr;
                                </a>
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
            #layanan .portfolio-entry:hover img {
                transform: scale(1.05);
            }

            #layanan .portfolio-entry {
                transition: box-shadow 0.3s;
            }

            #layanan .portfolio-entry:hover {
                box-shadow: 0 8px 25px rgba(6, 92, 194, 0.15) !important;
            }

            .read-more-link:hover {
                color: #13447f !important;
                text-decoration: underline !important;
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
