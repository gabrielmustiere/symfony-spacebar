<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use function bin2hex;
use function random_bytes;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ApiTokenRepository")
 */
class ApiToken
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
    private $token;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expiresAt;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="apiTokens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * ApiToken constructor.
     *
     * @param User $user
     *
     * @throws \Exception
     */
    public function __construct(User $user)
    {
        $this->token = bin2hex(random_bytes(60));
        $this->user = $user;
        $this->expiresAt = new \DateTime('+24 hour');
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expiresAt;
    }

    /**
     * @throws \Exception
     */
    public function renewExpiresAt()
    {
        $this->expiresAt = new \DateTime('+24 hour');
    }

    /**
     * @throws \Exception
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return true;

        return $this->getExpiresAt() <= new \DateTime();
    }

    /**
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->user;
    }
}
