<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ticket
 *
 * @ORM\Table(name="ticket")
 * @ORM\Entity
 */
class Ticket
{
    /**
     * @var int
     *
     * @ORM\Column(name="IdTicket", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idticket;

    /**
     * @var float
     *
     * @ORM\Column(name="PrixTicket", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixticket;

    /**
     * @var string
     *
     * @ORM\Column(name="NomEvent", type="string", length=255, nullable=false)
     */
    private $nomevent;

    public function getIdticket(): ?int
    {
        return $this->idticket;
    }

    public function getPrixticket(): ?float
    {
        return $this->prixticket;
    }

    public function setPrixticket(float $prixticket): self
    {
        $this->prixticket = $prixticket;

        return $this;
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


}
