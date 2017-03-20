<?php
namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
/**
 * ログイン関連のController
 * Class SecurityController
 * @package AppBundle\Controller
 *
 * 参考URL
 * http://fusyomono1.blogspot.jp/2011/11/symfony2_04.html
 * https://symfony.com/doc/current/security/form_login_setup.html
 * ** CSRF
 * https://symfony.com/doc/current/security/csrf_in_login_form.html
 */
class SecurityController extends BaseController
{
    /**
     * @Route("/login", name="security_login")
     * ** twigでurl生成するためにpath('name')指定するために名前をつけましょう
     */
    public function loginAction()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        // ** ユーザ名と認証エラーを取得
        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();

        return $this->render(
            'AppBundle:Security:login.html.twig',
            [
                'last_username' => $lastUsername,
                'error'         => $error,
            ]
        );
    }

    /**
     * @Route("/logout", name="security_logout")
     */
    public function logoutAction()
    {
        throw new \Exception('This should never be reached!');
    }
}
