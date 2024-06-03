@extends('layouts.app')

@section('content')

<div style="background-color: #DDE6ED;" class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <form action="{{ route('profile.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="max-w-xl">
                    <!-- Input for uploading picture -->
                    <div class="mb-6">
                        <label for="picture" class="block text-sm font-medium text-gray-700">Upload Picture</label>
                        <div class="mt-1 flex items-center">
                            <input type="file" name="picture" id="picture" class="hidden" accept="image/*">
                            <label for="picture" class="cursor-pointer">
                                <span class="py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                                    Choose File
                                </span>
                            </label>
                        </div>
                    </div>

                    <!-- Display image preview -->
                    <div class="mb-6 rounded-md bg-gray-200 mr-2" id="imagePreview" style="width:200px; height:200px">
                        <img src="" alt="Image Preview" class="mt-2 rounded-md object-cover" style="width:200px; height:200px">
                    </div>

                    <!-- JavaScript to display image preview -->
                    <script>
                        document.getElementById('picture').addEventListener('change', function (event) {
                            var input = event.target;
                            var reader = new FileReader();
                            reader.onload = function () {
                                var image = document.getElementById('imagePreview');
                                image.querySelector('img').src = reader.result;
                                image.style.display = 'block';
                            };
                            reader.readAsDataURL(input.files[0]);
                        });
                    </script>
                </div>

                <!-- Submit button -->
                <div class="mt-8">
                    <button type="submit" class="py-1.5 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-700 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        SAVE
                    </button>
                </div>
            </form>
        </div>

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
