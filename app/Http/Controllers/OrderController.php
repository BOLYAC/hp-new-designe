<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Models\Project;
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
        $invoices = Invoice::with(['user', 'client'])->get();
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
        $amount = $invoice->price - $invoice->installment - $invoice->payments()->sum('amount');
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
        $m = $request->get('price') - $request->get('installment') - $invoice->payments()->sum('amount');

        if ($m === 0) {
            $data['status'] = 3;
        } else {
            $data['status'] = 2;
        }

        $invoice->update($data);
        $amount = $invoice->price - $invoice->installment - $invoice->payments()->sum('amount');
        return view('invoices.show', compact('invoice', 'amount'))
            ->with('success', 'Invoice updated successfully');
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

}
