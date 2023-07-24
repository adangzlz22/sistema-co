@extends('layout.default')

@section('title', 'Home')

@push('css')
    <!-- extra css here -->
    <link href="{{ asset('assets/plugins/select-picker/dist/picker.min.css') }}" rel="stylesheet"/>
@endpush

@push('scripts')
    <!-- extra js here -->
    <script src="{{ asset('assets/plugins/bootstrap-3-typeahead/bootstrap3-typeahead.js') }}"></script>
    <script src="{{ asset('assets/plugins/select-picker/dist/picker.min.js') }}"></script>
    <script src="{{ asset('assets/js/demo/form-plugins.demo.js') }}"></script>
@endpush

@section('content')
    <!-- BEGIN #content -->
    <div id="content" class="app-content">


        <div class="container-fluid">


        </div>


    </div>
    <!-- END #content -->
@endsection
