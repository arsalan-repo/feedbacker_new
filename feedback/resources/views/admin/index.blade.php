@extends('admin.layouts.main')



@section('content')

@include('admin.surveys')



@endsection()


@section('admin-script')
<script src="/js/admin/controllers/surveys.js"></script>

@endsection
