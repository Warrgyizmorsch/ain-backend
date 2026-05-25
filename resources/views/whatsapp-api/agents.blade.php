@extends('layouts.app')

@section('content')
<div class="container mt-10">

  <!-- Quota + Button -->
  <div class="d-flex flex-wrap justify-content-between align-items-center mb-7">
    <div>
      <h6 class="text-gray-700 fw-bold fs-6">Total Quota</h6>
      <div class="fs-2hx fw-bolder text-dark">15 <span class="fs-6 fw-normal text-muted">Agents</span></div>
    </div>
    <a href="#" class="btn btn-light-primary">
      <i class="fas fa-user-plus fs-5 me-2"></i>Request More Agents
    </a>
  </div>

  <!-- Search and Add -->
  <div class="d-flex justify-content-end mb-6">
    <div class="input-group w-md-300px me-4">
      <span class="input-group-text bg-white border-end-0">
        <i class="fas fa-search text-muted fs-6"></i>
      </span>
      <input type="text" class="form-control " placeholder="Search by Agent Name" />
    </div>
    <button class="btn btn-secondary" disabled>
      <i class="fas fa-plus me-2"></i>Add Agent
    </button>
  </div>

  <p class="text-gray-600 mb-6">Your team for Assistant management.</p>

  <!-- Cards Grid -->
  <div class="row g-6">
    @php
      $agents = [
        ['name' => 'Mahipal Singh Rathore', 'username' => 'singhmahipal23@gmail.com', 'email' => 'singhmahipal23@gmail.com', 'status' => 'Online', 'role' => 'Super Admin', 'color' => '#c084fc', 'text' => 'white'],
        ['name' => 'Aafrin', 'username' => 'aafrin', 'email' => 'aafrin', 'status' => 'Online', 'role' => 'Manager', 'color' => '#fde68a', 'text' => 'dark'],
        ['name' => 'Siddharth', 'username' => 'siddharth.warr', 'email' => 'siddharth.warr', 'status' => 'Online', 'role' => 'Manager', 'color' => '#fef08a', 'text' => 'dark'],
        ['name' => 'War_Developer', 'username' => 'war_developer', 'email' => 'war_developer', 'status' => 'Online', 'role' => 'Manager', 'color' => '#fde68a', 'text' => 'dark'],
      ];
    @endphp

    @foreach($agents as $agent)
    <div class="col-md-4">
    <div class="card card-flush h-100 position-relative">
  <div class="card-body">
    <div class="d-flex align-items-center mb-5">
      <div class="symbol symbol-45px me-5">
        <div class="symbol-label" style="background-color: {{ $agent['color'] }}; color: {{ $agent['text'] }};">
          {{ strtoupper(substr($agent['name'], 0, 1)) }}
        </div>
      </div>
      <div>
        <h6 class="mb-0 text-dark fw-semibold">{{ $agent['name'] }}</h6>
        <div class="text-muted fw-normal fs-7">{{ $agent['email'] }}</div>
      </div>

      <div class="position-absolute top-0 end-0 m-3">
      {{-- If NOT super admin, show edit --}}
      @if($agent['role'] !== 'Super Admin')
        <a href=""  class="btn btn-sm btn-icon btn-light-primary" title="Edit">
          <i class="fas fa-edit"></i>
        </a>
      @endif
      </div>
    </div>

    <div class="d-flex justify-content-between mb-3">
      <div>
        <div class="fw-bold text-gray-600">Username</div>
        <div class="text-dark">{{ $agent['username'] }}</div>
      </div>
      <div>
        <div class="fw-bold text-gray-600">Status</div>
        <div class="text-success">{{ $agent['status'] }}</div>
      </div>
    </div>

    {{-- Highlighted Role --}}
    <div class="fw-bold text-danger {{ $agent['role'] === 'Super Admin' ? 'text-uppercase' : 'text-muted' }}">
      {{ $agent['role'] }}
    </div>

    {{-- Delete only if not super admin --}}
    @if($agent['role'] !== 'Super Admin')
      <form action="" method="POST" class="position-absolute bottom-0 end-0 m-3">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-icon btn-light-danger">
          <i class="bi bi-trash-fill"></i>
        </button>
      </form>
    @endif
  </div>
</div>


    </div>
    @endforeach
  </div>
</div>
@endsection
