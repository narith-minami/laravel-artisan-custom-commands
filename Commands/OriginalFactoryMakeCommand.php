<?php

namespace app\Console\Commands;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Database\Console\Factories\FactoryMakeCommand;

class OriginalFactoryMakeCommand extends FactoryMakeCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:origin_factory';

    /**
     * Build the class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $model = $this->option('model')
                        ? $this->qualifyClass($this->option('model'))
                        : 'Model';
        $target = ['DummyModel', 'DummyContent'];
        $result = [$model, Self::generateResultCode($name)];
        return str_replace(
            $target, $result, parent::buildClass($name)
        );
    }

    protected function generateResultCode($name) {
      $lines = Self::getMigrationLines();

      $result = '';
      $rowNo = 1;
      for ($i=0; $i < count($lines); $i++) {
        $str = $lines[$i];
        if (strpos($str, '$table->') === false) {
          continue;
        }
        $type = explode('>', explode('(', $str)[0])[1];
        if ($type === 'timestamps' || $type === 'boolean') {
          continue;
        }
        $column = explode("'", $str)[1];
        if ($column === 'id' && $type === 'increments') {
          continue;
        }

        if (1 < $rowNo) {
          $result .= "\t\t\t\t";
        }
        $result .= "'".$column."' => $"."faker->";
        $result .= Self::getFakerCode($type ,$column);

        if ($i < count($lines)-1) {
          $result .= ","."\n";
        }
        $rowNo++;
      }
      return $result;
    }

    private function getFakerCode($type ,$column)
    {
      switch ($type) {
        case 'string':
          return "sentence(5)";
        case 'text':
          return "text(100)";
        case 'integer':
          return "randomNumber(3)";
        default:
          // code...
          break;
      }
    }

    protected function getMigrationLines()
    {
      if ($this->option('file')) {
        $file_name = $this->option('file');
        $lines = file(getcwd().'/database/migrations/'.$file_name.'.php', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      } else {
        $lines = file(__DIR__.'/input/migration.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
      }

      $str_array = [];
      foreach ($lines as $key => $value) {
        // echo $value."\n";
        $str_array[] = $value;
      }
      return $str_array;
    }

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__.'/stubs/factory.stub';
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['model', 'm', InputOption::VALUE_OPTIONAL, 'The name of the model'],
            ['file', 'f', InputOption::VALUE_OPTIONAL, 'The name of the migration file name']
        ];
    }
}
