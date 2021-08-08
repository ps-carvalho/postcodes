<?php

namespace App\Dto;



use Symfony\Component\HttpFoundation\Request;

class PostCodeDto extends Dto
{

    private null|string $postCode;

    private float $latitude;

    private float $longitude;

    private float $radius;

    private $unit;

    private $empty = true;

    public function __construct(Request $request){
        parent:: __construct($request);
        if(!empty($this->getRequest()->getQueryString())) {
            $this->setPostCode(
                $this->getRequest()->get('postcode', null)
            );
            $this->setLatitude(
                $this->getRequest()->get('lat', 0)
            );
            $this->setLongitude(
                $this->getRequest()->get('long', 0)
            );
           $this->setRadius(
               $this->getRequest()->get('radius', 0)
           );
           $this->setUnit(
               $this->getRequest()->get('unit', 'mi')
           );
           $this->setEmpty(false);
        }
    }

    /**
     * @return bool
     */
    public function isEmpty(): bool
    {
        return $this->empty;
    }

    /**
     * @param bool $empty
     * @return PostCodeDto
     */
    public function setEmpty(bool $empty): PostCodeDto
    {
        $this->empty = $empty;
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
     * @return PostCodeDto
     */
    public function setPostCode($postCode)
    {
        $this->postCode = $postCode;
        return $this;
    }

    /**
     * @return float
     */
    public function getLatitude() : float
    {
        return $this->latitude;
    }

    /**
     * @param float $latitude
     * @return PostCodeDto
     */
    public function setLatitude(float $latitude)
    {
        $this->latitude = $latitude;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLongitude() : float
    {
        return $this->longitude;
    }

    /**
     * @param mixed $longitude
     * @return PostCodeDto
     */
    public function setLongitude(float $longitude)
    {
        $this->longitude = $longitude;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getRadius() : float
    {
        return $this->radius;
    }

    /**
     * @param float $radius
     * @return PostCodeDto
     */
    public function setRadius(float $radius)
    {
        $this->radius = $radius;
        return $this;
    }

    /**
     * @return string
     */
    public function getUnit()  : string
    {
        return $this->unit;
    }

    /**
     * @param mixed $unit
     * @return PostCodeDto
     */
    public function setUnit(string $unit)
    {
        $this->unit = $unit;
        return $this;
    }


}
