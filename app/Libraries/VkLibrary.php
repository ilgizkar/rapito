<?php

namespace App\Libraries;

use App\User;

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
    }

    public static function auth(Array $scopes = null)
    {
        $scopes = ['friends', 'groups','wall', 'photos', 'video', 'status'];
        if($scopes) {
            $scopes = http_build_query(array_merge($scopes, $scopes));
        }
        $redirect = config('services.vkontakte.redirect');
        $v = config('services.vkontakte.v');
        $client_id = config('services.vkontakte.client_id');
        $url = 'https://oauth.vk.com/authorize?client_id='.$client_id.'&display=page&redirect_uri='.$redirect.'&scope='.$scopes.'&response_type=token&v='.$v;
        file_get_contents($url);
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
