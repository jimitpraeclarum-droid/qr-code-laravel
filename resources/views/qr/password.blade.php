@extends('layouts.app')

@section('title', 'Password Protected QR Code')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Password Required</div>

                <div class="card-body">
                    <form action="{{ route('qr.show', $qrData->qrcode_key) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="password">Enter Password</label>
                            <input type="password" name="password" id="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
