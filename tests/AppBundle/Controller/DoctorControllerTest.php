<?php

namespace Tests\AppBundle\Controller;

use AppBundle\Entity\Doctor;
use AppBundle\Entity\Patient;
use Doctrine\ORM\EntityManager;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DoctorControllerTest extends WebTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    public function setUp()
    {
        parent::setUp();

        $this->entityManager = static::createClient()->getContainer()->get('doctrine.orm.default_entity_manager');
    }

    public function testAddPatientToDoctorWithoutPatients()
    {
        $client = static::createClient();

        $doctor = $this->createDoctor('Dr. John Smith');
        $patient = $this->createPatient('Fred Miller');

        $client->request('PUT', '/doctor/' . $doctor->getId() . '/patients/' . $patient->getId());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $responseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($doctor->getId(), $responseData['doctor']['id']);
        $this->assertEquals($doctor->getName(), $responseData['doctor']['name']);

        $this->assertCount(1, $responseData['patients']);

        $this->assertCount($patient->getId(), $responseData['patients'][0]['id']);
        $this->assertCount($patient->getName(), $responseData['patients'][0]['name']);
    }

    public function testAddPatientToDoctorWithoutExistingPatients()
    {
        $client = static::createClient();

        $doctor = $this->createDoctor('Dr. John Smith');
        $existingPatient = $this->createPatient('Fred Miller');

        $doctor->addPatient($existingPatient);
        $this->entityManager->flush();

        $newPatient = $this->createPatient('Tom Hardy');


        $client->request('PUT', '/doctor/' . $doctor->getId() . '/patients/' . $newPatient->getId());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $responseData = json_decode($client->getResponse()->getContent(), true);

        $this->assertEquals($doctor->getId(), $responseData['doctor']['id']);
        $this->assertEquals($doctor->getName(), $responseData['doctor']['name']);

        $this->assertCount(2, $responseData['patients']);

        $this->assertCount($existingPatient->getId(), $responseData['patients'][0]['id']);
        $this->assertCount($existingPatient->getName(), $responseData['patients'][0]['name']);

        $this->assertCount($newPatient->getId(), $responseData['patients'][1]['id']);
        $this->assertCount($newPatient->getName(), $responseData['patients'][1]['name']);
    }

    /**
     * @param string $name
     * @return Doctor
     */
    private function createDoctor($name)
    {
        $doctor = new Doctor();
        $doctor->setName($name);

        $this->entityManager->persist($doctor);
        $this->entityManager->flush();

        return $doctor;
    }

    /**
     * @param string $name
     * @return Patient
     */
    private function createPatient($name)
    {
        $patient = new Patient();
        $patient->setName($name);

        $this->entityManager->persist($patient);
        $this->entityManager->flush();

        return $patient;
    }
}
