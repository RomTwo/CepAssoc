<?php

namespace App\Controller;

use App\Entity\Job;
use App\Form\EventType;
use Firebase\JWT\JWT;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class JobController extends AbstractController
{

    public function index()
    {
        $manager = $this->getDoctrine()->getManager();
        return $manager->getRepository(Job::class)->findAll();
    }

    public function add(Request $request)
    {
        $token = $request->request->get('jobToken');
        $name = $request->request->get('jobName');

        try {
            JWT::decode($token, $_ENV['PRIVATE_KEY'], array($_ENV['ALG']));
            $manager = $this->getDoctrine()->getManager();

            if ($manager->getRepository(Job::class)->findOneBy(array('name' => $name))) {
                return new JsonResponse('already exist', 204);
            } else {
                $job = new Job();
                $job->setName($name);
                $manager->persist($job);
                $manager->flush();

                $job = $this->getDoctrine()->getRepository(Job::class)->findOneBy(array('name' => $name));
                $data = $this->get('serializer')->serialize($job, 'json');

                return new Response($data, 200, array("Content-Type" => "application/json"));
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            return new JsonResponse('error', 401);
        }
    }
}
