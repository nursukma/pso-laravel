<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\App;
use App\Imports\ModelImport;
use App\Imports\ModelImportAsli;
use App\Imports\TargetImport;
use App\Models\ModelVariabel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Lang;

use Illuminate\Support\Carbon;

use PDF;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\Response;

use Symfony\Component\Stopwatch\Stopwatch;
use Illuminate\Support\Str;

class PSOController extends Controller
{
    const populasi = [
        [0.17, 0.93, 0.41, 0.48, 0.76],
        [0.09, 0.05, 0.11, 0.46, 0.29],
        [0.61, 0.18, 0.94, 0.50, 0.96],
        [0.90, 0.81, 0.79, 0.88, 0.80],
        [0.72, 0.20, 0.13, 0.65, 0.41]
        // [0.01, 0.68, 0.55, 0.36, 0.87],
        // [0.71, 0.05, 0.26, 0.16, 0.76],
        // [0.41, 0.37, 0.28, 0.39, 0.88],
        // [0.52, 0.75, 0.07, 0.46, 0.89],
        // [0.51, 0.40, 0.44, 0.06, 0.22]
    ];

    const klasifikasi = [
        [1, 1, 4, 3, 4],
        [3, 1, 4, 4, 4],
        [1, 1, 1, 2, 1],
        [1, 1, 1, 2, 1],
        [1, 1, 1, 2, 2],
        [2, 1, 4, 3, 4],
        [2, 1, 1, 2, 1],
        [1, 1, 1, 2, 4],
        [1, 1, 1, 2, 1],
        [1, 1, 1, 2, 1]
    ];

    const target = [2.54, 3.25, 1.19, 1.19, 1.36, 2.80, 1.45, 1.71, 1.19, 1.19];

    private $data = [[1, 1, 4, 3, 4], [3, 1, 4, 4, 4], [1, 1, 1, 2, 1]];
    private $target = [2.54, 3.25, 1.19];

