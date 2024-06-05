<?php

namespace App\Http\Controllers;

use App\PhotoLink;
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
        return view('photo_links.index',compact('photo_links'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $photo_links = PhotoLink::orderBy('order_by')
            ->paginate(24);
        return view('photo_links.show',compact('photo_links'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(PhotoLink $photo_link)
    {
        $data = [
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

        $photo_link->update($att);

        $school_code = school_code();
        $folder = 'public/'. $school_code .'/photo_links';

        //處理檔案上傳
        if ($request->hasFile('image')) {
            //先刪之前的
            unlink(storage_path('app/'.$folder.'/'.$photo_link->image));

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
