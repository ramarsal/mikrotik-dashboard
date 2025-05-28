<?php

namespace App\Http\Controllers;

use App\Services\MikroTikService;

class MikroTikController extends Controller
{
    protected $mikrotik;

    public function __construct(MikroTikService $mikrotik)
    {
        $this->mikrotik = $mikrotik;
    }

    /*public function index()
    {
        $system = $this->mikrotik->getSystemResource();
        $interfaces = $this->mikrotik->getInterfaces();
        $active = $this->mikrotik->getActiveUsers();
        $ip = $this->mikrotik->getIPAddress();

        return view('mikrotik.dashboard', compact('system', 'interfaces', 'active', 'ip'));
    }*/
    public function index()
{
    // MOCK SYSTEM DATA
    $system = [[
        'uptime' => '1d 2h 3m',
        'version' => '6.49.6',
        'board-name' => 'hAP lite',
        'cpu' => 'MIPS 24Kc V7.4',
        'cpu-load' => '20%',
        'free-memory' => '128000 KiB',
        'total-memory' => '256000 KiB',
    ]];

    // MOCK INTERFACES
    $interfaces = [
        [
            'name' => 'ether1',
            'type' => 'ether',
            'running' => 'true',
            'disabled' => 'false'
        ],
        [
            'name' => 'wlan1',
            'type' => 'wlan',
            'running' => 'true',
            'disabled' => 'false'
        ]
    ];

    // MOCK CONNECTED USERS
    $connectedDevices = 5;

    // MOCK IP Address
    $ipAddress = '10.0.0.1';

    return view('mikrotik.dashboard', compact('system', 'interfaces', 'connectedDevices', 'ipAddress'));
}

}
