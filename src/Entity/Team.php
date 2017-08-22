<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Team entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="teams")
 */
class Team
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
     * @ORM\Column(type="string")
     */
    private $alias;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $city;

    /**
     * @var Stadium
     *
     * @ORM\ManyToOne(targetEntity="Stadium")
     */
    private $stadium;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $color;

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
     * @return Team
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
     * Set alias
     *
     * @param string $alias
     *
     * @return Team
     */
    public function setAlias($alias)
    {
        $this->alias = $alias;

        return $this;
    }

    /**
     * Get alias
     *
     * @return string
     */
    public function getAlias()
    {
        return $this->alias;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Team
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
     * Set color
     *
     * @param string $color
     *
     * @return Team
     */
    public function setColor($color)
    {
        $this->color = $color;

        return $this;
    }

    /**
     * Get color
     *
     * @return string
     */
    public function getColor()
    {
        return $this->color;
    }

    /**
     * Set image
     *
     * @param \App\Entity\Image $image
     *
     * @return Team
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

    /**
     * Set stadium
     *
     * @param \App\Entity\Stadium $stadium
     *
     * @return Team
     */
    public function setStadium(Stadium $stadium = null)
    {
        $this->stadium = $stadium;

        return $this;
    }

    /**
     * Get stadium
     *
     * @return \App\Entity\Stadium
     */
    public function getStadium()
    {
        return $this->stadium;
    }

    public function getTeamImage()
    {
        return $this->getImage()->getImage();
    }

    public function __toString()
    {
        return $this->getName();
    }
}
