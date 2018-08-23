<?php

namespace Lightrail;

require_once __DIR__ . '/../init.php';

$dotenv = new \Dotenv\Dotenv(__DIR__ . "/..");
$dotenv->load();

use PHPUnit\Framework\TestCase;

class LightrailTest extends TestCase
{
    public function testEnvVarsSet()
    {
        $this->assertNotEmpty(getEnv("LIGHTRAIL_API_KEY"));
        $this->assertNotEmpty(getEnv("LIGHTRAIL_SHARED_SECRET"));
        $this->assertNotEmpty(getEnv("CONTACT_ID"));
    }
}
