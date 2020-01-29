<?php

namespace App\Entity;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @Vich\Uploadable()
 * @ORM\Entity(repositoryClass="App\Repository\EventRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Event
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $town;

    /**
     * @ORM\Column(type="date")
     */
    private $performanceDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Price", mappedBy="events")
     */
    private $prices;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $eventImageName;

    /**
     * @var File
     * @Vich\UploadableField(mapping="event", fileNameProperty="eventImageName")
     */
    private $eventImagefile;

    /**
     * @return mixed
     */
    public function getEventImageName()
    {
        return $this->eventImageName;
    }

    /**
     * @param mixed $eventImageName
     */
    public function setEventImageName($eventImageName): void
    {
        $this->eventImageName = $eventImageName;
    }

    /**
     * @return null|File
     */
    public function getEventImagefile(): ?File
    {
        return $this->eventImagefile;
    }

    /**
     * @param File $eventImagefile
     * @return Event
     * @throws \Exception
     */
    public function setEventImagefile(File $eventImagefile): Event
    {
        $this->eventImagefile = $eventImagefile;
        if ($this->eventImagefile instanceof UploadedFile) {
            $this->updatedAt = new DateTime();
        }
        return $this;
    }

    public function __construct()
    {
        $this->prices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getTown(): ?string
    {
        return $this->town;
    }

    public function setTown(string $town): self
    {
        $this->town = $town;

        return $this;
    }

    public function getPerformanceDate(): ?\DateTimeInterface
    {
        return $this->performanceDate;
    }

    public function setPerformanceDate(\DateTimeInterface $performanceDate): self
    {
        $this->performanceDate = $performanceDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|Price[]
     */
    public function getPrices(): Collection
    {
        return $this->prices;
    }

    public function addPrice(Price $price): self
    {
        if (!$this->prices->contains($price)) {
            $this->prices[] = $price;
            $price->addEvent($this);
        }

        return $this;
    }

    public function removePrice(Price $price): self
    {
        if ($this->prices->contains($price)) {
            $this->prices->removeElement($price);
            $price->removeEvent($this);
        }

        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function handleCreationDate()
    {
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTimeImmutable());
        }
        $this->setUpdatedAt(new \DateTimeImmutable());
    }

    /**
     * @ORM\PreUpdate
     */
    public function handleUpdateDate()
    {
        $this->setUpdatedAt(new \DateTimeImmutable());
    }

}
