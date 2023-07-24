<?php

use App\Enums\custom_reports;
use App\Enums\log_movements;
use App\Enums\system_catalogues;
use App\Enums\status;
use App\Enums\dropdown;
use App\Enums\published;
use App\Enums\assignment_method;
use App\Enums\kind_person;
use App\Enums\Days;
use App\Enums\priority;

return [

    log_movements::class => [
        log_movements::LogIn => 'Inicio sesión',
        log_movements::LogOut => 'Cerro sesión',
        log_movements::Edit => 'Edito',
        log_movements::NewRegister => 'Nuevo registro',
        log_movements::Inactive => 'Desactivo',
        log_movements::Reactivate => 'Reactivo',
        log_movements::Delete => 'Elimino',
        log_movements::ChangePassword => 'Cambio contraseña',
    ],
    system_catalogues::class => [
        system_catalogues::Users => 'Usuarios',
        system_catalogues::Agreements => 'Acuerdos',
        system_catalogues::Organisms => 'Organismos',
        system_catalogues::OrganismsTypes => 'Tipo de organismos',
        system_catalogues::AdministrativeUnits => 'Unidades administrativas',
        system_catalogues::Category => 'Categoria del menú',
        system_catalogues::Icon => 'Icono',
        system_catalogues::Menu => 'Menú',
        system_catalogues::ContractType => 'Tipo de contrato',
        system_catalogues::JurisdictionContract => 'Jurisdicción a la que se sujeta el contrato',
        system_catalogues::PaymentMethods => 'Métodos de pago',
        system_catalogues::Contracts => 'Contrato',
        system_catalogues::ContractTrackig => 'Registro de actividad del contrato',
        system_catalogues::Judgmennt => 'Juicios'
    ],
    // status::class => [
    //     status::EnEspera => 'En Espera',
    //     status::EnProceso => 'En Proceso',
    //     status::Terminado => 'Terminado',
    //     status::Cancelado => 'Cancelado',
    // ],
    status::class => [
        status::Saved => 'Guardado',
        status::Registered => 'Registrado',
        status::Received => 'Recibido',
        status::Shifted => 'Turnado',
        status::Revision => 'Revisión',
        status::Recommendations => 'Observaciones',
        status::Attended => 'Atendido',
        status::Cancelled => 'Cancelado',
       // status::Finished => 'Terminado',

    ],
    dropdown::class => [
        dropdown::Desplegable => 'Desplegable',
        dropdown::NoDesplegable => 'No desplegable',
    ],
    published::class => [
        published::Publicado => 'Publicado',
        published::NoPublicado => 'No publicado'
    ],
    assignment_method::class => [
        assignment_method::DirectAward => 'Adjudicación directa',
        assignment_method::Invitation => 'Invitación a cuando menos 3 participantes',
        assignment_method::PublicTender => 'Licitación pública',
        assignment_method::None => 'No aplica',
    ],
    kind_person::class => [
        kind_person::Physical => 'Física',
        kind_person::Moral => 'Moral',

    ],
    Days::class => [
        // Days::days => 'Días',
        Days::BusinessDays => 'Hábiles',
        Days::NaturalDays => 'Naturales',
        Days::None => '',
    ],
    custom_reports::class => [
        custom_reports::Contracts => 'Contratos',
        custom_reports::FinalAcknowledgment => 'Acuse Final',
    ],
    priority::class => [
        priority::High => 'Alta',
        priority::Medium => 'Media',
        priority::Low => 'Baja',
        priority::Na => 'N/A',
    ],
    origin_of_resources::class => [
        origin_of_resources::Estatal => 'Estatal',
        origin_of_resources::Federal => 'Federal',
        origin_of_resources::Propio => 'Propio'
    ]
];
