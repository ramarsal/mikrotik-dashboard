<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use RouterOS\Client;
use RouterOS\Query;

class MikroTikService
{
    protected $client;
    protected $useMock;

    public function __construct()
    {
        $this->useMock = env('MIKROTIK_MOCK', false);

        if (!$this->useMock) {
            $this->client = new Client([
                'host' => env('MIKROTIK_HOST', '10.0.0.1'),
                'user' => env('MIKROTIK_USER', 'admin'),
                'pass' => env('MIKROTIK_PASS', ''),
                'port' => (int) env('MIKROTIK_PORT', 8728),
                'timeout' => (int) env ('MIKROTIK_TIMEOUT', 10),
            ]);
        }
    }

    public function getSystemResource()
    {
        if ($this->useMock) {
            return [[
                'uptime' => '3d 14:23:12',
                'cpu-load' => '35',
                'total-memory' => '256 MiB',
                'free-memory' => '123 MiB',
                'version' => '6.49.6'
            ]];
        }

        $query = new Query('/system/resource/print');
        return $this->client->query($query)->read();
    }

    public function getInterfaces()
    {
        if ($this->useMock) {
            return [
                ['name' => 'ether1', 'type' => 'ether', 'running' => 'true', 'disabled' => 'false'],
                ['name' => 'wlan1', 'type' => 'wireless', 'running' => 'false', 'disabled' => 'true'],
            ];
        }

        $query = new Query('/interface/print');
        return $this->client->query($query)->read();
    }

    public function getActiveUsers()
    {
        if ($this->useMock) {
            return [
                ['user' => 'student1', 'address' => '192.168.88.10'],
                ['user' => 'student2', 'address' => '192.168.88.11'],
            ];
        }

        $query = new Query('/ip/hotspot/active/print');
        return $this->client->query($query)->read();
    }

    public function getIPAddress()
    {
        if ($this->useMock) {
            return [['address' => '192.168.88.1/24']];
        }

        $query = new Query('/ip/address/print');
        return $this->client->query($query)->read();
    }

    public function getInterfaceTraffic($interface = 'ether1') 
    {
        $query = new Query('/interface/monitor-traffic');
        $query->equal('interface', $interface)->equal('once', 'true');
        return $this->client->query($query)->read();
    }
    
    public function getActiveLeases()
    {
        if ($this->useMock || !$this->client) {
            return [
                ['mac-address' => '00:11:22:33:44:55', 'address' => '192.168.88.100', 'status' => 'bound'],
                ['mac-address' => '66:77:88:99:AA:BB', 'address' => '192.168.88.101', 'status' => 'bound'],
            ]; // Sample mock data
        }

        $query = new Query('/ip/dhcp-server/lease/print');
        $leases = $this->client->query($query)->read();

        return array_filter($leases, function ($lease) {
        return isset($lease['status']) && $lease['status'] === 'bound';
        });
    }

}
