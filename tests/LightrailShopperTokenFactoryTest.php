<?php

namespace Lightrail;

require_once __DIR__ . '/../init.php';

$dotenv = new \Dotenv\Dotenv(__DIR__ . "/..");
$dotenv->load();

use PHPUnit\Framework\TestCase;

class LightrailShopperTokenFactoryTest extends TestCase
{
    public function testSignsContactId()
    {
        Lightrail::$apiKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJnIjp7Imd1aSI6Imdvb2V5IiwiZ21pIjoiZ2VybWllIiwidG1pIjoidGVlbWllIn19.Xb8x158QIV2ukGuQ3L5u4KPrL8MC-BToabnzKMQy7oc";
        Lightrail::$sharedSecret = "secret";

        $shopperToken = LightrailShopperTokenFactory::generate("chauntaktEyeDee", array("validityInSeconds" => 600));
        $shopperPayload = \Firebase\JWT\JWT::decode($shopperToken, Lightrail::$sharedSecret, array('HS256'));

        $this->assertEquals("chauntaktEyeDee", $shopperPayload->g->coi, "g.coi");
        $this->assertEquals("gooey", $shopperPayload->g->gui, "g.gui");
        $this->assertEquals("germie", $shopperPayload->g->gmi, "g.gmi");
        $this->assertEquals("teemie", $shopperPayload->g->tmi, "g.tmi");
        $this->assertEquals("MERCHANT", $shopperPayload->iss, "iss");
        $this->assertObjectNotHasAttribute("metadata", $shopperPayload);
        $this->assertGreaterThan(0, $shopperPayload->iat, "iat is a number > 0");
        $this->assertEquals($shopperPayload->iat + 600, $shopperPayload->exp, "exp = iat + 600");
    }

    public function testSignsEmptyContactId()
    {
        Lightrail::$apiKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJnIjp7Imd1aSI6Imdvb2V5IiwiZ21pIjoiZ2VybWllIiwidG1pIjoidGVlbWllIn19.Xb8x158QIV2ukGuQ3L5u4KPrL8MC-BToabnzKMQy7oc";
        Lightrail::$sharedSecret = "secret";

        $shopperToken = LightrailShopperTokenFactory::generate("", array("validityInSeconds" => 600));
        $shopperPayload = \Firebase\JWT\JWT::decode($shopperToken, Lightrail::$sharedSecret, array('HS256'));

        $this->assertEquals("", $shopperPayload->g->coi, "g.coi");
        $this->assertEquals("gooey", $shopperPayload->g->gui, "g.gui");
        $this->assertEquals("germie", $shopperPayload->g->gmi, "g.gmi");
        $this->assertEquals("teemie", $shopperPayload->g->tmi, "g.tmi");
        $this->assertEquals("MERCHANT", $shopperPayload->iss, "iss");
        $this->assertObjectNotHasAttribute("metadata", $shopperPayload);
        $this->assertGreaterThan(0, $shopperPayload->iat, "iat is a number > 0");
        $this->assertEquals($shopperPayload->iat + 600, $shopperPayload->exp, "exp = iat + 600");
    }

