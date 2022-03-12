<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected string $baseUrl = 'http://chatter.test:8082';
    protected string $apiBaseUrl;

    public function setup(): void
    {
        $this->apiBaseUrl = $this->baseUrl . '/api/v1';
        parent::setUp();
        $this->faker = \Faker\Factory::create();
    }
}