    public function downloadPdfFile(): BinaryFileResponse
    {
        // return response()->download(public_path('storage/file/contoh.xlsx'));
        $filePath = public_path('storage/file/contoh.xlsx');
        $headers = [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        return Response::download($filePath, 'contoh.xlsx', $headers);
    }

    public function lang()
    {
        if (app()->getLocale() == 'id') {
            App::setLocale('en');
            // dd(app()->getLocale());
            session()->put('locale', 'en');
        } else {
            App::setLocale('id');
            session()->put('locale', 'id');
        }
        return redirect()->back();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $notif = Lang::get('notif.clear_all');

        $request->session()->forget('data');
        $request->session()->forget('target');
        $request->session()->forget('nama_berkas');

        $history = session()->get('history');

        $history['aktivitas'][count($history['aktivitas'])] = "Hapus";

        $history['waktu'][count($history['waktu'])] = Carbon::now()->format('H:i:m');

        $request->session()->put('history', $history);

        return back()->with('message', $notif);
    }

    public function hitung(Request $request)
    {
        set_time_limit(0);

        if (!session()->has('data'))
            return back()->with('error', Lang::get('notif.err1'));

        if (!$request->iter)
            return redirect('/table-data')->with('error', Lang::get('notif.err2'));

        if (!$request->partikel)
            return redirect('/table-data')->with('error', Lang::get('notif.err3'));

        $stopwatch = new Stopwatch();
        $stopwatch->start('Perhitungan');

        $klasifikasi = $request->session()->get('data');
        $target = $request->session()->get('target');

        $populasi = $this->randomPartikel($request->partikel);

        $start = 0;
        $iter = $request->iter;
        // $iter = 100;

        // INISIALISASI VARIABEL //
        // $r1 = [0.42, 0.46];
        // $r2 = [0.03, 0.58];
        $c1 = 2;
        $c2 = 2;

        $hasil_fitness = [];
        $hasil_selisih = [];

        $p_best = [];
        $g_best = 0;

        $velocity = 0;

        $posisi_x = array(array());

        $hasil_velocity = array(array());

        $solusi_akhir = [];

        $total_kali = array(array());
        $arrayCopy = array(array());

        $arrayR = [];

        // $cekSelisih = array(array());
        // ======================================== //

        while ($start < $iter) {
            $b_inersia = 0.9 - ((0.9 - 0.4) / $iter) * $start;

            $r1 = $this->tampil();
            $r2 = $this->tampil();
            // ====================================== //
            if ($start != 0) {
                $arrayCopy = $posisi_x;

                // PERHTIUNGAN PARTIKEL KALI DATA AWAL
                for ($j = 0; $j < count($klasifikasi); $j++) {
                    for ($k = 0; $k < count($arrayCopy); $k++) {
                        $totalSementara = 0;
                        $pembagi = 0;
                        for ($i = 0; $i < count($klasifikasi[$j]); $i++) {
                            $totalSementara += $klasifikasi[$j][$i] * $arrayCopy[$k][$i];
                            $pembagi += $arrayCopy[$k][$i];
                        }
                        $total_kali[$k][$j] = round($totalSementara / $pembagi, 5);
                    }
                }

                // ====================================== //

                // MENGHITUNG SELISIH HASIL KALI DENGAN TARGET
                for ($x = 0; $x < count($total_kali); $x++) {
                    $total_selisih = 0;
                    for ($i = 0; $i < count($total_kali[$x]); $i++) {
                        $total[$x][$i] = abs($total_kali[$x][$i] - $target[$i]);
                        $total_selisih += round(abs($total_kali[$x][$i] - $target[$i]), 5);
                    }
                    $hasil_selisih[$x] = $total_selisih;
                }
                // ====================================== //

                // MENGHITUNG NILAI FITNESS
                for ($x = 0; $x < count($hasil_selisih); $x++) {
                    $hasil_fitness[$x] = round(1 / $hasil_selisih[$x], 5);
                }
                // ================================= //

                // MENCARI GBEST DAN PBEST 
                for ($x = 0; $x < count($hasil_fitness); $x++) {
                    // $max = max($hasil_fitness);
                    // $index_max = array_search(max($hasil_fitness), $hasil_fitness);

                    $max = max($p_best);
                    $index_max = array_search($max, $p_best);

                    if ($p_best[$x] < $hasil_fitness[$x]) {
                        $p_best[$x] = $hasil_fitness[$x];
                    }

                    if ($g_best < $max) {
                        $g_best = $max;
                    }

                    // Random Populasi
                    $solusi_akhir = array("Solusi" => $populasi[$index_max], "GBest" => $g_best, 'iterasi' => $iter, "index" => $index_max);
                }
                // ============================ //

                // MENGHITUNG VELOCITY
                for ($k = 0; $k < count($arrayCopy); $k++) {
                    for ($j = 0; $j < count($arrayCopy[$k]); $j++) {
                        $hasil_velocity[$k][$j] = round(($b_inersia * $hasil_velocity[$k][$j]) +
                            (($c1 * $r1) * ($p_best[$k] - $arrayCopy[$k][$j])) +
                            (($c2 * $r2) * ($g_best - $arrayCopy[$k][$j])), 5);
                    }
                }
                // ============================ //

                // MEMPERBARUI POSISI X
                for ($k = 0; $k < count($hasil_velocity); $k++) {
                    for ($j = 0; $j < count($hasil_velocity[$k]); $j++) {
                        $posisi_x[$k][$j] +=  $hasil_velocity[$k][$j];
                    }
                }
                // ==================== //
            } else {

                // Random populasi
                for ($j = 0; $j < count($klasifikasi); $j++) {
                    for ($k = 0; $k < count($populasi); $k++) {
                        $totalSementara = 0;
                        $pembagi = 0;
                        for ($i = 0; $i < count($klasifikasi[$j]); $i++) {
                            $totalSementara += $klasifikasi[$j][$i] * $populasi[$k][$i];
                            $pembagi += $populasi[$k][$i];
                        }
                        $total_kali[$k][$j] = round($totalSementara / $pembagi, 5);
                    }
                }

                // ====================================== //

                // MENGHITUNG SELISIH HASIL KALI DENGAN TARGET
                for ($x = 0; $x < count($total_kali); $x++) {
                    $total_selisih = 0;
                    for ($i = 0; $i < count($total_kali[$x]); $i++) {
                        $total[$x][$i] = abs($total_kali[$x][$i] - $target[$i]);
                        $total_selisih += round(abs($total_kali[$x][$i] - $target[$i]), 5);
                    }
                    $hasil_selisih[$x] = $total_selisih;
                }
                // ====================================== //

                // MENGHITUNG NILAI FITNESS
                for ($x = 0; $x < count($hasil_selisih); $x++) {
                    if ($hasil_selisih[$x] == 0)
                        return back()->with('warning', Lang::get('notif.err4'));
                    $hasil_fitness[$x] = round(1 / $hasil_selisih[$x], 5);
                }
                // ================================= //

                // MENCARI GBEST DAN PBEST 
                for ($x = 0; $x < count($hasil_fitness); $x++) {
                    $max = max($hasil_fitness);
                    $index_max = array_search(max($hasil_fitness), $hasil_fitness);

                    if (empty($p_best)) {
                        $p_best = $hasil_fitness;
                    }

                    if ($g_best == 0) {
                        $g_best = $max;
                    }

                    // Random Populasi
                    $solusi_akhir = array("Solusi" => $populasi[$index_max], "GBest" => $g_best, 'iterasi' => $iter);
                }
                // ============================ //

                // MENGHITUNG VELOCITY
                for ($k = 0; $k < count($populasi); $k++) {
                    for ($j = 0; $j < count($populasi[$k]); $j++) {
                        $hasil_velocity[$k][$j] = round(($b_inersia * $velocity) +
                            (($c1 * $r1) * ($p_best[$k] - $populasi[$k][$j])) +
                            (($c2 * $r2) * ($g_best - $populasi[$k][$j])), 5);
                    }
                }
                // ============================ //

                // MEMPERBARUI POSISI X
                for ($k = 0; $k < count($populasi); $k++) {
                    for ($j = 0; $j < count($populasi[$k]); $j++) {
                        $posisi_x[$k][$j] = $populasi[$k][$j] + $hasil_velocity[$k][$j];
                    }
                }
                // ==================== //
            }
            // ===================================== //

            array_push($arrayR, $g_best);
            $start++;
        };

        $history = session()->get('history');
        $history['aktivitas'][count($history['aktivitas'])] = "Hitung";
        $history['waktu'][count($history['waktu'])] = Carbon::now()->format('H:i:m');

        $request->session()->put('history', $history);

        $request->session()->put('g_best', $arrayR);
        $request->session()->put('solusi_akhir', $solusi_akhir);

        // $request->session()->put('posisi', $posisi_x);
        $event = $stopwatch->stop('Perhitungan');
        $exeTime = $event->getDuration() / 1000;

        $request->session()->put('exeTime', $exeTime);



        return view('hasil-data.index')->with(['data' => $solusi_akhir]);
        // return (["r1" => $r1, "r2" => $r2]);
        // dd($populasi);
    }


    public function tampil()
    {
        return round(0.08 + mt_rand() / mt_getrandmax() * (1.0 - 0.08), 5);
    }

    public function import(Request $request)
    {
        if (!$request->hasFile('file') && ($request->bdv == 0 || $request->water == 0 || $request->acidity == 0 || $request->ift == 0 || $request->color == 0 || $request->target == 0)) {

            return back()->with('error', Lang::get('notif.err5'));
        }

        if (!$request->hasFile('file') && (!$request->bdv || !$request->water || !$request->acidity || !$request->ift || !$request->color || !$request->target)) {

            return back()->with('error', Lang::get('notif.err6'));
        }


        if (session()->has('data') && session()->has('target')) {
            if (!$request->hasFile('file') && $request->bdv && $request->water && $request->acidity && $request->ift && $request->color && $request->target) {
                $data =  $request->session()->get('data');
                $target = $request->session()->get('target');

                $jmlData = count($data);

                if ($jmlData > 0) {
                    $data[$jmlData] = [0 => $request->bdv, 1 => $request->water, 2 => $request->acidity, 3 => $request->ift, 4 => $request->color];
                    $target[$jmlData] = $request->target;
                } else {
                    $data[] = [0 => $request->bdv, 1 => $request->water, 2 => $request->acidity, 3 => $request->ift, 4 => $request->color];
                    $target[] = $request->target;
                }

                $this->data = $data;

                $history = session()->get('history');
                $history['aktivitas'][count($history['aktivitas'])] = "Input";
                $history['waktu'][count($history['waktu'])] = Carbon::now()->format('H:i:m');

                $request->session()->put('history', $history);

                $request->session()->put('data', $data);
                $request->session()->put('target', $target);

                // dd($request->session()->get('target'));
                // return view('table-data.index')->with(['data' => $data]);
                return back()->with('message', Lang::get('notif.succ_add'));
            }

            if ($request->hasFile('file') && (!$request->bdv || !$request->water || !$request->acidity || !$request->ift || !$request->color || !$request->target)) {
                // import excel
                $fix = Excel::toArray(new ModelImport, request()->file('file'));

                for ($i = 0; $i < count($fix); $i++) {
                    for ($j = 0; $j < count($fix[$i]); $j++) {
                        for ($k = 0; $k < count($fix[$i][$j]); $k++) {
                            if ($k != 5) {
                                $this->data[$j][$k] = $fix[$i][$j][$k];
                            } else {
                                $this->target[$j] = $fix[$i][$j][$k];
                            }
                        }
                    }
                }

                $data = array_merge($request->session()->get('data'), $this->data);
                $target = array_merge($request->session()->get('target'), $this->target);

                $history = session()->get('history');
                $history['aktivitas'][count($history['aktivitas'])] = "Upload";
                $history['waktu'][count($history['waktu'])] = Carbon::now()->format('H:i:m');

                $request->session()->put('history', $history);

                $request->session()->put('data', $data);
                $request->session()->put('target', $target);

                // dd($request->session()->get('data'));
                // return view('table-data.index')->with(['data' => $data]);
                return back()->with('message', Lang::get('notif.succ_add'));
            }

            if ($request->hasFile('file') && $request->bdv && $request->water && $request->acidity && $request->ift && $request->color  && $request->target) {
                // import excel
                $arr = [];
                $fix = Excel::toArray(new ModelImport, request()->file('file'));
                // foreach ($fix as $data1) {
                //     $arr = $data1;
                // }

                for ($i = 0; $i < count($fix); $i++) {
                    for ($j = 0; $j < count($fix[$i]); $j++) {
                        for ($k = 0; $k < count($fix[$i][$j]); $k++) {
                            if ($k != 5) {
                                $this->data[$j][$k] = $fix[$i][$j][$k];
                            } else {
                                $this->target[$j] = $fix[$i][$j][$k];
                            }
                        }
                    }
                }

                $history = session()->get('history');
                $history['aktivitas'][count($history['aktivitas'])] = "Upload";
                $history['aktivitas'][count($history['aktivitas'])] = "Input";
                $history['waktu'][count($history['waktu']) + 1] = Carbon::now()->format('H:i:m');
                $history['waktu'][count($history['waktu']) + 1] = Carbon::now()->format('H:i:m');

                $request->session()->put('history', $history);

                $data = array_merge($request->session()->get('data'), $this->data);
                $target = array_merge($request->session()->get('target'), $this->target);

                $data[count($data)] = [0 => $request->bdv, 1 => $request->water, 2 => $request->acidity, 3 => $request->ift, 4 => $request->color];
                $target[count($target)] = $request->target;

                $request->session()->put('data', $data);
                $request->session()->put('target', $target);

                // dd($request->session()->get('target'));
                // return view('table-data.index')->with(['data' => $data]);
                return back()->with('message', Lang::get('notif.succ_add'));
            }
        } else {
            if ($request->hasFile('file') && (!$request->bdv || !$request->water || !$request->acidity || !$request->ift || !$request->color || !$request->target)) {
                // import excel
                $imdata = new ModelImport;
                $fix = Excel::toArray($imdata, request()->file('file'));

                for ($i = 0; $i < count($fix); $i++) {
                    for ($j = 0; $j < count($fix[$i]); $j++) {
                        for ($k = 0; $k < count($fix[$i][$j]); $k++) {
                            if ($k != 5) {
                                $this->data[$j][$k] = $fix[$i][$j][$k];
                            } else {
                                $this->target[$j] = $fix[$i][$j][$k];
                            }
                        }
                    }
                }

                $history = session()->get('history');
                $history['aktivitas'][count($history['aktivitas'])] = "Upload";
                $history['waktu'][count($history['waktu'])] = Carbon::now()->format('H:i:m');

                $request->session()->put('history', $history);

                $request->session()->put('nama_berkas', $request->file('file')->getClientOriginalName());
                $request->session()->put('data', $this->data);
                $request->session()->put('target', $this->target);


                // dd(session()->get('data'));
                // return $fix;
                return back()->with('message', Lang::get('notif.succ_add'));
            }

            if ($request->hasFile('file') && $request->bdv && $request->water && $request->acidity && $request->ift && $request->color && $request->target) {
                // import excel
                $fix = Excel::toArray(new ModelImport, request()->file('file'));

                for ($i = 0; $i < count($fix); $i++) {
                    for ($j = 0; $j < count($fix[$i]); $j++) {
                        for ($k = 0; $k < count($fix[$i][$j]); $k++) {
                            if ($k != 5) {
                                $this->data[$j][$k] = $fix[$i][$j][$k];
                            } else {
                                $this->target[$j] = $fix[$i][$j][$k];
                            }
                        }
                    }
                }

                $this->data[count($this->data)] = [0 => $request->bdv, 1 => $request->water, 2 => $request->acidity, 3 => $request->ift, 4 => $request->color];
                $this->target[count($this->target)] = $request->target;

                $history = session()->get('history');
                $history['aktivitas'][count($history['aktivitas'])] = "Upload";
                $history['aktivitas'][count($history['aktivitas'])] = "Input";
                $history['waktu'][count($history['waktu']) + 1] = Carbon::now()->format('H:i:m');
                $history['waktu'][count($history['waktu']) + 1] = Carbon::now()->format('H:i:m');

                $request->session()->put('history', $history);

                $request->session()->put('data', $this->data);
                $request->session()->put('target', $this->target);

                // dd($request->session()->get('target'));
                // return view('table-data.index')->with(['data' => $this->data]);
                return back()->with('message', Lang::get('notif.succ_add'));
            }

            if (!$request->hasFile('file') && $request->bdv && $request->water && $request->acidity && $request->ift && $request->color && $request->target) {
                $this->data[] = [0 => $request->bdv, 1 => $request->water, 2 => $request->acidity, 3 => $request->ift, 4 => $request->color];
                $this->target[] = $request->target;

                $history = session()->get('history');
                $history['aktivitas'][count($history['aktivitas'])] = "Input";
                $history['waktu'][count($history['waktu'])] = Carbon::now()->format('H:i:m');

                $request->session()->put('history', $history);

                $request->session()->put('data', $this->data);
                $request->session()->put('target', $this->target);

                // dd($request->session()->get('target'));
                // return view('table-data.index')->with(['data' => $this->data]);
                return back()->with('message', Lang::get('notif.succ_add'));
            }
        }
        // $request->session()->put('data', $this->data);
        // $request->session()->put('target', $this->target);
        // return back()->with('message', "OKE");
    }

    public function import_asli(Request $request)
    {
        if (!$request->hasFile('file_asli') && ($request->bdv_asli == 0 || $request->water_asli == 0 || $request->acidity_asli == 0 || $request->ift_asli == 0 || $request->color_asli == 0 || $request->target_asli == 0)) {

            return back()->with('error', Lang::get('notif.err5'));
        }

        if (!$request->hasFile('file_asli') && (!$request->bdv_asli || !$request->water_asli || !$request->acidity_asli || !$request->ift_asli || !$request->color_asli || !$request->target_asli)) {

            return back()->with('error', Lang::get('notif.err6'));
        }


        if (session()->has('data') && session()->has('target')) {
            if (!$request->hasFile('file_asli') && $request->bdv_asli && $request->water_asli && $request->acidity_asli && $request->ift_asli && $request->color_asli && $request->target_asli) {
                $data =  $request->session()->get('data');
                $target = $request->session()->get('target');

                $jmlData = count($data);

                $bdv_asli = 0;
                $water_asli = 0;
                $acidity_asli = 0;
                $ift_asli = 0;
                $color_asli = 0;

                if ($request->bdv_asli >= 0 && $request->bdv_asli < 40) {
                    $bdv_asli = 4;
                }
                if ($request->bdv_asli >= 40 && $request->bdv_asli < 45) {
                    $bdv_asli = 3;
                }
                if ($request->bdv_asli >= 45 && $request->bdv_asli < 50) {
                    $bdv_asli = 2;
                }
                if ($request->bdv_asli >= 50) {
                    $bdv_asli = 1;
                }

                if ($request->water_asli >= 0 && $request->water_asli < 20) {
                    $water_asli = 1;
                }
                if ($request->water_asli >= 20 && $request->water_asli < 25) {
                    $water_asli = 2;
                }
                if ($request->water_asli >= 25 && $request->water_asli < 30) {
                    $water_asli = 3;
                }
                if ($request->water_asli >= 30) {
                    $water_asli = 4;
                }

                if ($request->acidity_asli >= 0 && $request->acidity_asli < 0.1) {
                    $acidity_asli = 1;
                }
                if ($request->acidity_asli >= 0.1 && $request->acidity_asli < 0.15) {
                    $acidity_asli = 2;
                }
                if ($request->acidity_asli >= 0.15 && $request->acidity_asli < 0.2) {
                    $acidity_asli = 3;
                }
                if ($request->acidity_asli >= 0.2) {
                    $acidity_asli = 4;
                }

                if ($request->ift_asli >= 0 && $request->ift_asli < 20) {
                    $ift_asli = 4;
                }
                if ($request->ift_asli >= 20 && $request->ift_asli < 25) {
                    $ift_asli = 3;
                }
                if ($request->ift_asli >= 25 && $request->ift_asli < 35) {
                    $ift_asli = 2;
                }
                if ($request->ift_asli >= 35) {
                    $ift_asli = 1;
                }

                if ($request->color_asli >= 0 && $request->color_asli < 1.5) {
                    $color_asli = 1;
                }
                if ($request->color_asli >= 1.5 && $request->color_asli < 2) {
                    $color_asli = 2;
                }
                if ($request->color_asli >= 2 && $request->color_asli < 2.5) {
                    $color_asli = 3;
                }
                if ($request->color_asli >= 2.5) {
                    $color_asli = 4;
                }

                if ($jmlData > 0) {
                    $data[$jmlData] = [0 => $bdv_asli, 1 => $water_asli, 2 => $acidity_asli, 3 => $ift_asli, 4 => $color_asli];
                    $target[$jmlData] = $request->target_asli;
                } else {
                    $data[] = [0 => $bdv_asli, 1 => $water_asli, 2 => $acidity_asli, 3 => $ift_asli, 4 => $color_asli];
                    $target[] = $request->target_asli;
                }

                $this->data = $data;

                $history = session()->get('history');
                $history['aktivitas'][count($history['aktivitas'])] = "Input";
                $history['waktu'][count($history['waktu'])] = Carbon::now()->format('H:i:m');

                $request->session()->put('history', $history);

                $request->session()->put('data', $data);
                $request->session()->put('target', $target);

                return back()->with('message', Lang::get('notif.succ_add'));
            }

            if ($request->hasFile('file_asli') && (!$request->bdv_asli || !$request->water_asli || !$request->acidity_asli || !$request->ift_asli || !$request->color_asli || !$request->target_asli)) {
                // import excel
                $fix = Excel::toArray(new ModelImportAsli, request()->file('file_asli'));

                for ($i = 0; $i < count($fix); $i++) {
                    for ($j = 0; $j < count($fix[$i]); $j++) {
                        for ($k = 0; $k < count($fix[$i][$j]); $k++) {
                            if ($k != 5) {
                                // $this->data[$j][$k] = $fix[$i][$j][$k];
                                if ($k == 0) {
                                    if ($fix[$i][$j][$k] >= 0 && $fix[$i][$j][$k] < 40) {
                                        $this->data[$j][$k] = 4;
                                    }
                                    if ($fix[$i][$j][$k] >= 40 && $fix[$i][$j][$k] < 45) {
                                        $this->data[$j][$k] = 3;
                                    }
                                    if ($fix[$i][$j][$k] >= 45 && $fix[$i][$j][$k] < 50) {
                                        $this->data[$j][$k] = 2;
                                    }
                                    if ($fix[$i][$j][$k] >= 50) {
                                        $this->data[$j][$k] = 1;
                                    }
                                }
                                if ($k == 1) {
                                    if ($fix[$i][$j][$k] >= 0 && $fix[$i][$j][$k] < 20) {
                                        $this->data[$j][$k] = 1;
                                    }
                                    if ($fix[$i][$j][$k] >= 20 && $fix[$i][$j][$k] < 25) {
                                        $this->data[$j][$k] = 2;
                                    }
                                    if ($fix[$i][$j][$k] >= 25 && $fix[$i][$j][$k] < 30) {
                                        $this->data[$j][$k] = 3;
                                    }
                                    if ($fix[$i][$j][$k] >= 30) {
                                        $this->data[$j][$k] = 4;
                                    }
                                }
                                if ($k == 2) {
                                    if ($fix[$i][$j][$k] >= 0 && $fix[$i][$j][$k] < 0.1) {
                                        $this->data[$j][$k] = 1;
                                    }
                                    if ($fix[$i][$j][$k] >= 0.1 && $fix[$i][$j][$k] < 0.15) {
                                        $this->data[$j][$k] = 2;
                                    }
                                    if ($fix[$i][$j][$k] >= 0.15 && $fix[$i][$j][$k] < 0.2) {
                                        $this->data[$j][$k] = 3;
                                    }
                                    if ($fix[$i][$j][$k] >= 0.2) {
                                        $this->data[$j][$k] = 4;
                                    }
                                }
                                if ($k == 3) {
                                    if ($fix[$i][$j][$k] >= 0 && $fix[$i][$j][$k] < 20) {
                                        $this->data[$j][$k] = 4;
                                    }
                                    if ($fix[$i][$j][$k] >= 20 && $fix[$i][$j][$k] < 25) {
                                        $this->data[$j][$k] = 3;
                                    }
                                    if ($fix[$i][$j][$k] >= 25 && $fix[$i][$j][$k] < 35) {
                                        $this->data[$j][$k] = 2;
                                    }
                                    if ($fix[$i][$j][$k] >= 35) {
                                        $this->data[$j][$k] = 1;
                                    }
                                }
                                if ($k == 4) {
                                    if ($fix[$i][$j][$k] >= 0 && $fix[$i][$j][$k] < 1.5) {
                                        $this->data[$j][$k] = 1;
                                    }
                                    if ($fix[$i][$j][$k] >= 1.5 && $fix[$i][$j][$k] < 2) {
                                        $this->data[$j][$k] = 2;
                                    }
                                    if ($fix[$i][$j][$k] >= 2 && $fix[$i][$j][$k] < 2.5) {
                                        $this->data[$j][$k] = 3;
                                    }
                                    if ($fix[$i][$j][$k] >= 2.5) {
                                        $this->data[$j][$k] = 4;
                                    }
                                }
                            } else {
                                $this->target[$j] = $fix[$i][$j][$k];
                            }
                        }
                    }
                }

                $data = array_merge($request->session()->get('data'), $this->data);
                $target = array_merge($request->session()->get('target'), $this->target);

                $history = session()->get('history');
                $history['aktivitas'][count($history['aktivitas'])] = "Upload";
                $history['waktu'][count($history['waktu'])] = Carbon::now()->format('H:i:m');

                $request->session()->put('history', $history);

                $request->session()->put('data', $data);
                $request->session()->put('target', $target);
                $request->session()->put('nama_berkas', $request->file('file_asli')->getClientOriginalName());

                return back()->with('message', Lang::get('notif.succ_add'));
            }

            if ($request->hasFile('file_asli') && $request->bdv_asli && $request->water_asli && $request->acidity_asli && $request->ift_asli && $request->color_asli  && $request->target_asli) {
                // import excel
                $fix = Excel::toArray(new ModelImportAsli, request()->file('file_asli'));

                for ($i = 0; $i < count($fix); $i++) {
                    for ($j = 0; $j < count($fix[$i]); $j++) {
                        for ($k = 0; $k < count($fix[$i][$j]); $k++) {
                            if ($k != 5) {
                                // $this->data[$j][$k] = $fix[$i][$j][$k];
                                if ($k == 0) {
                                    if ($fix[$i][$j][$k] >= 0 && $fix[$i][$j][$k] < 40) {
                                        $this->data[$j][$k] = 4;
                                    }
                                    if ($fix[$i][$j][$k] >= 40 && $fix[$i][$j][$k] < 45) {
                                        $this->data[$j][$k] = 3;
                                    }
                                    if ($fix[$i][$j][$k] >= 45 && $fix[$i][$j][$k] < 50) {
                                        $this->data[$j][$k] = 2;
                                    }
                                    if ($fix[$i][$j][$k] >= 50) {
                                        $this->data[$j][$k] = 1;
                                    }
                                }
                                if ($k == 1) {
                                    if ($fix[$i][$j][$k] >= 0 && $fix[$i][$j][$k] < 20) {
                                        $this->data[$j][$k] = 1;
                                    }
                                    if ($fix[$i][$j][$k] >= 20 && $fix[$i][$j][$k] < 25) {
                                        $this->data[$j][$k] = 2;
                                    }
                                    if ($fix[$i][$j][$k] >= 25 && $fix[$i][$j][$k] < 30) {
                                        $this->data[$j][$k] = 3;
                                    }
                                    if ($fix[$i][$j][$k] >= 30) {
                                        $this->data[$j][$k] = 4;
                                    }
                                }
                                if ($k == 2) {
                                    if ($fix[$i][$j][$k] >= 0 && $fix[$i][$j][$k] < 0.1) {
                                        $this->data[$j][$k] = 1;
                                    }
                                    if ($fix[$i][$j][$k] >= 0.1 && $fix[$i][$j][$k] < 0.15) {
                                        $this->data[$j][$k] = 2;
                                    }
                                    if ($fix[$i][$j][$k] >= 0.15 && $fix[$i][$j][$k] < 0.2) {
                                        $this->data[$j][$k] = 3;
                                    }
                                    if ($fix[$i][$j][$k] >= 0.2) {
                                        $this->data[$j][$k] = 4;
                                    }
                                }
                                if ($k == 3) {
                                    if ($fix[$i][$j][$k] >= 0 && $fix[$i][$j][$k] < 20) {
                                        $this->data[$j][$k] = 4;
                                    }
                                    if ($fix[$i][$j][$k] >= 20 && $fix[$i][$j][$k] < 25) {
                                        $this->data[$j][$k] = 3;
                                    }
                                    if ($fix[$i][$j][$k] >= 25 && $fix[$i][$j][$k] < 35) {
                                        $this->data[$j][$k] = 2;
                                    }
                                    if ($fix[$i][$j][$k] >= 35) {
                                        $this->data[$j][$k] = 1;
                                    }
                                }
                                if ($k == 4) {
                                    if ($fix[$i][$j][$k] >= 0 && $fix[$i][$j][$k] < 1.5) {
                                        $this->data[$j][$k] = 1;
                                    }
                                    if ($fix[$i][$j][$k] >= 1.5 && $fix[$i][$j][$k] < 2) {
                                        $this->data[$j][$k] = 2;
                                    }
                                    if ($fix[$i][$j][$k] >= 2 && $fix[$i][$j][$k] < 2.5) {
                                        $this->data[$j][$k] = 3;
                                    }
                                    if ($fix[$i][$j][$k] >= 2.5) {
                                        $this->data[$j][$k] = 4;
                                    }
                                }
                            } else {
                                $this->target[$j] = $fix[$i][$j][$k];
                            }
                        }
                    }
                }

                $bdv_asli = 0;
                $water_asli = 0;
                $acidity_asli = 0;
                $ift_asli = 0;
                $color_asli = 0;

                if ($request->bdv_asli >= 0 && $request->bdv_asli < 40) {
                    $bdv_asli = 4;
                }
                if ($request->bdv_asli >= 40 && $request->bdv_asli < 45) {
                    $bdv_asli = 3;
                }
                if ($request->bdv_asli >= 45 && $request->bdv_asli < 50) {
                    $bdv_asli = 2;
                }
                if ($request->bdv_asli >= 50) {
                    $bdv_asli = 1;
                }

                if ($request->water_asli >= 0 && $request->water_asli < 20) {
                    $water_asli = 1;
                }
                if ($request->water_asli >= 20 && $request->water_asli < 25) {
                    $water_asli = 2;
                }
                if ($request->water_asli >= 25 && $request->water_asli < 30) {
                    $water_asli = 3;
                }
                if ($request->water_asli >= 30) {
                    $water_asli = 4;
                }

                if ($request->acidity_asli >= 0 && $request->acidity_asli < 0.1) {
                    $acidity_asli = 1;
                }
                if ($request->acidity_asli >= 0.1 && $request->acidity_asli < 0.15) {
                    $acidity_asli = 2;
                }
                if ($request->acidity_asli >= 0.15 && $request->acidity_asli < 0.2) {
                    $acidity_asli = 3;
                }
                if ($request->acidity_asli >= 0.2) {
                    $acidity_asli = 4;
                }

                if ($request->ift_asli >= 0 && $request->ift_asli < 20) {
                    $ift_asli = 4;
                }
                if ($request->ift_asli >= 20 && $request->ift_asli < 25) {
                    $ift_asli = 3;
                }
                if ($request->ift_asli >= 25 && $request->ift_asli < 35) {
                    $ift_asli = 2;
                }
                if ($request->ift_asli >= 35) {
                    $ift_asli = 1;
                }

                if ($request->color_asli >= 0 && $request->color_asli < 1.5) {
                    $color_asli = 1;
                }
                if ($request->color_asli >= 1.5 && $request->color_asli < 2) {
                    $color_asli = 2;
                }
                if ($request->color_asli >= 2 && $request->color_asli < 2.5) {
                    $color_asli = 3;
                }
                if ($request->color_asli >= 2.5) {
                    $color_asli = 4;
                }

                $history = session()->get('history');
                $history['aktivitas'][count($history['aktivitas'])] = "Upload";
                $history['aktivitas'][count($history['aktivitas'])] = "Input";
                $history['waktu'][count($history['waktu']) + 1] = Carbon::now()->format('H:i:m');
                $history['waktu'][count($history['waktu']) + 1] = Carbon::now()->format('H:i:m');

                $request->session()->put('history', $history);

                $data = array_merge($request->session()->get('data'), $this->data);
                $target = array_merge($request->session()->get('target'), $this->target);

                $data[count($data)] = [0 => $bdv_asli, 1 => $water_asli, 2 => $acidity_asli, 3 => $ift_asli, 4 => $color_asli];
                $target[count($target)] = $request->target_asli;

                $request->session()->put('data', $data);
                $request->session()->put('target', $target);
                $request->session()->put('nama_berkas', $request->file('file_asli')->getClientOriginalName());

                return back()->with('message', Lang::get('notif.succ_add'));
            }
        } else {
            if ($request->hasFile('file_asli') && (!$request->bdv_asli || !$request->water_asli || !$request->acidity_asli || !$request->ift_asli || !$request->color_asli || !$request->target_asli)) {
                // import excel
                $fix = Excel::toArray(new ModelImportAsli, request()->file('file_asli'));

                for ($i = 0; $i < count($fix); $i++) {
                    for ($j = 0; $j < count($fix[$i]); $j++) {
                        for ($k = 0; $k < count($fix[$i][$j]); $k++) {
                            if ($k != 5) {
                                // $this->data[$j][$k] = $fix[$i][$j][$k];
                                if ($k == 0) {
                                    if ($fix[$i][$j][$k] >= 0 && $fix[$i][$j][$k] < 40) {
                                        $this->data[$j][$k] = 4;
                                    }
                                    if ($fix[$i][$j][$k] >= 40 && $fix[$i][$j][$k] < 45) {
                                        $this->data[$j][$k] = 3;
                                    }
                                    if ($fix[$i][$j][$k] >= 45 && $fix[$i][$j][$k] < 50) {
                                        $this->data[$j][$k] = 2;
                                    }
                                    if ($fix[$i][$j][$k] >= 50) {
                                        $this->data[$j][$k] = 1;
                                    }
                                }
                                if ($k == 1) {
                                    if ($fix[$i][$j][$k] >= 0 && $fix[$i][$j][$k] < 20) {
                                        $this->data[$j][$k] = 1;
                                    }
                                    if ($fix[$i][$j][$k] >= 20 && $fix[$i][$j][$k] < 25) {
                                        $this->data[$j][$k] = 2;
                                    }
                                    if ($fix[$i][$j][$k] >= 25 && $fix[$i][$j][$k] < 30) {
                                        $this->data[$j][$k] = 3;
                                    }
                                    if ($fix[$i][$j][$k] >= 30) {
                                        $this->data[$j][$k] = 4;
                                    }
                                }
                                if ($k == 2) {
                                    if ($fix[$i][$j][$k] >= 0 && $fix[$i][$j][$k] < 0.1) {
                                        $this->data[$j][$k] = 1;
                                    }
                                    if ($fix[$i][$j][$k] >= 0.1 && $fix[$i][$j][$k] < 0.15) {
                                        $this->data[$j][$k] = 2;
                                    }
                                    if ($fix[$i][$j][$k] >= 0.15 && $fix[$i][$j][$k] < 0.2) {
                                        $this->data[$j][$k] = 3;
                                    }
                                    if ($fix[$i][$j][$k] >= 0.2) {
                                        $this->data[$j][$k] = 4;
                                    }
                                }
                                if ($k == 3) {
                                    if ($fix[$i][$j][$k] >= 0 && $fix[$i][$j][$k] < 20) {
                                        $this->data[$j][$k] = 4;
                                    }
                                    if ($fix[$i][$j][$k] >= 20 && $fix[$i][$j][$k] < 25) {
                                        $this->data[$j][$k] = 3;
                                    }
                                    if ($fix[$i][$j][$k] >= 25 && $fix[$i][$j][$k] < 35) {
                                        $this->data[$j][$k] = 2;
                                    }
                                    if ($fix[$i][$j][$k] >= 35) {
                                        $this->data[$j][$k] = 1;
                                    }
                                }
                                if ($k == 4) {
                                    if ($fix[$i][$j][$k] >= 0 && $fix[$i][$j][$k] < 1.5) {
                                        $this->data[$j][$k] = 1;
                                    }
                                    if ($fix[$i][$j][$k] >= 1.5 && $fix[$i][$j][$k] < 2) {
                                        $this->data[$j][$k] = 2;
                                    }
                                    if ($fix[$i][$j][$k] >= 2 && $fix[$i][$j][$k] < 2.5) {
                                        $this->data[$j][$k] = 3;
                                    }
                                    if ($fix[$i][$j][$k] >= 2.5) {
                                        $this->data[$j][$k] = 4;
                                    }
                                }
                            } else {
                                $this->target[$j] = $fix[$i][$j][$k];
                            }
                        }
                    }
                }

                $history = session()->get('history');
                $history['aktivitas'][count($history['aktivitas'])] = "Upload";
                $history['waktu'][count($history['waktu'])] = Carbon::now()->format('H:i:m');

                $request->session()->put('history', $history);


                $request->session()->put('data', $this->data);
                $request->session()->put('target', $this->target);
                $request->session()->put('nama_berkas', $request->file('file_asli')->getClientOriginalName());

                return back()->with('message', Lang::get('notif.succ_add'));
            }

            if ($request->hasFile('file_asli') && $request->bdv_asli && $request->water_asli && $request->acidity_asli && $request->ift_asli && $request->color_asli && $request->target_asli) {
                $bdv_asli = 0;
                $water_asli = 0;
                $acidity_asli = 0;
                $ift_asli = 0;
                $color_asli = 0;

                if ($request->bdv_asli >= 0 && $request->bdv_asli < 40) {
                    $bdv_asli = 4;
                }
                if ($request->bdv_asli >= 40 && $request->bdv_asli < 45) {
                    $bdv_asli = 3;
                }
                if ($request->bdv_asli >= 45 && $request->bdv_asli < 50) {
                    $bdv_asli = 2;
                }
                if ($request->bdv_asli >= 50) {
                    $bdv_asli = 1;
                }

                if ($request->water_asli >= 0 && $request->water_asli < 20) {
                    $water_asli = 1;
                }
                if ($request->water_asli >= 20 && $request->water_asli < 25) {
                    $water_asli = 2;
                }
                if ($request->water_asli >= 25 && $request->water_asli < 30) {
                    $water_asli = 3;
                }
                if ($request->water_asli >= 30) {
                    $water_asli = 4;
                }

                if ($request->acidity_asli >= 0 && $request->acidity_asli < 0.1) {
                    $acidity_asli = 1;
                }
                if ($request->acidity_asli >= 0.1 && $request->acidity_asli < 0.15) {
                    $acidity_asli = 2;
                }
                if ($request->acidity_asli >= 0.15 && $request->acidity_asli < 0.2) {
                    $acidity_asli = 3;
                }
                if ($request->acidity_asli >= 0.2) {
                    $acidity_asli = 4;
                }

                if ($request->ift_asli >= 0 && $request->ift_asli < 20) {
                    $ift_asli = 4;
                }
                if ($request->ift_asli >= 20 && $request->ift_asli < 25) {
                    $ift_asli = 3;
                }
                if ($request->ift_asli >= 25 && $request->ift_asli < 35) {
                    $ift_asli = 2;
                }
                if ($request->ift_asli >= 35) {
                    $ift_asli = 1;
                }

                if ($request->color_asli >= 0 && $request->color_asli < 1.5) {
                    $color_asli = 1;
                }
                if ($request->color_asli >= 1.5 && $request->color_asli < 2) {
                    $color_asli = 2;
                }
                if ($request->color_asli >= 2 && $request->color_asli < 2.5) {
                    $color_asli = 3;
                }
                if ($request->color_asli >= 2.5) {
                    $color_asli = 4;
                }

                // import excel
                $fix = Excel::toArray(new ModelImportAsli, request()->file('file_asli'));

                for ($i = 0; $i < count($fix); $i++) {
                    for ($j = 0; $j < count($fix[$i]); $j++) {
                        for ($k = 0; $k < count($fix[$i][$j]); $k++) {
                            if ($k != 5) {
                                // $this->data[$j][$k] = $fix[$i][$j][$k];
                                if ($k == 0) {
                                    if ($fix[$i][$j][$k] >= 0 && $fix[$i][$j][$k] < 40) {
                                        $this->data[$j][$k] = 4;
                                    }
                                    if ($fix[$i][$j][$k] >= 40 && $fix[$i][$j][$k] < 45) {
                                        $this->data[$j][$k] = 3;
                                    }
                                    if ($fix[$i][$j][$k] >= 45 && $fix[$i][$j][$k] < 50) {
                                        $this->data[$j][$k] = 2;
                                    }
                                    if ($fix[$i][$j][$k] >= 50) {
                                        $this->data[$j][$k] = 1;
                                    }
                                }
                                if ($k == 1) {
                                    if ($fix[$i][$j][$k] >= 0 && $fix[$i][$j][$k] < 20) {
                                        $this->data[$j][$k] = 1;
                                    }
                                    if ($fix[$i][$j][$k] >= 20 && $fix[$i][$j][$k] < 25) {
                                        $this->data[$j][$k] = 2;
                                    }
                                    if ($fix[$i][$j][$k] >= 25 && $fix[$i][$j][$k] < 30) {
                                        $this->data[$j][$k] = 3;
                                    }
                                    if ($fix[$i][$j][$k] >= 30) {
                                        $this->data[$j][$k] = 4;
                                    }
                                }
                                if ($k == 2) {
                                    if ($fix[$i][$j][$k] >= 0 && $fix[$i][$j][$k] < 0.1) {
                                        $this->data[$j][$k] = 1;
                                    }
                                    if ($fix[$i][$j][$k] >= 0.1 && $fix[$i][$j][$k] < 0.15) {
                                        $this->data[$j][$k] = 2;
                                    }
                                    if ($fix[$i][$j][$k] >= 0.15 && $fix[$i][$j][$k] < 0.2) {
                                        $this->data[$j][$k] = 3;
                                    }
                                    if ($fix[$i][$j][$k] >= 0.2) {
                                        $this->data[$j][$k] = 4;
                                    }
                                }
                                if ($k == 3) {
                                    if ($fix[$i][$j][$k] >= 0 && $fix[$i][$j][$k] < 20) {
                                        $this->data[$j][$k] = 4;
                                    }
                                    if ($fix[$i][$j][$k] >= 20 && $fix[$i][$j][$k] < 25) {
                                        $this->data[$j][$k] = 3;
                                    }
                                    if ($fix[$i][$j][$k] >= 25 && $fix[$i][$j][$k] < 35) {
                                        $this->data[$j][$k] = 2;
                                    }
                                    if ($fix[$i][$j][$k] >= 35) {
                                        $this->data[$j][$k] = 1;
                                    }
                                }
                                if ($k == 4) {
                                    if ($fix[$i][$j][$k] >= 0 && $fix[$i][$j][$k] < 1.5) {
                                        $this->data[$j][$k] = 1;
                                    }
                                    if ($fix[$i][$j][$k] >= 1.5 && $fix[$i][$j][$k] < 2) {
                                        $this->data[$j][$k] = 2;
                                    }
                                    if ($fix[$i][$j][$k] >= 2 && $fix[$i][$j][$k] < 2.5) {
                                        $this->data[$j][$k] = 3;
                                    }
                                    if ($fix[$i][$j][$k] >= 2.5) {
                                        $this->data[$j][$k] = 4;
                                    }
                                }
                            } else {
                                $this->target[$j] = $fix[$i][$j][$k];
                            }
                        }
                    }
                }

                $this->data[count($this->data)] = [0 => $bdv_asli, 1 => $water_asli, 2 => $acidity_asli, 3 => $ift_asli, 4 => $color_asli];
                $this->target[count($this->target)] = $request->target_asli;

                $history = session()->get('history');
                $history['aktivitas'][count($history['aktivitas'])] = "Upload";
                $history['aktivitas'][count($history['aktivitas'])] = "Input";
                $history['waktu'][count($history['waktu']) + 1] = Carbon::now()->format('H:i:m');
                $history['waktu'][count($history['waktu']) + 1] = Carbon::now()->format('H:i:m');

                $request->session()->put('history', $history);

                $request->session()->put('data', $this->data);
                $request->session()->put('target', $this->target);
                $request->session()->put('nama_berkas', $request->file('file_asli')->getClientOriginalName());

                return back()->with('message', Lang::get('notif.succ_add'));
            }

            if (!$request->hasFile('file_asli') && $request->bdv_asli && $request->water_asli && $request->acidity_asli && $request->ift_asli && $request->color_asli && $request->target_asli) {

                $bdv_asli = 0;
                $water_asli = 0;
                $acidity_asli = 0;
                $ift_asli = 0;
                $color_asli = 0;

                if ($request->bdv_asli >= 0 && $request->bdv_asli < 40) {
                    $bdv_asli = 4;
                }
                if ($request->bdv_asli >= 40 && $request->bdv_asli < 45) {
                    $bdv_asli = 3;
                }
                if ($request->bdv_asli >= 45 && $request->bdv_asli < 50) {
                    $bdv_asli = 2;
                }
                if ($request->bdv_asli >= 50) {
                    $bdv_asli = 1;
                }

                if ($request->water_asli >= 0 && $request->water_asli < 20) {
                    $water_asli = 1;
                }
                if ($request->water_asli >= 20 && $request->water_asli < 25) {
                    $water_asli = 2;
                }
                if ($request->water_asli >= 25 && $request->water_asli < 30) {
                    $water_asli = 3;
                }
                if ($request->water_asli >= 30) {
                    $water_asli = 4;
                }

                if ($request->acidity_asli >= 0 && $request->acidity_asli < 0.1) {
                    $acidity_asli = 1;
                }
                if ($request->acidity_asli >= 0.1 && $request->acidity_asli < 0.15) {
                    $acidity_asli = 2;
                }
                if ($request->acidity_asli >= 0.15 && $request->acidity_asli < 0.2) {
                    $acidity_asli = 3;
                }
                if ($request->acidity_asli >= 0.2) {
                    $acidity_asli = 4;
                }

                if ($request->ift_asli >= 0 && $request->ift_asli < 20) {
                    $ift_asli = 4;
                }
                if ($request->ift_asli >= 20 && $request->ift_asli < 25) {
                    $ift_asli = 3;
                }
                if ($request->ift_asli >= 25 && $request->ift_asli < 35) {
                    $ift_asli = 2;
                }
                if ($request->ift_asli >= 35) {
                    $ift_asli = 1;
                }

                if ($request->color_asli >= 0 && $request->color_asli < 1.5) {
                    $color_asli = 1;
                }
                if ($request->color_asli >= 1.5 && $request->color_asli < 2) {
                    $color_asli = 2;
                }
                if ($request->color_asli >= 2 && $request->color_asli < 2.5) {
                    $color_asli = 3;
                }
                if ($request->color_asli >= 2.5) {
                    $color_asli = 4;
                }

                $this->data[] = [0 => $bdv_asli, 1 => $water_asli, 2 => $acidity_asli, 3 => $ift_asli, 4 => $color_asli];
                $this->target[] = $request->target_asli;

                $history = session()->get('history');
                $history['aktivitas'][count($history['aktivitas'])] = "Input";
                $history['waktu'][count($history['waktu'])] = Carbon::now()->format('H:i:m');

                $request->session()->put('history', $history);

                $request->session()->put('data', $this->data);
                $request->session()->put('target', $this->target);

                return back()->with('message', Lang::get('notif.succ_add'));
            }
        }
    }

    public function randomPartikel($jml)
    {
        $arr = array(array());

        for ($i = 0; $i < $jml; $i++) {
            $total = 0;
            for ($j = 0; $j < 5; $j++) {
                $arr[$i][$j] = $this->tampil();
                $total += $arr[$i][$j];
            }
            if ($total > 0.654) {
                if ($i == 0) {
                    $i = 0;
                }
                unset($arr[$i]);
                $i -= 1;
            }
        }
        return $arr;
    }

    public function coba()
    {
        $values = [];

        for ($i = 1; $i <= 65; $i++) {
            $variableValues = [];
            $sum = 0;

            for ($j = 1; $j <= 4; $j++) {
                $maxValue = min(0.7 - $sum - (4 - $j) * 0.1, 0.6);  // Maximum value for the current variable
                $randomValue = round(0.1 + mt_rand() / mt_getrandmax() * ($maxValue - 0.1), 5);
                $variableValues[] = $randomValue;
                $sum += $randomValue;
            }

            // Handle the last variable to ensure the sum is less than 0.7
            $variableValues[] = round(0.7 - $sum, 5);

            $values[] = $variableValues;
        }

        // Output the generated values
        foreach ($values as $variableValues) {
            foreach ($variableValues as $value) {
                echo $value . "\n";
            }
            echo "----------\n";
        }
    }

    public function searchData($id)
    {
        $data = session()->get('data');
        $target = session()->get('target');

        $dataBaru = $data[$id];
        $targetBaru = round($target[$id], 5);
        $jmlData = count($dataBaru);

        $final = $dataBaru;
        $final[$jmlData] = $targetBaru;

        // return $final;
        return response()->json($final);
    }

    public function updateData(Request $request, $id)
    {
        $data = session()->get('data');
        $target = session()->get('target');

        $data[$id] =  [0 => $request->bdv, 1 => $request->water, 2 => $request->acidity, 3 => $request->ift, 4 => $request->color];
        $target[$id] = $request->target;

        $request->session()->put('data', $data);
        $request->session()->put('target', $target);

        return redirect('/table-data')->with('message', 'Berhasil mengubah data!');
    }

    public function deleteData($id)
    {
        $data = session()->get('data');
        $target = session()->get('target');

        if (array_splice($data, $id, 1) &&  array_splice($target, $id, 1)) {
            // array_splice($data, $id, 1);
            // array_splice($target, $id, 1);

            session()->put('data', $data);
            session()->put('target', $target);

            return redirect('/table-data')->with('message', Lang::get('notif.succ_del'));
        }
        // dd($data);
    }

    public function execTime()
    {
        $start = microtime(true);

        // Your code here

        $end = microtime(true);
        $executionTime = ($end - $start) * 1000; // milliseconds
        return $executionTime;
    }

    public function viewPDF()
    {
        $data = session()->get('solusi_akhir');
        $pdf = PDF::loadView('export.pdf', array('data' =>  $data))
            ->setPaper('a4', 'portrait')->setOptions(['defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true]);
        // $pdf = PDF::loadHTML('<h1>TES</h1>'); 
        return $pdf->stream();
        // dd($data);
    }

    public function getVariabel()
    {
        $data = Storage::disk('public')->get('variabel.txt');
        // $data = json_decode($contents, true);
        return $data;
    }

    public function indexVariabel()
    {
        $contents = Storage::disk('public')->get('variabel.txt');
        $data = json_decode($contents, true);
        return view('manage-variabel.index', compact('data'));
        // return view('manage-variabel.index');
        // dd($data);
    }

    public function addVariabel(Request $request)
    {
        $var = $request->nama_variabel;
        $firstLetter = Str::substr($var, 0, 1);

        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomString = '';
        $angka = mt_rand(0, 10);

        for ($i = 0; $i < 3; $i++) {
            $randomString .= $characters[mt_rand(0, strlen($characters) - 1)];
        }

        $dataString = json_encode(['id' => $firstLetter . $randomString . $angka, 'data' => $var]);
        $filePath = storage_path('app/public/variabel.txt');
        // Read existing data from the file, if any
        $existingData = [];
        if (File::exists($filePath)) {
            $existingData = json_decode(File::get($filePath), true);
        }

        // Append the new data to the existing data
        $existingData[] = $dataString;

        // Save the data as JSON to the text file
        File::put($filePath, json_encode($existingData));

        return back()->with('message', Lang::get('notif.succ_add'));
    }

    public function deleteVariabel($idToDelete)
    {
        $filePath = storage_path('app/public/variabel.txt');

        // Read existing data from the file, if any
        $existingData = [];
        if (File::exists($filePath)) {
            $jsonData = File::get($filePath);
            $existingData = json_decode($jsonData, true);
        }

        // Find the index of the object with the specified ID in the array
        $indexToDelete = null;
        foreach ($existingData as $index => $data) {
            $decodedData = json_decode($data, true);
            if ($decodedData['id'] === $idToDelete) {
                $indexToDelete = $index;
                break;
            }
        }

        // If the object with the specified ID was found, remove it from the array
        if ($indexToDelete !== null) {
            unset($existingData[$indexToDelete]);
            // Reindex the array after removing the element
            $existingData = array_values($existingData);

            // Save the updated data as JSON to the text file
            File::put($filePath, json_encode($existingData));

            return redirect()->back()->with('success', 'Data with ID ' . $idToDelete . ' has been deleted.');
        } else {
            return redirect()->back()->with('error', 'Data with ID ' . $idToDelete . ' not found.');
        }
    }
}