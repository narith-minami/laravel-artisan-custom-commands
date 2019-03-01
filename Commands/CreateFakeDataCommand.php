<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CreateFakeDataCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'create_fake_data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Factory / Seeder and run db:seed --class Target';

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
        $model = $this->getNameInput();
        if ($this->option('file')) {
          $file_name = $this->option('file');
          $this->call('make:origin_factory', ['name'=>$model.'Factory', '-m'=>$model, '-f'=>$file_name]);
        } else {
          $this->call('make:origin_factory', ['name'=>$model.'Factory', '-m'=>$model]);
        }
        $this->call('make:origin_seed', ['name'=>$model.'Seeder', '-m'=>$model]);

        $confirmed = $this->confirm('Do you wish to run db:seed --class'.$model.'Seeder??');
        if (! $confirmed) {
            // $this->comment('create_fake_data completed!!');
            return;
        }
        $this->info('run >> db:seed --class '.$model.'Seeder');
        $this->call('db:seed', ['--class'=>$model.'Seeder']);
        $this->info('Finished!!');
    }

    /**
     * Get the desired class name from the input.
     *
     * @return string
     */
    protected function getNameInput()
    {
        return trim($this->argument('name'));
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'The name of the Model or Prefix']
        ];
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['file', 'f', InputOption::VALUE_OPTIONAL, 'The name of the migration file name']
        ];
    }
}
