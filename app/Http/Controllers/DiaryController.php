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

    public function postPageCreate()
    {
        $userIdx = Auth::user()->user_idx;
        $diaryIdx = request()->diaryIdx;
        $title = request()->title;
        $content = request()->content;

        try {
            \DB::transaction(function () use ($userIdx, $diaryIdx, $title, $content) {
                return $this->diaryRepository->postPageCreate($userIdx, $diaryIdx, $title, $content);
            });
            \DB::commit();
            return response()->success('일기 등록 성공');
        } catch (\Exception $e) {
            \Log::info('PageCreateError: '.$e->getMessage());
            \DB::rollBack();
            return response()->fail('일기 등록 실패');
        }
    }

    public function putPageUpdate()
    {
        $userIdx = Auth::user()->user_idx;
        $idx = request()->idx;
        $title = request()->title ?? null;
        $content = request()->content ?? null;

        try {
            \DB::transaction(function () use ($userIdx, $idx, $title, $content) {
                return $this->diaryRepository->postPageUpdate($userIdx, $idx, $title, $content);
            });
            \DB::commit();
            return response()->success('일기 수정 성공');
        } catch (\Exception $e) {
            \Log::info('PageUpdateError: '.$e->getMessage());
            \DB::rollBack();
            return response()->fail('일기 수정 실패');
        }
    }

    public function getPageList()
    {
        $diaryIdx = request()->diaryIdx;
        $offset = request()->offset ?? 0;
        $limit = request()->limit ?? 10;

        list($result, $totalCount) = $this->diaryRepository->getPageList($diaryIdx, $offset, $limit);
        $pageData = array(
            'totalCount' => $totalCount,
            'offset' => (int)$offset,
            'limit' => (int)$limit
        );

        return response()->list($result, $pageData);
    }

    public function getPageDetail()
    {
        $idx = request()->idx;
        $userIdx = Auth::user()->user_idx;

        list($result, $status) = $this->diaryRepository->getPageDetail($idx, $userIdx);

        if ($status) {
            return response()->object($result);
        } else {
            return response()->fail('권한이 없습니다.');
        }
    }
}
