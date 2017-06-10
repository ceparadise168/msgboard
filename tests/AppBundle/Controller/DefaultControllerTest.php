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
     * It tests indexActionAPI by creating a client
     * then request to the controller and check following assertions with the response.
     * 1.assert statusCode is 200.
     * 2.assert there are 5 messages in the response json.
     * 3.assert that messages ,the maxpage and the thispage parms are in the response.
     */
    public function testIndexActionAPI()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/list');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $content = $client->getResponse()->getcontent();
        $data = json_decode($content,true);
        $this->assertCount(5, $data["messages"]);
        $this->assertCount(3, $data);
    }

    /**
     * It tests createActionAPI by creating a client
     * then request to the controller and check following assertions with the response.
     * 1.assert the statusCode is 200.
     * 2.assert the userName in the request parameter including a random number is equal to the number in the response.
     */
    public function testCreateActionAPI()
    {
        $client = static::createClient();

        $postData = [
            'userName' => 'testPHPUnit'. mt_rand(),
            'msg' => 'testAdd'
        ];
        $paramArray = [];
        $uploadFileArray = [];
        $contentTypeArray = ['CONTENT_TYPE' => 'application/json'];

        $crawler = $client->request(
                'POST',
                '/api/add',
                $paramArray,
                $uploadFileArray,
                $contentTypeArray,
                json_encode($postData)
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $content = $client->getResponse()->getcontent();
        $responseCheck = json_decode($content, true);

        $this->assertEquals($postData['userName'], $responseCheck["userName"]);
    }

    /**
     * It tests deleteAcitonAPI by creating a client
     * then request to the controller and check following assertions with the response.
     * 1.assert the id requested by the client was not found in the controller when get a response "ID NOT FOUND".
     * 2.assert the id requested by the client is equal to to the id which has been deleted in the controller.
     */
    public function testDeleteActionAPIT()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/api/list');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $content = $client->getResponse()->getcontent();
        $data = json_decode($content,true);
        $this->assertCount(5, $data["messages"]);
        $this->assertCount(3, $data);
        $id = $data["messages"][0]["id"];
        dump($id);
//        $id = 334;
        $path = "api/delete/" . $id;

        $client = static::createClient();
        $crawler = $client->request(
                'GET',
                $path
        );
        $content = $client->getResponse()->getcontent();
        $responseCheck = json_decode($content, true);

        if ($responseCheck == "ID NOT FOUND") {
            $this->assertNotEquals($id, $responseCheck);
        } else {
            $this->assertEquals($id, $responseCheck["id"]);
        }
    }

    /**
     * It tests deleteAcitonAPI by creating a client
     * then request to the controller and check following assertions with the response.
     * 1.assert the id requested by the client was not found in the controller when get a response "ID NOT FOUND".
     * 2.assert the id requested by the client is equal to to the id which has been deleted in the controller.
     */
    public function testDeleteActionAPIF()
    {
        $id = 338;
        $path = "api/delete/" . $id;

        $client = static::createClient();
        $crawler = $client->request(
                'GET',
                $path
        );
        $content = $client->getResponse()->getcontent();
        $responseCheck = json_decode($content, true);

        if ($responseCheck == "ID NOT FOUND") {
            $this->assertNotEquals($id, $responseCheck);
        } else {
            $this->assertEquals($id, $responseCheck["id"]);
        }
    }
    /**
     * It tests editActionAPI by creating a client
     * then request to the controller and check following assertions with the response.
     * 1.assert the statusCode is 200.
     * 2.assert the id requested by the client was not found in the conreollwe when get a response "GG".
     * 3.assert the userName in the request parameter including a random number is equal to the number in the response.
     */
    public function testEditActionAPIT()
    {
        $postData = [
            'userName' => 'testEdit'. mt_rand(),
            'msg' => 'testEdit'
        ];
        $json = json_encode($postData);
        $id = 167;
        $path = 'api/edit/' .  $id;
        $uploadFileArray = [];
        $contentTypeArray = ['CONTENT_TYPE' => 'application/json'];

        $client = static::createClient();
        $crawler = $client->request(
                'PUT',
                $path,
                $postData,
                $uploadFileArray,
                $contentTypeArray
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $content = $client->getResponse()->getcontent();

        if ($content == "GG") {
            $this->assertNotEquals($id, (string)$content);
        } else {
            $this->assertContains($postData['userName'], (string)$content);
        }
    }

    /**
     * It tests editActionAPI by creating a client
     * then request to the controller and check following assertions with the response.
     * 1.assert the statusCode is 200.
     * 2.assert the id requested by the client was not found in the conreollwe when get a response "GG".
     * 3.assert the userName in the request parameter including a random number is equal to the number in the response.
     */
    public function testEditActionAPIF()
    {
        $postData = [
            'userName' => 'testEdit'. mt_rand(),
            'msg' => 'testEdit'
        ];
        $json = json_encode($postData);
        $id = 111;
        $path = 'api/edit/' .  $id;
        $uploadFileArray = [];
        $contentTypeArray = ['CONTENT_TYPE' => 'application/json'];

        $client = static::createClient();
        $crawler = $client->request(
                'PUT',
                $path,
                $postData,
                $uploadFileArray,
                $contentTypeArray
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $content = $client->getResponse()->getcontent();

        if ($content == "GG") {
            $this->assertNotEquals($id, (string)$content);
        } else {
            $this->assertContains($postData['userName'], (string)$content);
        }
    }
}
