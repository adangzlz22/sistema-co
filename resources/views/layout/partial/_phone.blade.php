
{{-- <a href="javascript:void(0);" class="btn @if(isset($btnType)) {{ $btnType }} @else btn-outline-dark @endif " title="Teléfonos de Atención" data-bs-toggle="modal" data-bs-target="#phoneModal">
    <span class="mdi mdi-phone"></span> <span class="text"> Teléfonos de Atención</span>
</a> --}}

@push('scripts')
    <!-- Modal -->
    <div class="modal fade" id="phoneModal" tabindex="-1" aria-labelledby="phoneModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="phoneModalLabel">Teléfonos de Atención</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Atención Servidores Públicos</strong></p>
                    <p>A fin de poder asesorarte sobre la presentación de tu declaración patrimonial y de intereses, la Secretaría de la Contrarloría General a través de la Coordinación Ejecutiva de Sustanciación y Resolución de Responsabilidades y Situación Patrimonial
                        pone a su servicio de lunes a viernes de 08:00 a 16:00 horas los siguientes canales de atención:</p>

                    <div class="row">
                        <div class="col-sm-6">
                            <p>Los números teléfonicos</p>
                            <ul class="list-group">
                                <li class="list-group-item"><span class="mdi mdi-phone"></span> 662 2172168</li>
                                <li class="list-group-item"><span class="mdi mdi-phone"></span> 662 2136207</li>
                            </ul>
                        </div>
                        <div class="col-sm-6">
                            <p>En las siguientes extensiones:</p>
                            <ul class="list-group">
                                <li class="list-group-item">1299, 1297, 1300, 1301, 1303, 1304, 1305, 1306, 1328, 1330.</li>


                                {{--
                                <li class="list-group-item">1299</li>
                                <li class="list-group-item">1297</li>
                                <li class="list-group-item">1300</li>
                                <li class="list-group-item">1301</li>
                                <li class="list-group-item">1303</li>
                                <li class="list-group-item">1304</li>
                                <li class="list-group-item">1305</li>
                                <li class="list-group-item">1306</li>
                                <li class="list-group-item">1328</li>
                                <li class="list-group-item">1330</li>
                            </ul>--}}
                        </div>
                    </div>




                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endpush
