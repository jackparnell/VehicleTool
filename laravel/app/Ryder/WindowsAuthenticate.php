<?php

namespace App\Ryder;

use Adldap\Laravel\Traits\ImportsUsers;
use Adldap\Models\User;
use Closure;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class WindowsAuthenticate
{
    use ImportsUsers;

    /**
     * The authenticator implementation.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param Guard $auth
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {

        if (!$this->auth->check()) {
            // Retrieve the SSO login attribute.
            $auth = $this->getWindowsAuthAttribute();

            // Retrieve the SSO input key.
            $key = key($auth);

            // Handle Windows Authentication.
            if ($account = $request->server($auth[$key])) {
                // Usernames may be prefixed with their domain,
                // we just need their account name.
                $username = explode('@', $account);


                /* CHANGES FROM ADLDAP2 START */

                if (count($username) === 2) {
                    list($username, $domain) = $username;
                } else {
                    $username = $username[key($username)];
                }

                // Find the user in AD.
                $user = $this->newAdldapUserQuery()
                    ->whereEquals('sAMAccountName', $username)
                    ->first();

                /* CHANGES FROM ADLDAP2 END */


                if ($user instanceof User) {

                    $model = $this->getModelFromAdldap($user, str_random());

                    if ($model instanceof Model) {
                        // Double check user instance before logging them in.
                        $this->auth->login($model);
                    }
                }
            }
        }

        return $this->returnNextRequest($request, $next);
    }

    /**
     * Returns the next request.
     *
     * This method exists for override ability.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function returnNextRequest(Request $request, Closure $next)
    {
        return $next($request);
    }

    /**
     * Returns a new auth model instance.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function createModel()
    {
        $model = $this->auth->getProvider()->getModel();

        return new $model();
    }

    /**
     * Returns the windows authentication attribute.
     *
     * @return string
     */
    protected function getWindowsAuthAttribute()
    {
        return Config::get('adldap_auth.windows_auth_attribute', [$this->getSchema()->accountName() => 'AUTH_USER']);
    }
}