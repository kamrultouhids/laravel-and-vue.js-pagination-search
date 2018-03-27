@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">User List</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                        <div class="create text-right">
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#create-item">
                                <i class="glyphicon glyphicon-plus"></i> Create User
                            </button>
                            <br>
                            <br>
                        </div>

                        <create-user></create-user>
                        <view-user></view-user>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script type="text/javascript" src="{{ asset('js/user.js') }}"></script>
@endpush