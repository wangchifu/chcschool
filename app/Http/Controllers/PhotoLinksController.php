<?php

namespace App\Http\Controllers;

use App\PhotoLink;
use App\PhotoType;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PhotoLinksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $photo_links = PhotoLink::orderBy('order_by','DESC')
            ->get();
        $photo_types = PhotoType::orderBy('order_by')->get();
        foreach($photo_types as $photo_type){
            $photo_type_array[$photo_type->id] = $photo_type->name;
        }
        $photo_type_array[0] = "不分類"; 

        $photo_link_data = [];
        foreach($photo_links as $photo_link){
            $type = ($photo_link->photo_type_id==null)?0:$photo_link->photo_type_id;
            $photo_link_data[$type][$photo_link->id]['id'] = $photo_link->id;
            $photo_link_data[$type][$photo_link->id]['name'] = $photo_link->name;
            $photo_link_data[$type][$photo_link->id]['url'] = $photo_link->url;
            $photo_link_data[$type][$photo_link->id]['image'] = $photo_link->image;
            $photo_link_data[$type][$photo_link->id]['order_by'] = $photo_link->order_by;
            $photo_link_data[$type][$photo_link->id]['user_id'] = $photo_link->user_id;
        }

        $photo_type = PhotoType::orderBy('order_by','DESC')->first();
        if(!empty($photo_type)){
            $new_order_by = $photo_type->order_by+1;
        }else{
            $new_order_by = 1;
        }

        $data = [
            'photo_link_data'=>$photo_link_data,
            'photo_type_array'=>$photo_type_array,
            'photo_types'=>$photo_types,
            'new_order_by'=>$new_order_by,
        ];
        return view('photo_links.index',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $photo_types = PhotoType::orderBy('order_by')->get();
        
        $new_type_order_by = [];

        $photo_link = PhotoLink::where(function ($query) {
            $query->where('photo_type_id',null)->orWhere('photo_type_id','0');
            })->orderBy('order_by','DESC')->first();
        if(!empty($photo_link)){
            $new_link_order_by[0] = $photo_link->order_by+1;
        }else{
            $new_link_order_by[0] = 1;
        }    

        foreach($photo_types as $photo_type){
            $photo_link = PhotoLink::where('photo_type_id',$photo_type->id)->orderBy('order_by','DESC')->first();
            if(!empty($photo_link)){
                $new_link_order_by[$photo_type->id] = $photo_link->order_by+1;
            }else{
                $new_link_order_by[$photo_type->id] = 1;
            }
        }
    

        $data = [
            'photo_types'=>$photo_types,
            'new_link_order_by'=>$new_link_order_by,
        ];
        return view('photo_links.create',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_by'=>'required|numeric',
            'name' => 'required',
            'image' => 'required|mimes:jpeg,png|max:5120',
            'url' => 'required',
        ]);

        $att['name'] = $request->input('name');
        $att['url'] = $request->input('url');
        $att['image'] = "image";
        $att['order_by'] = $request->input('order_by');
        $att['photo_type_id'] = $request->input('photo_type_id');
        $att['user_id'] = auth()->user()->id;

        $photo_link = PhotoLink::create($att);

        $school_code = school_code();
        $folder = 'public/'. $school_code .'/photo_links';

        //處理檔案上傳
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $info = [
                'original_filename' => $image->getClientOriginalName(),
                'extension' => $image->getClientOriginalExtension(),
            ];

            $image_name = $photo_link->id.'.'.$info['extension'];
            $image->storeAs($folder,$image_name);

            $att2['image'] = $image_name;
            $photo_link->update($att2);

            Image::make(storage_path('app/'.$folder.'/'.$image_name))->heighten(500)
                ->save(storage_path('app/'.$folder.'/'.$image_name));
        }



        return redirect()->route('photo_links.index');
    }

    public function type_store(Request $request)
    {
        $att = $request->all();
        $att['user_id'] = auth()->user()->id;
        
        PhotoType::create($att);

        return back();
    }

    public function type_update(Request $request, PhotoType $photo_type)
    {
        $att = $request->all();
        
        $photo_type->update($att);

        return back();
    }

    public function type_delete(PhotoType $photo_type)
    {
        if(auth()->user()->admin !=1 and $photo_type->user_id != auth()->user()->id){
            return back();
        }
        $att['photo_type_id'] = null;
        PhotoLink::where('photo_type_id',$photo_type->id)->update($att);
        $photo_type->delete();

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($photo_type_id=null)
    {
        if($photo_type_id==null){
            $photo_links = PhotoLink::orderBy('created_at','DESC')->orderBy('order_by','DESC')
            ->paginate(24);
        }else{
            $photo_links = PhotoLink::where('photo_type_id',$photo_type_id)->orderBy('order_by','DESC')
            ->paginate(24);
        }
        $photo_types = PhotoType::orderBy('order_by')->get();
        $data = [
            'photo_types'=>$photo_types,
            'photo_type_id'=>$photo_type_id,
            'photo_links'=>$photo_links,
        ];
        return view('photo_links.show',$data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PhotoLink $photo_link)
    {
        $photo_types = PhotoType::orderBy('order_by')->get();

        $data = [
            'photo_types'=>$photo_types,
            'photo_link'=>$photo_link,
        ];
        return view('photo_links.edit',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PhotoLink $photo_link)
    {
        $request->validate([
            'order_by'=>'required|numeric',
            'name' => 'required',
            'image' => 'mimes:jpeg,png|max:5120',
            'url' => 'required',
        ]);

        $att['name'] = $request->input('name');
        $att['url'] = $request->input('url');
        $att['order_by'] = $request->input('order_by');
        $att['photo_type_id'] = $request->input('photo_type_id');

        $photo_link->update($att);

        $school_code = school_code();
        $folder = 'public/'. $school_code .'/photo_links';

        //處理檔案上傳
        if ($request->hasFile('image')) {
            //先刪之前的
            if(file_exists(storage_path('app/'.$folder.'/'.$photo_link->image))){
                unlink(storage_path('app/'.$folder.'/'.$photo_link->image));
            }
            

            $image = $request->file('image');
            $info = [
                'original_filename' => $image->getClientOriginalName(),
                'extension' => $image->getClientOriginalExtension(),
            ];

            $image_name = $photo_link->id.'.'.$info['extension'];
            $image->storeAs($folder,$image_name);

            $att2['image'] = $image_name;
            $photo_link->update($att2);

            Image::make(storage_path('app/'.$folder.'/'.$image_name))->heighten(500)
                ->save(storage_path('app/'.$folder.'/'.$image_name));
        }

        echo "<body onload='opener.location.reload();window.close();'>";
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PhotoLink $photo_link)
    {
        $school_code = school_code();
        $folder = 'public/'. $school_code .'/photo_links';
        if(file_exists(storage_path('app/'.$folder.'/'.$photo_link->image))){
            unlink(storage_path('app/'.$folder.'/'.$photo_link->image));
        }
        
        $photo_link->delete();
        return redirect()->route('photo_links.index');
    }
}
