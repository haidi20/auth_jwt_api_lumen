<?php
namespace App\Http\Middleware;

use App\Models\Participant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{
    /**
     * The authentication guard factory instance.
     *
     * @var \Illuminate\Contracts\Auth\Factory
     */
    protected $auth;
    protected $request;

    /**
     * Create a new middleware instance.
     *
     * @param  \Illuminate\Contracts\Auth\Factory  $auth
     * @return void
     */
    public function __construct(Auth $auth, Request $request)
    {
        $this->auth = $auth;
        $this->request = $request;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // if ($this->auth->guard($guard)->guest()) {
            if ($request->bearerToken()) {
                $token = $request->bearerToken();
                $check_token = Participant::where('token', $token)->count();
                if ($check_token <= 0) {
                    $res['success'] = false;
                    $res['message'] = 'Permission not allowed!';

                    return response($res);
                }
            }else{
                $res['success'] = false;
                $res['message'] = 'Login please!';

                return response($res);
            }
        // }

        return $next($request);
    }
}
