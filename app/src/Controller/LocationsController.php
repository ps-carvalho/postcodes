<?php

namespace App\Controller;

use App\Dto\PostCodeDto;
use App\Repository\LocationRepository;
use App\Responses\ApiResponse;
use App\Responses\WelcomeApiResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LocationsController extends AbstractController
{
    #[Route('/', name: 'index', methods: 'GET')]
    public function index(){
        return new RedirectResponse('/locations');
    }
    #[Route('/locations', name: 'locations', methods: 'GET')]
    public function locations(Request $request, LocationRepository $locationRepository): Response
    {
        $data = [];
        $postCodeDto = new PostCodeDto($request);
        if($postCodeDto->isEmpty()) {
            return $this->json(new WelcomeApiResponse());
        }
        if($postCodeDto->getPostCode()) {
            $data = $locationRepository->findAllMatchingPartialPostcode($postCodeDto->getPostCode());
            if(empty($data)){
                return $this->json(new ApiResponse(
                    [
                        'parameters' => [
                            'postcode' => $postCodeDto->getPostCode(),
                            'radius' => $postCodeDto->getRadius(),
                            'latitude' => $postCodeDto->getLatitude(),
                            'longitude' => $postCodeDto->getLongitude(),
                            'unit' => $postCodeDto->getUnit(),
                        ],
                        'result' => 'No post code available similar to : '. $postCodeDto->getPostCode(),
                    ],
                    200
                ));
            }
        }
        if(!$postCodeDto->getPostCode() && $postCodeDto->getRadius() != 0) {
            $data = $locationRepository->findAllLocationsByProximity(
                $postCodeDto->getLatitude(),
                $postCodeDto->getLongitude(),
                $postCodeDto->getRadius(),
                $postCodeDto->getUnit()
            );
        }
        return $this->json(new ApiResponse(
            [
                'parameters' =>[
                    'postcode' => $postCodeDto->getPostCode(),
                    'radius' => $postCodeDto->getRadius(),
                    'latitude' => $postCodeDto->getLatitude(),
                    'longitude' => $postCodeDto->getLongitude(),
                    'unit' => $postCodeDto->getUnit(),
                ],
                'result' => $data,
            ],
            200
        ));
    }


}
