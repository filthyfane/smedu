<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

//Validation Classes
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *     fields={"email"},
 *     message="This e-mail address is already in use"
 * )
 * @UniqueEntity(
 *     fields={"username"},
 *     message="This username is already in use"
 * )
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32, unique=true)
     * @Assert\NotBlank(message = "This field can not be blank")
     * @Assert\Length(
     *     min=4, minMessage = "The username must be at least '{{ limit }}' characters long",
     *     max=32, maxMessage = "The username can NOT contain more than '{{ limit }}' characters"
     * )
     * @Assert\Type(
     *     type="alpha",
     *     message="The username can only contain letters"
     * )
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=128, unique=true)
     * @Assert\NotBlank(message = "This field can not be blank")
     * @Assert\Email(
     *     message = "The email '{{ value }}' is not a valid email",
     *     checkMX = true
     * )
     */
    private $email;

    /**
    * @ORM\Column(type="string", length=128)
    * @Assert\Length(
    *     min=8, minMessage = "The password must be at least '{{ limit }}' characters long",
    *     max=32, maxMessage = "The password can NOT contain more than '{{ limit }}' characters"
    * )
    */
    private $password;

    /**
     * @ORM\Column(type="string", length=16)
     */
    private $usertype;

    public function getId()
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;

    }

    public function getUsertype(): ?string
    {
        return $this->usertype;
    }

    public function setUsertype(string $usertype): self
    {
        $this->usertype = $usertype;

        return $this;
    }

    public function getRoles()
    {
      return [
        $this->usertype
      ];
    }

    public function getSalt() {}

    public function eraseCredentials() {}

    public function serialize()
    {
      return serialize([
        $this->id,
        $this->username,
        $this->email,
        $this->password,
        $this->usertype
      ]);
    }

    public function unserialize($string)
    {
      list (
        $this->id,
        $this->username,
        $this->email,
        $this->password,
        $this->usertype
      ) = unserialize($string, ['allowed_classes' => false]);
    }
}