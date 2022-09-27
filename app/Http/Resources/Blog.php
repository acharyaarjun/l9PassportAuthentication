<?php

namespace App\Http\Resources;

use App\Http\Resources\User as ResourcesUser;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\User;

class Blog extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'blog_title' => $this->blog_title,
            'blog_description' => $this->blog_description,
            'created_at' => $this->created_at->format('m/d/Y'),
            'updated_at' => $this->updated_at->format('m/d/Y'),
            'user' => new ResourcesUser(User::findOrFail($this->user_id))
        ];
    }
}
