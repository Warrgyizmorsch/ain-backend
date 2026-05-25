@extends('layouts.app')

@section('content')
@php
    $tabs = [
        ['name' => 'Explore', 'icon' => 'fa-compass'],
        ['name' => 'All', 'icon' => 'fa-rotate'],
        ['name' => 'Draft', 'icon' => 'fa-envelope-open-text'],
        ['name' => 'Pending', 'icon' => 'fa-clock'],
        ['name' => 'Approved', 'icon' => 'fa-check-circle'],
        ['name' => 'Action Required', 'icon' => 'fa-exclamation-circle'],
    ];

    $exploreItems = [
        [
            'title' => 'Holi Wishes & Offer',
            'type' => 'IMAGE',
            'description' => '🎉💥 Holi Special: Exclusive Discounts! Enjoy *25% OFF* on Bassheads 225. Limited time only!',
            'image' => 'https://via.placeholder.com/100?text=Holi',
        ],
        [
            'title' => 'Holi Wishes + Offer',
            'type' => 'IMAGE',
            'description' => '🧨 Hey [Shivam], Holi is here – get *20% OFF* on Bassheads! Celebrate with savings!',
            'image' => 'https://via.placeholder.com/100?text=Offer',
        ],
        [
            'title' => 'First Message',
            'type' => 'IMAGE',
            'description' => '🎁 Your first order deserves something special. Don’t miss your FLAT discount 🎉',
            'image' => 'https://via.placeholder.com/100?text=First',
        ],
    ];
@endphp

<div class="container-fluid my-10">
    <!-- Tabs -->
    <div class="card">
        <div class="card-header border-0 pb-0">
            <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold" role="tablist">
                @foreach($tabs as $index => $tab)
                    <li class="nav-item" role="presentation">
                        <a class="nav-link @if($index === 0) active @endif" data-bs-toggle="tab" href="#tab-{{ $index }}" role="tab">
                            <i class="fas {{ $tab['icon'] }} me-2"></i>{{ $tab['name'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <div class="card-body">
            <div class="tab-content">
                <!-- Explore Tab -->
                <div class="tab-pane fade show active" id="tab-0" role="tabpanel">
                    <div class="row g-6">
                        @foreach($exploreItems as $item)
                            <div class="col-md-4">
                                <div class="card card-flush h-100">
                                    <div class="card-body text-center py-6">
                                        <img src="{{ $item['image'] }}" class="mb-4 rounded-circle" width="80" height="80" alt="Image">
                                        <h4 class="fw-bold">{{ $item['title'] }}</h4>
                                        <div class="badge badge-light-warning mb-2">{{ $item['type'] }}</div>
                                        <p class="text-muted fs-7">{{ $item['description'] }}</p>
                                        <div class="d-flex justify-content-center gap-2">
                                            <a href="#" class="btn btn-light-primary btn-sm">Preview</a>
                                            <a href="#" class="btn btn-success btn-sm">Submit</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Other Tabs -->
                @for($i = 1; $i <= 6; $i++)
                    <div class="tab-pane fade" id="tab-{{ $i }}" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-row-dashed align-middle gs-0 gy-3">
                                <thead>
                                    <tr class="fw-semibold text-muted bg-light">
                                        <th>Name</th>
                                        <th>Campaign</th>
                                        <th>Status</th>
                                        <th>Type</th>
                                        <th>Health</th>
                                        <th>Created At</th>
                                        <th class="text-center">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @switch($i)
                                        @case(1)
                                        @case(4)
                                            @for($j = 0; $j < 2; $j++)
                                                <tr>
                                                    <td>Initial Conversation</td>
                                                    <td>Marketing</td>
                                                    <td><span class="badge badge-light-success">Approved</span></td>
                                                    <td>Text</td>
                                                    <td><span class="badge badge-light-success">High</span></td>
                                                    <td>April 26, 2025</td>
                                                    <td class="text-center">
                                                        <a href="#" class="btn btn-icon btn-light-warning btn-sm" title="Favorite"><i class="fas fa-star"></i></a>
                                                        <a href="#" class="btn btn-icon btn-light-primary btn-sm" title="Copy"><i class="fas fa-copy"></i></a>
                                                        <a href="#" class="btn btn-icon btn-light-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></a>
                                                    </td>
                                                </tr>
                                            @endfor
                                            @break
                                        @case(2)
                                            <tr>
                                                <td>followup</td>
                                                <td>NA</td>
                                                <td><span class="badge badge-light-danger">DRAFT</span></td>
                                                <td>NA</td>
                                                <td><span class="badge badge-light-success">High</span></td>
                                                <td>April 7, 2025</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-icon btn-light-primary btn-sm" title="Copy"><i class="fas fa-copy"></i></a>
                                                    <a href="#" class="btn btn-icon btn-light-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            @break
                                        @case(6)
                                            <tr>
                                                <td>order_information</td>
                                                <td>Marketing</td>
                                                <td><span class="badge badge-danger">REJECTED</span></td>
                                                <td>Text</td>
                                                <td><span class="badge badge-light-success">High</span></td>
                                                <td>April 4, 2025</td>
                                                <td class="text-center">
                                                    <a href="#" class="btn btn-icon btn-light-warning btn-sm" title="Favorite"><i class="fas fa-star"></i></a>
                                                    <a href="#" class="btn btn-icon btn-light-primary btn-sm" title="Copy"><i class="fas fa-copy"></i></a>
                                                    <a href="#" class="btn btn-icon btn-light-danger btn-sm" title="Delete"><i class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
                                            @break
                                        @default
                                            <tr><td colspan="7" class="text-center text-muted">No records found.</td></tr>
                                    @endswitch
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endfor
            </div>
        </div>
    </div>
</div>
@endsection
