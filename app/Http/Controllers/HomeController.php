<?php

namespace App\Http\Controllers;

use App\Libraries\VkLibrary;
use App\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('dashboard');
    }

    public function vkAuth()
    {
        return Socialite::with('vkontakte')->scopes(['friends', 'groups','wall', 'photos', 'video', 'status'])->redirect();
    }

    public function vkAuthToken()
    {
        $code = Socialite::with('vkontakte')->user();
        $data['vk_user_id'] = $code->id;
        $data['token'] = $code->token;
        $data['nickname'] = $code->nickname;
        $data['avatar'] = $code->avatar;
        $data['expiresIn'] = time() + $code->expiresIn;
        $data['first_name'] = $code->user['first_name'];
        $data['last_name'] = $code->user['last_name'];

        auth()->user()->update($data);

        return redirect('/dashboard')
            ->withCookie(cookie('expiresIn', $data['expiresIn'], 720));
    }

    public function getUser()
    {
        $user = auth()->user();

        return response($user, 200);
    }

    public function a(Request $request)
    {
        $post = '';
        $item_id = '';
        $query = VkLibrary::getUserMethod('likes.add', [
            'type' => 'photo' ,
            'item_id' => '456239017',
        ], auth()->user(), 'json');

        return response($query, 200);

//        VkLibrary::getUserMethod('users.get', ['user_id' => '454162779', 'fields' => 'bdate'], auth()->user());
//        VkLibrary::getUserMethod('groups.get', ['user_id' => '454162779', 'extended' => 1], auth()->user());
//        VkLibrary::getUserMethod('groups.search', ['q' => 'казань', 'sort' => 0, 'count' => 1000, 'offset' => 500], auth()->user());
//        VkLibrary::getUserMethod('groups.getById', ['group_id' => '25335219', 'fields' => 'activity'], auth()->user());

//        dd(VkLibrary::getUserMethod('execute', ['code' => '
//        var posts = API.groups.search({"q": "казань"});
//        var data = posts.items@.id;
//        var groups = API.groups.getById({"group_ids":data, "fields": "activity,addresses,city,contacts,members_count,wall"});
//
//        var group_ids = groups@.id;
//        var len = group_ids.length;
//        var users = [];
//        var i = 0;
//        while(len > 0) {
//            var all = API.groups.getMembers({"group_id":group_ids[i], "count": 1000});
//            var all_count = all.count;
//            var offset = 1000;
//            while (all_count > 0) {
//
//                all = API.groups.getMembers({"group_id":group_ids[i], "count": 1000, "offset": offset});
//                offset = offset + 1000;
//                all_count = all_count - 1000;
//            }
//
//            users.push(all);
//            i = i + 1;
//            len = len - 1;
//        }
//
//        return {"groups": groups, "users": users};
//        '], auth()->user()));
    }

    public function searchAllUsersInGroups($group = 'https://vk.com/rospotrebnadzor.official')
    {
//        $group = mb_substr( $group, 15);
//        $page = 0;
//        $limit = 1000;
//        $users = [];
//        do {
//            $offset = $page * $limit;
//            //Получаем список пользователей
//            $members = VkLibrary::getUserMethod('groups.getMembers', ['group_id' => $group, "count"=> $limit, "offset"=> $offset], auth()->user());
//
////            $users_string = implode(",", $members->response->items);
//            //Спим
//            sleep(1);
//            dd($members);
//            $users[] = $members->response->items; // добавляем юзера к юзерам
//            //Спим
//            sleep(1);
//
//            //Увеличиваем страницу
//            $page++;
//        } while($members->response->count > $offset + $limit );

        $group = mb_substr( $group, 15);
        $count_users = VkLibrary::getUserMethod('groups.getById', ['group_ids' =>  $group, 'fields' => 'members_count'], auth()->user())->response[0]->members_count;
        $all_res = [];
        while($count_users > 0) {
            $res = VkLibrary::getUserMethod('execute', ['code' => '
            var len = 25;
            var users = [];
            var offset = 0;
            while(len > 0) {
                var one_step_users = API.groups.getMembers({"group_id":"'.$group.'", "count": 1000, "offset": offset});
                users.push(one_step_users.items);
                offset = offset + 1000;
                len = len - 1;
            }

            return users;
            '], auth()->user());
            dump($res);
            $all_res[] = $res->response;
            $count_users -= 25000;
        }
        $all = call_user_func_array('array_merge', $all_res);
        dump($all);
        echo '111';
    }

    public function searchGroups(Request $request)
    {
        $text = $request->text;
        $city = $this->allCities($request->city);
        $sort = $request->sort;
        $type = $request->type;
        $future = $request->future;
        $market = $request->market;

        $query = VkLibrary::getUserMethod('groups.search', [
            'q' => $text,
            'sort' => $sort,
            'city_id' => $city ? $city->id : '',
            'type' => $type,
            'future' => $future,
            'market' => $market,
        ], auth()->user(), 'json');

        return response($query, 200);
    }

    public function allCities($text)
    {
        if(!empty($text)) {
            $query = VkLibrary::getUserMethod('database.getCities', [
                'q' => $text,
                'country_id' => 1
            ], auth()->user());
            return $query->response->items[0];
        } else {
            return null;
        }
    }
}
