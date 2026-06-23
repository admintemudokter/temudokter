@extends('errors.layout')

@section('title', 'Kesalahan Sistem')
@section('code', '500')
@section('message', 'Terjadi Kesalahan Server')
@section('description')
Mohon maaf, sistem kami sedang mengalami gangguan internal sementara. Tim teknis kami telah diberitahu dan sedang memperbaikinya. Silakan coba beberapa saat lagi.
@endsection

@section('debug')
{{ $exception->getMessage() }}
@endsection
