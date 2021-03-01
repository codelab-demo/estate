<?php

namespace App\Repository;

use App\Document\Estate;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

/**
 * @method Estate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Estate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Estate[]    findAll()
 * @method Estate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstateRepository extends DocumentRepository
{

    public function __construct(DocumentManager $dm)
    {
        $uow = $dm->getUnitOfWork();
        $classMetaData = $dm->getClassMetadata(Estate::class);
        parent::__construct($dm, $uow, $classMetaData);
    }
    /** funkcja zwraca obiekty wg filtrów */
    public function findBySearch($type= null, $location=null,$minPrice=null,$maxPrice=null) {
        $builder = $this->createAggregationBuilder(Estate::class);
        $builder->hydrate(Estate::class);
        $stage = 0;
        if($type) {
            $builder->match()->field('type')->equals($type);
            $stage++;
        }
        if($location) {
            $builder->match()->field('city')->equals($location);
            $stage++;
        }

        if($minPrice) {
            $builder->match()->field('price')->gte($minPrice);
            $stage++;
        }

        if($maxPrice) {
            $builder->match()->field('price')->lte($maxPrice);
            $stage++;
        }
        if(!$stage) {
            return $this->findBy([],[],3);
        }

        $resultCursor = $builder->getAggregation()->getIterator();
        $resultArray = [];
        while($resultCursor->valid()) {
            $resultArray[] = $resultCursor->current();
            $resultCursor->next();
        }

        return $resultArray;
    }

    /** Funkcja zwraca 6 losowych obiektów */
    public function findRandom() {

        $builder = $this->createAggregationBuilder(Estate::class);
        $builder
//            ->hydrate(Estate::class)
            ->sample(6)
            ->project()
                ->field('link')->expression('$slug')
                ->field('_id')->expression(1)
                ->field("type")->expression(1)
                ->field("price")->expression(1)
                ->field("city")->expression(1)
                ->field("address")->expression(1)
                ->field("estateDetails")->expression(1)
                ->field('image')->arrayElemAt('$images',0)

        ;
        $resultCursor = $builder->getAggregation()->getIterator();
        $resultArray = [];
        while($resultCursor->valid()) {
            $resultArray[] = $resultCursor->current();
            $resultCursor->next();
        }

        return $resultArray;
    }
    /** funkcja zwraca najpopularniejsze lokalizacje */
    public function findPopularPlaces($limit = null) {

        $builder = $this->createAggregationBuilder(Estate::class);
        $builder
            ->group()
                ->field('_id')->expression('$city')
                ->field('quantity')->sum(1)
            ->sort('quantity',-1)

       ;

        if($limit) {
            $builder->sort('quantity',-1);
            $builder->limit(3);
        } else {
            $builder->sort('_id',1);
        }
        $resultCursor = $builder->getAggregation()->getIterator();
        $resultArray = [];
        while($resultCursor->valid()) {
            $resultArray[] = $resultCursor->current();
            $resultCursor->next();
        }
        return $resultArray;
    }

    /** Funkcja znajduje najbliższe lokalizacje, wyklucza szukaną */
    public function findClosestEstates($x,$y, $objectId) {

        $builder = $this->createAggregationBuilder(Estate::class);
        $builder
            ->geoNear($x,$y)
            ->spherical(true)
            ->distanceField('distance')
            ->distanceMultiplier(6378.137)
            ->sort('distance',1)
            ->limit(4)
        ;

        $resultCursor = $builder->getAggregation();

        $resultArray = [];
        foreach ($resultCursor as $value) {
            if(count($resultArray) == 3)
                break;
            if($value['_id'] == $objectId)
                continue;
            $resultArray[] = $value;
        }

        return $resultArray;
    }
    /** Funkcja liczy wszystkie obiekty w bazie  */
    public function countAllEstates() {


        $builder = $this->createQueryBuilder();
        $builder->count();
        return $builder->getQuery()->execute();

    }

    /** funkcja znajduje losowy obiekt */
    public function findLucky() {

        $builder = $this->createAggregationBuilder(Estate::class);
        $builder
            ->hydrate(Estate::class)
            ->sample(1)

        ;
        $resultCursor = $builder->getAggregation();
        $result = $resultCursor->getIterator()->current();

        return $result;
    }
}
