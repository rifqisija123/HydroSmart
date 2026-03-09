<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Illuminate\Support\HtmlString;
use App\Filament\Widgets\IncomeChart;
use App\Filament\Widgets\DrinkStatsChart;
use App\Models\Transaction;
use App\Filament\Widgets\TotalIncomeSummary;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                \App\Filament\Pages\Dashboard::class,
            ])
            ->routes(function () {
                \Route::get('/transactions/print', function () {

                    $query = Transaction::query();

                    if (request()->has('tableFilters')) {
                        // TODO: apply filters di sini
                    }

                    return view('exports.transactions-print', [
                        'records' => $query->get(),
                    ]);
                })->name('transactions.print');
            })
            ->renderHook(
                PanelsRenderHook::BODY_END,
                fn() => new HtmlString('
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        document.addEventListener("livewire:init", () => {
                            Livewire.on("swal:no-transactions", (event) => {
                                const isDark = document.documentElement.classList.contains("dark");
                                Swal.fire({
                                    icon: "warning",
                                    title: "Tidak Ada Transaksi",
                                    text: event.message || "Belum ada data transaksi.",
                                    confirmButtonText: "OK",
                                    confirmButtonColor: "#fe9a00",
                                    background: isDark ? "#1a1a2e" : "#ffffff",
                                    color: isDark ? "#e8ecff" : "#1f2937",
                                });
                            });
                        });

                        document.addEventListener("click", function(e) {
                            const logoutButton = e.target.closest("button[type=submit]");
                            if (!logoutButton) return;

                            const form = logoutButton.closest("form");
                            if (!form || !form.action.includes("logout")) return;

                            e.preventDefault();
                            e.stopPropagation();

                            const isDark = document.documentElement.classList.contains("dark");
                            Swal.fire({
                                icon: "question",
                                title: "Logout",
                                text: "Apakah Anda yakin ingin logout?",
                                showCancelButton: true,
                                confirmButtonText: "Ya, Logout",
                                cancelButtonText: "Batal",
                                confirmButtonColor: "#fe9a00",
                                cancelButtonColor: "#6b7280",
                                background: isDark ? "#1a1a2e" : "#ffffff",
                                color: isDark ? "#e8ecff" : "#1f2937",
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    form.submit();
                                }
                            });
                        }, true);
                    </script>
                ')
            )
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                // AccountWidget::class,
                TotalIncomeSummary::class,
                IncomeChart::class,
                DrinkStatsChart::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
