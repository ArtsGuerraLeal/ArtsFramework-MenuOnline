<?php

namespace App\Entity;

use App\Repository\MenuRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MenuRepository::class)
 */
class Menu
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Company::class, inversedBy="menus")
     */
    private $company;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $data;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $greeting;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="text",nullable=true)
     */
    private $notes;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $preview;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $info;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $numOfImages;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @ORM\Column(type="integer")
     */
    private $websiteVisits;

    /**
     * @ORM\Column(type="integer")
     */
    private $phoneVisits;

    /**
     * @ORM\Column(type="integer")
     */
    private $whatsappVisits;

    /**
     * @ORM\Column(type="integer")
     */
    private $visits;

    /**
     * @ORM\Column(type="integer")
     */
    private $infoVisits;

    /**
     * @ORM\Column(type="integer")
     */
    private $promotionVisits;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $whatsapp;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCompany(): ?Company
    {
        return $this->company;
    }

    public function setCompany(?Company $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getData(): ?string
    {
        return $this->data;
    }

    public function setData(?string $data): self
    {
        $this->data = $data;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getGreeting(): ?string
    {
        return $this->greeting;
    }

    public function setGreeting(?string $greeting): self
    {
        $this->greeting = $greeting;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getPreview(): ?string
    {
        return $this->preview;
    }

    public function setPreview(?string $preview): self
    {
        $this->preview = $preview;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(?string $info): self
    {
        $this->info = $info;

        return $this;
    }

    public function getNumOfImages(): ?int
    {
        return $this->numOfImages;
    }

    public function setNumOfImages(?int $numOfImages): self
    {
        $this->numOfImages = $numOfImages;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getWebsiteVisits(): ?int
    {
        return $this->websiteVisits;
    }

    public function setWebsiteVisits(?int $websiteVisits): self
    {
        $this->websiteVisits = $websiteVisits;

        return $this;
    }

    public function getPhoneVisits(): ?int
    {
        return $this->phoneVisits;
    }

    public function setPhoneVisits(?int $phoneVisits): self
    {
        $this->phoneVisits = $phoneVisits;

        return $this;
    }

    public function getWhatsappVisits(): ?int
    {
        return $this->whatsappVisits;
    }

    public function setWhatsappVisits(?int $whatsappVisits): self
    {
        $this->whatsappVisits = $whatsappVisits;

        return $this;
    }

    public function getVisits(): ?int
    {
        return $this->visits;
    }

    public function setVisits(?int $visits): self
    {
        $this->visits = $visits;

        return $this;
    }

    public function getInfoVisits(): ?int
    {
        return $this->infoVisits;
    }

    public function setInfoVisits(?int $infoVisits): self
    {
        $this->infoVisits = $infoVisits;

        return $this;
    }

    public function getPromotionVisits(): ?int
    {
        return $this->promotionVisits;
    }

    public function setPromotionVisits(?int $promotionVisits): self
    {
        $this->promotionVisits = $promotionVisits;

        return $this;
    }

    public function getWhatsapp(): ?string
    {
        return $this->whatsapp;
    }

    public function setWhatsapp(?string $whatsapp): self
    {
        $this->whatsapp = $whatsapp;

        return $this;
    }

   
}
