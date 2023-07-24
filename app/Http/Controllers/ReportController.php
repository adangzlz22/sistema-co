<?php

namespace App\Http\Controllers;

use App\Enums\assignment_method;
use App\Enums\custom_reports;
use App\Enums\origin_of_resources;
use App\Enums\priority;
use App\Exports\ExportContract;
use App\Helpers\HelperApp;
use App\Models\AgrmntAgreement;
use App\Models\AttendedContract;
use App\Models\Category;
use App\Models\Contract;
use App\Models\ContractType;
use App\Models\CustomReport;
use Carbon\Carbon;
use Cassandra\Custom;
use Dompdf\Options;
use Illuminate\Http\Request;

//models for
use App\Models\Organism;
use App\Models\Agreement;

//enums
use App\Enums\status;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use Maatwebsite\Excel\Facades\Excel;
use PDF;
use PHPUnit\TextUI\Help;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class ReportController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    private function Update(){
        $model = CustomReport::get_by_report(custom_reports::Reporte);
        $model->parameters = '';
        CustomReport::edit($model);

        // $model = CustomReport::get_by_report(custom_reports::Contracts);
        // $model->parameters = '@Organismo|@Suborgannismo|@Numero_de_oficio|@Fecha_de_registro|@Objeto|@Puesto_del_usuario|@Nombre_usuario|@Tipo_contrato|@Fecha_inicio_contrato|@Persona_juridica|@Nombre_firmante_oficio|@Puesto_firmante_oficio';
        // CustomReport::edit($model);

    }
    public function index(Request $request)
    {
        $this->Update();
        $models = CustomReport::get_pagination(25);
        return view('custom_reports.index', compact('models','request'));
    }

    public function edit($id)
    {
        $model = CustomReport::get_by_id($id);
        return view('custom_reports.edit', compact('model'));
    }

    public function edit_post(Request $request)
    {

        $model = CustomReport::get_by_id($request->id);
        $model->html_report = $request->html_report;

        $response = CustomReport::edit($model);

        if(!$response['saved']){
            flash('<i class="mdi mdi-information-outline"></i> Ocurrio un error al momento de cear, Error:  <strong>' . $response['error'] . '</strong>.')->error();
            return \Redirect::back();
        }

        flash('<i class="mdi mdi-information-outline"></i> Se ha actualizo el reporte  <strong>' . custom_reports::getDescription($model->report) . '</strong> de forma exitosa.')->success();
        return redirect()->route('custom_reports.index');
    }

    public function generate_pdf($id)
    {
        $model = CustomReport::get_by_id($id);
        $model->html_report = Str::replace('@Entidad', 'Ejecutivo', $model->html_report);
        $model->html_report = Str::replace('@Suborganismo', 'SDAT', $model->html_report);
        $model->html_report = Str::replace('@Frase', 'Esta seria la frase de aqui', $model->html_report);
        $model->html_report = Str::replace('@Fecha', Carbon::now(), $model->html_report);
        $model->html_report = Str::replace('@Contenido_1', "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.", $model->html_report);
        $model->html_report = Str::replace('@Contenido_2', "Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.", $model->html_report);
        $model->html_report = Str::replace('&quot;', '', $model->html_report);
        $model->html_report = Str::replace('﻿', '', $model->html_report);

        $code = 'test-code';

        $code_sha512 = hash('sha512', $code);

        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE,
            ]
        ]);

        $pdf = \PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true]);
        $pdf->getDomPDF()->setHttpContext($contxt);

        //enviamos la informacion de la// share data to view
        $pdf->loadView('custom_reports.pdfview',['model' => $model, 'qr_code' => 'test', 'code_sha512' => $code_sha512 ]);
        $datetime = HelperApp::get_datetime()->format('Ymd-Hi');
        $filename = 'reporte-' . $datetime . '.pdf';
        //$pdf->setOptions(['isRemoteEnabled' => true]);

        // download PDF file with download method
        return $pdf->download($filename);
    }

    public function generate_pdf_by_id($register_id, $report)
    {
        $filename = '';
        $model_report = CustomReport::get_by_report($report);

        $code = '';
        switch ($report){
            case custom_reports::Contracts:
                $contract = Contract::get_by_id($register_id);
                $code = $contract->organism->name??''.'|'.$contract->administrative_unit->name??''.'|'.$contract->invoice_number.'|'.$contract->registration_date_format.'|'.$contract->object.'|'.$contract->created_user->job.'|'.$contract->created_user->name;
                $filename = 'Oficio-de-Solicitud';
                $model_report->html_report = Str::replace('@Organismo', $contract->organism->name, $model_report->html_report);
                $model_report->html_report = Str::replace('@Suborgannismo', $contract->administrative_unit->name ?? 'NA', $model_report->html_report);
                $model_report->html_report = Str::replace('@Numero_de_oficio', $contract->invoice_number ?? 'SSP/UAJ/2021-2/1809(DF)', $model_report->html_report);
                $model_report->html_report = Str::replace('@Fecha_de_registro', $contract->created_at_date_format ?? '03 de noviembre de 2021(DF)', $model_report->html_report);
                $model_report->html_report = Str::replace('@Objeto', $contract->object, $model_report->html_report);
                $model_report->html_report = Str::replace('@Puesto_del_usuario', $contract->created_user->job ?? 'El Director General de la Unidad de Asuntos(DF)', $model_report->html_report);
                $model_report->html_report = Str::replace('@Nombre_usuario', $contract->created_user->name, $model_report->html_report);
                $model_report->html_report = Str::replace('@Nombre_firmante_oficio', $contract->name_signer, $model_report->html_report);
                $model_report->html_report = Str::replace('@Puesto_firmante_oficio', $contract->job_signer, $model_report->html_report);
                $model_report->html_report = Str::replace('@Tipo_contrato', $contract->contract_type->name, $model_report->html_report);

                $day = Carbon::parse($contract->valid_from)->translatedFormat('d');
                $month_name = Carbon::parse($contract->valid_from)->translatedFormat('F');
                $year = Carbon::parse($contract->valid_from)->translatedFormat('Y');
                $current_date = HelperApp::get_datetime();
                $current_year = Carbon::parse($current_date)->translatedFormat('Y');
                $year_text = $current_year == $year ? 'presente' : $year;
                $valid_from = $day . ' de ' . $month_name . ' del '. $year_text;
                $model_report->html_report = Str::replace('@Fecha_inicio_contrato', $valid_from, $model_report->html_report);
                $model_report->html_report = Str::replace('@Persona_juridica', $contract->legal_name, $model_report->html_report);
                break;
            case custom_reports::FinalAcknowledgment:
                $contract = Contract::get_by_id($register_id);
                $code = $contract->organism->name??''.'|'.$contract->administrative_unit->name??''.'|'.$contract->invoice_number.'|'.$contract->registration_date_format.'|'.$contract->object.'|'.$contract->created_user->job.'|'.$contract->created_user->name;
                $code = $contract->organism->name??''.'|'.$contract->administrative_unit->name??''.'|'.$contract->invoice_number.'|'.$contract->registration_date_format.'|'.$contract->object.'|'.$contract->created_user->job.'|'.$contract->created_user->name;
                $filename = 'Oficio-de-Validacion';
                $day = Carbon::parse($contract->created_at)->translatedFormat('d');
                $month_name = Carbon::parse($contract->created_at)->translatedFormat('F');
                $year = Carbon::parse($contract->created_at)->translatedFormat('Y');

                $current_date = HelperApp::get_datetime();
                $current_year = Carbon::parse($current_date)->translatedFormat('Y');
                $year_text = $current_year == $year ? 'presente' : $year;
                $created_at = Str::lower($day . ' de ' . $month_name . ' del '. $year_text);
                $attended_contract = AttendedContract::get_by_contract_id($register_id);

                $contract_shares = '';
                foreach ($contract->contract_shareds as $item){
                    $contract_shares .= $item->user->acronym . ', ';
                }

                $model_report->html_report = Str::replace('@Nombre_dest_oficio', $attended_contract->addressee_job_name ?? '', $model_report->html_report);
                $model_report->html_report = Str::replace('@Puesto_nombre_dest_oficio', $attended_contract->position_addressee_job_name ?? '', $model_report->html_report);
                $model_report->html_report = Str::replace('@Numero_de_oficio', $attended_contract->destination_folio ?? 'SSP/UAJ/2021-2/1809(DF)', $model_report->html_report);
                $model_report->html_report = Str::replace('@Fecha_de_atendido', $attended_contract->attended_date_format ?? '', $model_report->html_report);
                $model_report->html_report = Str::replace('@Oficio_No', $contract->invoice_number ?? 'SCJ/0000/0000', $model_report->html_report);
                $model_report->html_report = Str::replace('@Fecha_de_registro', $created_at, $model_report->html_report);
                $model_report->html_report = Str::replace('@Contenido_1', $attended_contract->content_1 ?? '', $model_report->html_report);
                $model_report->html_report = Str::replace('@Contenido_2', $attended_contract->content_2 ?? '', $model_report->html_report);
                $model_report->html_report = Str::replace('@Folio', $attended_contract->folio ?? '', $model_report->html_report);
                $model_report->html_report = Str::replace('@Siglas_Firmante', Str::substr($contract_shares, 0, Str::length($contract_shares) - 2), $model_report->html_report);
                $model_report->html_report = Str::replace('@Nombre_Firmante', $attended_contract->signer_user->name ?? '', $model_report->html_report);
                $model_report->html_report = Str::replace('@Puesto_Firmante', $attended_contract->signer_user->job ?? '', $model_report->html_report);
                $model_report->html_report = Str::replace('@Organismo', $contract->organism->name, $model_report->html_report);
                $model_report->html_report = Str::replace('@Empresa', $contract->legal_name, $model_report->html_report);
                break;
            case custom_reports::Agreement:
                $agreement = AgrmntAgreement::get_by_id($register_id);

                $code = $agreement->organism->name??''.'|'.$agreement->administrative_unit->name??''.'|'.$agreement->invoice_number.'|'.$agreement->registration_date_format.'|'.$agreement->description.'|'.$agreement->created_user->job.'|'.$agreement->created_user->name;
                $filename = 'Oficio-de-Solicitud';
                $model_report->html_report = Str::replace('@Organismo', $agreement->organism->name, $model_report->html_report);
                $model_report->html_report = Str::replace('@Suborgannismo', $agreement->administrative_unit->name ?? 'NA', $model_report->html_report);
                $model_report->html_report = Str::replace('@Numero_de_oficio', $agreement->invoice_number ?? 'SSP/UAJ/2021-2/1809(DF)', $model_report->html_report);
                $model_report->html_report = Str::replace('@Fecha_de_registro', $agreement->created_at_date_format ?? '03 de noviembre de 2021(DF)', $model_report->html_report);
                $model_report->html_report = Str::replace('@Descripcion', $agreement->description, $model_report->html_report);
                $model_report->html_report = Str::replace('@Puesto_del_usuario', $agreement->created_user->job ?? 'El Director General de la Unidad de Asuntos(DF)', $model_report->html_report);
                $model_report->html_report = Str::replace('@Nombre_usuario', $agreement->created_user->name, $model_report->html_report);
                $model_report->html_report = Str::replace('@Nombre_firmante_oficio', $agreement->name_signer, $model_report->html_report);
                $model_report->html_report = Str::replace('@Puesto_firmante_oficio', $agreement->job_signer, $model_report->html_report);
                $model_report->html_report = Str::replace('@Tipo_convenio', $agreement->type_agreement->name, $model_report->html_report);

                $day = Carbon::parse($agreement->valid_from)->translatedFormat('d');
                $month_name = Carbon::parse($agreement->valid_from)->translatedFormat('F');
                $year = Carbon::parse($agreement->valid_from)->translatedFormat('Y');
                $current_date = HelperApp::get_datetime();
                $current_year = Carbon::parse($current_date)->translatedFormat('Y');
                $year_text = $current_year == $year ? 'presente' : $year;
                $valid_from = $day . ' de ' . $month_name . ' del '. $year_text;
                $model_report->html_report = Str::replace('@Fecha_inicio_contrato', $valid_from, $model_report->html_report);
                $model_report->html_report = Str::replace('@Persona_juridica', $agreement->legal_name, $model_report->html_report);
                break;
        }
        $model_report->html_report = Str::replace('&quot;', '', $model_report->html_report);
        $model_report->html_report = Str::replace('﻿', '', $model_report->html_report);



        $code_sha512 = hash('sha512', $code);

        $contxt = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE,
            ]
        ]);

        $pdf = \PDF::setOptions(['isHTML5ParserEnabled' => true, 'isRemoteEnabled' => true,'is_enable_php' => true]);
        $pdf->getDomPDF()->setHttpContext($contxt);
        //print($model_report->html_report);
        //dd('');
        //enviamos la informacion de la// share data to view
        $pdf->loadView('custom_reports.pdfview',['model' => $model_report, 'qr_code' => $code, 'code_sha512' => $code_sha512 ]);

        $datetime = HelperApp::get_datetime()->format('Ymd-Hi');
        $filename .= '-' . $datetime . '.pdf';
        //$pdf->setOptions(['isRemoteEnabled' => true]);

        // download PDF file with download method
        return $pdf->download($filename);
    }


    public function contracts(){
        $organisms = null;
        if(!Auth::user()->hasRole(HelperApp::$roleDependenciaEntidad )){
            $organisms = Organism::getForCatalog();
        }
        $contract_types = ContractType::getForCatalog();
        $assignment_methods = assignment_method::toSelectArray();
        $origin_of_resources = origin_of_resources::toSelectArray();
        $priorities = priority::toSelectArray();
        $status = status::toSelectArray();
        $status = Arr::except($status, [9]); // except the finished status

        return view('reports.contracts.index')
            ->with(compact('assignment_methods'))
            ->with(compact('organisms'))
            ->with(compact('origin_of_resources'))
            ->with(compact('priorities'))
            ->with(compact('status'))
            ->with(compact('contract_types'));
    }

    public function contracts_excel(Request $request)
    {
        if(Auth::user()->hasRole(HelperApp::$roleDependenciaEntidad ))
            $request->organisms_id = Auth::user()->organisms_id;

        return Excel::download(new ExportContract($request), 'reporte-contratos.xlsx');
    }

}
