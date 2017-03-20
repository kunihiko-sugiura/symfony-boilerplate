<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * ** コメントに関する注意 ** 半角アットマークから始まる注釈を使用する場合は /** から始まるルールがある
 * 以下が注釈によるコントローラーレベルでのルート定義
 * @Route("/")
 * TODO:Controllerのディレクトリ階層構造の変更方法があんまりわかってない。
 *
 * Class DefaultController
 * @package AppBundle\Controller
 */
class ClientIndexController extends ClientController
{
    /**
     * 以下が注釈によるメソッドレベルでのルート定義
     * @Route("/", name="top_index", defaults={"param1" = 1 })
     */
    public function indexAction(Request $request)
    {
        $logger = $this->get('logger');

        $logger->info(realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR);


        $logger->info('!!!!!!!! I just got the logger');
        $logger->error('!!!!!!!! An error occurred');
        $logger->critical('!!!!!!!! I left the oven on!', array(
            // include extra "context" info in your logs
            'cause' => 'in_hurry',
        ));

        return $this->render(
            // app/Resources/viewsからの相対pathを指定
            'AppBundle:Index:index.html.twig',
            [
                'base_dir'  => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
                'number'    => 1111111,
            ]
        );
    }
}
