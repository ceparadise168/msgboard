<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Form\PostType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use AppBundle\Entity\Message;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class DefaultController extends Controller
{
    /**
     *get page then redirect list to list/page
     * @Route("p/{page}", name="apiage")
     * @Method("GET")
     */
    public function pageActionAPI($page)
    {
        $returnArray = ['page'=>$page ];
        return $this->redirectToRoute('apiList', $returnArray);
    }

    /**
     * get message list and setting page
     * index
     * @Route("/apii/list/{page}", name="apiList", requirements={"page": "\d+"})
     */
    public function indexActionAPiI($page =2)
    {
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository('AppBundle:Message');

        $thisPage = $page;
        $limit = 5;
        $offset = $limit * ($page-1);
        $maxPages = ceil(count($messages->findAll()) / $limit);

        $qb = $messages->createQueryBuilder('m')
            ->orderBy('m.updatedAt', 'DESC')
            ->setFirstResult($limit * ($page -1))
            ->setMaxResults($limit);
        $query = $qb->getQuery();
        $result = $query->getResult();
        $messages = $result;
        $renderArray = [
            'messages' => $messages,
            'maxPages' => $maxPages,
            'thisPage' => $thisPage
                ];

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($renderArray,'json');

        $response = new Response();
        $response->setContent($json);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        if (isset($messages)) {
            return $response;
        } else {
            return new Response('QwQ something worng with messages');
        }
    }

    /**
     * Add New Message
     * @Route("api/add", name="apiAdd")
     * @Method("POST")
     */
    public function createActionAPI(Request $request)
    {
        $content = $request->getContent();
        $data = json_decode($content, true);
        $u = $data['userName'];
        $m = $data['msg'];
        $publishAt = new \DateTime('now', new \DateTimeZone('Asia/Taipei'));

        $message = new Message();
        $message->setUserName($u);
        $message->setMsg($m);
        $message->setPublishedAt($publishAt);

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($message, 'json');

        $response = new Response();
        $response->setContent($json);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        $em = $this->getDoctrine()->getManager();
        $em->persist($message);
        $em->flush();

        $redirectArray = ['id' => $message->getId()];
        return $response;
    }

    /**
     * Update the Message
     * @Route("api/edit/{id}", name="api/edit")
     * @Method("POST")
     */
    public function updateActionAPI(Request $request, $id)
    {

        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository('AppBundle:Message');
        $message = $messages->find($id);
        $temp  = $message;
        $content = $request->getContent();

        $paramMsg = $request->request->get('msg');
        // dump($paramMsg);
        $paramName = $request->request->get('userName');
        // dump($paramName);

        // $data = json_decode($content, true);
        // $u = $data['userName'];
        // $m = $data['msg'];
        // $message->setUserName($u);
        // $message->setMsg($m);

        $message->setUserName($paramName);
        $message->setMsg($paramMsg);
        $message->setupdatedAt(new \DateTime('now', new \DateTimeZone('Asia/Taipei')));

        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($message, 'json');
        $dejson = json_decode($json, true);
        $json = $serializer->serialize($dejson, 'json');

        $response = new Response();
        $response->setContent($json);
        $response->headers->set('Content-Type', 'application/json');
        $response->headers->set('Access-Control-Allow-Origin', '*');

        $em->flush();

        return $response;
    }

    /**
     * Delete the Message
     * @Route("api/delete/{id}", name="deleteAPI")
     */
    public function deleteActionAPI(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository('AppBundle:Message');
        // $message = $messages->find($id);
        if ($message = $messages->find($id)){
            $encoders = array(new XmlEncoder(), new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());
            $serializer = new Serializer($normalizers, $encoders);
            $json = $serializer->serialize($message, 'json');

            $em->remove($message);
            $em->flush();

            return JsonResponse::create(json_decode($json, true), 200);
            //        return $this->redirectToRoute('list');
        } else {
            return JsonResponse::create("ID NOT FOUND", 404 );
        }
    }
}  $request = $this->container->get('request');
