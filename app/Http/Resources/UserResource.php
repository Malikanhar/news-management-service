<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    private $message;
    private $token;

    public function __construct($message, $resource, $token)
    {
        parent::__construct($resource);
        $this->message = $message;
        $this->token = $token;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'message' => $this->message,
            'data' => $this->whenNotNull($this->resource),
            'token' => $this->whenNotNull($this->token)
        ];
    }
}
