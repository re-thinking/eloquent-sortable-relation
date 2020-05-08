<?php

namespace Rethinking\Eloquent\Relations\Sortable\Test;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * @var Generator
     */
    private $faker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->faker = Factory::create();

        $this->setUpDatabase();
    }

    /**
     * @return Generator
     */
    public function getFaker(): Generator
    {
        return $this->faker;
    }

    protected function setUpDatabase()
    {
        $this->app['db']->connection()->getSchemaBuilder()->create('owners', function (Blueprint $table) {
            $table->increments('id');
        });

        $this->app['db']->connection()->getSchemaBuilder()->create('default_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('owner_id');
            $table->unsignedSmallInteger('position');
            $table->timestamps();
        });

        $this->app['db']->connection()->getSchemaBuilder()->create('custom_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('owner_id');
            $table->unsignedSmallInteger('place');
            $table->timestamps();
        });
    }
}