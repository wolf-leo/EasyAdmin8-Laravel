<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasOne;

class MallGoods extends BaseModel
{

    public function cate(): HasOne
    {
        return $this->hasOne(MallCate::class, 'id', 'cate_id')->select('id', 'title');
    }

}
