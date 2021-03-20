<?php

namespace App\Libraries;

use App\User;
use Illuminate\Http\RedirectResponse;

class VkLibrary
{
    protected $vk;
    protected $method_name;
    protected $access_token;
    protected $parametrs;
    protected $v;

    /**
     * {@inheritdoc}
     */
    protected $scopes = ['friends', 'groups','wall', 'photos', 'video', 'status'];

    public function create(User $user)
    {
        $this->v = config('services.vkontakte.v');
        $this->access_token = $user->token;
        $url = 'https://api.vk.com/method/users.get?user_ids=210700286&fields=bdate&access_token=533bacf01e11f55b536a565b57531ac114461ae8736d6506a3&v=5.107';
        return redirect()->away($url);
    }

    public static function auth(Array $scopes = null)
    {
        $scopes = ['friends', 'groups', 'wall', 'photos', 'video', 'status'];

        if ($scopes) {
            $scopes = http_build_query(array_merge($scopes, $scopes));
        }
        $redirect = config('services.vkontakte.redirect');
        $v = config('services.vkontakte.v');
        $client_id = config('services.vkontakte.client_id');
        $url = 'https://oauth.vk.com/authorize?client_id=' . $client_id . '&display=page&redirect_uri=' . $redirect . '&scope=' . $scopes . '&response_type=token&v=' . $v;
        $url = 'https://oauth.vk.com/authorize?client_id=7491900&display=page&redirect_uri=http://ilgizkar.ru/vkAuthToken&scope=0=friends&1=groups&2=wall&3=photos&4=video&5=status&6=friends&7=groups&8=wall&9=photos&10=video&11=status&response_type=token&v=5.107';
//        $result = file_get_contents($url);
        return new RedirectResponse($url);
    }

    public static function getUserMethod($method_name, $params, $user, $type = null)
    {
        $v = config('services.vkontakte.v');
        $request_params = array(
            'v' => $v,
            'access_token' => $user->token
        );
        $get_params = http_build_query(array_merge($request_params, $params));
        if($type == 'json') {
            $result = file_get_contents('https://api.vk.com/method/'.$method_name.'?'. $get_params);
            return $result;
        } else {
            $result = json_decode(file_get_contents('https://api.vk.com/method/'.$method_name.'?'. $get_params));
            return $result;
        }
    }

}
