<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Evenement
 *
 * @ORM\Table(name="evenement")
 * @ORM\Entity
 */
class Evenement
{
    /**
     * @var int
     *
     * @ORM\Column(name="idEvent", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idevent;

    /**
     * @var string
     *
     * @ORM\Column(name="NomEvent", type="string", length=255, nullable=false)
     */
    private $nomevent;

    /**
     * @var string
     *
     * @ORM\Column(name="AdresseEvent", type="string", length=255, nullable=false)
     */
    private $adresseevent;

    /**
     * @var int
     *
     * @ORM\Column(name="CapaciteEvent", type="integer", nullable=false)
     */
    private $capaciteevent;

    /**
     * @var int
     *
     * @ORM\Column(name="nbrTicketdispo", type="integer", nullable=false)
     */
    private $nbrticketdispo;

    /**
     * @var string
     *
     * @ORM\Column(name="DateDebutEvent", type="string", length=255, nullable=false)
     */
    private $datedebutevent;

    /**
     * @var string
     *
     * @ORM\Column(name="DateFinEvent", type="string", length=255, nullable=false)
     */
    private $datefinevent;

    /**
     * @var string
     *
     * @ORM\Column(name="DescriptionEvent", type="string", length=255, nullable=false)
     */
    private $descriptionevent;

    /**
     * @var int
     *
     * @ORM\Column(name="PrixEntre", type="integer", nullable=false)
     */
    private $prixentre;

    /**
     * @var string
     *
     * @ORM\Column(name="image1", type="string", length=255, nullable=false)
     */
    private $image1;

    public function getIdevent(): ?int
    {
        return $this->idevent;
    }

    public function getNomevent(): ?string
    {
        return $this->nomevent;
    }

    public function setNomevent(string $nomevent): self
    {
        $this->nomevent = $nomevent;

        return $this;
    }

    public function getAdresseevent(): ?string
    {
        return $this->adresseevent;
    }

    public function setAdresseevent(string $adresseevent): self
    {
        $this->adresseevent = $adresseevent;

        return $this;
    }

    public function getCapaciteevent(): ?int
    {
        return $this->capaciteevent;
    }

    public function setCapaciteevent(int $capaciteevent): self
    {
        $this->capaciteevent = $capaciteevent;

        return $this;
    }

    public function getNbrticketdispo(): ?int
    {
        return $this->nbrticketdispo;
    }

    public function setNbrticketdispo(int $nbrticketdispo): self
    {
        $this->nbrticketdispo = $nbrticketdispo;

        return $this;
    }

    public function getDatedebutevent(): ?string
    {
        return $this->datedebutevent;
    }

    public function setDatedebutevent(string $datedebutevent): self
    {
        $this->datedebutevent = $datedebutevent;

        return $this;
    }

    public function getDatefinevent(): ?string
    {
        return $this->datefinevent;
    }

    public function setDatefinevent(string $datefinevent): self
    {
        $this->datefinevent = $datefinevent;

        return $this;
    }

    public function getDescriptionevent(): ?string
    {
        return $this->descriptionevent;
    }

    public function setDescriptionevent(string $descriptionevent): self
    {
        $this->descriptionevent = $descriptionevent;

        return $this;
    }

    public function getPrixentre(): ?int
    {
        return $this->prixentre;
    }

    public function setPrixentre(int $prixentre): self
    {
        $this->prixentre = $prixentre;

        return $this;
    }

    public function getImage1(): ?string
    {
        return $this->image1;
    }

    public function setImage1(string $image1): self
    {
        $this->image1 = $image1;

        return $this;
    }


}
