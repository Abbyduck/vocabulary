<?php
namespace App\Domain;

use App\Model\History as ModelHistory;


class History{
    public function history($openid, $book,$date)
    {
        $history=new ModelHistory();
        $history=$history->history($openid, $book,$date);
        return !empty($history)?$history['a']:'';
    }
    public function total($openid, $book)
    {
        $history=new ModelHistory();
        $history=$history->total($openid,$book);
        return $history;
    }
}
