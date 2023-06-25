<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Laravel DB Auditor</title>
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous" />
    <style>
        body {
            background-color: #000000;
        }

        .bg-light-black {
            background-color: #1e1f25 !important;
        }

        .border-gray {
            border-color: #4c4c4c;
        }

        .rounded-2xl {
            border-radius: 1rem;
        }

        .text-gray-dark {
            color: #d1d1d1;
        }

        /* tabs  */
        [data-tab-info] {
            display: none;
        }

        .active[data-tab-info] {
            display: block;
        }

        .tabs {
            border-bottom: 1px solid #4c4c4c;
        }

        .tabs button svg {
            fill: #ffffff;
        }

        .tabs button {
            color: #ffffff;
        }

        .tabs button.active {
            color: #4bc1db;
        }

        .tabs button.active svg {
            fill: #4bc1db;
            .bg-main
        }

        .tabs button.active::after {
            position: absolute;
            content: "";
            border-bottom: 1px solid #4bc1db;
            width: 100%;
            height: 1px;
            bottom: 0;
            left: 0;
        }

        .tab-content {
            background-image: url("{{ asset('auditor/icon/bg-gray.png') }}");
        }

        /* tabs end  */

        .constraints tbody {
            color: white;
            text-align: center;
        }

        /* table bottom  */
        table.dataTable td.dt-control:before {
            height: 26px !important;
            width: 26px !important;
            color: white !important;
            border: 0.15em solid #4bc1db !important;
            border-radius: 3px !important;
            background-color: #4bc1db !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin: 0 auto !important;
        }

        table.dataTable tr.dt-hasChild td.dt-control:before {
            background-color: transparent !important;
            border-color: #4bc1db !important;
            color: #4bc1db !important;
        }

        .dropdown-toggle::after {
            right: 20px;
            top: 15px;
            position: absolute;
        }

        .bottom {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 15px;
            color: #b7b7ba;
            font-size: 11px;
        }

        .bottom .dataTables_length.form-select {
            background-color: #1e1f25;
            border: 1px solid #4c4c4c;
            color: #b7b7ba;
            height: 30px;
            font-size: 12px;
        }

        .bottom .dataTables_info {
            padding: 0 !important;
            margin-left: 10px;
            font-size: 12px;
        }

        .bottom .dataTables_paginate {
            margin-left: auto !important;
        }

        .bottom .dataTables_paginate.paginate_button.next.page-link,
        .bottom .dataTables_paginate.paginate_button.previous.page-link {
            background-color: #1e1f25;
        }

        .bottom .dataTables_paginate .paginate_button .page-link {
            background-color: #0c0c16;
            border: 0;
            color: #b7b7ba;
            font-size: 12px;
        }

        .bottom .dataTables_paginate.paginate_button.page-link: focus {
            box-shadow: none;
        }

        .bottom .dataTables_paginate.page-link.active,
        .active>.page-link {
            background-color: #4bc1db !important;
            color: #f1f1f1 !important;
        }

        /* table bottom end  */
        .dropdown-toggle::after {
            right: 20px;
            top: 15px;
            position: absolute;
        }

        .bg-main {
            background-image: url({{ asset('auditor/icon/bg.png') }});
        }
    </style>
</head>

<body class="bg-black bg-main bg-[length:100%] text-white font-raleway h-[85vh] mb-10">
    <div id="app">
        <a href="https://github.com/vcian/laravel-db-auditor" class="flex justify-center pl-3 pt-3" target=”_blank”>
            <img src="{{ asset('auditor/icon/laraveldbauditor.png') }}" alt="Logo" width="180">
        </a>
        <div class="p-3 pt-0" style="padding-bottom: 6% !important">
            @yield('section')
        </div>
    </div>
    <div class="px-4 fixed w-100 bottom-0 mt-auto bg-dark" style="z-index: -1">
        <div class="container mx-auto">
            <div class="flex justify-center py-3 md:py-5 xl:py-7">
                <p class="text-sm text-gray-light text-white">©2023 <a href="https://viitorcloud.com/"
                        target="_blank">ViitorCloud Technologies</a>. All rights reserved</p>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>
    @yield('script')
</body>

</html>
