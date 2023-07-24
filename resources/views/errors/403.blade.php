@extends('errors.layout')

@section('title', __('Forbidden'))
@section('code', '403')
@section('message', 'Su usuario no cuenta con los permisos necesarios para consultar esta página')
