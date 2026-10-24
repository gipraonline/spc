<?php

namespace App\Http\Controllers;

use App\Services\IndiaPostService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use RuntimeException;

class IndiaPostTrackingController extends Controller
{
    public function __construct(private IndiaPostService $indiaPost)
    {
    }

    // GET /indiapost/track/{articleNumber}
    public function single(string $articleNumber): JsonResponse
    {
        validator(['article_number' => $articleNumber], [
            'article_number' => ['required', 'string', 'size:13'],
        ])->validate();

        return $this->run([$articleNumber]);
    }

    // POST /indiapost/track   body: { "article_numbers": ["RK775227016IN", ...] }
    public function bulk(Request $request): JsonResponse
    {
        $data = $request->validate([
            'article_numbers'   => ['required', 'array', 'min:1', 'max:500'],
            'article_numbers.*' => ['required', 'string', 'size:13'],
        ]);

        return $this->run($data['article_numbers']);
    }

    private function run(array $numbers): JsonResponse
    {
        try {
            $result = $this->indiaPost->track($numbers);
        } catch (RuntimeException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Unable to fetch tracking details right now. Please try again later.',
            ], 502);
        }

        return response()->json([
            'success'   => true,
            'data'      => $result['found'],
            'not_found' => $result['not_found'],
        ]);
    }
}



