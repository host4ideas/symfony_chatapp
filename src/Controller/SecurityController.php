<?php
namespace Controller;

use Utils\UserOnline;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Doctrine\ORM\EntityManagerInterface;

class SecurityController
{
    /**
     * @Route("/add/", name="add_online")
     *
     * Adds info about user to users online in database.
     */
    public function addOnlineUserAction(UserOnline $userOnline)
    {
        $this->get('session')->set('channel', 1);
        $userOnline->addUserOnline($this->getUser(), 1);

        return $this->redirectToRoute('chat_index');
    }
}