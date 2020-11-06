<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Adldap\AdldapInterface;

/**
 * @OA\Info(title="API Usuarios LDAP", version="1.0")
 *
 * @OA\Server(url="http://localhost")
 */

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
     * @OA\Post(
     * path="/api/usuario",
     * summary="Ingreso al LDAP",
     * description="Login con usuario y contraseña de Windows.",
     * @OA\RequestBody(
     *    required=true,
     *    description="Credenciales de Usuario y Contraseña.",
     *    @OA\JsonContent(
     *       required={"username","password"},
     *       @OA\Property(property="username", type="string", example="jrodriguez"),
     *       @OA\Property(property="password", type="string", format="password", example="PassWord12345"),
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Error de Credenciales",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong email address or password. Please try again")
     *        )
     *     ),
     * @OA\Response(
     *    response=200,
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="user", type="object", ref="#/components/schemas/User"),
     *    )
     *  ),
     * )
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

        return response()->json($result);
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
     * @OA\Get(
     *     path="/api/usuario/{username}",
     *     summary="Devuelve los datos básicos",
     *     @OA\Parameter(
     *         name="username",
     *         in="query",
     *         @OA\Schema(
     *           type="string"
     *         ),
     *         description="Entrada de datos",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Retorna el email, nombre completo y nickname del usuario."
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Array vacio con datos no encontrados."
     *     )
     * )
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
