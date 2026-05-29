<!-- Portfolio Section -->
<section id="produk" class="portfolio section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h1 style="font-family: 'Quicksand', sans-serif; font-weight: bold; color: #13447f;">Produk Kami</h1>
        <p style="color: #7f9ab7;">Katalog alat kesehatan, peralatan radiography, & suku cadang elektromedis pilihan</p>
    </div><!-- End Section Title -->

    <div class="container">

        <div class="swiper produk-swiper" data-aos="fade-up" data-aos-delay="200">
            <div class="swiper-wrapper">
                @forelse($products as $product)
                    <div class="swiper-slide">
                        <div class="portfolio-entry bg-white shadow-sm rounded-4 overflow-hidden"
                            style="border: 1px solid rgba(0,0,0,0.05);">
                            <div class="entry-image position-relative" style="aspect-ratio: 4/3; overflow: hidden; background-color: #f0f4f9;">
                                <a href="{{ route('produk.detail', $product->id) }}">
                                    @if (!empty($product->images) && count($product->images) > 0)
                                        <img src="{{ asset('storage/' . $product->images[0]) }}"
                                            alt="{{ $product->name }}"
                                            class="img-fluid w-100 h-100 object-fit-cover"
                                            style="transition: transform 0.3s;"
                                            loading="lazy" decoding="async">
                                    @else
                                        <img src="{{ asset('style/assets/img/portfolio/portfolio-1.webp') }}"
                                            alt="{{ $product->name }}"
                                            class="img-fluid w-100 h-100 object-fit-cover"
                                            style="transition: transform 0.3s;"
                                            loading="lazy" decoding="async">
                                    @endif
                                </a>
                            </div>
                            <div class="entry-title-wrapper p-3">
                                <h4 class="entry-title mb-1" style="font-size: 1.1rem; font-weight: bold;">
                                    <a href="{{ route('produk.detail', $product->id) }}"
                                        style="color: #13447f; text-decoration: none; transition: 0.3s;"
                                        class="hover-primary">
                                        {{ $product->name }}
                                    </a>
                                </h4>
                                <p class="entry-category text-secondary mb-0 small" style="font-weight: 500;">
                                    {{ $product->category }}</p>
                            </div>
                        </div>
                    </div><!-- End Portfolio Item -->
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Produk akan segera ditampilkan.</p>
                    </div>
                @endforelse
            </div><!-- End Portfolio Container -->
            <div class="swiper-pagination produk-pagination mt-4 position-relative"></div>
        </div>

    </div>

    @push('styles')
        <style>
            #produk .portfolio-entry:hover img {
                transform: scale(1.05);
            }

            #produk .portfolio-entry {
                transition: box-shadow 0.3s;
            }

            #produk .portfolio-entry:hover {
                box-shadow: 0 8px 25px rgba(6, 92, 194, 0.15) !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                new Swiper('.produk-swiper', {
                    loop: true,
                    speed: 800,
                    autoplay: {
                        delay: 4000,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: true
                    },
                    pagination: {
                        el: '.produk-pagination',
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

</section><!-- /Portfolio Section -->
