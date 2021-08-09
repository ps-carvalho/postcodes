<?php

namespace App\Responses;

class WelcomeApiResponse extends ApiResponse
{
    public function setData($response)
    {
        $response = [
            'info' => 'Welcome to Post Code Finder',
            'required' => 'Either a post code or partial post code or latitude or longitude to be able to find a result.',
            'paramaters-available'=> [
                'postcode' => 'add a postcode [default is null]',
                'radius' => 'add a radius to apply on a search that is based on lat or long [default is 0.01] ',
                'lat' => 'search by latitude [default is 0]',
                'long' => 'search by longitude [default is 0]',
                'unit' => 'use miles (mi) or kilometers (km) [default is mi]'
            ],
            'examples' => [
                'postcode-example' => 'http://localhost:8080/locations?postcode=bh192qt',
                'partial-postcode-example' => 'http://localhost:8080/locations?postcode=bh19',
                'lat-radius-example'
                => 'http://localhost:8080/locations?lat=50.606122086912&radius=0.2&unit=mi',
                'long-radius-example'
                => 'http://localhost:8080/locations?long=-1.9708572830129&radius=0.2&unit=mi',
                'long-no-radius-example'
                => 'http://localhost:8080/locations?long=-1.9708572830129&unit=mi',
            ]
        ];
        return parent::setData($response);
    }
}
