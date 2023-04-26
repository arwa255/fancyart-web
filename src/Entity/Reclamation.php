<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\ReclamationRepository;
use App\Entity\Utilisateur;
use App\Entity\Reponse;
use App\Entity\Type;
use ORM\Table;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReclamationRepository::class)]


class Reclamation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id_reclamation = null;





    #[ORM\Column(length: 250)]
    #[Assert\NotBlank(message: "Tu dois saisir le texte du reclamation ")]  
    private ?string $text_rec = null;



    #[ORM\ManyToOne(targetEntity: Utilisateur::class)]
    #[ORM\JoinColumn(name: "id_user", referencedColumnName: "id")]
    protected $id_user;


#[ORM\OneToOne(targetEntity: Reponse::class, mappedBy: 'reclamation')]
    private $reponse;




   
    #[ORM\ManyToOne(targetEntity: Type::class)]
    #[ORM\JoinColumn(name: "idtype", referencedColumnName: "idtype")]
    protected $idtype;




    public function getId_Reclamation(): ?int
    {
        return $this->id_reclamation;
    }



    public function getTextRec(): ?string
    {
        return $this->text_rec;
    }
    public function gettext_Rec(): ?string
    {
        return $this->text_rec;
    }

    public function setTextRec(string $text_rec): self
    {
        $this->text_rec = $text_rec;

        return $this;
    }



    public function getIdUser(): ?Utilisateur
    {
        return $this->id_user;
    }

    public function setIdUser(?Utilisateur $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }

    public function getId_User(): ?Utilisateur
    {
        return $this->id_user;
    }

    public function setId_User(?Utilisateur $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }


    public function getIdReclamation(): ?int
    {
        return $this->id_reclamation;
    }

    public function getReponse(): ?Reponse
    {
        return $this->reponse;
    }

    public function setReponse(?Reponse $reponse): self
    {
        // unset the owning side of the relation if necessary
        if ($reponse === null && $this->reponse !== null) {
            $this->reponse->setReclamation(null);
        }

        // set the owning side of the relation if necessary
        if ($reponse !== null && $reponse->getReclamation() !== $this) {
            $reponse->setReclamation($this);
        }

        $this->reponse = $reponse;

        return $this;
    }

    public function getIdtype(): ?Type
    {
        return $this->idtype;
    }

    public function setIdtype(?Type $idtype): self
    {
        $this->idtype = $idtype;

        return $this;
    }



   
}