<a href="javascript:void(0);" class="btn  @if(isset($btnType)) {{ $btnType }} @else btn-outline-dark @endif " title="Teléfonos de Atención" data-bs-toggle="modal" data-bs-target="#termsModal">
    <span class="text"> Aviso Legal</span>
</a>

@push('scripts')
    <!-- Modal -->
    <div class="modal fade" id="termsModal" tabindex="-1" aria-labelledby="termsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="termsModalLabel">Aviso de Privacidad Integral</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body text-justify">

                    <!-- 2 combo: text field + button -->
                    <p><strong>¿Quién es el responsable del tratamiento y protección de mis datos personales y cuál es su domicilio?</strong></p>
                    <p>
                        La Secretaría de la Consejería Jurídica del Gobierno del Estado de Sonora, con domicilio ubicado en Pedro Moreno número 35 “Casona”, Colonia Centenario, C.P. 83260, Ciudad de Hermosillo, Sonora, es el responsable del uso y protección de sus datos personales.
                    </p>

                    <p><strong>¿Que datos personales se recaban?</strong></p>
                    <p>
                        De forma directa se pueden obtener: el nombre (s), apellidos, teléfono, correo electrónico, domicilio, fecha de nacimiento, edad, firma, empleo actual, grado de escolaridad, Clave única de Registro de Población (CURP), Registro Federal de Contribuyentes (RFC) y demás análogos a los anteriores, tanto de las personas que capturen los datos, como de los potenciales contratistas, proveedores o prestadores de servicios del Gobierno del Estado.
                    </p><p>
                        De forma indirecta se pueden obtener: la dirección IP, horario de navegación, tiempo de navegación en nuestra página, secciones consultadas, el navegador del usuario, sistema operativo, momento en que se accedió a la página y estado del sitio.
                    </p>

                    <p><strong>¿Cómo se obtienen los datos personales?</strong></p>
                    <p>
                        Los datos personales se pueden obtener de formatos impresos, electrónicos, verbales o cualquier otro tipo de tecnología o forma análoga conocida o por conocer.
                    </p>
                    <p><strong>¿Por qué se recaban los datos personales?</strong></p>
                    <p>
                        Se recaban para volver más eficiente a la administración pública estatal, al tener una plataforma que permita el intercambio de datos entre las distintas dependencias, organismos y órganos de la Administración Pública.
                    </p><p>
                        Se recaban los datos personales para proteger la relación jurídica que llegue a existir entre el Gobierno del Estado de Sonora y cualquier potencial contratista, proveedor o prestador de servicios.
                    </p><p>
                        Se recaban los datos personales para que el Gobierno del Estado de Sonora tenga un panorama actualizado en tiempo real respecto del cumplimiento contractual de los contratistas, proveedores o prestadores, seguimiento actualizado de cualquier tipo de juicio, iniciativas legales y demás análogos a los anteriores.
                    </p><p>
                        En caso de que usted no desee que sus datos personales sean utilizados para estos fines puede desde este momento manifestar su negativa, como más adelante se indica.
                    </p>

                    <p><strong>¿Con quién se comparten?</strong></p>
                    <p>
                        La Secretaría de la Consejería Jurídica del Gobierno del Estado podrá compartir la información personal que recabe con cualquier tipo de autoridad y en cualquier orden de gobierno.</p>
                    <p>
                        Asimismo podrá compartir la información con terceros privados cuando se trate de cumplir con alguna obligación impuesta mediante algún dispositivo legal.
                    </p>

                    <p><strong>¿Qué son los derechos ARCO?</strong></p>
                    <p>
                        Son derechos que tiene la persona que proporciona los datos personales y consisten en el acceso, rectificación, cancelación y oposición a la divulgación de los datos personales que proporcione.
                    </p>

                    <p><strong>¿Cómo puedo acceder, rectificar, cancelar u oponerme a la divulgación de mis datos personales?</strong></p>

                    <p>
                        Mandando un correo a sicse@sonora.gob.mx quien se encargará de rectificar, limitar, oponer o cancelación de dicha divulgación. Sin embargo no se podrá limitar dicha divulgación cuando sea necesaria para el cumplimiento de los servicios ante alguna autoridad judicial o administrativa de cualquier ámbito de gobierno.
                    </p>

                    <p><strong>¿Qué normatividad protege mis datos personales?</strong></p>
                    <p>
                        La Ley de Protección de los Datos Personales en Posesión de los Particulares y su Reglamento.
                    </p>
                    <!-- 2 combo: text field + button -->


                    <!---div class="row">
                       <div class="col-xs-12">
                              <h1>AVISO DE PRIVACIDAD SIMPLIFICADO.</h1>
                         </div>
                    </div>

                    <div class="row c_form_line">
                       <div class="col-sm-12">
<u>Responsable del tratamiento y protecci&oacute;n de los datos personales</u>: La Secretar&iacute;a de la Consejer&iacute;a Jur&iacute;dica del Gobierno del Estado de Sonora, con domicilio ubicado en Pedro Moreno n&uacute;mero 35 �Casona�, Colonia Centenario, C.P. 83260, Ciudad de Hermosillo, Sonora, es el responsable del uso y protecci&oacute;n de sus datos personales.
<p><u>Finalidades del tratamiento</u>: llevar a cabo los servicios de la administraci&oacute;n p&uacute;blica conferidos a el responsable del tratamiento.<br></p>
<p><u>Mecanismos para limitar la divulgaci&oacute;n y tratamiento de sus datos personales</u>: mandando un correo electr&oacute;nico a contacto@consonora.mx se encargar&aacute; de rectificar, limitar, oponer o cancelaci&oacute;n de dicha divulgaci&oacute;n.<br></p>
<p>Aviso de privacidad integral consultable en www.consonora.mx</p>
                       </div>
                    </div---->



                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>
@endpush




