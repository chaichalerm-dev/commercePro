<?php

namespace Tests;

use Database\Seeders\RoleSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Roles are referenced by a users FK, so every RefreshDatabase test
     * needs them seeded before any User factory runs.
     */
    protected $seed = true;

    protected $seeder = RoleSeeder::class;
}
