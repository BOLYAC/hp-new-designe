<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function index()
    {
        $invoices = Invoice::with(['user', 'client', 'project'])->get();
        return view('invoices.index', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        dd($data);
    }

    /**
     * Display the specified resource.
     *
     * @param Invoice $invoice
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function show(Invoice $invoice)
    {
        //$amount = $invoice->price - $invoice->installment - $invoice->payments()->sum('amount');
        $amount = $invoice->commission_total - $invoice->payments()->sum('amount');
        return view('invoices.show', compact('invoice', 'amount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Invoice $invoice
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|Response|\Illuminate\View\View
     */
    public function edit(Invoice $invoice)
    {
        $projects = Project::all();
        return view('invoices.edit', compact('invoice', 'projects'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Invoice $invoice
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|RedirectResponse|\Illuminate\View\View
     */
    public function update(Request $request, Invoice $invoice)
    {

        $request->validate([
            'project_id' => 'required',
        ]);

        $data = $request->except('_token', 'files');
        //$m = $request->get('price') - $request->get('installment') - $invoice->payments()->sum('amount');

        $invoice->update($data);
        //$amount = $invoice->price - $invoice->installment - $invoice->payments()->sum('amount');

        $amount = $invoice->commission_total - $invoice->payments()->sum('amount');
        return view('invoices.show', compact('invoice', 'amount'))
            ->with('toast_success', 'Invoice updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Invoice $invoice
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(Invoice $invoice): RedirectResponse
    {
        try {
            $invoice->delete();
        } catch (\Exception $e) {
            return back()->withError($e->getMessage())->withInput();
        }
        return redirect()->route('invoices.index');
    }

    public function commissionStat(Request $request)
    {

        $invoice = Invoice::where('id', $request->get('invoice_id'));

        $invoice->update([
            'commission_stat' => $request->get('title')
        ]);

    }

    public function changeStatus(Request $request)
    {
        $lead = Invoice::findOrFail($request->get('invoice_id'));

        $lead->update([
            'status' => $request->get('status')
        ]);

        $i = $request->get('status');
        switch ($i) {
            case 1:
                $stage = 'Paid';
                break;
            case 2:
                $stage = 'Partially paid';
                break;
        }


        /*$lead->StatusLog()->create([
            'stage_name' => $stage,
            'update_by' => \auth()->id(),
            'user_name' => \auth()->user()->name,
            'stage_id' => $request->get('stage_id')
        ]);*/

        try {
            return json_encode($lead, JSON_THROW_ON_ERROR);
        } catch (JsonException $e) {
            return back()->withError($e->getMessage())->withInput();
        }
    }

    public function printInvoice(Invoice $invoice)
    {
        return view('invoices.print', compact('invoice'));
    }
}
