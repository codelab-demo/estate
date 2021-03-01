<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Doctrine\ODM\MongoDB\Mapping\Annotations\Index;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
/**
 * @MongoDB\Document(repositoryClass="App\Repository\EstateRepository")
 *
 */
class Estate
{
    /**
     * @MongoDB\Id
     */
    protected $id;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $name;

	/**
     * @MongoDB\Field(type="string")
	 * @MongoDB\Index(order="asc")
	 * @Assert\NotBlank()
     */
    protected $city;

    /**
     * @MongoDB\Field(type="string")
	 * @MongoDB\Index(order="asc")
	 * @Assert\NotBlank()
     */
    protected $country;
	
	/**
     * @MongoDB\Field(type="string")
	 * @Gedmo\Slug(fields={"name","city"})
	 * @Assert\NotBlank()
     */
    protected $slug;

    /**
     * @MongoDB\Field(type="hash")
     * @Index (order="2dsphere")
     */
    protected $location;
	
	/**
     * @MongoDB\Field(type="hash")
     */
    protected $estateDetails;
	
	/**
     * @MongoDB\Field(type="float")
     * @Index (order="asc")
     */
    protected $price;
	
	/**
     * @MongoDB\Field(type="date")
	 * @Assert\NotBlank()
	 * @Gedmo\Timestampable(on="create")
     */
    protected $createdAt;

    /**
     * @MongoDB\Field(type="string")
     * @Index (order="asc")
     */
    protected $type;

    /**
     * @MongoDB\Field(type="collection")
     */
    protected $images;

    /**
     * @MongoDB\Field(type="boolean")
     * @Index (order="asc")
     */
    protected $special;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $description;

    /**
     * @MongoDB\Field(type="string")
     */
    protected $address;

    /**
     * @MongoDB\Field(type="hash")
     */
    protected $features;
	

	

	public function setName($name) {
		$this->name = $name;
	}	
	
	public function getName() {
		return $this->name;
	}
	
	public function setCity($city) {
		$this->city = $city;
	}	
	
	public function getCity() {
		return $this->city;
	}	
	
	public function setPrice($price) {
		$this->price = $price;
	}	
	
	public function getPrice() {
		return $this->price;
	}
	
	public function setLocation($location) {
		$this->location = $location;
	}
	
	public function getLocation() {
		return $this->location;
	}
	
	public function getCreatedAt() {
		return $this->createdAt;
	}
	
	public function getId() {
		return $this->id;
	}

    public function getType()
    {
        return $this->type;
    }

    public function setType($type): void
    {
        $this->type = $type;
    }


    public function getImages()
    {
        return $this->images;
    }


    public function setImages($images): void
    {
        $this->images = $images;
    }


    public function getCountry()
    {
        return $this->country;
    }


    public function setCountry($country): void
    {
        $this->country = $country;
    }

    public function getEstateDetails()
    {
        return $this->estateDetails;
    }

    public function setEstateDetails($estateDetails): void
    {
        $this->estateDetails = $estateDetails;
    }

    /**
     * @return mixed
     */
    public function getSpecial()
    {
        return $this->special;
    }

    /**
     * @param mixed $special
     */
    public function setSpecial($special): void
    {
        $this->special = $special;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param mixed $address
     */
    public function setAddress($address): void
    {
        $this->address = $address;
    }

    /**
     * @return mixed
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * @param mixed $features
     */
    public function setFeatures($features): void
    {
        $this->features = $features;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }
}