<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard\ImportExport;

use App\Exports\InvoicesExport;
use App\Models\Admin;
use App\Notifications\ExportExcelReady;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

final class InvoiceExportController
{
    /**
     * Export the specified resource.
     */
    public function __invoke(Request $request): RedirectResponse
    {
        $uuid = Str::uuid()->toString();

        /** @var Admin $admin */
        $admin = type(auth()->user())->as(Admin::class);

        Excel::queue(
            new InvoicesExport(filter: $request->fluent()),
            $filePath = "export/invoices-{$uuid}.xlsx",
        )->chain([
            fn () => $admin->notify(new ExportExcelReady($filePath)),
            fn () => Storage::delete($filePath),
        ]);

        return to_route('dashboard.invoices.index');
    }
}
