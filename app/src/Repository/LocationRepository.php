<?php

namespace App\Repository;
ini_set('memory_limit', '-1');
use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Location|null find($id, $lockMode = null, $lockVersion = null)
 * @method Location|null findOneBy(array $criteria, array $orderBy = null)
 * @method Location[]    findAll()
 * @method Location[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    /**
     * @return Location[]
     */
    public function findAllMatchingPartialPostcode(string $postcode): array
    {
        $query =  $this->getEntityManager()->createQuery(
            'SELECT p
            FROM App\Entity\Location p
            WHERE p.postCode LIKE :partial 
            ORDER BY p.id ASC'
        )->setParameter('partial', '%'.$postcode.'%');

        // returns an array of Product objects
        return $query->getResult();
    }

    /**
     * @return Location[]
     */
    public function findAllLocationsByProximity($latitude, $longitude, $radius, $units = 'mi'): array
    {
        $found = false;

        if($latitude != 0 ) {
            $found = $this->findLocationByLatitude($latitude);
        }
        if($longitude != 0 ){
            $found = $this->findLocationByLongitude($longitude);
        }

        if($found){
            $latitude  = $found->getLatitude();
            $longitude = $found->getLongitude();
        }

        $conn = $this->getEntityManager()
            ->getConnection();

        $sql = "SELECT *, (
                    (ACOS(SIN(:latitude * PI() / 180) * 
                    SIN(location.latitude * PI() / 180) + 
                    COS(:latitude * PI() / 180) * COS(location.latitude * PI() / 180) *
                    COS((:longitude - location.longitude) * PI() / 180)) * 180 / PI()
                    ) * :unit )
            AS distance FROM location HAVING distance <= :radius ORDER BY distance ASC LIMIT 200";
        $stmt = $conn->prepare($sql);
        $result = $stmt->executeQuery(
            [
                'latitude' => $latitude,
                'longitude'=> $longitude,
                'radius'   => $radius,
                'unit'     => $units == 'mi' ? 60 * 1.1515 : 60 * 1.1515 * 1.609344
            ]
        );
       $results = $result->fetchAllAssociative();
       $resultData = [];
       foreach ($results as $result){
           $object = new Location;
           $object->setId($result['id']);
           $object->setPostCode($result['post_code']);
           $object->setEastings($result['eastings']);
           $object->setNorthings($result['northings']);
           $object->setLongitude();
           $object->setLatitude();
           $object->setDistance($result['distance']);
           $resultData[] = $object;
       }
       return $resultData;
    }

    private function findLocationByLatitude($latitude): ?Location
    {

        $query = $this->getEntityManager()->createQuery(
            'SELECT p
                  FROM App\Entity\Location p 
                  ORDER BY ABS(p.latitude - :latitude)'
        )->setParameter('latitude', $latitude)->setMaxResults(1);
        $found = $query->getResult();

        if(isset($found[0]) && $found[0] instanceof Location){
           return $found[0];
        }
        return null;
    }

    private function findLocationByLongitude($longitude): ?Location
    {

        $query = $this->getEntityManager()->createQuery(
            'SELECT p
                  FROM App\Entity\Location p 
                  ORDER BY ABS(p.longitude - :longitude)'
        )->setParameter('longitude', $longitude)->setMaxResults(1);
        $found = $query->getResult();

        if(isset($found[0]) && $found[0] instanceof Location){
            return $found[0];
        }
        return null;
    }
}
