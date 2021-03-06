<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use Illuminate\View\View;
    // impor model file
    use App\File;
    use Illuminate\Http\RedirectResponse;
    class FileController extends Controller
    {
        public function index(): View
        {
            $files = File::orderBy('created_at', 'DESC')
                ->paginate(30);
            return view('file.index', compact('files'));
        }
        public function form(): View
        {
            $list_post = \App\Post::all()->pluck('Nama_Artikel','id');
            return view('file.form',compact('list_post'));
        }
        public function upload(Request $request): RedirectResponse
        {
            $this->validate($request, [
                'title' => 'nullable|max:100',
                'file' => 'required|file|max:2000'
            ]);
            $uploadedFile = $request->file('file');        
            $path = $uploadedFile->store('public/files');
            $file = File::create([
                'title' => $request->title ?? $uploadedFile->getClientOriginalName(),
                'filename' => $path
            ]);
            
            return redirect('pengindex/create')
                ->withSuccess(sprintf('File %s has been uploaded.', $file->title));   
        }
    }