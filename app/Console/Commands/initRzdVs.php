<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class initRzdVs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'initRzdVs {--set_id=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      if (!$this->option('set_id')){
        $this->info('Необходимо указать id станции php artisan initrzd --set_id=xxx');
      }
      $this->info('Инициализация станции для РЖД');
      $this->info('Id = '.$this->option('set_id'));

      \App\Configuration::setId($this->option('set_id'));

      \App\Configuration::setSettings([
        'ui'=>'rzd',
      ]);

      \App\ExpansionModule::create([
        "id" => 1,
        "alias" => 'wxt-520',
        "is_optional" => 0,
      ]);

      \App\ExpansionModule::create([
        "id" => 2,
        "alias" => 'do-02',
        "is_optional" => 1,
      ]);

      \App\ExpansionModule::create([
        "id" => 3,
        "alias" => 'dpg-m',
        "is_optional" => 1,
      ]);

      \App\Device::create([
        "expansion_module_id" => 1,
        "alias" => "wxt520",
      ]);

      \App\Device::create([
        "expansion_module_id" => 2,
        "alias" => "precipitation",
      ]);

      \App\Device::create([
        "expansion_module_id" => 3,
        "alias" => "rail_prism_temperature",
      ]);


      \App\ftpClient::create([
        "host" => '37.140.192.116',
        "port" => 21,
        "login" => "u3040031_vniizht",
        "password" => "dW7uR9hZ6wsX7xP0",
        "station_id" => $this->option('set_id')
      ]);
    }
}
