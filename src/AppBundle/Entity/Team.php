<?php

namespace AppBundle\Entity;

use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
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
     * It only stores the name of the image associated with the product.
     *
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $image;

    /**
     * This unmapped property stores the binary contents of the image file
     * associated with the product.
     *
     * @Vich\UploadableField(mapping="product_images", fileNameProperty="image")
     *
     * @var File
     */
    private $imageFile;

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
     * @param string $image
     *
     * @return Team
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set stadium
     *
     * @param \AppBundle\Entity\Stadium $stadium
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
     * @return \AppBundle\Entity\Stadium
     */
    public function getStadium()
    {
        return $this->stadium;
    }

    /**
     * @return File
     */
    public function getImageFile(): File
    {
        return $this->imageFile;
    }

    /**
     * @param File $imageFile
     */
    public function setImageFile(File $imageFile)
    {
        $this->imageFile = $imageFile;
    }
}
