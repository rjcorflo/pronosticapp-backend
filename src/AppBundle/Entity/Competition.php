<?php

namespace RJ\PronosticApp\Persistence\PersistenceRedBean\Model\Entity;

use AppBundle\Entity\Image;

use Doctrine\ORM\Mapping as ORM;


/**
 * Class Competition.
 *
 * @ORM\Entity
 * @ORM\Table(name="competitions")
 */
class Competition
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
     * @var Image
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Image", fetch="EAGER")
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
     * @return Competition
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
     * @return Competition
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
     * Set image
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return Competition
     */
    public function setImage(Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \AppBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }

    public function getCompetitionImage()
    {
        return $this->getImage()->getImage();
    }
}
