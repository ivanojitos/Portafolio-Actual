<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        return view('home', [
            'profile' => Profile::query()
                ->with([
                    'avatarMedia:id,mediable_type,mediable_id,disk,path,alt_text',
                ])
                ->published()
                ->firstOrFail(),
        ]);
    }
}
