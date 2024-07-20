<?php

namespace App\Http\Controllers;

use App\Link;
use App\Type;
use Illuminate\Http\Request;

class LinksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type_id=null)
    {
        $types= Type::where('type_id',null)->orderBy('order_by')
            ->get();
        $type_array = [];
        foreach($types as $type){
            $type_array[$type->id] = $type->name;
        }
        $type2s= Type::where('type_id',"<>",null)->orderBy('type_id')->orderBy('order_by')
            ->get();
        
        $links = Link::orderBy('type_id')
            ->orderBy('order_by')
            ->get();
        $link_data = []; 
        foreach($links as $link){
            $link_data[$link->type_id][$link->id]['id'] = $link->id;
            $link_data[$link->type_id][$link->id]['icon'] = $link->icon;
            $link_data[$link->type_id][$link->id]['name'] = $link->name;
            $link_data[$link->type_id][$link->id]['url'] = $link->url;
            $link_data[$link->type_id][$link->id]['target'] = $link->target;
            $link_data[$link->type_id][$link->id]['order_by'] = $link->order_by;
        }

        $type = Type::orderBy('order_by','DESC')->first();
        if(!empty($type)){
            $new_type_order_by = $type->order_by+1;
        }else{
            $new_type_order_by = 1;
        }
        

        $data = [
            'type_id'=>$type_id,
            'types'=>$types,
            'type2s'=>$type2s,
            'type_array'=>$type_array,
            'new_type_order_by'=>$new_type_order_by,
            'link_data'=>$link_data,
        ];
        return view('links.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type_id=null)
    {
        $types = Type::where('type_id',null)->orderBy('order_by')->get();
        $type_array = [];
        foreach($types as $type){
            $type_array[$type->id] = $type->name;
            $type2s = Type::where('type_id',$type->id)->orderBy('order_by')->get();
            foreach($type2s aS $type2){
                $type_array[$type2->id] = "---->".$type2->name;
            }
        }
        
        $new_link_order_by = []; 
        foreach($types as $k=>$v){
            $link = Link::where('type_id',$k)->orderBy('order_by','DESC')->first();
            if(!empty($link)){
                $new_link_order_by[$k] = $link->order_by+1;
            }else{
                $new_link_order_by[$k] = 1;
            }
        }
        
        $data = [
            'type_array'=>$type_array,
            'new_link_order_by'=>$new_link_order_by,
            'type_id'=>$type_id,
        ];
        return view('links.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_type(Request $request)
    {
        $request->validate([
            'name'=>'required',
            'order_by'=>['nullable','numeric'],
        ]);
        Type::create($request->all());
        return redirect()->route('links.index');
    }
    public function store(Request $request) 
    {
        $request->validate([
            'name'=>'required',
            'url'=>'required',
            'order_by'=>['nullable','numeric'],
        ]);
        $link = Link::create($request->all());
        return redirect()->route('links.index',$link->type_id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Link $link)
    {
        $types = Type::where('type_id',null)->orderBy('order_by')->get();
        foreach($types as $type){
            $type_array[$type->id] = $type->name;
            $type2s = Type::where('type_id',$type->id)->orderBy('order_by')->get();
            foreach($type2s aS $type2){
                $type_array[$type2->id] = "---->".$type2->name;
            }
        }
        $data = [
            'link'=>$link,
            'type_array'=>$type_array,
        ];
        return view('links.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Link $link,$type_id=null)
    {
        $request->validate([
            'name'=>'required',
            'url'=>'required',
            'order_by'=>['nullable','numeric'],
        ]);
        $link->update($request->all());
        return redirect()->route('links.index',$link->type_id);
    }

    public function update_type(Request $request, Type $type)
    {
        $request->validate([
            'name'=>'required',
        ]);
        $type->update($request->all());
        return redirect()->route('links.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Link $link)
    {
        $link->delete();
        return redirect()->route('links.index');
    }

    public function delete(Link $link)
    {
        $link->delete();
        return back();
    }

    public function destroy_type(Type $type)
    {
        $type->links()->delete();
        $type->delete();
        return redirect()->route('links.index');
    }
}
