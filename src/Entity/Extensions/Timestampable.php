<?php

namespace App\Entity\Extensions;

use Doctrine\ORM\Mapping as ORM;

trait Timestampable
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     */
    protected $updated;

    /**
     * @internal
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updateTimestamp(): void
    {
        if ($this->created === null) {
            $this->created = new \DateTime();
        }

        $this->updated = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getCreated(): ?\DateTime
    {
        return $this->created;
    }

    /**
     * @param \DateTime $created
     * @return static
     */
    public function setCreated(\DateTime $created)
    {
        $this->created = $created;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getUpdated(): ?\DateTime
    {
        return $this->updated;
    }

    /**
     * @param \DateTime $updated
     * @return $this
     */
    public function setUpdated(\DateTime $updated)
    {
        $this->updated = $updated;
        return $this;
    }
}
