<?php
declare(strict_types=1);

namespace App\Repository\Diary;

use Carbon\Carbon;
use App\Models\Diary;

class DiaryRepository
{
    public function postDiaryCreate($creatorIdx, $inviteeIdx, $diaryName)
    {
        $diary = new Diary;
        $diary->creator_idx = $creatorIdx;
        $diary->invitee_idx = $inviteeIdx;
        $diary->diary_name = $diaryName;
        $diary->created_date = Carbon::now();
        $diary->save();
    }

    public function getDiaryList($userIdx, $offset, $limit)
    {
        $list = Diary::where('creator_idx', $userIdx)
        ->orWhere('invitee_idx', $userIdx)
        ->orderBy('idx','desc');

        $totalCount = $list->count();
        $result = $list->offset($offset)->limit($limit)->get();

        return [$result, $totalCount];
    }
}
