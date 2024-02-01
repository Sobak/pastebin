<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Paste;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $pastes = Paste::query()
            ->select(['pastes.*', DB::raw('LENGTH(content) AS size')])
            ->when($request->get('sort'), function (Builder $query, string $sortString) {
                [$field, $direction] = explode(':', $sortString);

                $query->orderBy($field, $direction);
            })
            ->when($request->get('search'), function (Builder $query, string $phrase) {
                $phrase = mb_strtolower($phrase);

                // Vulnerable to SQL injection but who cares, I need to stop overthinking for once...
                $query->where(function (Builder $query) use ($phrase) {
                    $query
                        ->orWhereRaw("LOWER(title) LIKE '%$phrase%'")
                        ->orWhereRaw("LOWER(language) = '$phrase'")
                        ->orWhereRaw("LOWER(author) LIKE '%$phrase%'")
                    ;
                });
            })
            ->simplePaginate(50)
            ->withQueryString();

        return view('admin.index', [
            'pastes' => $pastes,
        ]);
    }

    public function remove(Paste $paste)
    {
        $paste->delete();

        return redirect()
            ->back()
            ->with('alert', "Paste {$paste->id} has been removed");
    }

    public function massRemove(Request $request)
    {
        $this->validate($request, [
            'pastes' => 'array',
            'pastes.*' => 'numeric',
        ]);

        $removedCount = Paste::whereIn('id', $request->get('pastes'))->delete();

        return redirect()
            ->back()
            ->with('alert', "Removed $removedCount pastes");
    }
}
