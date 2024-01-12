<!-- resources/views/chat/index.blade.php -->

<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-200">
      {{ __('ChatGPTコーナー') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:w-10/12 md:w-8/10 lg:w-8/12">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-800 border-b border-grey-200 dark:border-gray-800">
          <table class="text-center w-full border-collapse">
            <thead>
              <tr>
                <th class="py-4 px-6 bg-gray-lightest dark:bg-gray-darkest font-bold  text-lg text-gray-dark dark:text-gray-200 border-b border-grey-light dark:border-grey-dark">ChatGPTで質問しよう</th>
              </tr>
            </thead>
            <tbody>
              
              <tr class="hover:bg-gray-lighter">
                <td class="py-4 px-6 border-b border-gray-light dark:border-gray-600">
                 
                  <div class="flex ">
                    <form method="POST" enctype="multipart/form-data" class="flex">
                        @csrf
                        <textarea rows="10" cols="50" name="sentence" class="mr-2 rounded-lg border border-gray-300 p-2">{{ isset($sentence) ? $sentence : '' }}</textarea>                      
                          <div class="flex items-center ">
                              <x-primary-button class="ml-3 flex items-center  bg-orange-500 hover:bg-orange-700 text-white px-4 py-2 rounded">
                                <svg class="h-6 w-6 flex items-center  " fill="none" viewBox="0 0 24 24" stroke="white">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                </svg>
                              </x-primary-button>
                          </div>
                        
                         <!-- <button type="submit">ChatGPT</button> -->
                         {{-- 結果 --}}
                    {{ isset($chat_response) ? $chat_response : '' }}
                    </form>
                    <!-- <div id="vision-container"></div> -->
                  </div>
                  
                </td>
                
              </tr>      
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>

