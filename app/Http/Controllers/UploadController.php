<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\Util;

class UploadController extends Controller
{
    public function upload(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'mission' => 'required',
            'institution' => 'required',
            'file' => 'required|image',
        ]);
        /** @var UploadedFile $file */
        $file      = $request->file;
        $dir       = now()->format('Ymd');
        $file_name = Util::normalizePath(sprintf("%s-%s-%s-%s.%s", $request->institution, $request->username, $request->mission, now()->format('His'), $file->extension()));
        $file->storeAs($dir, $file_name, 'public');
        $url = Storage::disk('public')->url("$dir/$file_name");
        return $this->response(['url' => $url]);
    }
}
