<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Doctor
{
	/** @var  int */
	private $id;

	/** @var  string */
	private $name;

	/** @var  array */
	private $patients;

	public function __construct()
    {
        $this->patients = new ArrayCollection();
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
	 * @return Doctor
	 */
	public function setId($id)
	{
		$this->id = $id;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Doctor
	 */
	public function setName($name)
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return ArrayCollection
	 */
	public function getPatients()
	{
		return $this->patients;
	}

	/**
	 * @param Patient $patient
	 * @return Doctor
	 */
	public function addPatient(Patient $patient)
	{
		$this->patients->add($patient);

		return $this;
	}
}