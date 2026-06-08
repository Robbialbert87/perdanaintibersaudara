@extends('layouts.style')

@section('title', $product->name . ' - (PIB) Perdana Inti Bersaudara')

@section('body-class', 'product-detail-page')

@section('content')

    <section class="product-detail section" style="padding: 60px 0;">
        <div class="container">
            <div class="row gy-4">
                
                <!-- Gambar Card Full Width -->
                <div class="col-12" data-aos="fade-up">
                    @php
                        $media = [];
                        foreach ($product->active_images ?? [] as $img) {
                            if (in_array($img, $product->images ?? [])) {
                                $media[] = ['type' => 'image', 'path' => $img];
                            }
                        }
                        foreach ($product->active_videos ?? [] as $vid) {
                            if (in_array($vid, $product->videos ?? [])) {
                                $media[] = ['type' => 'video', 'path' => $vid];
                            }
                        }
                    @endphp

                    @if(count($media) > 0)
                        @if(count($media) > 1)
                            <!-- Swiper Slider -->
                            <div class="swiper product-swiper rounded-4 shadow-sm" style="border: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
                                <div class="swiper-wrapper">
                                    @foreach($media as $item)
                                    <div class="swiper-slide">
                                        @if($item['type'] === 'image')
                                        <div class="text-center d-flex align-items-center justify-content-center" style="background-color: #f8f9fa; border-radius: 12px; aspect-ratio: 16/9; overflow: hidden;">
                                            <img src="{{ Storage::url($item['path']) }}" alt="{{ $product->name }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: contain;">
                                        </div>
                                        @else
                                        <div style="aspect-ratio: 16/9; overflow: hidden; background-color: #000;" class="d-flex align-items-center justify-content-center position-relative video-slide">
                                            <video class="product-video w-100 h-100" style="object-fit: contain; cursor: pointer;" src="{{ Storage::url($item['path']) }}" playsinline preload="metadata" muted controls></video>
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                <div class="swiper-pagination"></div>
                                <div class="swiper-button-prev" style="color: #065cc2;"></div>
                                <div class="swiper-button-next" style="color: #065cc2;"></div>
                            </div>
                        @else
                            @if($media[0]['type'] === 'image')
                            <div class="rounded-4 shadow-sm d-flex align-items-center justify-content-center" style="border: 1px solid rgba(0,0,0,0.05); overflow: hidden; background-color: #f8f9fa; aspect-ratio: 16/9;">
                                <img src="{{ Storage::url($media[0]['path']) }}" alt="{{ $product->name }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: contain;">
                            </div>
                            @else
                            <div class="rounded-4 shadow-sm" style="border: 1px solid rgba(0,0,0,0.05); overflow: hidden; aspect-ratio: 16/9; background-color: #000;">
                                <video controls playsinline preload="metadata" style="width: 100%; height: 100%; display: block; object-fit: contain;" src="{{ Storage::url($media[0]['path']) }}"></video>
                            </div>
                            @endif
                        @endif
                    @else
                        <!-- No Media Fallback -->
                        <div class="rounded-4 shadow-sm d-flex align-items-center justify-content-center" style="border: 1px solid rgba(0,0,0,0.05); overflow: hidden; background-color: #f8f9fa; aspect-ratio: 16/9;">
                                <img src="{{ asset('style/assets/img/portfolio/portfolio-1.webp') }}" alt="{{ $product->name }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: contain;">
                            </div>
                    @endif
                </div>

                <!-- Detail Produk di Bawah Gambar -->
                <div class="col-12" data-aos="fade-up" data-aos-delay="100">
                    <div class="product-info">
                        <span class="badge bg-light text-primary mb-2" style="font-size: 0.9rem; padding: 8px 15px; border-radius: 50px;">
                            {{ $product->category }}
                        </span>
                        
                        <h2 style="font-family: 'Quicksand', sans-serif; font-weight: bold; color: #13447f; font-size: 2.2rem; margin-bottom: 20px;">
                            {{ $product->name }}
                        </h2>

                        @if($product->description)
                        <div class="description-box mb-4">
                            <h4 style="font-size: 1.1rem; font-weight: 600; color: #314862; margin-bottom: 4px;">Deskripsi:</h4>
                            <p style="color: #5c7694; line-height: 1.8; text-align: justify; white-space: pre-line;">
                                {{ $product->description }}
                            </p>
                        </div>
                        @endif

                        @if($product->spesifikasi)
                        <div class="spesifikasi-box mb-4">
                            <h4 style="font-size: 1.1rem; font-weight: 600; color: #314862; margin-bottom: 4px;">Spesifikasi Detail:</h4>
                            <div style="color: #5c7694; line-height: 1.8; white-space: pre-line;">
                                {{ $product->spesifikasi }}
                            </div>
                        </div>
                        @endif

                        <!-- Action Button -->
                        <div class="action-buttons mt-5 d-flex gap-3 flex-wrap">
                            @php
                                // Format text WA
                                $waText = urlencode("Halo (PIB) Perdana Inti Bersaudara,\n\nSaya tertarik dan ingin menanyakan informasi lebih lanjut mengenai produk *{$product->name}* yang ada di website.\n\nTerima kasih.");
                            @endphp
                            <a href="https://wa.me/6281274044912?text={{ $waText }}" target="_blank" class="btn btn-primary d-inline-flex align-items-center gap-2" style="background-color: #25D366; border-color: #25D366; padding: 12px 30px; border-radius: 50px; font-weight: 600; font-family: 'Quicksand', sans-serif; font-size: 1.1rem; transition: 0.3s;">
                                <i class="bi bi-whatsapp" style="font-size: 1.2rem;"></i>
                                Pesan Sekarang
                            </a>
                            <a href="{{ route('produk.page') }}" class="btn btn-outline-primary d-inline-flex align-items-center gap-2" style="padding: 12px 30px; border-radius: 50px; font-weight: 600; font-family: 'Quicksand', sans-serif; font-size: 1.1rem; transition: 0.3s;">
                                <i class="bi bi-arrow-left" style="font-size: 1.2rem;"></i>
                                Kembali ke Produk
                            </a>
                        </div>
                        
                        <!-- Share Info (Optional/Aesthetic) -->
                        <div class="share-info mt-5 pt-4" style="border-top: 1px solid #eee;">
                            <span style="font-weight: 600; color: #314862; margin-right: 15px;">Bagikan:</span>
                            <a href="#" class="text-secondary me-3 hover-primary"><i class="bi bi-facebook fs-5"></i></a>
                            <a href="#" class="text-secondary me-3 hover-primary"><i class="bi bi-twitter-x fs-5"></i></a>
                            <a href="#" class="text-secondary me-3 hover-primary"><i class="bi bi-linkedin fs-5"></i></a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    @push('styles')
    <style>
        .hover-primary:hover {
            color: #065cc2 !important;
            transition: 0.3s;
        }
        .btn-primary:hover {
            background-color: #128C7E !important;
            border-color: #128C7E !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 211, 102, 0.3);
        }
    </style>
    @endpush

    @push('scripts')
    @if(count($media) > 1)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var swiper = new Swiper('.product-swiper', {
                loop: true,
                speed: 600,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false
                },
                pagination: {
                    el: '.swiper-pagination',
                    type: 'bullets',
                    clickable: true
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                on: {
                    slideChange: function() {
                        document.querySelectorAll('.product-video').forEach(function(v) {
                            v.pause();
                        });
                        var activeSlide = this.slides[this.activeIndex];
                        var video = activeSlide.querySelector('.product-video');
                        if (video) {
                            video.muted = true;
                            video.play();
                        }
                    }
                }
            });

            document.querySelectorAll('.product-video').forEach(function(video) {
                video.addEventListener('play', function() {
                    swiper.autoplay.stop();
                });
                video.addEventListener('pause', function() {
                    swiper.autoplay.start();
                });
                video.addEventListener('ended', function() {
                    swiper.autoplay.start();
                });
            });
        });
    </script>
    @endif
    @endpush
@endsection
