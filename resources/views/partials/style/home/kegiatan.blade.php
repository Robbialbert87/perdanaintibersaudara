<!-- Kegiatan Section -->
<section id="kegiatan" class="portfolio section light-background">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h1 style="font-family: 'Quicksand', sans-serif; font-weight: bold; color: #13447f;">Kegiatan Kami</h1>
        <p style="color: #7f9ab7;">Dokumentasi aktivitas dan kegiatan terbaru (PIB) Perdana Inti Bersaudara</p>
    </div><!-- End Section Title -->

    <div class="container">
        <div class="swiper kegiatan-swiper" data-aos="fade-up" data-aos-delay="100">
            <div class="swiper-wrapper">
                @forelse($activities as $activity)
                    <div class="swiper-slide">
                        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100"
                            style="border: 1px solid rgba(0,0,0,0.05) !important;">
                            @if (!empty($activity->images) && count($activity->images) > 0)
                                <div style="aspect-ratio: 16/9; overflow: hidden; background-color: #f0f4f9;">
                                    <a href="{{ route('kegiatan.detail', $activity->id) }}">
                                        <img src="{{ asset('storage/' . $activity->images[0]) }}"
                                            alt="{{ $activity->title }}"
                                            class="card-img-top w-100 h-100 object-fit-cover"
                                            style="transition: transform 0.3s;"
                                            loading="lazy" decoding="async">
                                    </a>
                                </div>
                            @endif
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <i class="bi bi-calendar3" style="color: #065cc2;"></i>
                                    <small style="color: #7f9ab7; font-weight: 500;">
                                        {{ \Carbon\Carbon::parse($activity->date)->translatedFormat('d F Y') }}
                                    </small>
                                </div>
                                <h5 class="card-title mb-2"
                                    style="font-family: 'Quicksand', sans-serif; font-weight: bold; font-size: 1.1rem;">
                                    <a href="{{ route('kegiatan.detail', $activity->id) }}"
                                        style="color: #13447f; text-decoration: none;" class="hover-primary">
                                        {{ $activity->title }}
                                    </a>
                                </h5>
                                <p class="card-text mb-0"
                                    style="color: #7f9ab7; font-size: 0.9rem; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                                    {{ $activity->content }}
                                </p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Kegiatan akan segera ditampilkan.</p>
                    </div>
                @endforelse
            </div>
            <div class="swiper-pagination kegiatan-pagination mt-4 position-relative"></div>
        </div>
    </div>

    @push('styles')
        <style>
            #kegiatan .card:hover img {
                transform: scale(1.05);
            }

            #kegiatan .card {
                transition: box-shadow 0.3s;
            }

            #kegiatan .card:hover {
                box-shadow: 0 8px 25px rgba(6, 92, 194, 0.15) !important;
            }
        </style>
    @endpush

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                new Swiper('.kegiatan-swiper', {
                    loop: true,
                    speed: 800,
                    autoplay: {
                        delay: 4000,
                        disableOnInteraction: false,
                        pauseOnMouseEnter: true
                    },
                    pagination: {
                        el: '.kegiatan-pagination',
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

</section><!-- /Kegiatan Section -->
