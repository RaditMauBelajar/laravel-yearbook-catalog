<!-- Alert Component - Success, Error, Info, Warning -->
@if(session('success'))
<div x-data="{ show: true }"
     x-show="show"
     x-transition
     x-init="setTimeout(() => show = false, 5000)"
     class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
    <div class="bg-green-50 border-l-4 border-green-500 rounded-lg p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-check-circle text-green-500 text-xl mr-3"></i>
                <div>
                    <p class="text-sm font-medium text-green-800">
                        {{ session('success') }}
                    </p>
                </div>
            </div>
            <button @click="show = false" class="text-green-500 hover:text-green-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>
@endif

@if(session('error'))
<div x-data="{ show: true }"
     x-show="show"
     x-transition
     x-init="setTimeout(() => show = false, 5000)"
     class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
    <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3"></i>
                <div>
                    <p class="text-sm font-medium text-red-800">
                        {{ session('error') }}
                    </p>
                </div>
            </div>
            <button @click="show = false" class="text-red-500 hover:text-red-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>
@endif

@if(session('info'))
<div x-data="{ show: true }"
     x-show="show"
     x-transition
     x-init="setTimeout(() => show = false, 5000)"
     class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
    <div class="bg-blue-50 border-l-4 border-blue-500 rounded-lg p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-info-circle text-blue-500 text-xl mr-3"></i>
                <div>
                    <p class="text-sm font-medium text-blue-800">
                        {{ session('info') }}
                    </p>
                </div>
            </div>
            <button @click="show = false" class="text-blue-500 hover:text-blue-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>
@endif

@if(session('warning'))
<div x-data="{ show: true }"
     x-show="show"
     x-transition
     x-init="setTimeout(() => show = false, 5000)"
     class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
    <div class="bg-yellow-50 border-l-4 border-yellow-500 rounded-lg p-4 shadow-sm">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-exclamation-triangle text-yellow-500 text-xl mr-3"></i>
                <div>
                    <p class="text-sm font-medium text-yellow-800">
                        {{ session('warning') }}
                    </p>
                </div>
            </div>
            <button @click="show = false" class="text-yellow-500 hover:text-yellow-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>
@endif

@if($errors->any())
<div x-data="{ show: true }"
     x-show="show"
     x-transition
     class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
    <div class="bg-red-50 border-l-4 border-red-500 rounded-lg p-4 shadow-sm">
        <div class="flex items-start justify-between">
            <div class="flex items-start flex-1">
                <i class="fas fa-exclamation-circle text-red-500 text-xl mr-3 mt-0.5"></i>
                <div class="flex-1">
                    <p class="text-sm font-medium text-red-800 mb-2">
                        Terdapat {{ $errors->count() }} error:
                    </p>
                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button @click="show = false" class="text-red-500 hover:text-red-700 ml-4">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</div>
@endif
