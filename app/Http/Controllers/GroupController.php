<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\View\View;

class GroupController extends Controller
{
    public function index(): View
    {
        $groups = Group::with('teams')->orderBy('name')->get();

        return view('groups.index', compact('groups'));
    }
}
