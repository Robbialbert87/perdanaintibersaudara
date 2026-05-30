@extends('layouts.style')

@section('title', $activity->title . ' - (PIB) Perdana Inti Bersaudara')

@section('body-class', 'activity-detail-page')

@section('content')

    <section class="activity-detail section" style="padding: 60px 0;">
        <div class="container">
            <div class="row gy-5 align-items-start">
                
                <!-- Sisi Kiri: Galeri Foto -->
                <div class="col-lg-6" data-aos="fade-right">
                    @if(!empty($activity->images) && count($activity->images) > 0)
                        @if(count($activity->images) > 1)
                            <!-- Swiper Slider -->
                            <div class="swiper product-swiper rounded-4 shadow-sm" style="border: 1px solid rgba(0,0,0,0.05); overflow: hidden;">
                                <div class="swiper-wrapper">
                                    @foreach($activity->images as $img)
                                    <div class="swiper-slide">
                                        <div style="aspect-ratio: 4/3; overflow: hidden; background-color: #f8f9fa;" class="d-flex align-items-center justify-content-center">
                                            <img src="{{ Storage::url($img) }}" alt="{{ $activity->title }}" class="img-fluid w-100 h-100 object-fit-cover">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="swiper-pagination"></div>
                                <div class="swiper-button-prev" style="color: #065cc2;"></div>
                                <div class="swiper-button-next" style="color: #065cc2;"></div>
                            </div>
                        @else
                            <!-- Single Image -->
                            <div class="rounded-4 shadow-sm" style="border: 1px solid rgba(0,0,0,0.05); overflow: hidden; aspect-ratio: 4/3; background-color: #f8f9fa;">
                                <img src="{{ Storage::url($activity->images[0]) }}" alt="{{ $activity->title }}" class="img-fluid w-100 h-100 object-fit-cover">
                            </div>
                        @endif
                    @else
                        <!-- No Image Fallback -->
                        <div class="rounded-4 shadow-sm" style="border: 1px solid rgba(0,0,0,0.05); overflow: hidden; aspect-ratio: 4/3; background-color: #f8f9fa;">
                            <img src="{{ asset('style/assets/img/blog/blog-1.webp') }}" alt="{{ $activity->title }}" class="img-fluid w-100 h-100 object-fit-cover">
                        </div>
                    @endif
                </div>

                <!-- Sisi Kanan: Detail Kegiatan -->
                <div class="col-lg-6" data-aos="fade-left" data-aos-delay="100">
                    <div class="product-info p-lg-4">
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
    @if(!empty($activity->images) && count($activity->images) > 1)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Swiper('.product-swiper', {
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
                }
            });
        });
    </script>
    @endif
    @endpush
@endsection
