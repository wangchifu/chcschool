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
    public function index()
    {
        $types= Type::orderBy('order_by')
            ->get();
        $links = Link::orderBy('type_id')
            ->orderBy('order_by')
            ->get();
        $data = [
            'types'=>$types,
            'links'=>$links,
        ];
        return view('links.index',$data);
    }

    public function browser(Type $select_type)
    {
        $types= Type::orderBy('order_by')
            ->get();
        $links = Link::where('type_id',$select_type->id)
            ->orderBy('order_by')
            ->get();
        $data = [
            'select_type'=>$select_type,
            'types'=>$types,
            'links'=>$links,
        ];
        return view('links.browser',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = Type::orderBy('order_by')->pluck('name', 'id')->toArray();
        $data = [
            'types'=>$types,
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
        return redirect()->route('links.browser',$link->type_id);
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
        $types = Type::orderBy('order_by')->pluck('name', 'id')->toArray();
        $data = [
            'link'=>$link,
            'types'=>$types,
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
    public function update(Request $request, Link $link)
    {
        $request->validate([
            'name'=>'required',
            'url'=>'required',
            'order_by'=>['nullable','numeric'],
        ]);
        $link->update($request->all());
        return redirect()->route('links.browser',$link->type_id);
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

    public function destroy_type(Type $type)
    {
        $type->links()->delete();
        $type->delete();
        return redirect()->route('links.index');
    }
}
