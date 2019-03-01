# laravel-artisan-custom-commands
Original artisan commands

## make:service {Name}
ex.  
<code>$ php artisan make:service BookService</code>

Result
```php
<?php

namespace App\Services;

use Illuminate\Http\Request;

class BookService
{
    //
}
```

## make:template {Name}
This command create some plane classes.
* Controller
* Service
* TestCase for Controller
* TestCase for Service

ex.  
<code>$ php artisan make:template Book</code>

Controller
```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookController extends Controller
{
    //
}
```

Service
```php
<?php

namespace App\Services;

use Illuminate\Http\Request;

class BookService
{
    //
}
```

ServiceTestCase
```php
<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookServiceTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
}
```

ControllerTestCase
```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
}
```

## make:origin_factory
ex.  
(1) Use Commands/input/migration.txt   

You need to add lines from your migration file.
```
$table->increments('id');
$table->integer('user_id');
$table->text('description');
$table->integer('type')->default(0);
$table->timestamps();
```

After, Run command below.  
<code>php artisan make:origin_factory BookFactory -m Book</code>

ex2.  
(2) Read migration file directoy.   
You need to add -f {migration file name} option, if run command.

<code>php artisan make:origin_factory BookFactory -m Book -f 2019_03_01_135536_create_books_table</code>

Factory
```php
<?php

use Faker\Generator as Faker;

$factory->define(App\Book::class, function (Faker $faker) {
    return [
        'user_id' => $faker->randomNumber(3),
			'title' => $faker->sentence(5),
			'description' => $faker->text(100),
			'type' => $faker->randomNumber(3),

    ];
});
```

## make:origin_seed 
<code>php artisan make:origin_seed BookSeeder -m Book</code>

```php
<?php

use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Book::class, 10)->create();
    }
}
```
## create_fake_data {Name}
This command execute below.
1. <p>php artisan make:origin_factory</p>
2. <p>php artisan make:origin_seed</p>
3. <p>php artisan db:seed --class {CreatedClassSeeder}</p> 

ex.  
(1) <code>php artisan create_fake_data Book</code>

(2) <code>php artisan create_fake_data Book -f 2019_03_01_135536_create_books_table</code>

### Note:
* You need create Model and migrate.
* You need to add <code>protected $fillabel = ['user_id'..column]</code> on Model.
