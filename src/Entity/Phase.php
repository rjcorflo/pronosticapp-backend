<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Phase.
 *
 * @ORM\Entity
 * @ORM\Table(name="phases")
 */
class Phase
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
     * @var float
     *
     * @ORM\Column(type="float")
     */
    private $multiplierFactor;

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
     * @return Phase
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
     * @return Phase
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
     * Set multiplierFactor
     *
     * @param float $multiplierFactor
     *
     * @return Phase
     */
    public function setMultiplierFactor($multiplierFactor)
    {
        $this->multiplierFactor = $multiplierFactor;

        return $this;
    }

    /**
     * Get multiplierFactor
     *
     * @return float
     */
    public function getMultiplierFactor()
    {
        return $this->multiplierFactor;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
