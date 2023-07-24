    @extends('layout.default')

    @section('title','Semáforo')

    @section('breadcrumbs')
        {{ Breadcrumbs::render('indicators.index') }}
    @endsection


    @section('content')

    <h3>
        <!-- Contratos -->
    </h3>
    @php
        $qr_code = QrCode::format('svg')->generate('SSP/UAJ/2021-2/1809');
    @endphp
    <div class="card card-body table-responsive">
            <table id="tablaSemaforo" class="table table-sm table-bordered">
                <thead class="bg-danger text-white">
                    <tr>
                        <th>ID</th>    
                        <th>Institución</th>    
                        <th>Asignados</th>    
                        <th>Concluídos</th>    
                        <th>En proceso</th>    
                        <th>Pendientes</th>    
                        <th>Porcentaje</th>    
                    </tr>
                </thead>   
                <tbody>
                    @foreach ($entities as $entitie)
                        <tr>
                            <td> {{ $entitie->id }}  </td>
                            <td> {{ $entitie->name }}  </td>
                            <td> {{ $entitie->TotalAsignados }}  </td>
                            <td style="color: #086408; font-weight: bold"> {{ $entitie->TotalConcluido }}  </td>
                            <td style="color: #DC7F37; font-weight: bold"> {{ $entitie->TotalEnProceso }}  </td>
                            <td style="color: #9B2F3E; font-weight: bold"> {{ $entitie->TotalPendiente }}  </td>
                            <td class="bg-{{($entitie->Porcentaje==0 ? 'secondary': ($entitie->Porcentaje > 0 && $entitie->Porcentaje <= 50?'danger':($entitie->Porcentaje > 50 && $entitie->Porcentaje <= 80 ? 'warning' :'success')) )}}" style="color: #FFFFFF"> {{ $entitie->Porcentaje == 0 ? "0.00": number_format($entitie->Porcentaje,2) }}%  </td>
                            {{-- <td> {{ $entitie->ActionsTotalConcluidas }}  </td>
                            <td> {{ $entitie->ActionsTotalEnProceso }}  </td>
                            <td> {{ $entitie->TotalActions }}  </td> --}}
                            
                        </tr>
                    @endforeach    
                </tbody> 
            </table>    
            <div class="d-flex justify-content-center">
                {!! $entities->appends(request()->except(['page','_token']))->render() !!}
            </div>
    </div>
    @php

    @endphp

    @endsection

    @push("css")

    @endpush

    @push("scripts")
        
    @endpush
