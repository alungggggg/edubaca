<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetPersonalToken extends Command
{
    protected $signature = 'personal_access_tokens:truncate';
    protected $description = 'Mengosongkan semua data tabel personal_access_tokens';

    public function handle()
    {
        DB::table('personal_access_tokens')->truncate(); // atau ->delete() kalau ingin tidak reset auto increment
        $this->info('Tabel personal_access_tokens berhasil dikosongkan.');
    }
}
