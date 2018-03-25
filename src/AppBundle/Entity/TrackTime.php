<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/23/18
 * Time: 9:16 AM
 */

namespace AppBundle\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class TrackTime
{
    /**
     * @Assert\Date()
     */
    protected $date;

    /**
     * @Assert\Type(type="integer")
     */
    protected $hours;

    /**
     * @Assert\NotBlank()
     */
    protected $comment;

    /**
     * @Assert\NotBlank()
     */
    protected $activity;

    protected $overtime;
    protected $overtime_int;

    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    public function setHours($hours)
    {
        $this->hours = $hours;
    }

    /**
     * @return mixed
     */
    public function getHours()
    {
        return $this->hours;
    }

    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * @return mixed
     */
    public function getComment()
    {
        return $this->comment;
    }

    public function setActivity($activity)
    {
        $this->activity = $activity;
    }

    /**
     * @return mixed
     */
    public function getActivity()
    {
        return $this->activity;
    }

    public function setOvertime($overtime)
    {
        $this->overtime = $overtime;
    }

    /**
     * @return mixed
     */
    public function getOvertime()
    {
        return $this->overtime;
    }

    /**
     * @return integer
     */
    public function getOvertimeInt()
    {
        if ($this->overtime) $this->overtime_int = 1;
        else $this->overtime_int = 0;

        return $this->overtime_int;
    }
}