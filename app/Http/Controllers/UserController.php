<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Adldap\AdldapInterface;

class UserController extends Controller
{
    /**
     * @var Adldap
     */
    protected $ldap;

    /**
     * Constructor.
     *
     * @param AdldapInterface $adldap
     */
    public function __construct(AdldapInterface $ldap)
    {
        $this->ldap = $ldap;
    }

    /**
     * Displays the all LDAP users.
     *
     * @return json
     */
    public function index()
    {
        $users = $this->ldap->search()->where('objectclass', '=', 'person')->sortBy('cn', 'asc')->get();

        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //TODO
    }

    /**
     * Displays the specified LDAP user.
     *
     * @return \Illuminate\View\View
     */
    public function checkValidateUser(Request $user)
    {

        $username = $user->username;
        $password = $user->password;

        $user_format = env('LDAP_USER_FORMAT');
        $userdn = sprintf($user_format, $username);
        $result = [];

        /**
         * Check is user auth
         */
        if ($this->ldap->auth()->attempt($userdn, $password, $bindAsUser = true)) {
            $result = $this->getInfo($username);
        }

        return $result;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //TODO
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //TODO
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //TODO
    }

    /**
     * Return user info
     *
     * @param Request $user name user
     * @return array
     */
    public function getUser(Request $user)
    {
        return $this->getInfo($user->username);
    }

    private function getInfo($username)
    {
        $result = [];
        $ldapuser = $this->ldap->search()
            ->where(env('LDAP_USER_BIND_ATTRIBUTE'), '=', $username)
            ->first();

        if ($ldapuser) {
            $result = [
                'email'     => str_replace('cemic1', 'cemic', $ldapuser->mail[0]),
                'nombre'   => $ldapuser->cn[0],
                'usuario'   => $ldapuser->samaccountname[0]
            ];
        }

        return response()->json($result);
    }

    public function checkCamillero(Request $user)
    {

        $username = $user->username;
        $password = $user->password;

        $user_format = env('LDAP_USER_FORMAT');
        $userdn = sprintf($user_format, $username);
        $result = [];

        /**
         * Check is user auth
         */
        if ($this->ldap->auth()->attempt($userdn, $password, $bindAsUser = true)) {
            $result = $this->getInfo($username);
        }

        return response()->json($result);
    }
}
