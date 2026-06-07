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
                        <div class="portfolio-entry bg-white shadow-sm rounded-4 overflow-hidden"
                            style="border: 1px solid rgba(0,0,0,0.05);">
                            @php
                                $activeImages = $activity->active_images ?? [];
                                $activeVideos = $activity->active_videos ?? [];
                                $firstImage = $activeImages[0] ?? ($activity->images[0] ?? null);
                            @endphp
                            <div class="entry-image position-relative" style="aspect-ratio: 4/3; overflow: hidden; background-color: #f0f4f9;">
                                <a href="{{ route('kegiatan.detail', $activity->id) }}">
                                    @if ($firstImage)
                                        <img src="{{ Storage::url($firstImage) }}"
                                            alt="{{ $activity->title }}"
                                            class="img-fluid w-100 h-100 object-fit-contain"
                                            style="transition: transform 0.3s;"
                                            loading="lazy" decoding="async">
                                    @else
                                        <img src="{{ asset('style/assets/img/portfolio/portfolio-1.webp') }}"
                                            alt="{{ $activity->title }}"
                                            class="img-fluid w-100 h-100 object-fit-contain"
                                            style="transition: transform 0.3s;"
                                            loading="lazy" decoding="async">
                                    @endif
                                    @if(!empty($activeVideos))
                                        <span class="position-absolute top-0 end-0 m-2 badge bg-danger d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                                            <i class="bi bi-play-fill"></i> Video
                                        </span>
                                    @endif
                                </a>
                            </div>
                            <div class="entry-title-wrapper p-3 d-flex flex-column">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <i class="bi bi-calendar3" style="color: #065cc2; font-size: 0.8rem;"></i>
                                    <small style="color: #7f9ab7; font-weight: 500; font-size: 0.8rem;">
                                        {{ \Carbon\Carbon::parse($activity->date)->translatedFormat('d F Y') }}
                                    </small>
                                </div>
                                <h4 class="entry-title mb-1" style="font-size: 1.1rem; font-weight: bold;">
                                    <a href="{{ route('kegiatan.detail', $activity->id) }}"
                                        style="color: #13447f; text-decoration: none; transition: 0.3s;"
                                        class="hover-primary">
                                        {{ $activity->title }}
                                    </a>
                                </h4>
                                <a href="{{ route('kegiatan.detail', $activity->id) }}"
                                    class="read-more-link"
                                    style="color: #065cc2; font-weight: 600; font-size: 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: color 0.2s; margin-top: auto;">
                                    Selengkapnya &rarr;
                                </a>
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
            #kegiatan .portfolio-entry:hover img {
                transform: scale(1.05);
            }

            #kegiatan .portfolio-entry {
                transition: box-shadow 0.3s;
            }

            #kegiatan .portfolio-entry:hover {
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
