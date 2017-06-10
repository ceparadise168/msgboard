<?php

// src/Appbundle/Entity/Message.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use \DateTime;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * @ORM\Entity
 * @ORM\Table(name="MsgBoard")
 */
class Message
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User
     * @ORM\Column(type="string", length=100)
     */
    private $userName;

    /**
     * @var Msg
     * @ORM\Column(type="text")
     */
    private $msg;

    /**
     * @var string
     * @ORM\Column(type="string")
     */

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private$publishedAt;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", nullable=true)
     */
    private$updatedAt;



    public function __construct()
    {
        $this->publishedAt = new \DateTime('now', new \DateTimeZone('Asia/Taipei'));
        $this->updatedAt = new \DateTime('now', new \DateTimeZone('Asia/Taipei'));
    }

    /**
     * Get Update Time
     */
    public function getUpdatedAt()
    {

        //解決更新問題 避免全部同時更新 使用this 時間格式只能回傳字串
        //       $this->updatedAt = new \DateTime('now', new \DateTimeZone('Asia/Taipei'));
        //       return $this->format('Y-m-d H:i:s');
        //        $this->updatedAt == null? '0000-00-00 00:00:00' : updatedAt;~
        //   return $this->publishedAt->format('Y-m-d H:i:s');
        if($this->updatedAt == null){return '0000-00-00 00:00:00';}
        else{
            return $this->updatedAt->format('Y-m-d H:i:s');
        }
    }

    /**
     * Set Update Time
     */
    public function setUpdatedAt(\DateTime $updatedAt )
    {
        $this->updatedAt = $updatedAt;
    }


    /**
     * Get Published Time
     */
    public function getPublishedAt()
    {
        return $this->publishedAt->format('Y-m-d H:i:s');
    }

    /**
     * Set Publish Time
     */
    public function setPublishedAt(\DateTime $publishedAt)
    {
        $this->publishedAt = $publishedAt;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set userName
     *
     * @param string $userName
     *
     * @return Message
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set msg
     *
     * @param string $msg
     *
     * @return Message
     */
    public function setMsg($msg)
    {
        $this->msg = $msg;

        return $this;
    }

    /**
     * Get msg
     *
     * @return string
     */
    public function getMsg()
    {
        return $this->msg;
    }

    /**
     *Set url
     *
     * @param string $url
     *
     * @return Message
     */
    public function setUrl($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     *Get url
     *
     * @return string
     */
/*
    public function getUrl()
    {
        return $this->url;
    }
*/

    /**
     * Our new getAllPosts() method
     *
     * 1. Create & pass query to paginate method
     * 2. Paginate will return a `\Doctrine\ORM\Tools\Pagination\Paginator` object
     * 3. Return that object to the controller
     *
     * @param integer $currentPage The current page (passed from controller)
     *
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
/*
    public function getAllMessages($currentPage = 1)
    {
        $query = $this->createQueryBuilder('Message')
            ->orderBy('Message.updatedAt', 'DESC')
            ->getQuery();

        // $query->getResult();
        $paginator = $this->paginate($query, $currentPage);

        return $paginator;
    }
*/
    /**
     * Paginator Helper
     *
     * Pass through aquery object, current page & limit
     * the offest is calculated from the page and limit
     * return an 'Paginator' instance, which you can call the following on:
     *
     *     $paginator->getIterator()->count() # Total fetched (ie: `5` posts)
     *     $paginator->count() # Count of ALL posts (ie: `20` posts)
     *     $paginator->getIterator() # ArrayIterator
     *
     * @param Doctrine\ORM\Query $dql   DQL Query Object
     * @param integer            $page  Current page (defaults to 1)
     * @param integer            $limit The total number per page (defaults to 5)
     *
     * @return \Doctrine\ORM\Tools\Pagination\Paginator
     */
/*
    public function paginate($dql, $page = 1, $limit = 5)
    {
        $paginator = new Paginator($dql);

        $paginator->getQuery()
            //從第幾筆開始 預設是一 就從第零筆開始 再找五筆 
            ->setFirstResult($limit * ($page - 1))
            ->setMaxResult($limit);

        return $paginator;
    }
*/



    /**
     * Get Slug
     */
    /*
       public function getSlug()
       {
       return $this->slug;
       }
     */
    /**
     * Set Slug
     * @param string $slug
     */
    /*
       public function setSlug($slug)
       {
       $this->slug = $slug;
       }
     */
}
