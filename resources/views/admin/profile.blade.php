@extends('layouts.app')

@section('content')

    <div style="background-color: #DDE6ED;" class="py-12">
        <a href="{{ route('home.admin'); }}"><div style="cursor: pointer; margin-left:68px; margin-bottom:24px; display:flex; align-items:center; justify-content:center; max-width:10%;" 
        class="p-2 sm:p-2 bg-white shadow sm:rounded-lg" >
                <div class="">
                    Back
                </div>
            </div></a>
        
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
@endsection