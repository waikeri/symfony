<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @author Vašek Boch <vasek.boch@live.com>
 * @author Jan Klat <jenik@klatys.cz>
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 * @UniqueEntity(fields="username", message="Tento e-mail je již registrován")
 */
class User implements UserInterface
{

	/**
	 * @var int
	 * @ORM\Id
	 * @ORM\GeneratedValue
	 * @ORM\Column(type="integer")
	 */
	private $id;

	/**
	 * @ORM\Column(type="string", length=255, unique=true, name="email")
	 * @Assert\NotBlank()
	 * @Assert\Email()
	 */
	private $username;

	/**
	 * @ORM\Column(type="string", length=64)
	 */
	private $password;

	/**
	 * @Assert\NotBlank()
	 * @Assert\Length(max=4096)
	 */
	private $plainPassword;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private $firstName;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private $lastName;

	/**
	 * @var string
	 * @ORM\Column(type="string")
	 */
	private $phone;

	/**
	 * @var Address[]
	 * @ORM\OneToMany(targetEntity="Address", mappedBy="user")
	 */
	private $addresses;

	/**
	 * @var array
	 * @ORM\Column(type="string")
	 */
	private $roles;

	public function __construct()
	{
		$this->addresses = new ArrayCollection();
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 * @return self
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * @param mixed $username
	 * @return self
	 */
	public function setUsername($username)
	{
		$this->username = $username;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param mixed $password
	 * @return self
	 */
	public function setPassword($password)
	{
		$this->password = $password;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getPlainPassword()
	{
		return $this->plainPassword;
	}

	/**
	 * @param mixed $plainPassword
	 * @return self
	 */
	public function setPlainPassword($plainPassword)
	{
		$this->plainPassword = $plainPassword;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFirstName()
	{
		return $this->firstName;
	}

	/**
	 * @param string $firstName
	 * @return self
	 */
	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLastName()
	{
		return $this->lastName;
	}

	/**
	 * @param string $lastName
	 * @return self
	 */
	public function setLastName($lastName)
	{
		$this->lastName = $lastName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPhone()
	{
		return $this->phone;
	}

	/**
	 * @param string $phone
	 * @return self
	 */
	public function setPhone($phone)
	{
		$this->phone = $phone;
		return $this;
	}

	/**
	 * @return Address[]
	 */
	public function getAddresses()
	{
		return $this->addresses;
	}

	/**
	 * @param Address[] $addresses
	 * @return self
	 */
	public function setAddresses($addresses)
	{
		$this->addresses = $addresses;
		return $this;
	}

	/**
	 * @return array
	 */
	public function getRoles()
	{
		if (empty($this->roles) || empty(json_decode($this->roles))) {
			return self::rolesDefaultArray();
		}
		return json_decode($this->roles, true);
	}

	/**
	 * @param array $roles
	 * @return self
	 */
	public function setRoles(array $roles)
	{
		if (empty($roles)) {
			$this->roles = json_encode(self::rolesDefaultArray());
		} else {
			foreach ($roles as $role) {
				if (!in_array($role, self::rolesArray())) {
					throw new InvalidArgumentException("");
				}
			}
			$this->roles = json_encode($roles);
		}
		return $this;
	}

	/**
	 * Roles for users from DB
	 * @return array
	 */
	public static function rolesArray()
	{
		return [
			"ROLE_USER",
			"ROLE_ADMIN",
		];
	}

	/**
	 * Roles default for users from DB
	 * @return array
	 */
	public static function rolesDefaultArray()
	{
		return [
			"ROLE_USER",
		];
	}

	public function getSalt()
	{
		return null;
	}

	public function eraseCredentials()
	{
		//nothing to do
		return;
	}

}
