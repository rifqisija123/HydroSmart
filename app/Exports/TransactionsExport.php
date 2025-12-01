<?php

namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Database\Eloquent\Builder;

class TransactionsExport implements FromView
{
    public function __construct(public Builder $query) {}

    public function view(): View
    {
        return view('exports.transactions-excel', [
            'records' => $this->query->get(),
        ]);
    }
}
