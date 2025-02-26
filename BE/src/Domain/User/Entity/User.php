<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Common\Entity\AbstractEntity;
use App\Domain\Article\Entity\Comment;
use App\Domain\Article\Entity\Score;
use App\Domain\User\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User extends AbstractEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Column(length: 180)]
    private ?string $email = null;
    /**
     * @var list<string> The user roles
     */
    #[ORM\Column(type: "jsonb", options: ['jsonb' => true])]
    private array $roles = [];
    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;
    #[ORM\Column]
    private bool $isVerified = false;
    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'user', cascade: ['remove'])]
    private Collection $comments;
    #[ORM\OneToMany(targetEntity: Score::class, mappedBy: 'user', cascade: ['remove'])]
    private Collection $scores;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $billingAddress = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $shippingAddress = null;

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
     * @return list<string>
     * @see UserInterface
     *
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
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

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function setComments(Collection $comments): void
    {
        $this->comments = $comments;
    }

    public function getScores(): Collection
    {
        return $this->scores;
    }

    public function setScores(Collection $scores): void
    {
        $this->scores = $scores;
    }

    public function getBillingAddress(): ?string
    {
        return $this->billingAddress;
    }

    public function setBillingAddress(?string $billingAddress): void
    {
        $this->billingAddress = $billingAddress;
    }

    public function getShippingAddress(): ?string
    {
        return $this->shippingAddress;
    }

    public function setShippingAddress(?string $shippingAddress): void
    {
        $this->shippingAddress = $shippingAddress;
    }
}
