<section id="produk" class="portfolio section">
    <div class="container section-title" data-aos="fade-up">
        <h1 style="font-family: 'Quicksand', sans-serif; font-weight: bold; color: #13447f;">Produk Kami</h1>
        <p style="color: #7f9ab7;">Katalog alat kesehatan, peralatan radiography, & suku cadang elektromedis pilihan</p>
    </div>

    <div class="container">
        <div class="row g-4">
            @forelse($products as $product)
                <div class="col-12 col-md-6 col-lg-4" data-aos="fade-up" data-aos-delay="{{ $loop->index % 3 * 100 }}">
                    <div class="portfolio-entry bg-white shadow-sm rounded-4 overflow-hidden h-100"
                        style="border: 1px solid rgba(0,0,0,0.05);">
                        @php
                            $activeImages = $product->active_images ?? [];
                            $activeVideos = $product->active_videos ?? [];
                            $firstImage = $activeImages[0] ?? ($product->images[0] ?? null);
                        @endphp
                        <div class="entry-image position-relative" style="aspect-ratio: 4/3; overflow: hidden; background-color: #f0f4f9;">
                            <a href="{{ route('produk.detail', $product->id) }}">
                                @if ($firstImage)
                                    <img src="{{ img_url($firstImage) }}"
                                        alt="{{ $product->name }}"
                                        class="img-fluid w-100 h-100 object-fit-contain"
                                        style="transition: transform 0.3s;"
                                        loading="lazy" decoding="async">
                                @else
                                    <img src="{{ asset('style/assets/img/portfolio/portfolio-1.webp') }}"
                                        alt="{{ $product->name }}"
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
                            <h4 class="entry-title mb-1" style="font-size: 1.1rem; font-weight: bold;">
                                <a href="{{ route('produk.detail', $product->id) }}"
                                    style="color: #13447f; text-decoration: none; transition: 0.3s;"
                                    class="hover-primary">
                                    {{ $product->name }}
                                </a>
                            </h4>
                            <p class="entry-category text-secondary mb-2 small" style="font-weight: 500;">
                                {{ $product->category }}</p>
                            <a href="{{ route('produk.detail', $product->id) }}"
                                class="read-more-link"
                                style="color: #065cc2; font-weight: 600; font-size: 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: color 0.2s; margin-top: auto;">
                                Selengkapnya &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <p class="text-muted">Produk akan segera ditampilkan.</p>
                </div>
            @endforelse
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
            .read-more-link:hover {
                color: #13447f !important;
                text-decoration: underline !important;
            }
        </style>
    @endpush
</section>
