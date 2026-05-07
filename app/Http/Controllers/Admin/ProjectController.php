<?php

// ProjectController.php (Admin)

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Contractor;
use App\Models\ProjectImage;
use App\Models\ProjectProgressLog;
use App\Models\ProjectAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::with('contractor')->latest()->get();
        return view('admin.projects.index', compact('projects'));
    }

    public function create()
    {
        $contractors = Contractor::all();
        return view('admin.projects.create', compact('contractors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'location' => 'nullable',
            'ward_no' => 'nullable|integer',
            'budget' => 'nullable|numeric',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:proposed,ongoing,completed',
            'contractor_id' => 'nullable|exists:contractors,id',
            'image' => 'nullable|image',
        ]);


            // public floder image upload

        if ($request->hasFile('image')) {
            $photo = $request->file('image');
            $photoName = time() . '_' . $photo->getClientOriginalName();
            $photo->move(public_path('uploads/projects'), $photoName);
            $data['image'] = 'uploads/projects/' . $photoName;
        }




        $project = Project::create($data);

        // Handle Multiple Images
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $img) {
                ProjectImage::create([
                // public floder image upload
                    $photo =$img,
                    $photoName = time() . '_' . $photo->getClientOriginalName(),
                    $photo->move(public_path('uploads/projects/gallery'), $photoName),
                    'image_path' => 'uploads/projects/gallery/' . $photoName,
                    'project_id' => $project->id,






                ]);
            }
        }

        // Handle Attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                ProjectAttachment::create([
                    // 'project_id' => $project->id,
                    // 'file_path' => $file->store('project_attachments' , 'public'),
                    // 'file_type' => $file->getClientOriginalExtension(),

                     $photo =$file,
                    $photoName = time() . '_' . $photo->getClientOriginalName(),
                    $photo->move(public_path('uploads/projects/gallery'), $photoName),
                    'file_path' => 'uploads/projects/gallery/' . $photoName,
                    'file_type'=> $file->getClientOriginalExtension(),

                    'project_id' => $project->id,
                ]);
            }
        }

        // Handle Progress Logs
        if ($request->progress_logs) {
            foreach ($request->progress_logs as $log) {
                if (!empty($log['note'])) {
                    ProjectProgressLog::create([
                        'project_id' => $project->id,
                        'progress_note' => $log['note'],
                        'progress_date' => $log['date']

                    ]);
                }
            }
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully');
    }

    public function show(Project $project)
    {
        $project->load(['contractor', 'images', 'progressLogs', 'attachments']);
        return view('admin.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $contractors = Contractor::all();
        return view('admin.projects.edit', compact('project', 'contractors'));
    }

    public function update(Request $request, Project $project)
    {
        $data = $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'location' => 'nullable',
            'ward_no' => 'nullable|integer',
            'budget' => 'nullable|numeric',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'status' => 'required|in:proposed,ongoing,completed',
            'contractor_id' => 'nullable|exists:contractors,id',
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            if ($project->image) Storage::delete($project->image);
            $data['image'] = $request->file('image')->store('projects');
        }

        $project->update($data);
        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully');
    }

    public function destroy(Project $project)
    {
        $project->delete();
        return back()->with('success', 'Project deleted');
    }
}



