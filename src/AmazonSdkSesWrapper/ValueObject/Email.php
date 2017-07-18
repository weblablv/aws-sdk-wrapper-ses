<?php

namespace WebLabLv\AmazonSdkSesWrapper\ValueObject;

use WebLabLv\AmazonSdkSesWrapper\ValueObject\Exception\EmailNotValidException;

final class Email
{
	/**
	 * @var string $email
	 */
	private $email;
	
	/**
	 * @param string $email
	 */
	public function __construct(string $email)
	{
		if (false === filter_var($email, FILTER_VALIDATE_EMAIL)) {
			throw new EmailNotValidException($email);
		}
		
		$this->email = $email;
	}
	
	/**
	 * @param string $email
	 * @return Email
	 */
	public static function create(string $email): Email
	{
		return new self($email);
	}
	
	/**
	 * @param Email $email
	 * @return bool
	 */
	public function equals(Email $email): bool
	{
		return strtolower((string)$this) === strtolower((string)$email);
	}
	
	/**
	 * @return string
	 */
	public function __toString(): string
	{
		return $this->email;
	}
}
