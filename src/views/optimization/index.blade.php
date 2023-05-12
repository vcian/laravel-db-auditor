<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/sql-formatter@2.3.3/dist/sql-formatter.min.js"></script>
    <style>
        :root {
            --primary: white;
            --secondary: gray;
        }

        /* Scrollbar styles */

        /* Firefox */
        * {
            scrollbar-width: thin;
            scrollbar-color: var(--primary) var(--secondary);
        }

        /* Chrome, Edge, and Safari */
        *::-webkit-scrollbar {
            width: 12px;
        }

        *::-webkit-scrollbar-track {
            background: var(--primary);
        }

        *::-webkit-scrollbar-thumb {
            background-color: var(--secondary);
            /*   background: repeating-linear-gradient(
                45deg,
                var(--secondary),
                var(--secondary) 5px,
                var(--primary) 5px,
                var(--primary) 10px
              ); */
            border-radius: 20px;
            border: 2px solid var(--primary);
        }
    </style>
</head>
<body>
<!-- component -->
<!-- This is an example component -->
<div>
    <nav class="bg-white border-b border-gray-200 fixed z-30 w-full">
        <div class="px-3 py-5 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex items-center justify-start">
                    <a href="#" class="text-xl font-bold flex items-center lg:ml-2.5">
                        <img src="https://demo.themesberg.com/windster/images/logo.svg" class="h-6 mr-2"
                             alt="Windster Logo">
                        <span class="self-center whitespace-nowrap">Laravel DB Auditor</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>
    <div class="flex overflow-hidden bg-white pt-16">
        <aside id="sidebar"
               class="fixed hidden z-20 h-full top-0 left-0 pt-16 flex lg:flex flex-shrink-0 flex-col w-64 transition-width duration-75"
               aria-label="Sidebar">
            <div class="relative flex-1 flex flex-col min-h-0 border-r border-gray-200 bg-white pt-0">
                <div class="flex-1 flex flex-col pt-5 pb-4 overflow-y-auto">
                    <div class="flex-1 px-3 bg-white divide-y space-y-1">
                        <ul class="space-y-2 pb-2">
                            <li>
                                <form action="#" method="GET" class="lg:hidden">
                                    <label for="mobile-search" class="sr-only">Search</label>
                                    <div class="relative">
                                        <div
                                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20"
                                                 xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                            </svg>
                                        </div>
                                        <input type="text" name="email" id="mobile-search"
                                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-cyan-600 focus:ring-cyan-600 block w-full pl-10 p-2.5"
                                               placeholder="Search">
                                    </div>
                                </form>
                            </li>
                            <li>
                                <a href="{{ route('db-auditor.optimization') }}"
                                   class="text-base text-gray-900 font-normal rounded-lg flex items-center p-2 hover:bg-gray-100 group ">
                                    <svg class="w-6 h-6 text-gray-500 group-hover:text-gray-900 transition duration-75"
                                         fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path>
                                        <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path>
                                    </svg>
                                    <span class="ml-3">Query Optimization</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('db-auditor.logs') }}"
                                   class="text-base text-gray-900 font-normal rounded-lg hover:bg-gray-100 flex items-center p-2 group ">
                                    <svg
                                        class="w-6 h-6 text-gray-500 flex-shrink-0 group-hover:text-gray-900 transition duration-75"
                                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                                    </svg>
                                    <span class="ml-3 flex-1 whitespace-nowrap">Query Logs</span>
                                </a>
                            </li>
                            <li>
                                <a href="#" target="_blank"
                                   class="text-base text-gray-900 font-normal rounded-lg hover:bg-gray-100 flex items-center p-2 group ">
                                    <svg
                                        class="w-6 h-6 text-gray-500 flex-shrink-0 group-hover:text-gray-900 transition duration-75"
                                        fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8.707 7.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l2-2a1 1 0 00-1.414-1.414L11 7.586V3a1 1 0 10-2 0v4.586l-.293-.293z"></path>
                                        <path
                                            d="M3 5a2 2 0 012-2h1a1 1 0 010 2H5v7h2l1 2h4l1-2h2V5h-1a1 1 0 110-2h1a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V5z"></path>
                                    </svg>
                                    <span class="ml-3 flex-1 whitespace-nowrap">Commands</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </aside>
        <div class="bg-gray-900 opacity-50 hidden fixed inset-0 z-10" id="sidebarBackdrop"></div>
        <div id="main-content" class="h-full w-full bg-gray-50 relative overflow-y-auto lg:ml-64">
            <main class="">
                <div class="pt-6 px-4">
                    <div class="grid grid-cols-1 2xl:grid-cols-2 xl:gap-4 my-4">
                        <div class="bg-white shadow rounded-lg mb-4 p-4 sm:p-6 h-full">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold leading-none text-gray-900">Input Query</h3>
                                <div class="action flex justify-between">
                                    <a href="" class="rounded border-2 border-black p-2 ml-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#292b2c"
                                             class="w-6 h-6">
                                            <path fill-rule="evenodd"
                                                  d="M17.663 3.118c.225.015.45.032.673.05C19.876 3.298 21 4.604 21 6.109v9.642a3 3 0 01-3 3V16.5c0-5.922-4.576-10.775-10.384-11.217.324-1.132 1.3-2.01 2.548-2.114.224-.019.448-.036.673-.051A3 3 0 0113.5 1.5H15a3 3 0 012.663 1.618zM12 4.5A1.5 1.5 0 0113.5 3H15a1.5 1.5 0 011.5 1.5H12z"
                                                  clip-rule="evenodd"/>
                                            <path
                                                d="M3 8.625c0-1.036.84-1.875 1.875-1.875h.375A3.75 3.75 0 019 10.5v1.875c0 1.036.84 1.875 1.875 1.875h1.875A3.75 3.75 0 0116.5 18v2.625c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 013 20.625v-12z"/>
                                            <path
                                                d="M10.5 10.5a5.23 5.23 0 00-1.279-3.434 9.768 9.768 0 016.963 6.963 5.23 5.23 0 00-3.434-1.279h-1.875a.375.375 0 01-.375-.375V10.5z"/>
                                        </svg>
                                    </a>
                                    <a href="" class="rounded border-2 border-black ml-2 p-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#d9534f"
                                             class="w-6 h-6 ">
                                            <path fill-rule="evenodd"
                                                  d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z"
                                                  clip-rule="evenodd"/>
                                        </svg>
                                    </a>
                                    <a class="rounded border-2 border-black p-2 ml-2 query-optimization">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#5cb85c"
                                             class="w-6 h-6">
                                            <path
                                                d="M3.478 2.405a.75.75 0 00-.926.94l2.432 7.905H13.5a.75.75 0 010 1.5H4.984l-2.432 7.905a.75.75 0 00.926.94 60.519 60.519 0 0018.445-8.986.75.75 0 000-1.218A60.517 60.517 0 003.478 2.405z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div class="flow-root">
                                <textarea
                                    class="input-data placeholder:italic placeholder:text-slate-400 text-white block bg-black w-full border text-lg border-slate-300 rounded-md py-4 pl-4 pr-4  shadow-sm focus:outline-none focus:border-sky-500 focus:ring-sky-500 focus:ring-1 sm:text-lg"
                                    placeholder="Enter Query..." rows="20" name="search"></textarea>
                            </div>
                        </div>
                        <div class="bg-white shadow rounded-lg p-4 sm:p-6 xl:p-8 ">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-xl font-bold leading-none text-gray-900">Optimized Query</h3>
                                <div class="flex">
                                    <a href="" class="rounded border-2 border-black p-2 ml-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                             stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                  d="M19.5 12c0-1.232-.046-2.453-.138-3.662a4.006 4.006 0 00-3.7-3.7 48.678 48.678 0 00-7.324 0 4.006 4.006 0 00-3.7 3.7c-.017.22-.032.441-.046.662M19.5 12l3-3m-3 3l-3-3m-12 3c0 1.232.046 2.453.138 3.662a4.006 4.006 0 003.7 3.7 48.656 48.656 0 007.324 0 4.006 4.006 0 003.7-3.7c.017-.22.032-.441.046-.662M4.5 12l3 3m-3-3l-3 3"/>
                                        </svg>
                                    </a>
                                    <a href="" class="rounded border-2 border-black p-2 ml-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#292b2c"
                                             class="w-6 h-6">
                                            <path fill-rule="evenodd"
                                                  d="M17.663 3.118c.225.015.45.032.673.05C19.876 3.298 21 4.604 21 6.109v9.642a3 3 0 01-3 3V16.5c0-5.922-4.576-10.775-10.384-11.217.324-1.132 1.3-2.01 2.548-2.114.224-.019.448-.036.673-.051A3 3 0 0113.5 1.5H15a3 3 0 012.663 1.618zM12 4.5A1.5 1.5 0 0113.5 3H15a1.5 1.5 0 011.5 1.5H12z"
                                                  clip-rule="evenodd"/>
                                            <path
                                                d="M3 8.625c0-1.036.84-1.875 1.875-1.875h.375A3.75 3.75 0 019 10.5v1.875c0 1.036.84 1.875 1.875 1.875h1.875A3.75 3.75 0 0116.5 18v2.625c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 013 20.625v-12z"/>
                                            <path
                                                d="M10.5 10.5a5.23 5.23 0 00-1.279-3.434 9.768 9.768 0 016.963 6.963 5.23 5.23 0 00-3.434-1.279h-1.875a.375.375 0 01-.375-.375V10.5z"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                            <div class="block w-full overscroll-none"
                                 style="max-height: 600px; overflow: hidden; overflow-y: scroll">
                                <pre
                                    class="placeholder:italic placeholder:text-slate-400 text-black block bg-gray-100 w-full border text-lg border-slate-300 rounded-md py-4 pl-4 pr-4 shadow-sm focus:outline-none focus:border-sky-500 focus:ring-sky-500 focus:ring-1 sm:text-lg overscroll-none overscroll-y-auto">

                                </pre>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <footer
                class="bg-white md:flex md:items-center md:justify-center shadow rounded-lg p-4 md:p-6 xl:p-4 fixed bottom-0 left-0 w-full"
                style="padding-left: 255px;">
                <p class="text-center text-sm text-gray-500">
                    &copy; 2023 <a href="#" class="hover:underline" target="_blank">Viitorcloud Technologies
                        PVT.LTD </a>. All rights reserved.
                </p>
            </footer>
        </div>
    </div>
    <script async defer src="https://buttons.github.io/buttons.js"></script>
    <script src="https://demo.themesberg.com/windster/app.bundle.js"></script>
    <script src="https://unpkg.com/sql-formatter@2.3.3/dist/sql-formatter.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script>
        var textarea = document.querySelector('textarea');
        var code = document.querySelector('pre');
        var format = window.sqlFormatter.format;

        textarea.addEventListener('blur', function (event) {
            textarea.value = format(textarea.value);
        });
        // textarea.addEventListener('input', function (event) {
        //     code.innerText = format(event.target.value);
        // });

        let queryOptimizationModule = {
            queryOptimization: function () {
                jQuery(document).on('click', '.query-optimization', function (e) {
                    e.preventDefault();
                    let query = $('.input-data').val();

                    jQuery.ajax({
                        url: 'https://api.openai.com/v1/completions',
                        headers: {
                            "Authorization": "Bearer",
                            "Content-Type": "application/json"
                        },
                        type: 'POST',
                        data : JSON.stringify({
                            "model": "text-davinci-003",
                            "prompt": "Give me well optimised query of "+query,
                            "temperature": 0.3,
                            "max_tokens": 600,
                            "top_p": 1.0,
                            "frequency_penalty": 0.0,
                            "presence_penalty": 0.0
                        }),
                        success: function (data) {
                            console.log(data.choices[0].text);
                            code.innerText = format(data.choices[0].text);
                        }
                    });
                });
            },
            init: function () {
                this.queryOptimization();
            }
        }

        queryOptimizationModule.init();
    </script>
</div>
</body>
</html>

