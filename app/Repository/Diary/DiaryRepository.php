<?php
declare(strict_types=1);

namespace App\Repository\Diary;

use Carbon\Carbon;
use App\Models\Diary;
use App\Models\Page;

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

    public function postPageCreate($userIdx, $diaryIdx, $title, $content)
    {
        $page = new Page;
        $page->diary_idx = $diaryIdx;
        $page->user_idx = $userIdx;
        $page->title = $title;
        $page->content = $content;
        $page->created_date = Carbon::now();
        $page->save();
    }

    public function postPageUpdate($userIdx, $idx, $title, $content)
    {
        $page = Page::where('user_idx', $userIdx)
        ->where('idx', $idx)
        ->whereNull('deleted_date')
        ->first();

        if ($page) {
            $updateArray = [];
            if (isset($title)) {
                $updateArray['title'] = $title;
            }
            if (isset($content)) {
                $updateArray['title'] = $content;
            }
            if (isset($updateArray)) {
                $updateArray['updated_date'] = Carbon::now();
                Page::where('idx', $idx)->update($updateArray);
            }
        } else {
            throw new \Exception('존재하지 않는 일기입니다.');
        }
    }

    public function getPageList($diaryIdx, $offset, $limit)
    {
        $list = Page::where('diary_idx', $diaryIdx)
        ->whereNull('deleted_date')
        ->orderBy('idx', 'desc');

        $totalCount = $list->count();
        $result = $list->offset($offset)->limit($limit)->get();

        return [$result, $totalCount];
    }

    public function getPageDetail($idx, $userIdx)
    {
        $result = Page::from('diary_page as page')
        ->join('diary', 'diary.idx', '=', 'page.diary_idx')
        ->select('page.*')
        ->whereNull('diary.deleted_date')
        ->whereNull('page.deleted_date')
        ->whereRaw('(diary.creator_idx = '.$userIdx.' or diary.invitee_idx = '.$userIdx.')')
        ->first();

        if ($result) {
            return [$result, true];
        } else {
            return [null, false];
        }
    }
}
