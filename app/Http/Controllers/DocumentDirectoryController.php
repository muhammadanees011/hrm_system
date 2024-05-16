<?php

namespace App\Http\Controllers;

use App\Models\DocumentDirectory;
use App\Models\DirectoryHasDocument;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class DocumentDirectoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $dirList= DocumentDirectory::get();
        return view('directories.index',compact('dirList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('directories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $validator = \Validator::make(
                $request->all(), [
                    'name' => 'required|max:20',
                ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $dir              = new DocumentDirectory();
            $dir->name        = $request->name;
            $dir->save();

            return redirect()->route('documentdirectories.index')->with('success', __('Directory successfully created.'));
    }

    /**
     * Display the specified resource.
     */
    public function show($dir)
    {
        $dir_id=$dir;
        $directory=DocumentDirectory::find($dir_id);
        $directory_name=$directory->name;
        $documents= DirectoryHasDocument::where('directory_id',$dir_id)->get();
        return view('directories.show',compact('dir_id','documents','directory_name'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DocumentDirectory $documentDirectory)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DocumentDirectory $documentDirectory)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($dir)
    {
        $dir=DocumentDirectory::find($dir);
        $dir->delete();
        return redirect()->route('documentdirectories.index')->with('success', __('Directory successfully deleted.'));
    }


    public function createfile($dir_id)
    {
        return view('directories.create-file',compact('dir_id'));
    }

    public function storefile(Request $request)
    {
        $validator = \Validator::make(
            $request->all(),
            [
                'file' => 'required|file|mimes:pdf|max:10240',
                'dir_id' => 'required'
            ]
        );

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        // $dir = 'documents_storage/';
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $path = public_path() . '/documents_storage';
            $file->move($path,$fileName);
        }
        $document=new DirectoryHasDocument();
        $document->directory_id=$request->dir_id;
        $document->file = $fileName ?? '';
        $document->save();

        return redirect()->route('documentdirectories.show',$request->dir_id)->with('success', __('Document successfully created.'));
    }

    public function viewFile($id)
    {
        $document=DirectoryHasDocument::find($id);
        $filename=$document->file;
        $path = public_path('documents_storage/' . $filename);

        if (!file_exists($path)) {
            abort(404);
        }
    
        $file = file_get_contents($path);
    
        return Response::make($file, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"'
        ]);
    }

    public function deletefile($file_id,$dir_id)
    {
        $document = DirectoryHasDocument::find($file_id);
        $filename=$document->file;
        $filePath = public_path('documents_storage/' . $filename);
        if (File::exists($filePath)) {
            File::delete($filePath);
            $document->delete();
            return redirect()->route('documentdirectories.show',$dir_id)->with('success', __('Document successfully deleted.'));
        } else {
            return redirect()->route('documentdirectories.show',$dir_id)->with('error', __('Document not found.'));
        }
    }

    public function downloadFile($file_id,$dir_id)
    {
        $document = DirectoryHasDocument::find($file_id);
        $filename=$document->file;
        $filePath = public_path('documents_storage/' . $filename);
        if (File::exists($filePath)) {
            $filePath = public_path('documents_storage/' . $filename);
            return response()->download($filePath, $filename);
        } else {
            return redirect()->route('documentdirectories.show',$dir_id)->with('error', __('File not found.'));
        }
    }
}
