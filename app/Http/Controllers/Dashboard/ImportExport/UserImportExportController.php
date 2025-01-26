<?php

declare(strict_types=1);

namespace App\Http\Controllers\Dashboard\ImportExport;

use App\Exports\UsersExport;
use App\Http\Requests\Dashboard\ImportRequest;
use App\Imports\UsersImport;
use App\Models\Admin;
use App\Models\TemporaryUpload;
use App\Notifications\CompletedImport;
use App\Notifications\ExportExcelReady;
use App\Traits\SendToasts;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;

final class UserImportExportController
{
    use SendToasts;

    /**
     * Import the specified resource.
     */
    public function import(ImportRequest $request): RedirectResponse
    {
        $temporaryUpload = TemporaryUpload::findByMediaUuidInCurrentSession($request->media['uuid']);

        if (! $temporaryUpload instanceof TemporaryUpload) {
            $this->error(__('dashboard.global.file_upload.upload_failed'));

            return to_route('dashboard.users.index');
        }

        $media = $temporaryUpload->getFirstMedia();

        /** @var Admin $admin */
        $admin = type(auth()->user())->as(Admin::class);

        Excel::queueImport(
            new UsersImport($admin),
            $media->getPath()
        )->chain([
            function () use ($admin, $temporaryUpload): void {
                $admin->notify(new CompletedImport);

                $temporaryUpload->delete();
            },
        ]);

        return to_route('dashboard.users.index');
    }

    /**
     * Export the specified resource.
     */
    public function export(Request $request): RedirectResponse
    {
        $uuid = Str::uuid()->toString();

        /** @var Admin $admin */
        $admin = type(auth()->user())->as(Admin::class);

        Excel::queue(
            new UsersExport(filter: $request->fluent()),
            $filePath = "export/users-{$uuid}.xlsx",
        )->chain([
            fn () => $admin->notify(new ExportExcelReady($filePath)),
            fn () => Storage::delete($filePath),
        ]);

        return to_route('dashboard.users.index');
    }
}
