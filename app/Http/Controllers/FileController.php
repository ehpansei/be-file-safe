<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    //
    public function create(Request $request)
    {
        $file = $request->file;

        // upload file
        $path = $file->store('files/' . $request->user()->name);

        // create database entry
        $newFile = new File();
        $newFile->user_id = $request->user()->id;
        $newFile->name = $file->getClientOriginalName(); // todo: is this safe?
        $newFile->path = $path;
        $newFile->save();

        return response()->json(["message" => "File has been uploaded"]);
    }

    public function show(File $file)
    {
        return response()->json($file);
    }

    public function get(Request $request)
    {

        if (isset($request->perPage)) {
            $pageSize = $request->perPage;
        }
        if (isset($request->searchTerm)) {
            // eloquent automatically escapes parameters
            $files = File::where('name', 'like', "%{$request->searchTerm}%")->paginate($pageSize);
        } else {
            $files = File::paginate($pageSize);
        }

        return response()->json(["list" => $files, "count" => $files->count()]);
    }

    public function delete(File $file)
    {

        // remove file
        Storage::disk('local')->delete($file->path);

        //remove database entry
        $file->delete();

        return response()->json($file);
    }

    public function download(File $file)
    {
        if (Storage::disk('local')->exists($file->path)) {
            // ...
            Log::info($file);
            return Storage::download($file->path, $file->name);
        } else {
            return response()->setStatusCode(404, "File not found");
        }
    }
}
