<?php

namespace AppBundle\Controller;

use AppBundle\Entity\People;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Form\PostType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class BankController extends Controller
{
    /**
     * @Route("bank/register", name = "bankRegister")
     * @Method("GET")
     */
    public function registerAction()
    {
        $people = new People();
        $people->setUsername("eric");
//        $bank->setPassword("777");
//        $bank->setMoney("100");
dump($people);
        $em = $this->getDoctrine()->getManager();
        $em->persist($people);
        $em->flush();

       $peoples = $em->getRepository('AppBundle:People');
        $people = $peoples->findAll();


        $encodersArray = [
            new XmlEncoder(),
            new JsonEncoder()
        ];
        $normalizersArray = [new objectNormalizer()];
        $encoders = $encodersArray;;
        $normalizers = $normalizersArray;
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($people, 'json');

        return new Response($json);
    }

    /**
     * @Route("bank/login", name = "bankLogin")
     * @Method("GET")
     */
    public function LoginAction()
    {
        $em = $this->getDoctrine()->getManager();
        $accounts = $em->getRepository('AppBundle:Account');
        $account = $accounts->findAll();

        $encodersArray = [
            new XmlEncoder(),
            new JsonEncoder()
        ];
        $normalizersArray = [new objectNormalizer()];
        $encoders = $encodersArray;;
        $normalizers = $normalizersArray;
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($account, 'json');

        return new Response("Login" . $json);
    }
}
