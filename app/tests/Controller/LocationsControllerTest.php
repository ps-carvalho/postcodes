<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LocationsControllerTest extends WebTestCase
{
    public function testLocationWithNoParameters()
    {
        $client = $this->getClient();
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
        $client = $this->getClient();
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
        $client = $this->getClient();
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

        $client = $this->getClient();
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

        $client = $this->getClient();
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
        $client = $this->getClient();
        $client->request('GET', '/locations?lat=50.606122086912&long=-1.9708572830129');
        $response = $client->getResponse();
        $this->assertTrue( $response->isClientError() );
    }

    public function testLocationByPartialPostcodeAndRadius(){
        $client = $this->getClient();
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
        $client = $this->getClient();
        $client->request('GET', '/locations?postcode=bh19');

        $result = $this->assertOkResponseAndGetResult($client);

        //do we have a result
        $this->assertArrayHasKey('result', $result['data']);
        $this->assertIsArray( $result['data']['result']);
    }

    /**
     * @return KernelBrowser
     */
    private function getClient(): KernelBrowser
    {
       return static::createClient([], [
            'accept' => 'application/json',
        ]);
    }

    /**
     * @param KernelBrowser $client
     * @return array[]
     */
    private function assertOkResponseAndGetResult(KernelBrowser $client){
        $response = $client->getResponse();
        $this->assertTrue($response->isOk());

        $data = $response->getContent();
        $this->assertJson($data);

        $result = json_decode($data, true);
        $this->assertIsArray($result);
        return $result;
    }
}
