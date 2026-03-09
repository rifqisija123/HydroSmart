<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionResource;
use App\Models\Transaction;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\TransactionsExport;

class ListTransactions extends ListRecords
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // EXPORT PDF
            Action::make('export_pdf')
                ->label('Cetak PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->action(function () {
                    if (Transaction::count() === 0) {
                        $this->dispatch('swal:no-transactions', message: 'Belum ada transaksi untuk di cetak ke PDF.');
                        return;
                    }
                    return $this->exportPdf();
                }),

            // EXPORT EXCEL
            Action::make('export_excel')
                ->label('Cetak Excel')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->action(function () {
                    if (Transaction::count() === 0) {
                        $this->dispatch('swal:no-transactions', message: 'Belum ada transaksi untuk di cetak ke Excel.');
                        return;
                    }
                    return $this->exportExcel();
                }),

            // PRINT
            Action::make('print')
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->action(function () {
                    if (Transaction::count() === 0) {
                        $this->dispatch('swal:no-transactions', message: 'Belum ada transaksi untuk di-print.');
                        return;
                    }
                    $this->redirect(route('transactions.print', request()->query()));
                }),
        ];
    }

    // === EXPORT PDF ===
    public function exportPdf()
    {
        $records = $this->getFilteredRecords()->get();

        $pdf = \PDF::loadView('exports.transactions-pdf', [
            'records' => $records,
        ]);

        return response()->streamDownload(
            fn() => print($pdf->output()),
            'transactionswadah.pdf'
        );
    }

    // === EXPORT EXCEL ===
    public function exportExcel()
    {
        return Excel::download(new TransactionsExport(
            $this->getFilteredRecords()
        ), 'transactionswadah.xlsx');
    }

    // === FILTER SUPPORT ===
    protected function getFilteredRecords(): Builder
    {
        return $this->getTableQuery()->clone()
            ->when(request('tableFilters'), function ($query) {});
    }
}
