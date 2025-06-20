@extends('layouts.client')

@section('content')
    <!-- Services Page Content -->
    <main class="services-page">
        <h1 class="services-page-title">Layanan Kami</h1>
        <p class="services-page-description">Temukan berbagai layanan desain berkualitas tinggi yang kami tawarkan untuk memenuhi kebutuhan kreatif Anda.</p>
        
        <div class="services-list">
            @foreach($layanan as $item)
            <div class="service-item">
                @if($item->gambar)
                    <img src="{{ asset('storage/' . $item->gambar) }}" alt="{{ $item->nama }}" class="service-image">
                @else
                    <img src="{{ asset('images/service-placeholder.jpg') }}" alt="{{ $item->nama }}" class="service-image">
                @endif
                <div class="service-content">
                    <h2 class="service-name">{{ $item->nama }}</h2>
                    <p class="service-description">{{ $item->deskripsi ?? 'Tidak ada deskripsi' }}</p>
                    <div class="service-details">
                        <span class="service-package">{{ $item->paketRevisi->nama ?? 'Tidak ada paket revisi' }}</span>
                        <span class="service-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                    </div>
                    <a href="{{ route('client.pesanan.create', ['layanan_id' => $item->id]) }}" class="service-order-btn">Pesan Sekarang</a>   
                </div>
            </div>
            @endforeach
        </div>
    </main>
@endsection