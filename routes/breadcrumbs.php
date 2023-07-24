<?php

    // Note: Laravel will automatically resolve `Breadcrumbs::` without
    // this import. This is nice for IDE syntax and refactoring.
    use Diglactic\Breadcrumbs\Breadcrumbs;

    // This import is also not required, and you could replace `BreadcrumbTrail $trail`
    //  with `$trail`. This is nice for IDE type checking and completion.
    use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

    //Acuerdos
    Breadcrumbs::for('agreements.index', function (BreadcrumbTrail $trail) {
        $trail->parent('home');
        $trail->push('Acuerdos', route('agreements.index'));
    });

    Breadcrumbs::for('agreements.create', function (BreadcrumbTrail $trail) {
        $trail->parent('agreements.index');
        $trail->push('Crear acuerdo', route('agreements.create'));
    });

    Breadcrumbs::for('agreements.edit', function (BreadcrumbTrail $trail, $item) {
        $trail->parent('agreements.index');
        $trail->push('Editar acuerdo', route('agreements.edit',$item->id));
    });

    //Organismos
    Breadcrumbs::for('organisms.index', function (BreadcrumbTrail $trail) {
        $trail->parent('home');
        $trail->push('Organismos', route('organisms.index'));
    });

    Breadcrumbs::for('organisms.create', function (BreadcrumbTrail $trail) {
        $trail->parent('organisms.index');
        $trail->push('Crear Organismo', route('organisms.create'));
    });

    Breadcrumbs::for('organisms.edit', function (BreadcrumbTrail $trail, $item) {
        $trail->parent('organisms.index');
        $trail->push('Editar Organismo', route('organisms.edit',$item->id));
    });

    //tipo organnismos
    Breadcrumbs::for('organisms_types.index', function (BreadcrumbTrail $trail) {
        $trail->parent('home');
        $trail->push('Tipos de organismo', route('organisms_types.index'));
    });

    Breadcrumbs::for('organisms_types.create', function (BreadcrumbTrail $trail) {
        $trail->parent('organisms_types.index');
        $trail->push('Crear tipo de organismo', route('organisms_types.create'));
    });

    Breadcrumbs::for('organisms_types.edit', function (BreadcrumbTrail $trail, $item) {
        $trail->parent('organisms_types.index');
        $trail->push('Editar tipo de organismo', route('organisms_types.edit',$item->id));
    });

    //Reports
    Breadcrumbs::for('report.contracts', function (BreadcrumbTrail $trail) {
        $trail->parent('home');
        $trail->push('Reporte contratos', route('report.contracts'));
    });


    //home

    Breadcrumbs::for('home', function (BreadcrumbTrail $trail) {
        $trail->push('Inicio', route('home'));
    });

    Breadcrumbs::for('users.index', function (BreadcrumbTrail $trail) {
        $trail->parent('home');
        $trail->push('Usuarios', route('users.index'));
    });

    Breadcrumbs::for('users.create', function (BreadcrumbTrail $trail) {
        $trail->parent('users.index');
        $trail->push('Crear usuarios', route('users.create'));
    });

    Breadcrumbs::for('users.edit', function (BreadcrumbTrail $trail, $item) {
        $trail->parent('users.index');
        $trail->push('Editar usuario', route('users.edit',$item->id));
    });

    Breadcrumbs::for('roles.index', function (BreadcrumbTrail $trail) {
        $trail->parent('home');
        $trail->push('Roles de Acceso', route('roles.index'));
    });

    Breadcrumbs::for('roles.create', function (BreadcrumbTrail $trail) {
        $trail->parent('roles.index');
        $trail->push('Crear roles', route('roles.create'));
    });

    Breadcrumbs::for('roles.edit', function (BreadcrumbTrail $trail, $item) {
        $trail->parent('roles.index');
        $trail->push('Editar Rol', route('roles.edit',$item->id));
    });

    Breadcrumbs::for('roles.permissions', function (BreadcrumbTrail $trail, $item) {
        $trail->parent('roles.index');
        $trail->push('Asignar Permisos', route('roles.permissions',$item->id));
    });

    Breadcrumbs::for('roles.assing', function (BreadcrumbTrail $trail, $item) {
        $trail->parent('roles.permissions', $item);
        $trail->push($item->name, route('roles.permissions',$item->id));
    });




Breadcrumbs::for('permissions.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Permisos de Usuario', route('permissions.index'));
});

Breadcrumbs::for('permissions.create', function (BreadcrumbTrail $trail) {
    $trail->parent('permissions.index');
    $trail->push('Crear Permiso', route('permissions.create'));
});

Breadcrumbs::for('permissions.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('permissions.index');
    $trail->push('Editar Permiso', route('permissions.edit',$item->id));
});

#region administrative units
Breadcrumbs::for('administrative_units.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Sub Organismo', route('administrative_units.index'));
});

Breadcrumbs::for('administrative_units.create', function (BreadcrumbTrail $trail) {
    $trail->parent('administrative_units.index');
    $trail->push('Crear sub organismo', route('administrative_units.create'));
});

