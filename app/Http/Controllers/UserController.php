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
        $users = $this->ldap->search()->users()->get();

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
        //
    }

    /**
     * Displays the specified LDAP user.
     *
     * @return \Illuminate\View\View
     */
    public function show($username=null, $password=null)
    {
        
        $username = 'jrodriguez';
        $ldapuser = $this->ldap->search()
                    ->where(env('LDAP_USER_BIND_ATTRIBUTE'), '=', $username)
                    ->first();
                            
        return response()->json(['principal_username' => $ldapuser->getUserPrincipalName()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
