<?php

namespace App\Models;

class SystemAdmin extends BaseModel
{

    public function getAuthList(): array
    {
        $list = SystemAuth::where('status', 1)->select(['id', 'title'])->get()->toArray();
        return collect($list)->pluck('title', 'id')->toArray();
    }
}