Breadcrumbs::for('administrative_units.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('administrative_units.index');
    $trail->push('Editar sub organismo', route('administrative_units.edit',$item->id));
});
#endregion



#region category
Breadcrumbs::for('categories.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Categorias', route('categories.index'));
});

Breadcrumbs::for('categories.create', function (BreadcrumbTrail $trail) {
    $trail->parent('categories.index');
    $trail->push('Crear Categoria', route('categories.create'));
});

Breadcrumbs::for('categories.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('categories.index');
    $trail->push('Editar Categoria', route('administrative_units.edit',$item->id));
});
#endregion

#region icons
Breadcrumbs::for('icons.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Iconos', route('icons.index'));
});

Breadcrumbs::for('icons.create', function (BreadcrumbTrail $trail) {
    $trail->parent('icons.index');
    $trail->push('Crear Icono', route('icons.create'));
});

Breadcrumbs::for('icons.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('icons.index');
    $trail->push('Editar Icono', route('administrative_units.edit',$item->id));
});
#endregion

#region menus
Breadcrumbs::for('menus.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Menú', route('menus.index'));
});

Breadcrumbs::for('menus.create', function (BreadcrumbTrail $trail) {
    $trail->parent('menus.index');
    $trail->push('Crear Menú', route('menus.create'));
});

Breadcrumbs::for('menus.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('menus.index');
    $trail->push('Editar Menú', route('administrative_units.edit',$item->id));
});
#endregion


#region Tipo de contratos
Breadcrumbs::for('contract_types.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Tipo de contrato', route('contract_types.index'));
});

Breadcrumbs::for('contract_types.create', function (BreadcrumbTrail $trail) {
    $trail->parent('contract_types.index');
    $trail->push('Crear Tipo de contrato', route('contract_types.create'));
});

Breadcrumbs::for('contract_types.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('contract_types.index');
    $trail->push('Editar Tipo de contrato', route('contract_types.edit',$item->id));
});
#endregion

#region Tipo de convenios
Breadcrumbs::for('type_agreement.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Tipo de convenio', route('type_agreement.index'));
});

Breadcrumbs::for('type_agreement.create', function (BreadcrumbTrail $trail) {
    $trail->parent('type_agreement.index');
    $trail->push('Crear Tipo de convenio', route('type_agreement.create'));
});

Breadcrumbs::for('type_agreement.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('type_agreement.index');
    $trail->push('Editar Tipo de convenio', route('type_agreement.edit',$item->id));
});
#endregion

#region Jurisdiccio del contratos
Breadcrumbs::for('jurisdiction_contracts.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Jurisdiccion a la que se sujeta el contrato', route('jurisdiction_contracts.index'));
});

Breadcrumbs::for('jurisdiction_contracts.create', function (BreadcrumbTrail $trail) {
    $trail->parent('jurisdiction_contracts.index');
    $trail->push('Crear Jurisdiccion a la que se sujeta el contrato', route('jurisdiction_contracts.create'));
});

Breadcrumbs::for('jurisdiction_contracts.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('jurisdiction_contracts.index');
    $trail->push('Editar Jurisdiccion a la que se sujeta el contrato', route('jurisdiction_contracts.edit',$item->id));
});
#endregion

#region Metodos de pago
Breadcrumbs::for('payment_methods.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Forma de pago', route('payment_methods.index'));
});

Breadcrumbs::for('payment_methods.create', function (BreadcrumbTrail $trail) {
    $trail->parent('payment_methods.index');
    $trail->push('Crear forma de pago', route('payment_methods.create'));
});

Breadcrumbs::for('payment_methods.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('payment_methods.index');
    $trail->push('Editar forma de pago', route('payment_methods.edit',$item->id));
});
#endregion

#region Tipo de expediente
Breadcrumbs::for('expedient_types.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Tipo de expediente', route('expedient_types.index'));
});

Breadcrumbs::for('expedient_types.create', function (BreadcrumbTrail $trail) {
    $trail->parent('expedient_types.index');
    $trail->push('Crear Tipo de expediente', route('expedient_types.create'));
});

Breadcrumbs::for('expedient_types.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('expedient_types.index');
    $trail->push('Editar Tipo de expediente', route('expedient_types.edit',$item->id));
});
#endregion

#region Organo Jurisdiccional
Breadcrumbs::for('jurisdictional_institution.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Tipo de Organo Jurisdiccional', route('jurisdictional_institution.index'));
});

Breadcrumbs::for('jurisdictional_institution.create', function (BreadcrumbTrail $trail) {
    $trail->parent('jurisdictional_institution.index');
    $trail->push('Crear Tipo de Organo Jurisdiccional', route('jurisdictional_institution.create'));
});

Breadcrumbs::for('jurisdictional_institution.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('jurisdictional_institution.index');
    $trail->push('Editar Tipo de Organo Jurisdiccional', route('jurisdictional_institution.edit',$item->id));
});
#endregion

#region contratos
Breadcrumbs::for('contracts.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Contratos', route('contracts.index'));
});

Breadcrumbs::for('contracts.create', function (BreadcrumbTrail $trail) {
    $trail->parent('contracts.index');
    $trail->push('Nuevo contrato', route('contracts.create'));
});

