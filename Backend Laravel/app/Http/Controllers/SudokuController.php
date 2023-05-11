<?php

namespace App\Http\Controllers;

use App\Models\Sudoku;
use App\Http\Requests\StoresudokuRequest;
use App\Http\Requests\UpdatesudokuRequest;


class SudokuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sudoku = Sudoku::all();
        return response()->json([
            'data' => $sudoku
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoresudokuRequest $request)
    {


        $request->validate([
            'name' => 'required|unique:sudokus',
        ], [
            'name.unique' => 'Level Dengan Nama ini Telah Terdaftar',
            'name.required' => 'Nama Level Tidak Boleh Kosong',
        ]);


        if ($request->file('file')) {

            $file = $request->file('file');
            $origin_name = $file->getClientOriginalName();
            $nama_file = time() . "_" . $origin_name;
            $data = json_decode(file_get_contents($file->getRealPath()), true);
            if (!$data) {
                return response()->json([
                    "errors" => [
                        "wrong_file" => "Mohon Maaf File yang di Input tidak memungkinkan untuk dipecahkan, silahkan coba file yang lain."
                    ]

                ], 422);
            }
            $solver = new SudokuSolver($data);

            $result =  $solver->solve();

            if (!$result) {
                return response()->json([
                    "errors" => [
                        "not_possible" => "Mohon Maaf File yang di Input tidak memungkinkan untuk dipecahkan, pastikan tidak ada duplikasi antar array, atau coba file yang lain."
                    ]
                ], 422);
            }


            Sudoku::create([
                "name" => $request->string("name"),
                "file_name" => $nama_file,
            ]);

            $tujuan_upload = '../../Saved Files/';
            $file->move($tujuan_upload, $nama_file);
        } else {
            $newData = [];
            for ($i = 0; $i < 9; $i++) {
                $data = $request->column[$i]["value"];
                array_push($newData, $data);
            }

            $solver = new SudokuSolver($newData);
            $result =  $solver->solve();
            if (!$result) {
                return response()->json([
                    "errors" => [
                        "not_possible" => "Mohon Maaf Input tidak memungkinkan untuk dipecahkan, pastikan tidak ada duplikasi antar kolom"
                    ]
                ], 422);
            }

            $tujuan_upload = '../../Saved Files/';
            $name = $request->name;
            $file_name = time() . "_" . $name . ".txt";
            file_put_contents($tujuan_upload .  $file_name, json_encode($newData));
            Sudoku::create([
                "name" => $name,
                "file_name" => $file_name,
            ]);
        }
        return response()->json([
            "message" => "Data Berhasil Di Tambahkan",
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Sudoku $sudoku)
    {

        $file_location = "../../Saved Files/" . $sudoku->file_name;
        if (!file_exists($file_location)) {
            return response()->json([
                "errors" => [
                    "file_not_found" => "Mohon Data Tidak Dapat Ditemukan, Pastikan File .txt masih tersimpan, Jika tidak silahkan hapus board ini."
                ]
            ], 404);
        }
        $data = json_decode(file_get_contents($file_location), true);
        $solver = new SudokuSolver($data);
        $result =  $solver->solve();

        if (!$result) {
            return response()->json([
                "not_possible" => "Mohon Maaf File yang di Input tidak memungkinkan untuk dipecahkan, pastikan tidak ada duplikasi antar array, atau coba file yang lain."
            ]);
        }

        return response()->json([
            "sudoku" => $data,
            "solved" =>  $result
        ]);
    }
}
