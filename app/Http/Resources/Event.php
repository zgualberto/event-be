<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class Event extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'edate' => $this->edate
        ];
    }

    public function with($request)
    {
        // return parent::toArray($request);

        return [
            'version' => '1.0.0',
            'author_url' => url('http://www.sample.com')
        ];
    }
}
