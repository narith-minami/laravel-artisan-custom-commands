<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use InvalidArgumentException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CreateTemplateCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:template';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create plane classes >> Controller / Service / TestCase';

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
        $name = $this->getNameInput();
        $this->call('make:controller', ['name'=>$name.'Controller']);
        $this->call('make:service', ['name'=>$name.'Service']);
        $this->call('make:test', ['name'=>$name.'ServiceTest', '--unit'=>true]);
        $this->call('make:test', ['name'=>$name.'ControllerTest']);

        // exec unit test
        // $this->info(shell_exec("vendor/bin/phpunit tests/unit/".$name.'ServiceTest.php'));
    }

    /**
     * Get the root namespace for the class.
     *
     * @return string
     */
    protected function rootNamespace()
    {
        return $this->laravel->getNamespace();
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
            ['name', InputArgument::REQUIRED, 'The name of the Model or Prefix'],
        ];
    }
}
