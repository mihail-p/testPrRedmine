<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 3/28/18
 * Time: 6:09 PM
 */

namespace AppBundle\Service;

use Redmine\Client;

class ConnectAPI
{
    protected $url;
    protected $user;
    protected $pass;

    public function __construct($url, $user, $pass)
    {
        $this->url = $url;
        $this->user = $user;
        $this->pass = $pass;
    }

    public function connect()
    {
        return $client = new Client($this->url, $this->user, $this->pass);
    }
}