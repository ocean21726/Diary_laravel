<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repository\Diary\DiaryRepository;

class DiaryController extends Controller
{
    private $diaryRepository;

    public function __construct(DiaryRepository $diaryRepository)
    {
        $this->diaryRepository = $diaryRepository;
    }

    public function postDiaryCreate()
    {
        $creatorIdx = Auth::user()->user_idx;
        $inviteeIdx = request()->inviteeIdx;
        $diaryName = request()->diaryName;

        try {
            \DB::transaction(function () use ($creatorIdx, $inviteeIdx, $diaryName) {
                return $this->diaryRepository->postDiaryCreate($creatorIdx, $inviteeIdx, $diaryName);
            });
            \DB::commit();
            return response()->success('교환일기 등록 성공');
        } catch (\Exception $e) {
            \Log::info('DiaryCreateError: '.$e->getMessage());
            \DB::rollBack();
            return response()->fail('교환일기 등록 실패');
        }
    }

    public function getDiaryList()
    {
        $userIdx = Auth::user()->user_idx;
        $offset = request()->offset ?? 0;
        $limit = request()->limit ?? 10;

        list($result, $totalCount) = $this->diaryRepository->getDiaryList($userIdx, $offset, $limit);
        $pageData = array(
            'totalCount' => $totalCount,
            'offset' => (int)$offset,
            'limit' => (int)$limit
        );

        return response()->list($result, $pageData);
    }
}
