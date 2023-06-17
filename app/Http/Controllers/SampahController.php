<?php

namespace App\Http\Controllers;

use App\Models\Sampah;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use Exception;

class SampahController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $sampah = Sampah::all();

        $search =$request->search_kepala_keluarga;

        $limit = $request->limit;
        $sampah = Sampah::where('kepala_keluarga', 'LIKE', '%'.$search.'%')->limit($limit)->get();

        if ($sampah) {
            return ApiFormatter::createApi(200, 'success', $sampah);
        }else {
            return ApiFormatter::createApi(400, 'failed');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'kepala_keluarga' => 'required',
                'no_rumah' => 'required',
                'rt_rw' => 'required',
                'total_karung_sampah' => 'required',
                'tanggal_pengangkutan' => 'required',
            ]);

            $total_karung_sampah = $request['total_karung_sampah'];

            if ($total_karung_sampah > 3) {
                $kriteria = "collapse";
            }else {
                $kriteria = "standar";
            }

            $sampah = Sampah::create([
                'kepala_keluarga' => $request->kepala_keluarga,
                'no_rumah' => $request->no_rumah,
                'rt_rw' => $request->rt_rw,
                'total_karung_sampah' => $request->total_karung_sampah,
                'kriteria' => $kriteria,
                'tanggal_pengangkutan' => $request->tanggal_pengangkutan,
            ]);

            $getDataSaved = Sampah::where('id', $sampah->id)->first();

            if ($getDataSaved) {
                return ApiFormatter::createApi(200, 'success', $getDataSaved);
            }else {
                return ApiFormatter::createApi(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createApi(400, 'error', $error->getMessage());
        }
    }
    
    public function createToken()
    {
        return csrf_token();
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $sampah = Sampah::find($id);
            if ($sampah) {
                return ApiFormatter::createAPI(200, 'success', $sampah);
            }else {
                return ApiFormatter::createAPI(400, 'failed',);
            }
        } catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sampahs $sampahs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'kepala_keluarga' => 'required',
                'no_rumah' => 'required',
                'rt_rw' => 'required',
                'total_karung_sampah' => 'required',
                'tanggal_pengangkutan' => 'required',
            ]);

            $sampah = Sampah::findOrFail($id);
            $sampah->update([
                'kepala_keluarga' => $request->kepala_keluarga,
                'no_rumah' => $request->no_rumah,
                'rt_rw' => $request->rt_rw,
                'total_karung_sampah' => $request->total_karung_sampah,
                'tanggal_pengangkutan' => $request->tanggal_pengangkutan,
            ]);

            $newData = Sampah::where('id', $sampah->id)->first();
            if ($newData) {
                return ApiFormatter::createAPI(200, 'success', $newData);
            }else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        } 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $sampah = Sampah::find($id);
            $cekBerhasil = $sampah->delete();
            if ($cekBerhasil) {
                return ApiFormatter::createAPI(200, 'success', 'Data anda terhapus!');
            }else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        } 
    }


    public function trash()
{
    try {
        $sampah = Sampah::onlyTrashed()->get();
        if ($sampah) {
            return ApiFormatter::createAPI(200, 'success', $sampah);
        }else {
            return ApiFormatter::createAPI(400, 'failed');
        }
    } catch (Exception $error) {
        return ApiFormatter::createAPI(400, 'error', $error->getMessage);
    }
}

    public function restore($id)
    {
        try {
            $sampah = Sampah::onlyTrashed()->where('id', $id);
            $sampah->restore();
            $dataRestore = Sampah::where('id', $id)->first();
            if ($dataRestore) {
                return ApiFormatter::createAPI(200, 'success', $dataRestore);
            }else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        } catch (Exception $error) {
            return ApiFormatter::createAPI(400, 'error', $error->getMessage);
        }
    }


    public function permanentDelete($id)
    {
        try {
            $sampah = Sampah::onlyTrashed()->where('id', $id);
            $proses = $sampah->forceDelete();
            if($proses) {
                return ApiFormatter::createAPI(200, 'success', 'Data dihapus permanen!');
            }else {
                return ApiFormatter::createAPI(400, 'failed');
            }
        } catch (\Exception $error) {    
            return ApiFormatter::createAPI(400, 'error', $error->getMessage());
        }
    }
}