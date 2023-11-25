<?php
namespace App\Http\Controllers;

use App\Models\Drive;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;




class DriveController extends Controller
{





    public function MyFiles() {
        $user_id = auth()->user()->id;
        $drive = Drive::where("user_id","=",$user_id)->get();
        return view("drives\index" ,compact("drive"));
    }


    public function publicFile() {
        $drive = Drive::where("status","=","Public")->get();
        return view('drives.publicFile' ,compact("drive"));
    }


    public function allFile()
    {
        $drives = Drive::all();

        return view("drives/allFiles" , compact("drives"));
    }


    public function create()
    {
        return view("drives\create");
    }


    public function store(Request $request)
    {
        $request->validate([
          'title'=>"required | min:3 | max:20 | string",
          'description'=> "required ",
          'file'=>"required | file |mimes:png,jpg,jpeg"
        ]);
        $drive = new Drive();
        $drive->title = $request->title;
        $drive->description = $request->description;
        $file_data = $request->file("file");
        $drive_name = time() . $file_data->getClientOriginalName();
        $drive_extension = $file_data->getClientOriginalExtension();
        $location = public_path("./upload");
        $file_data->move($location , $drive_name);
        $drive->file = $drive_name;
        $drive->extension = $drive_extension;
        $drive->status = $request->status;
        $drive->user_id = auth()->user()->id;
        $drive->save();
        return redirect()->back()->with("Done" , "Insert Successfully Thank You");
    }

    public function show($id)
    {
        $drive = DB::table('drivewithusers')->where('DriveId' , $id)->first();

        return view('drives\show' , compact("drive"));
    }


    public function edit($id)
    {
        $drive = Drive::find($id);
        return view('drives\edit' , compact("drive"));
    }


    public function update(Request $request, $id)
    {
        $drive = Drive::find($id);
        $drive->title = $request->title;
        $drive->description = $request->description;
        $file_data = $request->file("file");
        if ($file_data == null) {
            $drive_name = $drive->file;
            $drive_extension = $drive->extension;
        }else{
            $filePath = public_path("/upload/$drive->file");
            unlink($filePath);


            $drive_name = time() . $file_data->getClientOriginalName();
            $drive_extension = $file_data->getClientOriginalExtension();
            $location = public_path("/upload");
            $file_data->move($location , $drive_name);
        }
        $drive->file = $drive_name;
        $drive->extension = $drive_extension;
        $drive->status = $request->status;
        $drive->user_id = auth()->user()->id;

        $drive->save();

        return redirect()->route("drives\index")->with("Done" , "Update Successfully Thank You");
    }
    public function destroy($id)
    {
        $drive = Drive::where("id",$id)->first();
        $filePath = public_path("/upload/$drive->file");
        unlink($filePath);
        $drive->delete();

        return redirect()->back()->with("Done" , "Delete Successfully !");
    }



    public function download($id)
    {
        $drive = Drive::where("id",$id)->first();
        $filePath = public_path("/upload/$drive->file");

        return response()->download($filePath);
    }

    public function changeStatus($id) {
        $drive = Drive::find($id);
        if ($drive->status == 'Private') {
            $drive->status = "Public";
            $drive->save();

            return redirect()->back()->with("Done" , "Your File Public Now");
        } else {
            $drive->status = "Private";
            $drive->save();

            return redirect()->back()->with("Done" , "Your File Private Now ");
        }

    }
}
