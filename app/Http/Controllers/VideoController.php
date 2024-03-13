<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Auth;
class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::where('created_by', '=', \Auth::user()->creatorId())->get();
        return view('video.index', compact('videos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $videos = Video::where('created_by', '=', \Auth::user()->creatorId())->get();
        return view('video.create', compact('videos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $auth_id = Auth::user()->id;
        $validator = \Validator::make(
            $request->all(),
            [
                'title' => 'string|required',
                'source_type' => 'string|required',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('video_file')) {
            $file = $request->file('video_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = public_path() . '/videos';
            $file->move($path,$fileName);
        }


        $video = new Video();
        $video->title = $request->title;
        $video->source_type = $request->source_type;
        $video->video_link = $request->video_link;
        $video->video_file = $fileName ?? null;
        $video->created_by = $auth_id;
        $video->save();
    
        return redirect()->route('video.index')->with('success', __('Video uploaded successfully.'));
    }

    

    /**
     * Display the specified resource.
     */
    public function show(Video $video)
    {
        return view('video.video', compact('video'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $video =  Video::where('id', $id)->first();
        return view('video.edit', compact('video'));
    }
    

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Video $video)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'title' => 'string|required',
                'source_type' => 'string|required',
            ]
        );
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        if(!empty($video->video_file) && !empty($request->video_link)){
            $oldFile = public_path('/videos/'.$video->video_file);
            if (\File::exists($oldFile)) {
                \File::delete($oldFile);
            }
            $video->video_file = null;
        }

        if ($request->hasFile('video_file')) {
            $file = $request->file('video_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $path = public_path() . '/videos';
            $file->move($path,$fileName);

            $oldFile = public_path('/videos/'.$video->video_file);
            if (\File::exists($oldFile)) {
                \File::delete($oldFile);
            }
            $video->video_file = $fileName;
        }

        $video->title = $request->title;
        $video->source_type = $request->source_type;
        $video->video_link = $request->video_link;
        $video->save();
    
        return redirect()->route('video.index')->with('success', __('Video updated successfully.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Video $video)
    {
        // CHECKING IF FILE EXISTS THEN DELETE
        $oldFile = public_path('/videos/'.$video->video_file);
        if (\File::exists($oldFile)) {
            \File::delete($oldFile);
        }
        $video->delete();
        return redirect()->route('video.index')->with('success', __('Video deleted successfully.'));
        
    }
}
