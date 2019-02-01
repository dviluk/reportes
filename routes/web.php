<?php

use JasperPHP\JasperPHP;
use Illuminate\Support\Facades\Storage;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('r', function(){
    $asd = new JasperPHP();
    // COMPILAR PLANTILLA
    $asd->compile(base_path('vendor/cossou/jasperphp/examples/hello_world.jrxml'))->execute();


    $outputDIR = storage_path('app/public/reportes/');
    if(!file_exists($outputDIR)){
        Storage::makeDirectory('public/reportes/');
    }
    $filename = 'hello-world';
    $full = $outputDIR . $filename;

    // CARGAR DATOS A PLANTILLA COMPILADA
    $exec = $asd->process(
        base_path('vendor/cossou/jasperphp/examples/hello_world.jasper'),
        $full,
        ['pdf'],
        ['php_version' => 'ASDADSADA']
    );
    $exec = $asd->output();
    $exec = $asd->execute();

    return response()->download($full.'.pdf');
});

Route::get('r2', function(){
    $asd = new JasperPHP();
    // COMPILAR PLANTILLA
    $compile = $asd->compile(base_path('storage/reportes/ETIQUETA_ACTIVOS.jrxml'));
    $compile = $asd->output();
    $compile = $asd->execute();
    $compiled = base_path('storage/reportes/ETIQUETA_ACTIVOS.jasper');
    $exec = $asd->list_parameters($compiled);

    $data = storage_path('reportes/qrs.json');
    $outputDIR = storage_path('app/public/reportes/');
    if(!file_exists($outputDIR)){
        Storage::makeDirectory('public/reportes/');
    }
    $filename = 'qr';
    $full = $outputDIR . $filename;

    // CARGAR DATOS A PLANTILLA COMPILADA
    $exec = $asd->process(
        $compiled,
        $full,
        ['pdf'],
        [],
        [
            'driver' => 'json',
            'json_query' => 'data.activos',
            'data_file' => $data
        ]
    );
    $exec = $asd->output();
    $exec = $asd->execute();

    return response()->download($full.'.pdf');
});
