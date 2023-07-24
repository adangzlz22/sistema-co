<?php

namespace App\Exports;

use App\Enums\assignment_method;
use App\Enums\origin_of_resources;
use App\Enums\priority;
use App\Enums\status;
use App\Models\Contract;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithFormatData;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class ExportContract implements FromCollection, WithHeadings, WithStyles,WithEvents,ShouldAutoSize, WithMapping
{

    protected $request;

    function __construct($request) {
        $this->request = $request;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Contract::query()
            ->leftJoin('attended_contracts AS ate_con', 'contracts.id', '=', 'ate_con.contract_id')
            ->leftJoin('organisms AS org', 'contracts.organisms_id', '=', 'org.id')
            ->leftJoin('contract_types AS con_typ', 'contracts.contract_type_id', '=', 'con_typ.id')
            ->select([
                'contracts.contract_number',
                'ate_con.destination_folio',
                'ate_con.folio',
                'contracts.invoice_number',
                'org.name AS organism_name',
                'contracts.name_signer',
                'contracts.job_signer',
                DB::raw("
                    (SELECT group_concat(users.name)
                    FROM contract_shareds con_sha
                    INNER JOIN users ON users.id = con_sha.user_id
                    WHERE con_sha.active = 1 AND con_sha.contract_id = contracts.id) AS reviewer_names
                "),
                'con_typ.name AS contract_type',
                'contracts.created_at',
                DB::raw("CONCAT(contracts.received_date, ' ', contracts.received_hour) AS recived_date"),
                'contracts.object',
                'contracts.assignment_method_id',
                'contracts.origin_of_resources_id',
                'contracts.contract_number_general',
                'contracts.total_amount',
                'contracts.valid_from',
                'contracts.valid_to',
                'contracts.legal_name',
                'contracts.priority_id',
                'contracts.status_id',
                'ate_con.validated',
            ]);

        if(!empty($this->request->contract_number))
        {
            $query = $query->where('contracts.contract_number', 'LIKE', '%'.$this->request->contract_number.'%');
        }

        if(!empty($this->request->folio))
        {
            $query = $query->where('ate_con.folio', 'LIKE', '%'.$this->request->folio.'%');
        }

        if(!empty($this->request->invoice_number))
        {
            $query = $query->where('contracts.invoice_number', 'LIKE', '%'.$this->request->invoice_number.'%');
        }

        if(!empty($this->request->organisms_id))
        {
            $query = $query->where('organisms_id', $this->request->organisms_id);
        }

        if(!empty($this->request->contract_type_id))
        {
            $query = $query->where('contracts.contract_type_id', $this->request->contract_type_id);
        }

        if (!empty($this->request->received_date_init)) {
            $query = $query->whereDate('contracts.created_at', '>=', \Carbon\Carbon::parse($this->request->received_date_init)->format('Y-m-d'));
        }

        if (!empty($this->request->received_date_end)) {
            $query = $query->whereDate('contracts.created_at', '<=', \Carbon\Carbon::parse($this->request->received_date_end)->format('Y-m-d'));
        }

        if(!empty($this->request->assignment_method_id))
        {
            $query = $query->where('contracts.assignment_method_id', $this->request->assignment_method_id);
        }

        if(!empty($this->request->origin_of_resources_id))
        {
            $query = $query->where('contracts.origin_of_resources_id', $this->request->origin_of_resources_id);
        }

        if(!empty($this->request->contract_number_general))
        {
            $query = $query->where('contracts.contract_number_general', 'LIKE', '%'.$this->request->contract_number_general.'%');
        }

        if(!empty($this->request->legal_name))
        {
            $query = $query->where('contracts.legal_name', 'LIKE', '%'.$this->request->legal_name.'%');
        }

        if(!empty($this->request->priority_id))
        {
            $query = $query->where('contracts.priority_id', $this->request->priority_id);
        }

        if(!empty($this->request->estatus))
        {
            $query = $query->where('contracts.status_id', $this->request->estatus);
        }
        if($this->request->validated != null)
        {
            $query = $query->where('ate_con.validated', $this->request->validated);
        }

        $data = $query->get();

        return $data;
    }

    public function headings(): array
    {
        return [
            "Folio del Oficio Destino",
            "Folio Interno",
            "No. Oficio",
            "Organismo",
            "Solicitante",
            "Puesto Solicitante",
            "Nombre del Revisor",
            "Tipo Contrato",
            "Fecha de Registro",
            "Fecha y Hora Recibido",
            "Objeto del Contrato",
            "Metodo de Asignacion",
            "Origen del Recurso",
            "Numero de Contrato",
            "Cantidad Total",
            "Vigencia Desde",
            "Vigencia Hasta",
            "Persona Fisica/Moral",
            "Prioridad",
            "Estatus",
            "Validado"
        ];
    }
    public function map($item): array
    {
        $validated_text = '';
        if(!is_null($item->validated)){
            if($item->validated)
                $validated_text = 'Si';
            else
                $validated_text = 'No';
        }

        return [
            $item->destination_folio,
            $item->folio,
            $item->invoice_number,
            $item->organism_name,
            $item->name_signer,
            $item->job_signer,
            $item->reviewer_names,
            $item->contract_type,
            \Carbon\Carbon::parse($item->created_at)->format('Y-m-d'),
            $item->recived_date,
            $item->object,
            assignment_method::getDescription($item->assignment_method_id),
            origin_of_resources::getDescription($item->origin_of_resources_id),
            $item->contract_number_general,
            $item->total_amount,
            \Carbon\Carbon::parse($item->valid_from)->format('Y-m-d'),
            \Carbon\Carbon::parse($item->valid_to)->format('Y-m-d'),
            $item->legal_name,
            priority::getDescription($item->priority_id),
            status::getDescription($item->status_id),
            $validated_text
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2,
            'B' => PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1    => [
                'font' => ['bold' => true]
            ]
        ];
    }


    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {

                /*$event->sheet->getDelegate()->getStyle('A1:U1')
                    ->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('CC0000');

                $event->sheet->getDelegate()->getStyle('A1:U1')
                    ->getFont()
                    ->getColor()
                    ->setARGB('FFFFFFFF');*/

            },
        ];
    }
}
