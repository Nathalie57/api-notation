<?php

namespace App\Entity;

use App\Repository\StudentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\Mark;

/**
 * @ORM\Entity(repositoryClass=StudentRepository::class)
 * @ApiResource(
 *  collectionOperations={"GET", "POST"},
 *  itemOperations={"GET", "DELETE", "PUT",
 *      "average"={
 *          "method"="get", 
 *          "path"="/students/{id}/average",
 *          "controller"="App\Controller\AverageStudentController",
 *          "openapi_context"={
 *              "summary"="Calculates the student average"
 *          }
 *      }
 *  },
 *  normalizationContext={
 *      "groups"={"students_read"}
 *  },
 * denormalizationContext={"disable_type_enforcement"=true}
 * )
 */
class Student
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"students_read", "marks_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"students_read", "marks_read"})
     * @Assert\NotBlank(message="Le prénom de l'élève est obligatoire !")
     * @Assert\Length(min=2, minMessage="Le prénom doit faire au moins deux caractères !")
     * @Assert\Regex(pattern="/\d/", match=false, message="Le prénom ne peut pas contenir de chiffre !")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=64)
     * @Groups({"students_read", "marks_read"})
     * @Assert\NotBlank(message="Le nom de l'élève est obligatoire !")
     * @Assert\Length(min=2, minMessage="Le nom doit faire au moins deux caractères !")
     * @Assert\Regex(pattern="/\d/", match=false, message="Le nom ne peut pas contenir de chiffre !")
     */
    private $lastName;

    /**
     * @ORM\Column(type="datetime")
     * @Groups({"students_read", "marks_read"})
     * @Assert\NotBlank(message="Le date de naissance de l'élève est obligatoire !")
     * @Assert\Type(
     *  type = "\DateTime",
     *  message = "La date renseignée doit être au format YYYY-MM-DD !"
     * )
     */
    private $birthday;

    /**
     * @ORM\OneToMany(targetEntity=Mark::class, mappedBy="student", cascade={"persist", "remove"})
     * @Groups({"students_read"})
     */
    private $marks;

    public function __construct()
    {
        $this->marks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday($birthday): self
    {
        $this->birthday = $birthday;

        return $this;
    }

    /**
     * @return Collection|Mark[]
     */
    public function getMarks(): Collection
    {
        return $this->marks;
    }

    public function addMark(Mark $mark): self
    {
        if (!$this->marks->contains($mark)) {
            $this->marks[] = $mark;
            $mark->setStudent($this);
        }

        return $this;
    }

    public function removeMark(Mark $mark): self
    {
        if ($this->marks->contains($mark)) {
            $this->marks->removeElement($mark);
            // set the owning side to null (unless already changed)
            if ($mark->getStudent() === $this) {
                $mark->setStudent(null);
            }
        }

        return $this;
    }
}
