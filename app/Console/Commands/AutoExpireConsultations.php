<?php

namespace App\Console\Commands;

use App\Services\ConsultationService;
use Illuminate\Console\Command;

class AutoExpireConsultations extends Command
{
    protected $signature   = 'consultations:expire';
    protected $description = 'Automatically expire overdue active consultations';

    public function handle(ConsultationService $service): int
    {
        $count = $service->expireOverdue();

        if ($count > 0) {
            $this->info("[consultations:expire] {$count} consultation(s) expired.");
        }

        return Command::SUCCESS;
    }
}
