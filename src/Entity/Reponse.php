<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Reponse
 *
 * @ORM\Table(name="reponse", indexes={@ORM\Index(name="id_reclamation", columns={"id_reclamation"})})
 * @ORM\Entity
 */
class Reponse
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_reponse", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idReponse;

    /**
     * @var int
     *
     * @ORM\Column(name="id_reclamation", type="integer", nullable=false)
     */
    private $idReclamation;

    /**
     * @var int
     *
     * @ORM\Column(name="message_rep", type="integer", nullable=false)
     */
    private $messageRep;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_rep", type="date", nullable=false)
     */
    private $dateRep;

    public function getIdReponse(): ?int
    {
        return $this->idReponse;
    }

    public function getIdReclamation(): ?int
    {
        return $this->idReclamation;
    }

    public function setIdReclamation(int $idReclamation): self
    {
        $this->idReclamation = $idReclamation;

        return $this;
    }

    public function getMessageRep(): ?int
    {
        return $this->messageRep;
    }

    public function setMessageRep(int $messageRep): self
    {
        $this->messageRep = $messageRep;

        return $this;
    }

    public function getDateRep(): ?\DateTimeInterface
    {
        return $this->dateRep;
    }

    public function setDateRep(\DateTimeInterface $dateRep): self
    {
        $this->dateRep = $dateRep;

        return $this;
    }


}
