<?php

namespace App\Controller;

use App\Entity\Job;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class JobController extends AbstractController
{

    /**
     * Return all job present in the database
     *
     * @return object[]
     */
    public function index()
    {
        $manager = $this->getDoctrine()->getManager();
        return $manager->getRepository(Job::class)->findAll();
    }

    /**
     * Add job with ajax request
     *
     * @param Request $request
     * @return Response|static
     */
    public function add(Request $request)
    {
        $name = $request->request->get('jobName');

        $manager = $this->getDoctrine()->getManager();
        if ($manager->getRepository(Job::class)->findOneBy(array('name' => $name))) {
            return JsonResponse::create("Ce poste existe déjà", 409);
        }

        $job = new Job();
        $job->setName($name);
        $manager->persist($job);
        $manager->flush();

        $job = $this->getDoctrine()->getRepository(Job::class)->findOneBy(array('name' => $name));
        $data = $this->get('serializer')->serialize($job, 'json');

        return new Response($data, 200, array(
                "Content-Type" => "application/json"
            )
        );

    }
}
