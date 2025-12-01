<?php

namespace App\Filament\Resources\Transactions\Pages;

use App\Filament\Resources\Transactions\TransactionResource;
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
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->action('exportPdf'),

            // EXPORT EXCEL
            Action::make('export_excel')
                ->label('Export Excel')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->action('exportExcel'),

            // PRINT
            Action::make('print')
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->url(fn() => route('transactions.print', request()->query()))
                ->openUrlInNewTab(),
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
            ->when(request('tableFilters'), function ($query) {
                
            });
    }
}
