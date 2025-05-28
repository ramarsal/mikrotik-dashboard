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

    public function index()
    {
        $system = $this->mikrotik->getSystemResource();
        $interfaces = $this->mikrotik->getInterfaces();
        $ipData = $this->mikrotik->getIPAddress();
        $users = $this->mikrotik->getActiveUsers();

        $connectedDevices = count($users);
        $ipAddress = $ipData[0]['address'] ?? 'Unknown';

        return view('mikrotik.dashboard', compact(
            'system', 'interfaces', 'connectedDevices', 'ipAddress'
        ));
    }
}
