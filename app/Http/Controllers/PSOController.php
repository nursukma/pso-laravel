<?php

namespace App\Http\Controllers;

use App\Imports\ModelImport;
use App\Imports\TargetImport;
use App\Models\ModelVariabel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;

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

    private $data = [];
    private $target = [];

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
        $request->session()->forget('data');
        return redirect('/');
    }

    public function hitung(Request $request)
    {
        if (!$request->iter)
            return redirect('/data')->with('error', 'Silakan masukkan jumlah iterasi!');

        $klasifikasi = $request->session()->get('data');
        $target = $request->session()->get('target');

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
                        $total_kali[$k][$j] = round($totalSementara / $pembagi, 2);
                    }
                }

                // ====================================== //

                // MENGHITUNG SELISIH HASIL KALI DENGAN TARGET
                for ($x = 0; $x < count($total_kali); $x++) {
                    $total_selisih = 0;
                    for ($i = 0; $i < count($total_kali[$x]); $i++) {
                        $total[$x][$i] = abs($total_kali[$x][$i] - $target[$i]);
                        $total_selisih += round(abs($total_kali[$x][$i] - $target[$i]), 2);
                        // $total[$x][$i] = abs($total_kali[$x][$i] - self::target[$i]);
                        // $total_selisih += round(abs($total_kali[$x][$i] - self::target[$i]), 2);
                    }
                    $hasil_selisih[$x] = $total_selisih;
                }
                // ====================================== //

                // MENGHITUNG NILAI FITNESS
                for ($x = 0; $x < count($hasil_selisih); $x++) {
                    $hasil_fitness[$x] = round(1 / $hasil_selisih[$x], 2);
                }
                // ================================= //

                // MENCARI GBEST DAN PBEST 
                for ($x = 0; $x < count($hasil_fitness); $x++) {
                    $max = max($hasil_fitness);
                    // $index_max = max(array_keys($hasil_fitness));
                    $index_max = array_search(max($hasil_fitness), $hasil_fitness);

                    if ($p_best[$x] < $hasil_fitness[$x]) {
                        $p_best[$x] = $hasil_fitness[$x];
                    }

                    if ($g_best < $max) {
                        $g_best = $max;
                    }
                    $solusi_akhir = array("Solusi" => self::populasi[$index_max], "GBest" => $g_best);
                }
                // ============================ //

                // MENGHITUNG VELOCITY
                for ($k = 0; $k < count($arrayCopy); $k++) {
                    for ($j = 0; $j < count($arrayCopy[$k]); $j++) {
                        // $hasil_velocity[$k][$j] = round(($b_inersia * $hasil_velocity[$k][$j]) +
                        //     (($c1 * $r1[1]) * ($p_best[$k] - $arrayCopy[$k][$j])) +
                        //     (($c2 * $r2[1]) * ($g_best - $arrayCopy[$k][$j])), 2);
                        $hasil_velocity[$k][$j] = round(($b_inersia * $hasil_velocity[$k][$j]) +
                            (($c1 * $r1) * ($p_best[$k] - $arrayCopy[$k][$j])) +
                            (($c2 * $r2) * ($g_best - $arrayCopy[$k][$j])), 2);
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
                // PERHTIUNGAN PARTIKEL KALI DATA AWAL
                for ($j = 0; $j < count($klasifikasi); $j++) {
                    for ($k = 0; $k < count(self::populasi); $k++) {
                        $totalSementara = 0;
                        $pembagi = 0;
                        for ($i = 0; $i < count($klasifikasi[$j]); $i++) {
                            $totalSementara += $klasifikasi[$j][$i] * self::populasi[$k][$i];
                            $pembagi += self::populasi[$k][$i];
                        }
                        $total_kali[$k][$j] = round($totalSementara / $pembagi, 2);
                    }
                }

                // ====================================== //

                // MENGHITUNG SELISIH HASIL KALI DENGAN TARGET
                for ($x = 0; $x < count($total_kali); $x++) {
                    $total_selisih = 0;
                    for ($i = 0; $i < count($total_kali[$x]); $i++) {
                        $total[$x][$i] = abs($total_kali[$x][$i] - $target[$i]);
                        $total_selisih += round(abs($total_kali[$x][$i] - $target[$i]), 2);
                        // $total[$x][$i] = abs($total_kali[$x][$i] - self::target[$i]);
                        // $total_selisih += abs($total_kali[$x][$i] - self::target[$i]);
                    }
                    $hasil_selisih[$x] = $total_selisih;
                }
                // ====================================== //

                // MENGHITUNG NILAI FITNESS
                for ($x = 0; $x < count($hasil_selisih); $x++) {
                    $hasil_fitness[$x] = round(1 / $hasil_selisih[$x], 2);
                }
                // ================================= //

                // MENCARI GBEST DAN PBEST 
                for ($x = 0; $x < count($hasil_fitness); $x++) {
                    $max = max($hasil_fitness);
                    // $index_max = max(array_keys($hasil_fitness));
                    $index_max = array_search(max($hasil_fitness), $hasil_fitness);

                    if (empty($p_best)) {
                        $p_best = $hasil_fitness;
                    }

                    if ($g_best == 0) {
                        $g_best = $max;
                    }
                    $solusi_akhir = array("Solusi" => self::populasi[$index_max], "GBest" => $g_best);
                }
                // ============================ //

                // MENGHITUNG VELOCITY
                for ($k = 0; $k < count(self::populasi); $k++) {
                    for ($j = 0; $j < count(self::populasi[$k]); $j++) {
                        // $hasil_velocity[$k][$j] = round(($b_inersia * $velocity) +
                        //     (($c1 * $r1[0]) * ($p_best[$k] - self::populasi[$k][$j])) +
                        //     (($c2 * $r2[0]) * ($g_best - self::populasi[$k][$j])), 2);
                        $hasil_velocity[$k][$j] = round(($b_inersia * $velocity) +
                            (($c1 * $r1) * ($p_best[$k] - self::populasi[$k][$j])) +
                            (($c2 * $r2) * ($g_best - self::populasi[$k][$j])), 2);
                    }
                }
                // ============================ //

                // MEMPERBARUI POSISI X
                for ($k = 0; $k < count(self::populasi); $k++) {
                    for ($j = 0; $j < count(self::populasi[$k]); $j++) {
                        $posisi_x[$k][$j] = self::populasi[$k][$j] + $hasil_velocity[$k][$j];
                    }
                }
                // ==================== //
            }
            // ===================================== //

            $start++;
        };

        return view('hitung')->with(['data' => $solusi_akhir]);
        // return (["r1" => $r1, "r2" => $r2]);
    }


    public function tampil()
    {
        return round(0 + mt_rand() / mt_getrandmax() * (1.0 - 0), 2);
    }

    public function import(Request $request)
    {
        if (!$request->hasFile('file') && (!$request->bdv || !$request->water || !$request->acidity || !$request->ift || !$request->color || !$request->target)) {
            // return dd("DATA KOSONG");
            return redirect('/')->with('error', 'Silakan isi data terlebih dahulu!');
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

            $request->session()->put('data', $this->data);
            $request->session()->put('target', $this->target);

            // dd($request->session()->get('target'));
            return view('data')->with(['data' => $this->data]);
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

            $request->session()->put('data', $this->data);
            $request->session()->put('target', $this->target);

            // dd($request->session()->get('target'));
            return view('data')->with(['data' => $this->data]);
        }

        if (!$request->hasFile('file') && $request->bdv && $request->water && $request->acidity && $request->ift && $request->color && $request->target) {
            $this->data[] = [0 => $request->bdv, 1 => $request->water, 2 => $request->acidity, 3 => $request->ift, 4 => $request->color];
            $this->target[] = $request->target;

            $request->session()->put('data', $this->data);
            $request->session()->put('target', $this->target);

            // dd($request->session()->get('target'));
            return view('data')->with(['data' => $this->data]);
        }
    }

    public function addDataBaru(Request $request)
    {
        if (!$request->hasFile('file') && (!$request->bdv || !$request->water || !$request->acidity || !$request->ift || !$request->color || !$request->target)) {
            // return dd("DATA KOSONG");
            return redirect('/data')->with('error', 'Silakan isi data terlebih dahulu!');
        }

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

            $request->session()->put('data', $data);
            $request->session()->put('target', $target);

            // dd($request->session()->get('target'));
            return view('data')->with(['data' => $data]);
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

            $request->session()->put('data', $data);
            $request->session()->put('target', $target);

            // dd($request->session()->get('target'));
            return view('data')->with(['data' => $data]);
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

            $data = array_merge($request->session()->get('data'), $this->data);
            $target = array_merge($request->session()->get('target'), $this->target);

            $data[count($data)] = [0 => $request->bdv, 1 => $request->water, 2 => $request->acidity, 3 => $request->ift, 4 => $request->color];
            $target[count($target)] = $request->target;

            $request->session()->put('data', $data);
            $request->session()->put('target', $target);

            // dd($request->session()->get('target'));
            return view('data')->with(['data' => $data]);
        }
    }

    public function readText()
    {
        $contents = Storage::disk('public')->get('login.txt');
        $arr = explode(',', $contents);
        return $arr;
    }
}
