<!-- Portfolio Section -->
<section id="produk" class="portfolio section">

    <!-- Section Title -->
    <div class="container section-title" data-aos="fade-up">
        <h1 style="font-family: 'Quicksand', sans-serif; font-weight: bold; color: #13447f;">Produk Kami</h1>
        <p style="color: #7f9ab7;">Katalog alat kesehatan, peralatan radiography, & suku cadang elektromedis pilihan</p>
    </div><!-- End Section Title -->

    <div class="container">

        <div class="isotope-layout" data-default-filter="*" data-layout="masonry" data-sort="original-order">

            @php
                $categories = $products->pluck('category')->unique()->values();
            @endphp

            <ul class="portfolio-filters isotope-filters" data-aos="fade-up" data-aos-delay="100"
                style="display: flex; justify-content: center; gap: 15px; margin-bottom: 40px; list-style: none; padding: 0; flex-wrap: wrap;">
                <li data-filter="*" class="filter-active"
                    style="cursor: pointer; font-weight: 600; padding: 8px 20px; border-radius: 50px; background-color: #f0f4f9; color: #314862; transition: 0.3s;">
                    Semua</li>
                @foreach ($categories as $cat)
                    <li data-filter=".filter-{{ Str::slug($cat) }}"
                        style="cursor: pointer; font-weight: 600; padding: 8px 20px; border-radius: 50px; background-color: #f0f4f9; color: #314862; transition: 0.3s;">
                        {{ $cat }}</li>
                @endforeach
            </ul><!-- End Portfolio Filters -->

            <div class="row gy-4 isotope-container" data-aos="fade-up" data-aos-delay="200">

                @forelse($products as $product)
                    <div
                        class="col-lg-4 col-md-6 portfolio-item isotope-item filter-{{ Str::slug($product->category) }}">
                        <div class="portfolio-entry bg-white shadow-sm rounded-4 overflow-hidden"
                            style="border: 1px solid rgba(0,0,0,0.05);">
                            <div class="entry-image position-relative" style="aspect-ratio: 4/3; overflow: hidden;">
                                @if (!empty($product->images) && count($product->images) > 0)
                                    <img src="{{ asset('storage/' . $product->images[0]) }}" alt="{{ $product->name }}"
                                        class="img-fluid w-100 h-100 object-fit-cover">
                                @else
                                    <img src="{{ asset('style/assets/img/portfolio/portfolio-1.webp') }}"
                                        alt="{{ $product->name }}" class="img-fluid w-100 h-100 object-fit-cover">
                                @endif
                                <div class="entry-overlay d-flex align-items-center justify-content-center position-absolute top-0 start-0 w-100 h-100"
                                    style="background-color: rgba(6, 92, 194, 0.82); opacity: 0; transition: 0.4s;">
                                    <div class="entry-links d-flex flex-column align-items-center gap-3">
                                        <a href="{{ route('produk.detail', $product->id) }}" title="Lihat Detail Produk"
                                            class="d-inline-flex align-items-center gap-2 px-4 py-2 rounded-pill fw-semibold"
                                            style="background-color: #ffffff; color: #13447f; font-family: 'Quicksand', sans-serif; text-decoration: none; font-size: 0.95rem; box-shadow: 0 4px 15px rgba(0,0,0,0.25); transition: 0.3s; border: 2px solid #ffffff;">
                                            <i class="bi bi-eye-fill" style="color: #13447f;"></i> Lihat Produk
                                        </a>
                                    </div>
                                </div>
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
                    {{-- Fallback: tampilkan pesan jika belum ada produk --}}
                    <div class="col-12 text-center py-5">
                        <p class="text-muted">Produk akan segera ditampilkan.</p>
                    </div>
                @endforelse

            </div><!-- End Portfolio Container -->

        </div>

    </div>

    <!-- Custom CSS styles specifically for handling portfolio entry overlay effects -->
    @push('styles')
        <style>
            .portfolio-entry:hover .entry-overlay {
                opacity: 1 !important;
            }

            .portfolio-filters li:hover,
            .portfolio-filters li.filter-active {
                background-color: #065cc2 !important;
                color: #ffffff !important;
            }
        </style>
    @endpush

</section><!-- /Portfolio Section -->
