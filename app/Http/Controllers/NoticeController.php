<?php

namespace App\Http\Controllers;

class NoticeController extends Controller
{
    public function index()
    {
        return response('Notices are not configured.', 404);
    }

    public function show(string $slug)
    {
        return response('Notice not found.', 404);
    }
}