Breadcrumbs::for('contracts.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('contracts.index');
    $trail->push('Editar contrato', route('contracts.edit',$item->id));
});

Breadcrumbs::for('contracts.detail', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('contracts.index');
    $trail->push('Detalle contrato', route('contracts.detail',$item->id));
});
Breadcrumbs::for('contracts.tracking.index', function (BreadcrumbTrail $trail,$item) {
    $trail->parent('contracts.index');
    $trail->push('Seguimiento del contrato', route('contracts.tracking.index',$item->id));
});
#endregion

#region convenios
Breadcrumbs::for('agrmnt_agreement.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Convenios', route('agrmnt_agreement.index'));
});

Breadcrumbs::for('agrmnt_agreement.create', function (BreadcrumbTrail $trail) {
    $trail->parent('agrmnt_agreement.index');
    $trail->push('Nuevo convenio', route('agrmnt_agreement.create'));
});

Breadcrumbs::for('agrmnt_agreement.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('agrmnt_agreement.index');
    $trail->push('Editar convenio', route('agrmnt_agreement.edit',$item->id));
});

Breadcrumbs::for('agrmnt_agreement.detail', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('agrmnt_agreement.index');
    $trail->push('Detalle convenio', route('agrmnt_agreement.detail',$item->id));
});
Breadcrumbs::for('agrmnt_agreement.tracking.index', function (BreadcrumbTrail $trail,$item) {
    $trail->parent('agrmnt_agreement.index');
    $trail->push('Seguimiento del convenio', route('agrmnt_agreement.tracking.index',$item->id));
});
#endregion

#region Juicio
Breadcrumbs::for('judgment.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Juicios', route('judgment.index'));
});

Breadcrumbs::for('judgment.create', function (BreadcrumbTrail $trail) {
    $trail->parent('judgment.index');
    $trail->push('Nuevo juicio', route('judgment.create'));
});

#endjuicio

#region Custom reports
Breadcrumbs::for('custom_reports.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Reportes', route('custom_reports.index'));
});

Breadcrumbs::for('custom_reports.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('custom_reports.index');
    $trail->push('Editar reporte', route('custom_reports.edit',$item->id));
});
#endregion


//meeting type sec
Breadcrumbs::for('meeting_types.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Tipos de reuniones', route('meeting_types.index'));
});

Breadcrumbs::for('meeting_types.create', function (BreadcrumbTrail $trail) {
    $trail->parent('meeting_types.index');
    $trail->push('Crear tipo de reunión', route('meeting_types.create'));
});

Breadcrumbs::for('meeting_types.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('meeting_types.index');
    $trail->push('Editar tipo de reunión', route('meeting_types.edit', $item->id));
});


//entities sec
Breadcrumbs::for('entities.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Instituciones', route('entities.index'));
});

Breadcrumbs::for('entities.create', function (BreadcrumbTrail $trail) {
    $trail->parent('entities.index');
    $trail->push('Crear Institución', route('entities.create'));
});

Breadcrumbs::for('entities.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('entities.index');
    $trail->push('Editar Institución', route('entities.edit', $item->id));
});


//meeting sec
Breadcrumbs::for('meetings.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Reuniones', route('meetings.index'));
});

Breadcrumbs::for('meetings.create', function (BreadcrumbTrail $trail) {
    $trail->parent('meetings.index');
    $trail->push('Crear reunión', route('meetings.create'));
});

Breadcrumbs::for('meetings.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('meetings.index');
    $trail->push('Editar reunión', route('meetings.edit', $item->id));
});

Breadcrumbs::for('meetings.subject', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('meetings.index');
    $trail->push('Agregar Asunto', route('meetings.subject', $item[0]->id));
});

Breadcrumbs::for('meetings.edit_subject', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('meetings.subject', $item);
    $trail->push('Editar Asunto', route('meetings.edit_subject', $item[0]->id));
});

Breadcrumbs::for('meetings.agreement', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('meetings.index');
    $trail->push('Agregar Acuerdo', route('meetings.agreement_store', isset($item[0]->id)?$item[0]->id:$item->id ));
});

Breadcrumbs::for('meetings.edit_agreement', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('meetings.agreement', $item);
    $trail->push('Editar Acuerdo', route('meetings.edit_agreement', $item->id));
});


//places sec
Breadcrumbs::for('places.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Lugar de la reunión', route('places.index'));
});

Breadcrumbs::for('places.create', function (BreadcrumbTrail $trail) {
    $trail->parent('places.index');
    $trail->push('Crear lugar reunión', route('places.create'));
});

Breadcrumbs::for('places.edit', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('places.index');
    $trail->push('Editar lugar de reunión', route('places.edit', $item->id));
});    

Breadcrumbs::for('calendar.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Calendario', route('calendar.index'));
});

Breadcrumbs::for('indicators.index', function (BreadcrumbTrail $trail) {
    $trail->parent('home');
    $trail->push('Semaforo', route('indicators.index'));
});

Breadcrumbs::for('agreements.reply', function (BreadcrumbTrail $trail, $item) {
    $trail->parent('agreements.index');
    $trail->push('Avances', route('agreements.reply', $item->id));
});