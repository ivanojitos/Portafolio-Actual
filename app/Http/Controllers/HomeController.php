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
                ->published()
                ->firstOrFail(),
        ]);
    }
}
