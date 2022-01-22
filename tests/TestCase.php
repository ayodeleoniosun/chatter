<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public $baseUrl;

    public function setup(): void
    {
        parent::setUp();
        $this->baseUrl = sprintf('http://%s/api', config('app.domain'));
        $this->faker = \Faker\Factory::create();
    }
}
