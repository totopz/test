<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Doctor;
use AppBundle\Entity\Patient;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class DoctorController extends Controller
{
    /**
     * @Route("/doctor/{doctor}/patients/{patient}", name="app.doctor.add_patient")
     * @Method({"PUT"})
     */
    public function addPatient(Doctor $doctor, Patient $patient)
    {
        // TODO: apply some additional validation to $doctor and $patient if it is needed

        // add patient to the doctor
        $doctor->addPatient($patient);

        // save changes to the database
        $this->getDoctrine()->getManager()->flush();

        // convert entities to array  and include only needed fields
        $patients = array_map(function (\AppBundle\Entity\Patient $patient) {
            return [
                'id' => $patient->getId(),
                'name' => $patient->getName(),
                // etc.
            ];
        }, $doctor->getPatients()->toArray());

        return new JsonResponse([
            'doctor' => [
                'id' => $doctor->getId(),
                'name' => $doctor->getName(),
            ],
            'patients' => $patients,
            'msg' => 'Patient ' . $patient->getName() . ' was sucessfully added to doctor ' . $doctor->getName(),
        ]);
    }
}