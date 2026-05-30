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
                        <div class="entry-image position-relative" style="aspect-ratio: 4/3; overflow: hidden; background-color: #f0f4f9;">
                            <a href="{{ route('produk.detail', $product->id) }}">
                                @if (!empty($product->images) && count($product->images) > 0)
                                    <img src="{{ Storage::url($product->images[0]) }}"
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
                                class="btn d-flex justify-content-between align-items-center"
                                style="background-color: #2973cc; color: #fff; border: none; padding: 11px 20px; border-radius: 8px; font-weight: 600; font-size: 0.9rem; transition: background-color 0.3s;">
                                <span>Selengkapnya</span>
                                <i class="bi bi-arrow-right"></i>
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
            #produk .btn:hover {
                background-color: #13447f !important;
            }
        </style>
    @endpush
</section>
