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

    {{-- Interface Selector --}}
    <form id="interfaceSelectForm" class="mb-3">
        <label for="interfaceSelect" class="form-label">Select Interface:</label>
        <select id="interfaceSelect" class="form-select w-auto d-inline-block">
            @foreach ($interfaces as $interface)
                <option value="{{ $interface['name'] }}">{{ $interface['name'] }}</option>
            @endforeach
        </select>
    </form>

    {{-- Real-Time Bandwidth Chart --}}
    <div class="card mt-4">
        <div class="card-header">
            <h5>Real-Time Bandwidth Usage</h5>
        </div>
        <div class="card-body">
            <canvas id="bandwidthChart" height="100"></canvas>
        </div>
    </div>

    {{-- Interface Table --}}
    <div class="card shadow-sm mt-4">
        <div class="card-body">
            <h5 class="card-title">Network Interfaces</h5>
            @if(empty($interfaces))
                <p class="text-muted">No interface data available.</p>
            @else
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
                                    @if ($interface['disabled'] === 'true')
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
            @endif
        </div>
    </div>
</div>

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('bandwidthChart').getContext('2d');
    const labels = [];
    const dataRx = [];
    const dataTx = [];
    let selectedInterface = document.getElementById('interfaceSelect').value;

    document.getElementById('interfaceSelect').addEventListener('change', function () {
        selectedInterface = this.value;
        labels.length = 0; dataRx.length = 0; dataTx.length = 0;
    });

    const bandwidthChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Rx (Download) kbps',
                    data: dataRx,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    fill: false
                },
                {
                    label: 'Tx (Upload) kbps',
                    data: dataTx,
                    borderColor: 'rgba(255, 99, 132, 1)',
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    function fetchTraffic() {
        fetch(`/mikrotik-traffic?interface=${selectedInterface}`)
            .then(response => response.json())
            .then(data => {
                const time = new Date().toLocaleTimeString();
                const rx = parseInt(data['rx-bits-per-second']) / 1024;
                const tx = parseInt(data['tx-bits-per-second']) / 1024;

                if (labels.length > 20) {
                    labels.shift(); dataRx.shift(); dataTx.shift();
                }

                labels.push(time);
                dataRx.push(rx.toFixed(2));
                dataTx.push(tx.toFixed(2));

                bandwidthChart.update();
            })
            .catch(err => console.error('Traffic fetch error:', err));
    }

    setInterval(fetchTraffic, 3000); // refresh every 3 seconds
</script>
@endsection
