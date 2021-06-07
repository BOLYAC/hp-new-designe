<?php

namespace App\Http\Controllers;

use AloTech\AloTech;
use AloTech\Authentication;
use AloTech\Click2;
use AloTech\Report;

class AloTechController extends Controller
{
    public function getCall()
    {
        $token = 'ahRzfm11c3RlcmktaGl6bWV0bGVyaXIfCxISVGVuYW50QXBwbGljYXRpb25zGICA5Ibn17YKDKIBGGhhc2hpbWdyb3VwLmFsby10ZWNoLmNvbQ';
        $userName = 'mohammad.kouli@hashimproperty.com';
        $authentication = new Authentication();
        $authentication->setUsername($userName);
        $authentication->setAppToken($token);
        $authentication->setEmail($userName);

        $aloTech = new AloTech($authentication);

        $aloTech->login($userName);

        /*$click2 = new Click2($aloTech);

        $res = $click2->call([
            'phonenumber' => '5522926875',
        ]);*/

        $report = new Report($aloTech);

        $r = $report->agentPerf([
            'email' => $userName,           // Optional
        ]);

        return response()->json($r);
    }
}
