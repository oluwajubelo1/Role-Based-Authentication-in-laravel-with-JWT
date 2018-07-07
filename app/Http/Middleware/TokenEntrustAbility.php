<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\JsonResponse;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class TokenEntrustAbility extends BaseMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $roles, $permissions, $validateAll=false)
    {
        if (!$token=$this->auth->setRequest($request)->getToken()){
            return $this->respond('tymon.jwt.absent','token_not_provided',JsonResponse::HTTP_BAD_REQUEST);
        }
        try{
            $user=$this->auth->authenticate($token);
        }catch (TokenExpiredException $e){
            return $this->respond('tymon.jwt.expired','token_expired',$e->getStatusCode(),[$e]);
        }catch (JWTException $e){
            return $this->respond('tymon.jwt.invalid','token_invalid',$e->getStatusCode(),[$e]);
        }

        if (!$user){
            return $this->respond('tymon.jwt.user_not_found','user_not_found',JsonResponse::HTTP_NOT_FOUND);
        }

        //JWT has been extended to include the entrust's ability middleware with this code
        if (!$request->user()->ability(explode('|',$roles),explode('|',$permissions),['validate_all'=>$validateAll])){
            return $this->respond('tymon.jwt.invalid','token_invalid',JsonResponse::HTTP_UNAUTHORIZED,'Unauthorized');
        }

        $this->events->fire('tymon.jwt.valid',$user);
        return $next($request);
    }
}
