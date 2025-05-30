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
        $users = $this->mikrotik->getActiveLeases();
        $connectedDevices = count($users);

        $connectedDevices = count($users);
        $ipAddress = $ipData[0]['address'] ?? 'Unknown';

        return view('mikrotik.dashboard', compact(
            'system', 'interfaces', 'connectedDevices', 'ipAddress'
        ));
    }

    public function traffic()
    {
        $interface = 'ether1'; // change based on your active interface
        $traffic = $this->mikrotik->getInterfaceTraffic($interface);
        return response()->json($traffic[0]);
    }

    public function json()
    {
        $traffic = $this->mikrotik->getInterfaceTraffic('ether1');
        return response()->json([
            'rx' => $traffic[0]['rx-bits-per-second'] ?? 0,
            'tx' => $traffic[0]['tx-bits-per-second'] ?? 0,
        ]);
    }

}
