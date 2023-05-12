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
        <div id="main-content" class="h-full w-full relative overflow-y-auto lg:ml-64">
            <div class="p-6">
               <div class="max-w-xs mb-5 float-right">
                   <input type="text" name="email" class="mt-1 px-3 py-2 bg-white border shadow-sm border-slate-300 placeholder-slate-400 focus:outline-none focus:border-sky-500 focus:ring-sky-500 block w-full rounded-md sm:text-sm focus:ring-1" placeholder="Search" />
               </div>
            <table class="border-collapse table-auto w-full text-sm border">
                <thead class="bg-gray-500 text-white">
                <tr>
                    <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-3 pb-3 text-slate-400 text-white text-left">Query</th>
                    <th class="border-b dark:border-slate-600 font-medium p-4 pt-4 pb-3 text-slate-400 text-white text-left">Method</th>
                    <th class="border-b dark:border-slate-600 font-medium p-4 pt-4 pb-3 text-slate-400 text-white text-left">Duration</th>
                    <th class="border-b dark:border-slate-600 font-medium p-4 pr-8 pt-3 pb-3 text-slate-400 text-white text-left">Happened</th>
                    <th class="border-b dark:border-slate-600 font-medium p-4 pr-8 pt-3 pb-3 text-slate-400 text-white text-left">Actions</th>
                </tr>
                 </thead>
                <tbody class="bg-white dark:bg-slate-800">
                @if($responseData)
                    @foreach($responseData as $response)
                        <tr>
                            <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">{{ $response['query'] ?? '' }}</td>
                            <td class="border-b border-slate-100 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">{{ $response['method'] ?? '' }}</td>
                            <td class="border-b border-slate-100 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">{{ $response['duration'] ?? '' }}ms</td>
                            <td class="border-b border-slate-100 dark:border-slate-700 p-4 pr-8 text-slate-500 dark:text-slate-400">4h ago</td>
                            <td class="border-b border-slate-100 dark:border-slate-700 p-4 pr-8 text-slate-500 dark:text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#292b2c" class="w-6 h-6">
                                    <path fill-rule="evenodd" d="M17.663 3.118c.225.015.45.032.673.05C19.876 3.298 21 4.604 21 6.109v9.642a3 3 0 01-3 3V16.5c0-5.922-4.576-10.775-10.384-11.217.324-1.132 1.3-2.01 2.548-2.114.224-.019.448-.036.673-.051A3 3 0 0113.5 1.5H15a3 3 0 012.663 1.618zM12 4.5A1.5 1.5 0 0113.5 3H15a1.5 1.5 0 011.5 1.5H12z" clip-rule="evenodd"></path>
                                    <path d="M3 8.625c0-1.036.84-1.875 1.875-1.875h.375A3.75 3.75 0 019 10.5v1.875c0 1.036.84 1.875 1.875 1.875h1.875A3.75 3.75 0 0116.5 18v2.625c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 013 20.625v-12z"></path>
                                    <path d="M10.5 10.5a5.23 5.23 0 00-1.279-3.434 9.768 9.768 0 016.963 6.963 5.23 5.23 0 00-3.434-1.279h-1.875a.375.375 0 01-.375-.375V10.5z"></path>
                                </svg>
                            </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            </div>
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

</div>
</body>
</html>

