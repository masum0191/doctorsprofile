<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MessageTemplate;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    public function index() {
        $templates = MessageTemplate::orderByDesc('id')->paginate(20);
        return view('admin.marketing.templates.index', compact('templates'));
    }

    public function create() {
        return view('admin.marketing.templates.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'channel'=>'required|in:email,whatsapp',
            'name'=>'required|string|max:120',
            'subject'=>'nullable|string|max:190',
            'body'=>'required|string',
            'variables'=>'nullable|string', // JSON
            'meta'=>'nullable|string',      // JSON
        ]);

        $variables = $data['variables'] ? json_decode($data['variables'], true) : [];
        $meta = $data['meta'] ? json_decode($data['meta'], true) : [];

        MessageTemplate::create([
            'channel'=>$data['channel'],
            'name'=>$data['name'],
            'subject'=>$data['subject'] ?? null,
            'body'=>$data['body'],
            'variables'=> is_array($variables) ? $variables : [],
            'meta'=> is_array($meta) ? $meta : [],
            'is_active'=>1
        ]);

        return redirect()->route('superadmin.marketing.templates.index')->with('success','Template created');
    }
    public function edit($id) {
        $template = MessageTemplate::findOrFail($id);
        return view('admin.marketing.templates.edit', compact('template'));
    }
    public function update(Request $request, $id)
    {
        $template = MessageTemplate::findOrFail($id);

        $data = $request->validate([
            'channel'=>'required|in:email,whatsapp',
            'name'=>'required|string|max:120',
            'subject'=>'nullable|string|max:190',
            'body'=>'required|string',
            'variables'=>'nullable|string', // JSON
            'meta'=>'nullable|string',      // JSON
        ]);

        $variables = $data['variables'] ? json_decode($data['variables'], true) : [];
        $meta = $data['meta'] ? json_decode($data['meta'], true) : [];

        $template->update([
            'channel'=>$data['channel'],
            'name'=>$data['name'],
            'subject'=>$data['subject'] ?? null,
            'body'=>$data['body'],
            'variables'=> is_array($variables) ? $variables : [],
            'meta'=> is_array($meta) ? $meta : [],
        ]);

        return redirect()->route('superadmin.marketing.templates.index')->with('success','Template updated');
    }
    public function destroy($id)
    {
        $template = MessageTemplate::findOrFail($id);
        $template->delete();

        return redirect()->route('superadmin.marketing.templates.index')->with('success', 'Template deleted successfully.');
    }
}