    public function testSignsMetadata()
    {
        Lightrail::$apiKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJnIjp7Imd1aSI6Imdvb2V5IiwiZ21pIjoiZ2VybWllIiwidG1pIjoidGVlbWllIn19.Xb8x158QIV2ukGuQ3L5u4KPrL8MC-BToabnzKMQy7oc";
        Lightrail::$sharedSecret = "secret";

        $shopperTokenOptions = array("metadata" => array("foo" => "bar"));
        $shopperToken = LightrailShopperTokenFactory::generate("chauntaktEyeDee",
            $shopperTokenOptions);
        $shopperPayload = \Firebase\JWT\JWT::decode($shopperToken, Lightrail::$sharedSecret, array('HS256'));

        $this->assertEquals("chauntaktEyeDee", $shopperPayload->g->coi, "g.coi");
        $this->assertEquals("gooey", $shopperPayload->g->gui, "g.gui");
        $this->assertEquals("germie", $shopperPayload->g->gmi, "g.gmi");
        $this->assertEquals("teemie", $shopperPayload->g->tmi, "g.tmi");
        $this->assertEquals("MERCHANT", $shopperPayload->iss, "iss");
        $this->assertObjectHasAttribute("metadata", $shopperPayload);
        $this->assertObjectHasAttribute("foo", $shopperPayload->metadata);
        $this->assertEquals("bar", $shopperPayload->metadata->foo);
        $this->assertGreaterThan(0, $shopperPayload->iat, "iat is a number > 0");
        $this->assertGreaterThan(0, $shopperPayload->exp, "exp is a number > 0");
    }

    public function testThrowsExceptionIfApiKeyEmpty()
    {
        Lightrail::$apiKey = "";
        Lightrail::$sharedSecret = "secret";

        $this->expectException(\Exception::class);

        LightrailShopperTokenFactory::generate("chauntaktEyeDee", array("validityInSeconds" => 600));
    }

    public function testThrowsExceptionIfApiKeyMissingGui()
    {
        Lightrail::$apiKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJnIjp7ImdtaSI6Imdlcm1pZSIsInRtaSI6InRlZW1pZSJ9fQ.p0d-IOkELlQUWchphCEqYembTGOVvzdpnlqGpa34kKw";
        Lightrail::$sharedSecret = "secret";

        $this->expectException(\Exception::class);

        LightrailShopperTokenFactory::generate("chauntaktEyeDee", array("validityInSeconds" => 600));
    }

    public function testThrowsExceptionIfApiKeyMissingGmi()
    {
        Lightrail::$apiKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJnIjp7Imd1aSI6Imdvb2V5IiwidG1pIjoidGVlbWllIn19.JNNh_KyFwTfE6-p9AhcDhvD0wyJB2gZofvVewnG6p3s";
        Lightrail::$sharedSecret = "secret";

        $this->expectException(\Exception::class);

        LightrailShopperTokenFactory::generate("chauntaktEyeDee", array("validityInSeconds" => 600));
    }

    public function testThrowsExceptionIfApiKeyMissingTmi()
    {
        Lightrail::$apiKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJnIjp7Imd1aSI6Imdvb2V5IiwiZ21pIjoiZ2VybWllIn19.XxOjDsluAw5_hdf5scrLk0UBn8VlhT-3zf5ZeIkEld8";
        Lightrail::$sharedSecret = "secret";

        $this->expectException(\Exception::class);

        LightrailShopperTokenFactory::generate("chauntaktEyeDee", array("validityInSeconds" => 600));
    }

    public function testThrowsExceptionIfSharedSecretEmpty()
    {
        Lightrail::$apiKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJnIjp7Imd1aSI6Imdvb2V5IiwiZ21pIjoiZ2VybWllIiwidG1pIjoidGVlbWllIn19.Xb8x158QIV2ukGuQ3L5u4KPrL8MC-BToabnzKMQy7oc";
        Lightrail::$sharedSecret = "";

        $this->expectException(\Exception::class);

        LightrailShopperTokenFactory::generate("chauntaktEyeDee", array("validityInSeconds" => 600));
    }
    public function testThrowsExceptionIfContactIdNotString()
    {
        Lightrail::$apiKey = "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJnIjp7Imd1aSI6Imdvb2V5IiwiZ21pIjoiZ2VybWllIiwidG1pIjoidGVlbWllIn19.Xb8x158QIV2ukGuQ3L5u4KPrL8MC-BToabnzKMQy7oc";
        Lightrail::$sharedSecret = "";

        $this->expectException(\Exception::class);

        LightrailShopperTokenFactory::generate(array("contactId" => "chauntaktEyeDee"), array("validityInSeconds" => 600));
    }
}
