<?php

namespace App\Http\Controllers;

use App\Tree;
use Illuminate\Http\Request;

class TreesController extends Controller
{
    public function index(){
        $fs = Tree::where('type','1')
            ->orderBy('order_by')
            ->orderBy('name')
            ->get();
        $folders[0] = "根目錄";
        foreach($fs as $f){
            $folders[$f->id] = $f->name;
        }

        $trees = Tree::where('folder_id','0')
            ->orderBy('type')
            ->orderBy('order_by')
            ->orderBy('name')
            ->get();

        //預先排序
        $new_tree_order_by = [];

        $link_tree = Tree::where('folder_id',0)->orderBy('order_by','DESC')->first();
        if(!empty($link_tree)){
            $new_tree_order_by[0] = $link_tree->order_by+1;
        }else{
            $new_tree_order_by[0] = 1;
        }

        
        $folder_trees = Tree::where('type','1')->orderBy('order_by','DESC')->get();
        foreach($folder_trees as $folder_tree){
            $link_tree = Tree::where('folder_id',$folder_tree->id)->orderBy('order_by','DESC')->first();
            if(!empty($link_tree)){
                $new_tree_order_by[$folder_tree->id] = $link_tree->order_by+1;
            }else{
                $new_tree_order_by[$folder_tree->id] = 1;
            }
        }
        ksort($new_tree_order_by);
        
        $data = [
            'folders'=>$folders,
            'trees'=>$trees,
            'new_tree_order_by'=>$new_tree_order_by,
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
