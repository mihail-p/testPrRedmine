<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/23/18
 * Time: 9:16 AM
 */

namespace AppBundle\Entity;


class CommentForm
{
    protected $user;
    protected $comment;
    protected $date;

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    public function setUser($name)
    {
        $this->user = $name;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }
}