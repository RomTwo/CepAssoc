<?php

namespace App\Controller\administration;

use App\Entity\Adherent;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AdministrationController extends AbstractController
{

    /**
     * Print the home page of the administration part
     *
     * @return Response
     */
    public function home()
    {
        return $this->render('administration/home.html.twig');
    }

    /**
     * Return and Print a list of the competitors (for the plugin)
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function competiteurs()
    {
        $manager = $this->getDoctrine()->getManager();
        $competiteurs = $manager->getRepository(Adherent::class)->findBy(array(
                'isRegisteredInGestGym' => false,
                'isDeleted' => false
            )
        );

        if ($competiteurs) {
            return $this->render('administration/plugin/plugin_home.html.twig', array(
                    "comp" => $this->getSerializeAdherents($competiteurs)
                )
            );
        }
        return $this->render('administration/plugin/plugin_home.html.twig');
    }

    public function update_state(Request $req)
    {
        if ($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')) {
            $manager = $this->getDoctrine()->getManager();
            $adherentSynchonized = [];

            $ids = $req->get("ids");
            $new_ids = explode(",", substr(substr($ids, 0, -1), 1));
            for ($i = 0; $i < count($new_ids); $i++) {
                $adherent = $manager->getRepository(Adherent::class)->find($new_ids[$i]);
                $adherent->setIsRegisteredInGestGym(true);
                $manager->flush();
                array_push($adherentSynchonized, $adherent);
            }

            $competiteurs = $manager->getRepository(Adherent::class)->findBy(array(
                    'isRegisteredInGestGym' => false,
                    'isDeleted' => false
                )
            );

            $justSync = $this->getSerializeAdherents($adherentSynchonized);
            if ($competiteurs) {
                $notSync = $this->getSerializeAdherents($competiteurs);
                return new JsonResponse(array(
                    'notSync' => $notSync,
                    'justSync' => $justSync
                ), 200
                );
            }
            return new JsonResponse(array(
                'notSync' => array(),
                'justSync' => $justSync
            ), 200
            );

        } else {
            return new Response ("", 500);
        }
    }

    /**
     * Return the list of adherent serialize in json
     *
     * @param $adherents
     * @return bool|float|int|string
     */
    public function getSerializeAdherents($adherents)
    {
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        // Convert the datetime in string format : d-m-Y
        $callback = function ($innerObject) {
            return $innerObject instanceof \DateTime ? $innerObject->format('d-m-Y') : '';
        };

        // Manage the circular reference (the relationnship between entities)
        $defaultContext = [
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getName();
            },
        ];

        $normalizer = new ObjectNormalizer($classMetadataFactory, null, null, null, null, null, $defaultContext);
        $normalizer->setCallbacks(array(
                'birthDate' => $callback,
                'registrationDate' => $callback,
                'medicalCertificateDate' => $callback
            )
        );
        $encoder = new JsonEncoder();
        $serializer = new Serializer(array($normalizer), array($encoder));
        return $serializer->serialize($adherents, 'json', array(
                'groups' => 'competition'
            )
        );
    }

}
