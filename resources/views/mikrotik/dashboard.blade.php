@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="mb-4 text-center">MikroTik Router Dashboard</h2>

    <div class="row g-4 mb-4">
        <!-- Router Info -->
        <div class="col-md-6">
            <div class="card shadow-sm border-primary">
                <div class="card-header bg-primary text-white">
                    <strong>Router Info</strong>
                </div>
                <ul class="list-group list-group-flush">
                    @foreach ($system[0] as $key => $value)
                        <li class="list-group-item">
                            <strong>{{ ucwords(str_replace('-', ' ', $key)) }}:</strong> {{ $value }}
                        </li>
                    @endforeach
                    <li class="list-group-item">
                        <strong>IP Address:</strong> {{ $ipAddress }}
                    </li>
                </ul>
            </div>
        </div>

        <!-- Connected Devices & CPU Load -->
        <div class="col-md-6">
            <div class="row g-3">
                <!-- Connected Devices Card -->
                <div class="col-12">
                    <div class="card shadow-sm text-center bg-light">
                        <div class="card-body">
                            <h5 class="card-title">Connected Devices</h5>
                            <p class="display-4 text-primary fw-bold">{{ $connectedDevices }}</p>
                        </div>
                    </div>
                </div>

                <!-- CPU Load Progress -->
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="card-title">CPU Load</h6>
                            @php
                                $cpuLoad = (int) filter_var($system[0]['cpu-load'], FILTER_SANITIZE_NUMBER_INT);
                            @endphp
                            <div class="progress" style="height: 25px;">
                                <div class="progress-bar bg-success" role="progressbar"
                                     style="width: {{ $cpuLoad }}%;" aria-valuenow="{{ $cpuLoad }}"
                                     aria-valuemin="0" aria-valuemax="100">
                                    {{ $cpuLoad }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Interfaces Table -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <strong>Network Interfaces</strong>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-bordered m-0">
                <thead class="table-light">
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
                                @if($interface['running'] === 'true')
                                    <span class="badge bg-success">Running</span>
                                @else
                                    <span class="badge bg-secondary">Stopped</span>
                                @endif
                            </td>
                            <td>
                                @if($interface['disabled'] === 'true')
                                    <span class="badge bg-danger">Yes</span>
                                @else
                                    <span class="badge bg-success">No</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
