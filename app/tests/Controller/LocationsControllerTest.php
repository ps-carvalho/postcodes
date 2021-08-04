<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LocationsControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient([], [
            'accept'       => 'application/json',
        ]);
        $client->request('GET', '/locations');

        // the HttpKernel response instance
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());

        $data = $response->getContent();
        $this->assertJson($data);

        $result = json_decode($data, true);
        $this->assertIsArray($result);

        $this->assertArrayHasKey('data', $result);


        $client->request('GET', '/locations?radius=0.20');

        $response = $client->getResponse();
        $this->assertTrue($response->isOk());

        $data = $response->getContent();
        $this->assertJson($data);

        $result = json_decode($data, true);
        $this->assertIsArray($result);

        $this->assertArrayHasKey('data', $result);
    }
}
