<?php

namespace App\Http\Resources\v1;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    private $token='';
    public function __construct($token)
    {
        $this->token=$token;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user=auth()->user();
        return [
            'name'  => $user->name,
            'email'  => $user->email,
            'token'  => $this->token,
        ];
    }
}
