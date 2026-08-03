<!-- Services Grid Section - Full Page -->
<section id="layanan-page" class="services-page section" style="padding-top: 40px; padding-bottom: 80px;">
    <div class="container">

        <!-- Section Header -->
        <div class="section-title" data-aos="fade-up">
            <h1 style="font-family: 'Quicksand', sans-serif; font-weight: bold; color: #13447f;">Layanan Kami</h1>
            <p style="color: #7f9ab7;">Layanan unggulan teknik medis dan pengadaan alat kesehatan</p>
        </div>

        <div class="row g-4">
            @forelse($services as $index => $service)
            <div class="col-lg-4 col-md-6"
                 data-aos="fade-up"
                 data-aos-delay="{{ 50 + ($index % 3) * 100 }}"
                 data-aos-duration="600">

                <div class="service-card-full bg-white rounded-4 overflow-hidden shadow-sm d-flex flex-column h-100"
                     style="border: 1px solid rgba(0,0,0,0.06);">

                    <!-- Image -->
                    <div style="aspect-ratio: 4/3; overflow: hidden; background-color: #f0f4f9;">
                        <a href="{{ route('layanan.detail', $service->id) }}">
                            @php $layananImg = $service->images[0] ?? $service->image; @endphp
                            @if($layananImg)
                                <img src="{{ img_url($layananImg) }}"
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

                    <!-- Content -->
                    <div class="p-3 d-flex flex-column flex-grow-1">
                        <h4 style="font-size: 1.1rem; font-weight: bold; margin-bottom: 4px;">
                            <a href="{{ route('layanan.detail', $service->id) }}"
                                style="color: #13447f; text-decoration: none; transition: 0.3s;"
                                class="hover-primary">
                                {{ $service->title }}
                            </a>
                        </h4>
                        <p class="text-secondary mb-2 small" style="font-weight: 500;">Layanan</p>
                        <div class="mt-auto">
                            <a href="{{ route('layanan.detail', $service->id) }}"
                               class="read-more-link"
                               style="color: #065cc2; font-weight: 600; font-size: 0.9rem; text-decoration: none; display: inline-flex; align-items: center; gap: 6px; transition: color 0.2s;">
                                Selengkapnya &rarr;
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5" data-aos="fade-up">
                <i class="bi bi-briefcase" style="font-size: 3rem; color: #c4cfd9;"></i>
                <p class="text-muted mt-3">Layanan belum tersedia. Silakan tambahkan melalui panel admin.</p>
            </div>
            @endforelse
        </div>
    </div>

    @push('styles')
    <style>
        .service-card-full {
            transition: box-shadow 0.3s ease, transform 0.3s ease;
        }

        .service-card-full:hover {
            box-shadow: 0 8px 25px rgba(6, 92, 194, 0.15) !important;
        }

        .service-card-full:hover img {
            transform: scale(1.05);
        }

        .read-more-link:hover {
            color: #13447f !important;
            text-decoration: underline !important;
        }
    </style>
    @endpush

</section><!-- /Services Page Section -->
