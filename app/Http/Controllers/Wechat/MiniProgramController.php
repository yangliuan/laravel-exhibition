<?php
/**
 * 本案例使用EasyWechat5.0
 *
 * DOC:https://www.easywechat.com/5.x/
 */
namespace App\Http\Controllers\Wechat;

use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use EasyWeChat\Factory;
use App\Models\User;
use Illuminate\Support\Facades\Storage;

class MiniProgramController extends Controller
{
    /**
     * 小程序登录并获取用户信息
     *
     * @param Request $request
     * @return json
     */
    public function login(Request $request)
    {
        $request->validate([
            'code' => 'bail|required|string',
            'encryptedData' => 'bail|required|string',
            'iv' => 'bail|required|string',
        ]);

        $config = config('wechat.mini_program.default');
        $app = Factory::miniProgram($config);
        //auth.code2Session接口
        //DOC:https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/login/auth.code2Session.html
        //DOC:https://www.easywechat.com/5.x/mini-program/auth.html#%E6%A0%B9%E6%8D%AE-jscode-%E8%8E%B7%E5%8F%96%E7%94%A8%E6%88%B7-session-%E4%BF%A1%E6%81%AF
        $res_session = $app->auth->session($request->code);

        if (isset($res_session['errcode']) && $res_session['errcode']) {
            throw ValidationException::withMessages(['code' => $res_session['errcode'] . $res_session['errmsg']]);
        }

        //解密信息
        //DOC:https://developers.weixin.qq.com/miniprogram/dev/framework/open-ability/signature.html#%E5%8A%A0%E5%AF%86%E6%95%B0%E6%8D%AE%E8%A7%A3%E5%AF%86%E7%AE%97%E6%B3%95
        //DOC:https://www.easywechat.com/5.x/mini-program/decrypt.html
        try {
            $decryptedData = $app->encryptor->decryptData($res_session['session_key'], $request->iv, $request->encryptedData);
        } catch (\Exception $e) {
            throw ValidationException::withMessages(['encryptedData' => $e->getMessage()]);
        }

        //场景一:微信体系绑定同一个用户的微信号，使用unionid作为唯一标识
        //DOC:https://developers.weixin.qq.com/miniprogram/dev/framework/open-ability/union-id.html
        //存在使用code获取的unionid 则使用,不存在使用解密获得的unionid
        $unionid = isset($res_session['unionid']) && $res_session['unionid'] ? $res_session['unionid']:$decryptedData['unionId'];

        //场景二：使用openid作为微信用户唯一标识
        $res_session['openid'];

        //用户信息
        $user_info = [
            'name' => $decryptedData['nickName'],
            'avatar' => $decryptedData['avatarUrl'],
            'sex' => $decryptedData['gender'],
            'province' => $decryptedData['province'],
            'city' => $decryptedData['city'],
            'area' => $decryptedData['country'],
        ];
    }

    /**
     * 小程序获取微信手机号
     *
     * @param Request $request
     * @return json
     */
    public function bindMobile(Request $request)
    {
        $request->validate([
            'code' => 'bail|required|string',
            'encryptedData' => 'bail|required|string',
            'iv' => 'bail|required|string',
        ]);

        $config = config('wechat.mini_program.default');
        $app = Factory::miniProgram($config);
        //获取最新的session_key
        $res_session = $app->auth->session($request->code);

        if (isset($res_session['errcode']) && $res_session['errcode']) {
            throw ValidationException::withMessages(['code' => $res_session['errcode'] . $res_session['errmsg']]);
        }

        //解密用户手机号
        try {
            //因为session_key会过期，是由微信服务器控制的
            //所以每次通过最新code，获取最新的session_key解密，这样可以避免session_key失效导致解密失败，服务端不用存储session_key
            $decryptedData = $app->encryptor->decryptData($res_session['session_key'], $request->iv, $request->encryptedData);
        } catch (\Throwable $th) {
            throw ValidationException::withMessages(['encryptedData' => $th->getMessage()]);
        }

        //获取微信用户绑定的手机号，需先调用wx.login接口
        //DOC:https://developers.weixin.qq.com/miniprogram/dev/framework/open-ability/getPhoneNumber.html
        if (isset($decryptedData['purePhoneNumber']) && $decryptedData['purePhoneNumber']) {
            //场景一：用户已经登录，绑定手机号
            $request->user('api')->update([
                'mobile'=>$decryptedData['purePhoneNumber']
            ]);
            //场景二：使用手机号作为唯一标识,注册或登陆
            User::firstOrCreate(['mobile'=>$decryptedData['purePhoneNumber']]);
        }

        return response()->json(['mobile'=>$decryptedData['purePhoneNumber']]);
    }

