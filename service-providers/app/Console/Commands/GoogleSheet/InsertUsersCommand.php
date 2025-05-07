<?php

namespace App\Console\Commands\GoogleSheet;

use App\Models\User;
use App\Services\GoogleSheetsService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Symfony\Component\Console\Command\Command as CommandAlias;

class InsertUsersCommand extends Command
{
    protected $signature = 'google-sheet:insert-users';

    protected $description = 'Insert users from Google Sheet';

    public function handle(): int
    {
        $sheetId = '1pRVDZPEacKEdzYq5Qp0V4jMseBvNRAGOM36bfMZLZL4';
        $sheetName = 'Sheet1';
        $data = (new GoogleSheetsService())->getAllData($sheetId, $sheetName);
        $fiveMinutesAgo = Carbon::now()->subMinutes(5);

        foreach ($data as $key => $item) {
            if ($key === 0)
                continue;

            $createdAt = Carbon::createFromFormat('d/m/Y H:i:s', $item[3]);
            if ($createdAt->greaterThan($fiveMinutesAgo)) {
                User::query()->create([
                    'name' => $item[0],
                    'email' => $item[1],
                    'gender' => $item[2],
                    'added_from_sheet' => true,
                ]);
            }

        }

        return CommandAlias::SUCCESS;
    }
}
