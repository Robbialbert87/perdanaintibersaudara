@extends('layouts.style')

@section('title', $service->title . ' - Layanan (PIB) Perdana Inti Bersaudara')

@section('body-class', 'service-detail-page')

@section('content')

    <section class="service-detail section" style="padding: 60px 0;">
        <div class="container">
            <div class="row gy-4">

                <!-- Media Slider (Gambar & Video) -->
                <div class="col-12" data-aos="fade-up">
                    @php
                        $media = [];
                        foreach ($service->active_images ?? [] as $img) {
                            if (in_array($img, $service->images ?? [])) {
                                $media[] = ['type' => 'image', 'path' => $img];
                            }
                        }
                        foreach ($service->active_videos ?? [] as $vid) {
                            if (in_array($vid, $service->videos ?? [])) {
                                $media[] = ['type' => 'video', 'path' => $vid];
                            }
                        }
                    @endphp

                    @if(count($media) > 0)
                        @if(count($media) > 1)
                            <div class="swiper service-swiper rounded-4 shadow-sm" style="border: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
                                <div class="swiper-wrapper">
                                    @foreach($media as $item)
                                    <div class="swiper-slide">
                                        @if($item['type'] === 'image')
                                        <div class="text-center d-flex align-items-center justify-content-center" style="background-color: #f8f9fa; border-radius: 12px; aspect-ratio: 16/9; overflow: hidden;">
                                            <img src="{{ img_url($item['path']) }}" alt="{{ $service->title }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: contain;" decoding="async">
                                        </div>
                                        @else
                                        <div style="aspect-ratio: 16/9; overflow: hidden; background-color: #000;" class="d-flex align-items-center justify-content-center position-relative video-slide">
                                            <video class="service-video w-100 h-100" style="object-fit: contain; cursor: pointer;" src="{{ Storage::url($item['path']) }}" playsinline preload="metadata" muted controls></video>
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
                                <img src="{{ img_url($media[0]['path']) }}" alt="{{ $service->title }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: contain;" fetchpriority="high" decoding="async">
                            </div>
                            @else
                            <div class="rounded-4 shadow-sm" style="border: 1px solid rgba(0,0,0,0.05); overflow: hidden; aspect-ratio: 16/9; background-color: #000;">
                                <video controls playsinline preload="metadata" style="width: 100%; height: 100%; display: block; object-fit: contain;" src="{{ Storage::url($media[0]['path']) }}"></video>
                            </div>
                            @endif
                        @endif
                    @else
                        @php $legacyImg = $service->image; @endphp
                        @if($legacyImg)
                        <div class="rounded-4 shadow-sm d-flex align-items-center justify-content-center" style="border: 1px solid rgba(0,0,0,0.05); overflow: hidden; background-color: #f8f9fa; aspect-ratio: 16/9;">
                            <img src="{{ img_url($legacyImg) }}" alt="{{ $service->title }}" class="img-fluid" style="width: 100%; height: 100%; object-fit: contain;" fetchpriority="high" decoding="async">
                        </div>
                        @else
                        <div class="rounded-4 shadow-sm d-flex align-items-center justify-content-center" style="border: 1px solid rgba(0,0,0,0.06); aspect-ratio: 16/9; background-color: #f0f4f9;">
                            <i class="bi bi-briefcase" style="font-size: 5rem; color: #c4d3e0;"></i>
                        </div>
                        @endif
                    @endif
                </div>

                <!-- Detail Layanan di Bawah Gambar -->
                <div class="col-12" data-aos="fade-up" data-aos-delay="100">
                    <div class="product-info">

                        <!-- Badge kategori -->
                        <span class="badge mb-3" style="background-color: rgba(6,92,194,0.08); color: #065cc2; font-size: 0.85rem; padding: 8px 16px; border-radius: 50px; font-weight: 600;">
                            <i class="bi bi-briefcase me-1"></i> Layanan Kami
                        </span>

                        <h2 style="font-family: 'Quicksand', sans-serif; font-weight: bold; color: #13447f; font-size: 2.2rem; margin-bottom: 20px;">
                            {{ $service->title }}
                        </h2>

                        <div class="description-box mb-4">
                            <h4 style="font-size: 1.05rem; font-weight: 600; color: #314862; margin-bottom: 10px;">Deskripsi:</h4>
                            <p style="color: #5c7694; line-height: 1.8; text-align: justify; white-space: pre-line;">
                                {{ $service->description }}
                            </p>
                        </div>

                        @if(!empty($service->features))
                        <div class="features-box mb-5">
                            <h4 style="font-size: 1.05rem; font-weight: 600; color: #314862; margin-bottom: 14px;">Fitur Layanan:</h4>
                            <ul class="list-unstyled" style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                                @foreach($service->features as $feature)
                                <li style="display: flex; align-items: flex-start; gap: 8px; font-size: 0.95rem; color: #314862;">
                                    <i class="bi bi-check-circle-fill" style="color: #065cc2; font-size: 1.1rem; flex-shrink: 0; margin-top: 2px;"></i>
                                    <span style="line-height: 1.5;">{{ $feature }}</span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                        <!-- CTA Buttons -->
                        <div class="action-buttons d-flex flex-wrap gap-3">
                            <a href="{{ route('layanan.page') }}"
                               class="btn btn-outline-primary d-inline-flex align-items-center gap-2"
                               style="padding: 12px 28px; border-radius: 50px; font-weight: 600; font-family: 'Quicksand', sans-serif; font-size: 1rem;">
                                <i class="bi bi-arrow-left"></i>
                                Kembali ke Layanan
                            </a>
                            <a href="{{ route('contact.page') }}"
                               class="btn btn-primary d-inline-flex align-items-center gap-2"
                               style="padding: 12px 28px; border-radius: 50px; font-weight: 600; font-family: 'Quicksand', sans-serif; font-size: 1rem; background-color: #065cc2; border: none;">
                                <i class="bi bi-chat-dots"></i>
                                Hubungi Kami
                            </a>
                        </div>

                        <!-- Share Info -->
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
    </style>
    @endpush

    @push('scripts')
    @if(count($media) > 1)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var swiper = new Swiper('.service-swiper', {
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
                        document.querySelectorAll('.service-video').forEach(function(v) {
                            v.pause();
                        });
                        var activeSlide = this.slides[this.activeIndex];
                        var video = activeSlide.querySelector('.service-video');
                        if (video) {
                            video.muted = true;
                            video.play();
                        }
                    }
                }
            });

            document.querySelectorAll('.service-video').forEach(function(video) {
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
