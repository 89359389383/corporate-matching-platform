<?php

namespace App\Console\Commands;

use App\Services\ContractService;
use Illuminate\Console\Command;

class ActivateContracts extends Command
{
    protected $signature = 'contracts:activate';

    protected $description = '開始日到来の契約をactive化する';

    public function handle(ContractService $contractService): int
    {
        $count = $contractService->activateSignedContracts();
        $this->info('Activated: ' . $count);

        return self::SUCCESS;
    }
}

