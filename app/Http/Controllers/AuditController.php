<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class AuditController extends Controller
{
  public function index()
  {
      $audits = \OwenIt\Auditing\Models\Audit::with('user')
          ->orderBy('created_at', 'desc')->get();
      return view('audits', ['audits' => $audits]);
  }
}
