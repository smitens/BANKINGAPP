<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Foundation\Inspiring;

class DisplayInspiringQuote extends Command
{
    protected $signature = 'inspire';
    protected $description = 'Display an inspiring quote';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->comment(Inspiring::quote());
    }
}
