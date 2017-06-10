<?php

namespace Tests\AppBundle\Controller;
namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\Message;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;


class DefaultControllerTest extends WebTestCase
{

    /**
     * @group ll
     */
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/api/list');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $content = $client->getResponse()->getcontent();

        $data = json_decode($content,true);
        //   dump($data,count($data));

        $this->assertCount(5, $data["messages"]);
        $this->assertNotCount(0, $data);

        /*
           $this->assertContains('messages', $crawler->filter('body')->text());

           $this->assertCount(
           5,
           $crawler->filter('body > table > tbody > tr')
           );
         */
    }

    /**
     * @group aa
     */
    public function testCreateAction()
    {
        $client = static::createClient();

        $postData = [
            'userName' => 'testPHPUnit'. mt_rand(),
            'msg' => 'test'
                ];

        $crawler = $client->request(
                'POST',
                '/api/add',
                array(),
                array(),
                array('CONTENT_TYPE' => 'application/json'),
                json_encode($postData)
                );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $content = $client->getResponse()->getcontent();

        $responseCheck = json_decode($content, true);

        dump(gettype($responseCheck), $responseCheck["userName"], $responseCheck, $content);

        $this->assertContains($postData['userName'], (string)$content);
        $this->assertEquals($postData['userName'], $responseCheck["userName"]);

        //        $client->followRedirects();
        /*
           $crawler = $client->request('GET', '/add');
        // $postLink = $crawler->filter('body > ul > li > a')->link();
        $content = $crawler->getResponse()->getContent();
         */
    }

    /**
     * @group dd
     */
    public function testDeleteAction()
    {
        $id = 175;
        $path = "api/delete/" . $id;

        $client = static::createClient();
        $crawler = $client->request(
                'GET',
                $path
                );
        $content = $client->getResponse()->getcontent();
        $responseCheck = json_decode($content, true);
        dump($responseCheck,$path);
        if ($responseCheck != "ID NOT FOUND"){
           $this->assertEquals($id, $responseCheck["id"]);
        } else {
        $this->assertEquals(1,1);
           dump("NOT FOUND");
        }
    }

    /**
     * @group ee
     */
    public function testEditAction()
    {
        $client = static::createClient();

        $postData = [
            'userName' => 'testEdit'. mt_rand(),
            'msg' => 'testEdit'
                ];
        //  $id = 167;
        //  $path = "api/edit/" . $id;
        /*
           $encoders = array(new XmlEncoder(), new JsonEncoder());
           $normalizers = array(new ObjectNormalizer());
           $serializer = new Serializer($normalizers, $encoders);
           $json = $serializer->serialize($postData, 'json');
           $dejson = json_decode($json, true);
           $json = $serializer->serialize($dejson, 'json');
         */
        $json = json_encode($postData);
        $crawler = $client->request(
                'POST',
                'api/edit/111',
                $postData,
                array(),
                array('CONTENT_TYPE' => 'application/json'));
  //              $json
  //              );
         $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $content = $client->getResponse()->getcontent();
        // $responseCheck = json_decode($content, true);
        dump($content);
        $this->assertContains($postData['userName'], (string)$content);
    }
}
