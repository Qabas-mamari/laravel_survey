<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Http\Requests\StoreSurveyRequest;
use App\Http\Requests\UpdateSurveyRequest;
use Illuminate\Http\Request;
use App\Http\Resources\SurveyResource;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = $request->user();
        return SurveyResource::collection(Survey::where('user_id', $user->id)->orderBy('created_at', 'DESC')->paginate(10));
        //return SurveyResource::collection(Survey::where('user_id', $user->id)->paginate());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSurveyRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSurveyRequest $request)
    {
        // 1. validate data, 2. create survey
        $data = $request->validated();

        //check if image was given and save on local file system
        if (isset($data['image'])) {
            $relativePath = $this->saveImage($data['image']);
            // after cleaned the path, change the true image path
            $data['image'] = $relativePath;
        }
        $survey = Survey::create($data);
        return new SurveyResource($survey);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function show(Survey $survey, Request $request)
    {
        // check if user have a permission to return servery data
        $user = $request->user();
        if($user->id !== $survey->user_id){
            return abort(403, 'Unauthorized action');
        }
        return new SurveyResource($survey);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSurveyRequest  $request
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSurveyRequest $request, Survey $survey)
    {
        // 1. validate data, 2. create survey
        $data = $request->validated();

        //check if image was given and save on local file system
        if (isset($data['image'])) {
            $relativePath = $this->saveImage($data['image']);
            // after cleaned the path, change the true image path
            $data['image'] = $relativePath;

            // if there is an old image, delete it:
                if ($survey->image) {
                    $absolutePath = public_path($survey->image);
                    File::delete($absolutePath);
                }
        }

        //Update survey in the database
        $survey->update($data);
        return new SurveyResource($survey);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Survey  $survey
     * @return \Illuminate\Http\Response
     */
    public function destroy(Survey $survey, Request $request)
    {
        // check if user have a permission to delete servery data
        $user = $request->user();
        if($user->id !== $survey->user_id){
            return abort(403, 'Unauthorized action');
        }
        $survey->delete();

        // delete image
        if ($survey->image) {
            $absolutePath = public_path($survey->image);
            File::delete($absolutePath);
        }
        return response('', 204);
    }


    private function saveImage($image)
    {
        // Check if image is valid base64 string
        if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
            // Take out the base64 encoded text without mime type
            $image = substr($image, strpos($image, ',') + 1);
            // Get file extension
            $type = strtolower($type[1]); // jpg, png, gif

            // Check if file is an image
            if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) {
                throw new \Exception('invalid image type');
            }
            $image = str_replace(' ', '+', $image);
            $image = base64_decode($image);

            if ($image === false) {
                throw new \Exception('base64_decode failed');
            }
        } else {
            throw new \Exception('did not match data URI with image data');
        }

        $dir = 'images/';
        $file = Str::random() . '.' . $type;
        $absolutePath = public_path($dir);
        $relativePath = $dir . $file;
        if (!File::exists($absolutePath)) {
            File::makeDirectory($absolutePath, 0755, true);
        }
        file_put_contents($relativePath, $image);

        return $relativePath;
    }


    /**
     * @return $relativePath
     */
    // private function saveImage($image)
    // {
    //     // image: "data:image/jpeg;base64,/9j/4AAQSkZJ...AAA//Z"

    //     // check if $image value is valid in base64 string and substract the requird info and save it in $type
    //     if (preg_match('/^data:image\/(\w+);base64,/', $image, $type)) {
    //         // tack out the base64 text without image type(take out every thing after comma)
    //         $image = substr($image, strpos($image, ',') + 1); //strpos: specify the postion of comma

    //         // get file extenstion => type[1] = (\w+)
    //         $type = strtolower($type[1]); //jpg, png, gif

    //         if (!in_array($type, ['jpg', 'jpeg', 'gif', 'png'])) { // check if file is on image vaild extenstion
    //             throw new Exception("Invalid image type extenstion");
    //         }

    //         $image = str_replace(' ', '+', $image); //replace space with +
    //         $image = base64_decode($image);

    //         if ($image === false) {
    //             throw new Exception("base64 decode falid");
    //         }

    //         $dir = 'image/'; // save images in correct direction
    //         $file = Str::random().'.'.$type; //image file name

    //         // Image Paths
    //         $absoultePath = public_path($dir); // check if directory is exists
    //         $relativePath = $dir.$file;

    //         if (!File::exists($absoultePath)) { //if directory not exist
    //             File::makeDirection($absoultePath, 0775, true); //create directory
    //         }

    //         file_get_contents($relativePath, true); //return
    //         return $relativePath;

    //     }else{
    //         throw new Exception("did not match data URL with image data");
    //     }

    // }
}
