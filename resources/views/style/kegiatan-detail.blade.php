@extends('layouts.style')

@section('title', $activity->title . ' - (PIB) Perdana Inti Bersaudara')

@section('body-class', 'activity-detail-page')

@section('content')

    <section class="activity-detail section" style="padding: 60px 0;">
        <div class="container">
            <div class="row gy-4">

                <!-- Gambar Full Width -->
                <div class="col-12" data-aos="fade-up">
                    @php
                        $media = [];
                        foreach ($activity->active_images ?? [] as $img) {
                            if (in_array($img, $activity->images ?? [])) {
                                $media[] = ['type' => 'image', 'path' => $img];
                            }
                        }
                        foreach ($activity->active_videos ?? [] as $vid) {
                            if (in_array($vid, $activity->videos ?? [])) {
                                $media[] = ['type' => 'video', 'path' => $vid];
                            }
                        }
                    @endphp

                    @if(count($media) > 0)
                        @if(count($media) > 1)
                            <!-- Swiper Slider -->
                            <div class="swiper kegiatan-swiper-detail rounded-4 shadow-sm" style="border: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
                                <div class="swiper-wrapper">
                                    @foreach($media as $item)
                                    <div class="swiper-slide">
                                        @if($item['type'] === 'image')
                                        <div class="text-center" style="background-color: #f8f9fa; border-radius: 12px;">
                                            <img src="{{ img_url($item['path']) }}" alt="{{ $activity->title }}" class="img-fluid" style="width: 100%; height: auto; max-height: 75vh; object-fit: contain;" loading="lazy" decoding="async">
                                        </div>
                                        @else
                                        <div style="aspect-ratio: 16/9; overflow: hidden; background-color: #000;" class="d-flex align-items-center justify-content-center position-relative video-slide">
                                            <video class="activity-video w-100 h-100" style="object-fit: contain; cursor: pointer;" src="{{ Storage::url($item['path']) }}" playsinline preload="metadata" muted controls></video>
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
                            <div class="rounded-4 shadow-sm" style="border: 1px solid rgba(0,0,0,0.05); overflow: hidden; background-color: #f8f9fa;">
                                <img src="{{ img_url($media[0]['path']) }}" alt="{{ $activity->title }}" class="img-fluid" style="width: 100%; height: auto; max-height: 75vh; object-fit: contain;" fetchpriority="high" decoding="async">
                            </div>
                            @else
                            <div class="rounded-4 shadow-sm" style="border: 1px solid rgba(0,0,0,0.05); overflow: hidden; aspect-ratio: 16/9; background-color: #000;">
                                <video controls playsinline preload="metadata" style="width: 100%; height: 100%; display: block; object-fit: contain;" src="{{ Storage::url($media[0]['path']) }}"></video>
                            </div>
                            @endif
                        @endif
                    @else
                        <!-- No Media Fallback -->
                        <div class="rounded-4 shadow-sm" style="border: 1px solid rgba(0,0,0,0.05); overflow: hidden; background-color: #f8f9fa;">
                            <img src="{{ asset('style/assets/img/blog/blog-1.webp') }}" alt="{{ $activity->title }}" class="img-fluid" style="width: 100%; height: auto; max-height: 75vh; object-fit: contain;">
                        </div>
                    @endif
                </div>

                <!-- Detail Kegiatan di Bawah Gambar -->
                <div class="col-12" data-aos="fade-up" data-aos-delay="100">
                    <div class="product-info">
                        <span class="badge bg-light text-primary mb-2" style="font-size: 0.9rem; padding: 8px 15px; border-radius: 50px;">
                            <i class="bi bi-calendar-event me-1"></i> {{ \Carbon\Carbon::parse($activity->date)->locale('id')->translatedFormat('d F Y') }}
                        </span>
                        
                        <h2 style="font-family: 'Quicksand', sans-serif; font-weight: bold; color: #13447f; font-size: 2.2rem; margin-bottom: 20px;">
                            {{ $activity->title }}
                        </h2>

                        <div class="description-box mb-4">
                            <h4 style="font-size: 1.1rem; font-weight: 600; color: #314862; margin-bottom: 10px;">Deskripsi:</h4>
                            <p style="color: #5c7694; line-height: 1.8; text-align: justify; white-space: pre-line;">
                                {{ $activity->content ?? 'Tidak ada deskripsi tersedia untuk kegiatan ini.' }}
                            </p>
                        </div>

                        <!-- Action Button -->
                        <div class="action-buttons mt-5">
                            <a href="{{ route('kegiatan.page') }}" class="btn btn-outline-primary d-inline-flex align-items-center gap-2" style="padding: 12px 30px; border-radius: 50px; font-weight: 600; font-family: 'Quicksand', sans-serif; font-size: 1.1rem; transition: 0.3s;">
                                <i class="bi bi-arrow-left" style="font-size: 1.2rem;"></i>
                                Kembali ke Kegiatan
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
            var swiper = new Swiper('.kegiatan-swiper-detail', {
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
                        document.querySelectorAll('.activity-video').forEach(function(v) {
                            v.pause();
                        });
                        var activeSlide = this.slides[this.activeIndex];
                        var video = activeSlide.querySelector('.activity-video');
                        if (video) {
                            video.muted = true;
                            video.play();
                        }
                    }
                }
            });

            document.querySelectorAll('.activity-video').forEach(function(video) {
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