    /**
     * 登出
     *
     * @param Request $request
     * @return json
     */
    public function logout(Request $request)
    {
        $request->user('api')->tokens()->where('name', 'api')->delete();

        return response()->json();
    }

    /**
     * 获取小程序码
     * DOC:https://developers.weixin.qq.com/miniprogram/dev/framework/open-ability/qr-code.html
     *
     * @param Request $request
     * @return void
     */
    public function qrCode(Request $request)
    {
        //接口A wxacode.get：永久有效小程序码，有数量限制，适用于需要的码数量较少的业务场景
        //DOC:https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/qr-code/wxacode.get.html
        //DOC:https://www.easywechat.com/5.x/mini-program/app_code.html#%E6%8E%A5%E5%8F%A3a-%E9%80%82%E7%94%A8%E4%BA%8E%E9%9C%80%E8%A6%81%E7%9A%84%E7%A0%81%E6%95%B0%E9%87%8F%E8%BE%83%E5%B0%91%E7%9A%84%E4%B8%9A%E5%8A%A1%E5%9C%BA%E6%99%AF
        $config = config('wechat.mini_program.default');
        $app = Factory::miniProgram($config);
        //可接受 path 参数较长,扫码进入的小程序页面路径128字符长度，具体看文档
        $path = '?a=1';
        $response = $app->app_code->get($path, [
            'width' => 400,
            'auto_color' => false,
            'line_color' => [
                'r' => 0,
                'g' => 77,
                'b' => 159,
            ],
            'is_hyaline' => true
        ]);

        if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
            //文件名
            $file_name = 'mini-qrcode-a.png';
            //保存路径
            $save_path = storage_path('app/public/minicode');
            //保存图片到本地
            $file_name = $response->saveAs($save_path, $file_name);
            //获取图片url
            $url_a = Storage::disk('public')->url('minicode/' . $file_name);
        } else {
            //response为错误信息，数组格式
            dd($response);
        }

        $img_a = '<img src="'. ($url_a ?? '') . '" />';

        //接口B wxacode.getUnlimited :永久有效，数量暂无限制,适用于需要的码数量极多的业务场景，可接受 path 参数较长
        //DOC:https://developers.weixin.qq.com/miniprogram/dev/api-backend/open-api/qr-code/wxacode.getUnlimited.html

        $page = 'pages/index/index';
        $scene = 'a=1';
        $response = $app->app_code->getUnlimit('scene-value', [
            'page'  => $page,  //小程序页面
            'scene' => $scene, //场景值看文档
            'width' => 300,
            'check_path' => true, //检查页面是否存在
            'env_version' => 'release', //版本值看文档
            'line_color' => [ //线条颜色
                'r' => 0,
                'g' => 77,
                'b' => 159,
            ],
            'is_hyaline' => true //背景透明
        ]);

        if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
            //文件名
            $file_name = 'mini-qrcode-b.png';
            //保存路径
            $save_path = storage_path('app/public/minicode');
            //保存图片到本地
            $file_name = $response->saveAs($save_path, $file_name);
            //获取图片url
            $url_b = Storage::disk('public')->url('minicode/' . $file_name);
        } else {
            dd($response);
        }

        $img_b = '<img src="'. ($url_b ?? '') . '" />';

        //接口C wxacode.createQRCode : 永久有效，有数量限制,无法设置样式
        $page = 'pages/index/index';
        $width = 200;
        $response = $app->app_code->getQrCode($page, $width);

        if ($response instanceof \EasyWeChat\Kernel\Http\StreamResponse) {
            //文件名
            $file_name = 'mini-qrcode-c.png';
            //保存路径
            $save_path = storage_path('app/public/minicode');
            //保存图片到本地
            $file_name = $response->saveAs($save_path, $file_name);
            //获取图片url
            $url_c = Storage::disk('public')->url('minicode/' . $file_name);
        } else {
            dd($response);
        }

        $img_c = '<img src="'. ($url_c ?? '') . '" />';

        return $img_a.$img_b.$img_c;
    }
}
