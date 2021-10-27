<?php

namespace App\Http\Controllers;

use App\Models\DB2\Video;
use App\Models\Video as ModelsVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DB2VideosController extends Controller
{
    public function getVideos()
    {
        $videos = Video::all();
        return $videos;
    }

    public function migrateVideos()
    {
        $status = false;
        $result = null;
        DB::beginTransaction();
        try {
            $videos = Video::all();
            foreach ($videos as $key => $value) {
                ModelsVideo::create([
                    'title' => $value->medico,
                    'description' => $value->descripcion,
                    'image' => "https://grupocigo.com/uploads/min/$value->miniatura",
                    'video_url' => "https://grupocigo.com/uploads/video/$value->video",
                    'status_id' => 1
                ]);
            }

            $status = true;
            DB::commit();
        } catch (\Throwable $th) {
            $result = $th->getMessage();
            DB::rollback();
        }if($status){
            return [
                'transaction' => ['status' => true],
                'code' => 200,
                'data' => 'Hecho'
            ];
        }else{
            return [
                'transaction' => ['status' => false],
                'code' => 500,
                'data' => $result
            ];
        }
        
        return $videos;
    }

    public function getVideosDB1()
    {
        $videos = ModelsVideo::all();
        return [
            'transaction' => ['status' => true],
            'code' => 200,
            'data' => $videos
        ];

    }
}
