<?php

namespace App\Http\Controllers;

use AloTech\AloTech;
use AloTech\Authentication;
use AloTech\Click2;
use AloTech\Report;
use Illuminate\Http\Request;

class AloTechController extends Controller
{
    public function loginAloTech(Request $request)
    {
        $token = 'ahRzfm11c3RlcmktaGl6bWV0bGVyaXIfCxISVGVuYW50QXBwbGljYXRpb25zGICA5Ibn17YKDKIBGGhhc2hpbWdyb3VwLmFsby10ZWNoLmNvbQ';
        $userName = $request->email;
        $authentication = new Authentication();
        $authentication->setUsername($userName);
        $authentication->setAppToken($token);
        $authentication->setEmail($userName);

        $aloTech = new AloTech($authentication);

        $aloTech->login($userName);
        session(['alotech' => $aloTech]);
        //$request->session()->put($aloTech);
        return redirect()->back()->with('toast_success', 'Loged to Alotech successfully');
    }

    public function getCall(Request $request): \Illuminate\Http\JsonResponse
    {
        $aloTech = $request->session()->pull('alotech');
        $click2 = new Click2($aloTech);
        $res = $click2->call([
            'phonenumber' => '5522926875',

        ]);
        session(['current_call' => $click2]);
        return response()->json($res);
    }

    public function getHang(Request $request)
    {
        $aloTech = $request->session()->pull('alotech');
        $click2 = $request->session()->pull('current_call');
        return $res = $click2->hang();
    }
}
