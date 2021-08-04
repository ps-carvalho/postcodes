<?php

namespace App\Controller;

use App\Repository\LocationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationsController extends AbstractController
{
    #[Route('/locations', name: 'locations', methods: 'GET')]
    public function index(
        RequestStack $requestStack,
        LocationRepository $locationRepository): Response
    {
        $data = [];
        $postCode = $requestStack->getCurrentRequest()->get('postcode', null);
        $latitude =  (float) $requestStack->getCurrentRequest()->get('lat', 0);
        $longitude = (float) $requestStack->getCurrentRequest()->get('long', 0);
        $radius = (float) $requestStack->getCurrentRequest()->get('radius', 0.01);
        $unit = (string) $requestStack->getCurrentRequest()->get('unit', 'mi');
        if(
            empty($requestStack->getCurrentRequest()->getQueryString()) ||
            !$postCode && $latitude == 0 && $longitude == 0
        ){
            return $this->json([
                'data' => 'Welcome to Post Code Finder',
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
                    'lat-long-radius-example'
                    => 'http://localhost:8080/locations?lat=50.606122086912&long=-1.9708572830129&radius=0.2&unit=mi',
                    'lat-radius-example'
                    => 'http://localhost:8080/locations?lat=50.606122086912&radius=0.2&unit=mi',
                    'long-radius-example'
                    => 'http://localhost:8080/locations?long=-1.9708572830129&radius=0.2&unit=mi',
                    'long-no-radius-example'
                    => 'http://localhost:8080/locations?long=-1.9708572830129&unit=mi',
                ]
            ]);
        }
        if($postCode) {
            $data = $locationRepository->findAllMatchingPartialPostcode($postCode);
            if(empty($data)){
                return $this->json([
                    'radius' => $radius,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'unit' => $unit,
                    'data' => 'No post code available similar to : '. $postCode,
                ]);
            }
        }
        if(!$postCode && $radius != 0) {
            $data = $locationRepository->findAllLocationsByProximity(
                $latitude,
                $longitude,
                $radius,
                $unit);
        }
        return $this->json([
            'radius' => $radius,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'unit' => $unit,
            'data' => $data,
        ]);
    }
}
