<?php

namespace App\Http\Controllers;

use App\Tree;
use Illuminate\Http\Request;

class TreesController extends Controller
{
    public function index(){
        $fs = Tree::where('type','1')
            ->orderBy('name')
            ->get();
        $folders[0] = "根目錄";
        foreach($fs as $f){
            $folders[$f->id] = $f->name;
        }

        $trees = Tree::where('folder_id','0')
            ->orderBy('type')
            ->orderBy('name')
            ->get();
        $data = [
            'folders'=>$folders,
            'trees'=>$trees,
        ];
        return view('trees.index',$data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required',
        ]);
        Tree::create($request->all());
        return redirect()->route('trees.index');
    }

    public function delete(Tree $tree)
    {
        Tree::where('folder_id',$tree->id)->delete();
        $tree->delete();

        return redirect()->route('trees.index');
    }

    public function edit(Tree $tree)
    {
        $fs = Tree::where('type','1')
            ->orderBy('name')
            ->get();
        $folders[0] = "根目錄";
        foreach($fs as $f){
            $folders[$f->id] = $f->name;
        }
        $data = [
            'folders'=>$folders,
            'tree'=>$tree,
        ];
        return view('trees.edit',$data);
    }

    public function update(Request $request,Tree $tree)
    {
        $request->validate([
            'name'=>'required',
        ]);
        $tree->update($request->all());
        echo "<body onload='opener.location.reload();window.close();'>";
    }
}
