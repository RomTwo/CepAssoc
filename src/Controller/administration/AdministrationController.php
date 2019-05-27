<?php

namespace App\Controller\administration;

use App\Entity\Adherent;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use App\Entity\Activity;
use App\Entity\Event;
use App\Entity\Category;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class AdministrationController extends AbstractController
{

    public function home(){
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
        $competiteurs = $manager->getRepository(Adherent::class)->findByIsRegisteredInGestGym(false);

        if ($competiteurs) {
            $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

            $callback = function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
                return $innerObject instanceof \DateTime ? $innerObject->format('d-m-Y') : '';
            };

            $normalizer = new ObjectNormalizer($classMetadataFactory);
            $normalizer->setCallbacks(array('birthDate' => $callback));
            $encoder = new JsonEncoder();
            $serializer = new Serializer(array($normalizer), array($encoder));
            $data = $serializer->serialize($competiteurs, 'json', ['groups' => 'competition']);

            return $this->render('administration/plugin/plugin_home.html.twig', array(
                "comp" => $data
            ));
        }
        return $this->render('administration/plugin/plugin_home.html.twig');
    }

    public function update_state(Request $req){
        if($this->get('security.authorization_checker')->isGranted('ROLE_ADMIN')){
            $manager = $this->getDoctrine()->getManager();
            $adherentSynchonized = [];

            $ids =$req->get("ids");
            $new_ids = explode(",",substr(substr($ids, 0, -1),1));
            for ($i=0; $i < count($new_ids); $i++) {
                $adherent = $manager->getRepository(Adherent::class)->find($new_ids[$i]);
                $adherent->setIsRegisteredInGestGym(true);
                $manager->flush();
                array_push($adherentSynchonized,$adherent);
            }

            $competiteurs = $manager->getRepository(Adherent::class)->findByIsRegisteredInGestGym(false);

            $justSync= $this->getSerializeAdherents($adherentSynchonized);
            if ($competiteurs) {
                $notSync = $this->getSerializeAdherents($competiteurs);
                return new JsonResponse(['notSync' => $notSync,'justSync'=>$justSync],200);
            }
            return new JsonResponse(['notSync' => [],'justSync'=>$justSync],200);

        }else{
            return new Response ("",500);
        }
    }

    public function getSerializeAdherents($adherents){
        $classMetadataFactory = new ClassMetadataFactory(new AnnotationLoader(new AnnotationReader()));

        $callback = function ($innerObject, $outerObject, string $attributeName, string $format = null, array $context = []) {
            return $innerObject instanceof \DateTime ? $innerObject->format('d-m-Y') : '';
        };

        $normalizer = new ObjectNormalizer($classMetadataFactory);
        $normalizer->setCallbacks(array('birthDate' => $callback));
        $encoder = new JsonEncoder();
        $serializer = new Serializer(array($normalizer), array($encoder));
        return  $serializer->serialize($adherents, 'json', ['groups' => 'competition']);
    }

}
