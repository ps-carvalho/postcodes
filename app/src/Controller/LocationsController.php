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
        $postCode = $requestStack->getCurrentRequest()->get('postcode');
        $latitude =  (float) $requestStack->getCurrentRequest()->get('lat', 0);
        $longitude = (float) $requestStack->getCurrentRequest()->get('long', 0);
        $radius = (float) $requestStack->getCurrentRequest()->get('radius', 0);
        $unit = (string) $requestStack->getCurrentRequest()->get('unit', 'mi');
        if($postCode) {
            $data = $locationRepository->findAllMatchingPartialPostcode($postCode);
        }
        if(!$postCode && $radius != 0) {
            $data = $locationRepository->findAllLocationsByProximity(
                $latitude,
                $longitude,
                $radius,
                $unit);
        }
        return $this->json([
            'data' => $data,
            'radius' => $radius,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'unit' => $unit
        ]);
    }
}
