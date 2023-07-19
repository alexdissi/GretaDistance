<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $prenom = null;

    #[ORM\Column(length: 20)]
    private ?string $civilite = null;

    #[ORM\Column(length: 50)]
    private ?string $login = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $avatar = null;

    #[ORM\Column]
    private ?int $age = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateCreation = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Cours::class)]
    private Collection $cours;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: FormContact::class)]
    private Collection $formcontact;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Fichier::class)]
    private Collection $fichier;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Taches::class)]
    private Collection $taches;

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Pays $pays = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Calendar::class)]
    private Collection $calendars;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: CoursFinish::class)]
    private Collection $coursFinish;


    public function __construct()
    {
        $this->cours = new ArrayCollection();
        $this->formcontact = new ArrayCollection();
        $this->fichier = new ArrayCollection();
        $this->taches = new ArrayCollection();
        $this->calendars = new ArrayCollection();
        $this->coursFinish = new ArrayCollection();
    }

    public const ROLES_CHOICES = [
        'ROLE_ADMIN' => 'ROLE_ADMIN',
        'ROLE_ENSEIGNANT' => 'ROLE_ENSEIGNANT',
        'ROLE_USER' => 'ROLE_USER',
    ];
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(string $civilite): static
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getDateCreation(): ?\DateTimeInterface
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTimeInterface $dateCreation): static
    {
        $this->dateCreation = $dateCreation;

        return $this;
    }

    /**
     * @return Collection<int, Cours>
     */
    public function getCours(): Collection
    {
        return $this->cours;
    }

    public function addCour(Cours $cour): static
    {
        if (!$this->cours->contains($cour)) {
            $this->cours->add($cour);
            $cour->setUser($this);
        }

        return $this;
    }

    public function removeCour(Cours $cour): static
    {
        if ($this->cours->removeElement($cour)) {
            // set the owning side to null (unless already changed)
            if ($cour->getUser() === $this) {
                $cour->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FormContact>
     */
    public function getFormcontact(): Collection
    {
        return $this->formcontact;
    }

    public function addFormcontact(FormContact $formcontact): static
    {
        if (!$this->formcontact->contains($formcontact)) {
            $this->formcontact->add($formcontact);
            $formcontact->setUser($this);
        }

        return $this;
    }

    public function removeFormcontact(FormContact $formcontact): static
    {
        if ($this->formcontact->removeElement($formcontact)) {
            // set the owning side to null (unless already changed)
            if ($formcontact->getUser() === $this) {
                $formcontact->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Fichier>
     */
    public function getFichier(): Collection
    {
        return $this->fichier;
    }

    public function addFichier(Fichier $fichier): static
    {
        if (!$this->fichier->contains($fichier)) {
            $this->fichier->add($fichier);
            $fichier->setUser($this);
        }

        return $this;
    }

    public function removeFichier(Fichier $fichier): static
    {
        if ($this->fichier->removeElement($fichier)) {
            // set the owning side to null (unless already changed)
            if ($fichier->getUser() === $this) {
                $fichier->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Taches>
     */
    public function getTaches(): Collection
    {
        return $this->taches;
    }

    public function addTach(Taches $tach): static
    {
        if (!$this->taches->contains($tach)) {
            $this->taches->add($tach);
            $tach->setUser($this);
        }

        return $this;
    }

    public function removeTach(Taches $tach): static
    {
        if ($this->taches->removeElement($tach)) {
            // set the owning side to null (unless already changed)
            if ($tach->getUser() === $this) {
                $tach->setUser(null);
            }
        }

        return $this;
    }

    public function getPays(): ?Pays
    {
        return $this->pays;
    }

    public function setPays(?Pays $pays): static
    {
        $this->pays = $pays;

        return $this;
    }

    /**
     * @return Collection<int, Calendar>
     */
    public function getCalendars(): Collection
    {
        return $this->calendars;
    }

    public function addCalendar(Calendar $calendar): static
    {
        if (!$this->calendars->contains($calendar)) {
            $this->calendars->add($calendar);
            $calendar->setUser($this);
        }

        return $this;
    }

    public function removeCalendar(Calendar $calendar): static
    {
        if ($this->calendars->removeElement($calendar)) {
            // set the owning side to null (unless already changed)
            if ($calendar->getUser() === $this) {
                $calendar->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CoursFinish>
     */
    public function getCoursFinish(): Collection
    {
        return $this->coursFinish;
    }

    public function addCoursFinish(CoursFinish $coursFinish): static
    {
        if (!$this->coursFinish->contains($coursFinish)) {
            $this->coursFinish->add($coursFinish);
            $coursFinish->setUser($this);
        }

        return $this;
    }

    public function removeCoursFinish(CoursFinish $coursFinish): static
    {
        if ($this->coursFinish->removeElement($coursFinish)) {
            // set the owning side to null (unless already changed)
            if ($coursFinish->getUser() === $this) {
                $coursFinish->setUser(null);
            }
        }

        return $this;
    }
}
