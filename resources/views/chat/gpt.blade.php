<!-- resources/views/chat/index.blade.php -->

<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-gray-200">
      {{ __('chat Index') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:w-10/12 md:w-8/10 lg:w-8/12">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white dark:bg-gray-800 border-b border-grey-200 dark:border-gray-800">
          <table class="text-center w-full border-collapse">
            <thead>
              <tr>
                <th class="py-4 px-6 bg-gray-lightest dark:bg-gray-darkest font-bold uppercase text-lg text-gray-dark dark:text-gray-200 border-b border-grey-light dark:border-grey-dark">chat</th>
              </tr>
            </thead>
            <tbody>
              
              <tr class="hover:bg-gray-lighter">
                <td class="py-4 px-6 border-b border-gray-light dark:border-gray-600"white-space: normal text-overflow: clip>
                 
                  <div class="flex"white-space: normal text-overflow: clip>
                    <form method="POST" enctype="multipart/form-data">
                        @csrf
                        <textarea rows="10" cols="50" name="sentence">{{ isset($sentence) ? $sentence : '' }}</textarea>
                        
                        <!-- <input type="text" id="prompt-input" placeholder="Enter a prompt" />     
                         URL“ü—ÍƒGƒŠƒA -->
                        <!-- <input type="text" id="url-input" placeholder="Enter a URL" />
                        <button type="submit">Generate</button>
        -->
                        <button type="submit">ChatGPT</button>
                    </form>
                    <!-- <div id="vision-container"></div> -->

                    {{-- Œ‹‰Ê --}}
                    {{ isset($chat_response) ? $chat_response : '' }}
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

