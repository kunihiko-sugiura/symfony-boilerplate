<?php
namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 *
 * @Route("/admin")
 * Class AdminIndexController
 * @package AppBundle\Controller
 */
class AdminIndexController extends AdminController
{
    /**
     * 以下が注釈によるメソッドレベルでのルート定義
     * @Route("/", name="admin_index", defaults={"param1" = 1 })
     */
    public function indexAction(Request $request)
    {
        return $response =  new Response('OK');
    }
}
