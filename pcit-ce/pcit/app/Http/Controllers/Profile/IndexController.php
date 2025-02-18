<?php

declare(strict_types=1);

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Users\JWTController;
use App\User;
use PCIT\Framework\Attributes\Route;

/**
 * 个人中心.
 */
class IndexController
{
    /**
     * @var string
     */
    protected $git_type;

    /**
     * 个人中心 profile.
     *
     * @param mixed ...$args
     *
     * @throws \Exception
     */
    #[Route('get', 'profile/{git_type}/{username}')]
    public function __invoke(string $git_type, string $username): void
    {
        $username_from_web = $username;
        $access_token = \Session::get($git_type.'.access_token');
        $username = \Session::get($git_type.'.username');
        $uid = \Session::get($git_type.'.uid');
        $pic = \Session::get($git_type.'.pic');
        $email = \Session::get($git_type.'.email');

        if (null === $username or null === $access_token) {
            \Response::redirect(config('app.host').'/login');

            exit;
        }

        if ($username_from_web !== $username) {
            \Response::redirect('/profile/'.$git_type.'/'.$username);

            exit;
        }

        $result = JWTController::generate($git_type, $username, (int) $uid);
        $pcit_api_token = $result['token'];

        setcookie(
            $git_type.'_api_token',
            $pcit_api_token,
            time() + 24 * 60 * 60,
            '/',
            config('session.domain')
        );

        User::updateUserInfo((int) $uid, null, (string) $username, (string) $email, (string) $pic, false, $git_type);
        User::updateAccessToken((int) $uid, $access_token, $git_type);

        view('profile/index.html');
    }
}
