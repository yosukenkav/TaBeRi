<!-- resources/views/dish/create.blade.php -->

<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-200">
      {{ __('情報入力') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:w-8/12 md:w-1/2 lg:w-5/12">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-800 ">
          @include('common.errors')
          <form class="mb-6" action="{{ route('dish.store') }}" method="POST" >
            @csrf
            <div class="form-group">
                <label for="weight">体重（kg）:</label>
                <input type="number" step="any" class="form-control" id="weight" name="weight" required>
            </div>
            <div class="form-group">
                <label for="protein_drinks">プロテイン（杯）:</label>
                <input type="number" step="any" class="form-control" id="protein_drinks" name="protein_drinks" required>           
            </div>
           
            <div class="flex items-center justify-end mt-4">
              <x-primary-button class="ml-3">
                {{ __('登録') }}
              </x-primary-button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>

