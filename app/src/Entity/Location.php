<?php

namespace App\Entity;

use App\Repository\LocationRepository;
use App\Utility\CoordinatesConverter;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass=LocationRepository::class)
 */
class Location implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $postCode;


    /**
     * @ORM\Column(type="float")
     */
    private $eastings;

    /**
     * @ORM\Column(type="float")
     */
    private $northings;

    /**
     * @ORM\Column(type="float")
     */
    private $latitude;

    /**
     * @ORM\Column(type="float")
     */
    private $longitude;

    private $distance;



    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Location
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * @param mixed $postCode
     * @return Location
     */
    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;
        return $this;
    }



    /**
     * @return mixed
     */
    public function getEastings()
    {
        return $this->eastings;
    }

    /**
     * @param mixed $eastings
     * @return Location
     */
    public function setEastings($eastings)
    {
        $this->eastings = $eastings;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getNorthings()
    {
        return $this->northings;
    }

    /**
     * @param mixed $northings
     * @return Location
     */
    public function setNorthings($northings)
    {
        $this->northings = $northings;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * @return Location
     */
    public function setLatitude()
    {
        $this->latitude = $this->calculateCoordinates()['latitude'];
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @return Location
     */
    public function setLongitude()
    {
        $this->longitude = $this->calculateCoordinates()['longitude'];
        return $this;
    }

    private function calculateCoordinates()
    {
        return  (new CoordinatesConverter($this->getEastings(), $this->getNorthings()))->Convert();
    }

    /**
     * @return mixed
     */
    public function getDistance()
    {
        return $this->distance;
    }

    /**
     * @param float $distance
     * @return Location
     */
    public function setDistance(float $distance): self
    {
        $this->distance = $distance;
        return $this;
    }



    public function jsonSerialize()
    {
        return  [
           'id' => $this->getId(),
           'postCode' => $this->getPostCode(),
           'lat' => $this->getLatitude(),
           'long' => $this->getLongitude(),
           'distance' => $this->getDistance() > 0 ? $this->getDistance() : 0
            ];
    }
}
