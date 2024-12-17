@extends('layouts.adminLayout')

@section('content')
<div class="form-card">
    <div class="heading-div">
        <h2>{{ isset($user) ? 'Edit User' : 'Add User' }}</h2>
        <a href="{{ asset('/manageUser') }}" class="btnn btn-yellow">Back</a>
    </div>
    <div class="main-inner">
        <form name="valid_cont" class="triple-fields" id="send" method="POST" action="{{ isset($user) ? route('updateUserFunction', Crypt::encrypt($user->id)) : route('addUserFunction') }}">
            @csrf
            @if(isset($user))
                @method('POST')
            @endif
            <div class="form-group">
                <label for="txtName">Full Name*</label>
                <input class="form-control" type="text" id="txtName" name="txtName" maxlength="60" value="{{ old('txtName', isset($user) ? $user->name : '') }}">
                <span class="text-danger">
                    @error('txtName')
                        * {{ $message }}
                    @enderror
                </span>
            </div>
            <div class="form-group">
                <label for="txtEmail">Email*</label>
                <input class="form-control" type="text" id="txtEmail" name="txtEmail" maxlength="60" value="{{ old('txtEmail', isset($user) ? $user->email : '') }}">
                <span class="text-danger">
                    @error('txtEmail')
                        * {{ $message }}
                    @enderror
                </span>
            </div>
            <div class="form-group">
                <label for="txtPhone">Phone Number*</label>
                <input class="form-control" type="tel" id="txtPhone" name="txtPhone" maxlength="15" value="{{ old('txtPhone', isset($user) ? $user->phone_no : '') }}">
                <span class="text-danger">
                    @error('txtPhone')
                        * {{ $message }}
                    @enderror
                </span>
            </div>
            {{-- <div class="form-group">
                <label for="access_group_id">Access Group*</label>
                <select class="form-control" id="access_group_id" name="access_group_id">
                    @foreach($accessGroups as $group)
                        <option value="{{ $group->id }}" {{ isset($user) && $user->access_group_id == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                    @endforeach
                </select>
                <span class="text-danger"> @error('access_group_id')     * {{ $message }} @enderror</span>
            </div> --}}
            <div class="button-input">
                <button class="btnn btn-green" type="submit">{{ isset($user) ? 'Update' : 'Add' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
