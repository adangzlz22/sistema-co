@extends('errors.layout')

@section('title', __('Too Many Requests'))
@section('code', '429')
@section('message', "Hemos recibido, demasiadas solicitudes. Por favor espere a que se reestablezcan los servicios. ")
