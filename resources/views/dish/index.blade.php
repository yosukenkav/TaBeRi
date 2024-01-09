<!-- resources/views/dish/index.blade.php -->

<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-200">
      {{ __('一覧') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:w-10/12 md:w-8/10 lg:w-8/12">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-800 border-b border-grey-200 dark:border-gray-800">
          <table class="text-center w-full border-collapse">
            <thead>
              <tr>
                 <th class="py-4 px-6 bg-gray-lightest dark:bg-gray-darkest font-bold text-lg text-gray-dark dark:text-gray-200 border-b border-grey-light dark:border-grey-dark">体重(kg)</th>
                 <th class="py-4 px-6 bg-gray-lightest dark:bg-gray-darkest font-bold uppercase text-lg text-gray-dark dark:text-gray-200 border-b border-grey-light dark:border-grey-dark" white-space: nowrap>朝食画像</th>
                 <th class="py-4 px-6 bg-gray-lightest dark:bg-gray-darkest font-bold uppercase text-lg text-gray-dark dark:text-gray-200 border-b border-grey-light dark:border-grey-dark" white-space: nowrap>昼食画像</th>
                 <th class="py-4 px-6 bg-gray-lightest dark:bg-gray-darkest font-bold uppercase text-lg text-gray-dark dark:text-gray-200 border-b border-grey-light dark:border-grey-dark" white-space: nowrap>夕食画像</th>
                 <th class="py-4 px-6 bg-gray-lightest dark:bg-gray-darkest font-bold uppercase text-lg text-gray-dark dark:text-gray-200 border-b border-grey-light dark:border-grey-dark"white-space: >プロテイン(杯数)</th>
                 <th class="py-4 px-6 bg-gray-lightest dark:bg-gray-darkest font-bold text-lg text-gray-dark dark:text-gray-200 border-b border-grey-light dark:border-grey-dark"white-space: >理想的なタンパク質摂取量(g)</th>
                 <th class="py-4 px-6 bg-gray-lightest dark:bg-gray-darkest font-bold text-lg text-gray-dark dark:text-gray-200 border-b border-grey-light dark:border-grey-dark"white-space: >タンパク質摂取量(g)</th>
                 <th class="py-4 px-6 bg-gray-lightest dark:bg-gray-darkest font-bold text-lg text-gray-dark dark:text-gray-200 border-b border-grey-light dark:border-grey-dark"white-space: >判定</th>
                 


              </tr>
            </thead>
            <tbody> <!--情報一覧-->
              @foreach ($dishes as $dish)
              <tr class="hover:bg-gray-lighter">
                    <td class="py-4 px-6 border-b border-gray-light dark:border-gray-600 dark:text-gray-200" white-space: nowrap>{{$dish->weight}}</td>     
                    <td class="py-4 px-6 border-b border-gray-light dark:border-gray-600">
                                    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/css/lightbox.css" rel="stylesheet">
                                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/js/lightbox.min.js" type="text/javascript"></script>
                                    <a href="{{ Storage::url($dish->image_breakfast) }}" data-lightbox="group"><img src="{{ Storage::url($dish->image_breakfast) }}" style="display: block; margin: auto;" width="25%"></a>

                    </td>
                    <td class="py-4 px-6 border-b border-gray-light dark:border-gray-600">
                                    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/css/lightbox.css" rel="stylesheet">
                                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/js/lightbox.min.js" type="text/javascript"></script>
                                    <a href="{{ Storage::url($dish->image_lunch) }}" data-lightbox="group"><img src="{{ Storage::url($dish->image_lunch) }}" style="display: block; margin: auto;" width="25%"></a>

                    </td>
                    <td class="py-4 px-6 border-b border-gray-light dark:border-gray-600">
                                    <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/css/lightbox.css" rel="stylesheet">
                                    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
                                    <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/js/lightbox.min.js" type="text/javascript"></script>
                                    <a href="{{ Storage::url($dish->image_dinner) }}" data-lightbox="group"><img src="{{ Storage::url($dish->image_dinner) }}" style="display: block; margin: auto;" width="25%"></a>

                    </td>
                    <td class="py-4 px-6 border-b border-gray-light dark:border-gray-600 dark:text-gray-200" white-space: nowrap>{{$dish->protein_drinks}}</td>
                    <td class="py-4 px-6 border-b border-gray-light dark:border-gray-600 dark:text-gray-200" white-space: nowrap>{{ $dish->ideal_protein_amount }}</td>
                    <td class="py-4 px-6 border-b border-gray-light dark:border-gray-600 dark:text-gray-200" white-space: nowrap>{{ $dish->actual_protein_amount }}</td>
                    <td class="py-4 px-6 border-b border-gray-light dark:border-gray-600 dark:text-gray-200" white-space: nowrap>{{ $dish->protein_amount_judge }}</td>

                    {{ isset($chat_response) ? $chat_response : '' }}

                  <div class="flex">
                    <!--更新ボタン-->
                    <!--削除ボタン-->
                    <form action="{{ route('dish.destroy',$dish->id) }}" method="POST" class="text-left">
                      @method('delete')
                      @csrf
                      <td><!--<td>で投稿の横に削除ボタンを配置-->
                      <x-primary-button class="ml-3">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="gray">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </x-primary-button>
                      </td>
                    </form>
                     
                  </div>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>

