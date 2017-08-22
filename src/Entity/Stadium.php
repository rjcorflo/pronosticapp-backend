<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Stadium entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="stadiums")
 */
class Stadium
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $color;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $city;

    /**
     * @var Image
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Image", fetch="EAGER")
     */
    private $image;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Stadium
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * @param string $color
     * @return Stadium
     */
    public function setColor(string $color)
    {
        $this->color = $color;
        return $this;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Stadium
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set image
     *
     * @param \App\Entity\Image $image
     *
     * @return Stadium
     */
    public function setImage(Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \App\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }

    public function getStadiumImage()
    {
        return $this->getImage()->getImage();
    }

    public function __toString()
    {
        return $this->getName();
    }
}
