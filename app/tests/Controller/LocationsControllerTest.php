<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LocationsControllerTest extends WebTestCase
{
    public function testLocationWithNoParameters()
    {
        $client = static::createClient([], [
            'accept'       => 'application/json',
        ]);
        $client->request('GET', '/locations');

        $response = $client->getResponse();
        $this->assertTrue($response->isOk());

        $data = $response->getContent();
        $this->assertJson($data);

        $result = json_decode($data, true);
        $this->assertIsArray($result);

        //lets ensure we have a default data set.
        $this->assertArrayHasKey('data', $result);

        //lets have not searched for anything
        $this->assertArrayHasKey('info', $result['data']);

    }

    public function testLocationByPostCode()
    {
        $client = static::createClient([], [
            'accept'       => 'application/json',
        ]);
        $client->request('GET', '/locations?postcode=bh192qt');

        $response = $client->getResponse();
        $this->assertTrue($response->isOk());

        $data = $response->getContent();
        $this->assertJson($data);

        $result = json_decode($data, true);
        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);

        // do we have a result
        $this->assertArrayHasKey('result', $result['data']);
        //does the result contain 1 object
        $this->assertCount(1, $result['data']['result']);
    }

    public function testLocationByPartialPostCode()
    {
        $client = static::createClient([], [
            'accept'       => 'application/json',
        ]);
        $client->request('GET', '/locations?postcode=bh19');

        $response = $client->getResponse();
        $this->assertTrue($response->isOk());

        $data = $response->getContent();
        $this->assertJson($data);

        $result = json_decode($data, true);
        $this->assertIsArray($result);

        //do we have a result
        $this->assertArrayHasKey('result', $result['data']);
        $this->assertIsArray( $result['data']['result']);
    }

    public function testLocationByLatitude(){

        $client = static::createClient([], [
            'accept'       => 'application/json',
        ]);
        $client->request('GET', '/locations?lat=50.606122086912&radius=0.2&unit=mi');

        $response = $client->getResponse();
        $this->assertTrue($response->isOk());

        $data = $response->getContent();
        $this->assertJson($data);

        $result = json_decode($data, true);
        $this->assertIsArray($result);

        //do we have a result
        $this->assertArrayHasKey('result', $result['data']);
        $this->assertIsArray( $result['data']['result']);
    }

    public function testLocationByLongitude(){

        $client = static::createClient([], [
            'accept'       => 'application/json',
        ]);
        $client->request('GET', '/locations?long=-1.9708572830129&radius=0.2&unit=mi');

        $response = $client->getResponse();
        $this->assertTrue($response->isOk());

        $data = $response->getContent();
        $this->assertJson($data);

        $result = json_decode($data, true);
        $this->assertIsArray($result);

        //do we have a result
        $this->assertArrayHasKey('result', $result['data']);
        $this->assertIsArray( $result['data']['result']);
    }

    public function testLocationByLatitudeAndLongitude(){
        $client = static::createClient([], [
            'accept'       => 'application/json',
        ]);
        $client->request('GET', '/locations?lat=50.606122086912&long=-1.9708572830129');
        $response = $client->getResponse();
        $this->assertTrue( $response->isClientError() );
    }

    public function testLocationByPartialPostcodeAndRadius(){
        $client = static::createClient([], [
            'accept'       => 'application/json',
        ]);
        $client->request('GET', '/locations?postcode=bh19');

        $response = $client->getResponse();
        $this->assertTrue($response->isOk());

        $data = $response->getContent();
        $this->assertJson($data);

        $result = json_decode($data, true);
        $this->assertIsArray($result);

        //do we have a result
        $this->assertArrayHasKey('result', $result['data']);
        $this->assertIsArray( $result['data']['result']);
    }

    public function testLocationByPartialPostcodeAndRadiusAndUnits(){
        $client = static::createClient([], [
            'accept'       => 'application/json',
        ]);
        $client->request('GET', '/locations?postcode=bh19');

        $response = $client->getResponse();
        $this->assertTrue($response->isOk());

        $data = $response->getContent();
        $this->assertJson($data);

        $result = json_decode($data, true);
        $this->assertIsArray($result);

        //do we have a result
        $this->assertArrayHasKey('result', $result['data']);
        $this->assertIsArray( $result['data']['result']);
    }
}
