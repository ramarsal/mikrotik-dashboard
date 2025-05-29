@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h1 class="mb-4">📶 MikroTik Dashboard</h1>

    {{-- Top Stats --}}
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-bg-primary shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">CPU Load</h5>
                    <p class="fs-3">{{ $system[0]['cpu-load'] ?? 'N/A' }}%</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-success shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Uptime</h5>
                    <p class="fs-5">{{ $system[0]['uptime'] ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-info shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Connected Devices</h5>
                    <p class="fs-1 fw-bold">{{ $connectedDevices }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-bg-dark shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Router IP</h5>
                    <p class="fs-6">{{ $ipAddress }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Interface Table --}}
    <div class="card shadow-sm">
        <div class="card-body">
            <h5 class="card-title">Network Interfaces</h5>
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Disabled</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($interfaces as $interface)
                            <tr>
                                <td>{{ $interface['name'] }}</td>
                                <td>{{ $interface['type'] }}</td>
                                <td>
                                    @if ($interface['running'] === 'true')
                                        <span class="badge bg-success">Running</span>
                                    @else
                                        <span class="badge bg-secondary">Stopped</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $interface['disabled'] ?? 'false' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
