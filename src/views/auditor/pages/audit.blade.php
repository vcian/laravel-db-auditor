@extends('DBAuditor::auditor.layouts.default')

@push('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.6.5/flowbite.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="tab-content bg-no-repeat bg-cover mt-3 p-3 bg-gray-bg">
    <div class="tabs__tab active" id="tab_standard" data-tab-info>
        <table id="standards" class="table table-stripped table-bordered display nowrap w-100 border-gray" style="width:100%">
            <thead class="bg-black text-white">
                <tr>
                    <th></th>
                    <th class="text-center uppercase text-sm">Table</th>
                    <th class="text-center uppercase text-sm">Size</th>
                    <th class="text-center uppercase text-sm">Result</th>
                </tr>
            </thead>
        </table>
    </div>
    <div class="tabs__tab" id="tab_constraint" data-tab-info>
        <div class="dropdown mb-3">
            <button
                class="btn dropdown-toggle bg-light-black border-0 rounded-2xl text-white w-64 text-start relative text-sm hover:bg-light-black focus:bg-light-black active:bg-light-black"
                type="button" data-bs-toggle="dropdown" aria-expanded="false">
                Users
            </button>
            <ul class="dropdown-menu w-64 bg-black">
                <li class="text-gray-dark p-2">Action</li>
                <li class="text-gray-dark p-2">Another action</li>
                <li class="text-gray-dark p-2">Something else here</li>
            </ul>
        </div>
        <table id="constraints" class="table table-stripped table-bordered display nowrap w-100 border-gray"
            style="width: 100%">
            <thead class="bg-black text-white">
                <tr>
                    <th class="text-center uppercase text-sm">Columns</th>
                    <th class="text-center uppercase text-sm">Primary key</th>
                    <th class="text-center uppercase text-sm">Foreign key</th>
                    <th class="text-center uppercase text-sm">Indexing</th>
                    <th class="text-center uppercase text-sm">Unique key</th>
                </tr>
            </thead>
            <tbody class="bg-light-black">
                <tr>
                    <td class="text-center text-gray-dark text-sm text-white">
                        Comments
                    </td>
                    <td class="text-center text-gray-dark text-sm">
                        <img src="{{ asset('auditor/icon/gray-key.svg') }}" alt="key" class="m-auto" />
                    </td>
                    <td class="text-center text-gray-dark text-sm">
                        <img src="{{ asset('auditor/icon/gray-key.svg') }}" alt="key" class="m-auto" />
                    </td>
                    <td class="text-center text-gray-dark text-sm">
                        <img src="{{ asset('auditor/icon/close.svg') }}" alt="key" class="m-auto" />
                    </td>
                    <td class="text-center text-gray-dark text-sm">
                        <img src="{{ asset('auditor/icon/close.svg') }}" alt="key" class="m-auto" />
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.4.1/js/dataTables.responsive.min.js"></script>
<script>
    // datatable 	
    $('#constraints').DataTable({
        scrollX: true,
        scrollCollapse: true,
        filter: false,
        dom: 'rt<"bottom"lip><"clear">',
        ordering: false
    });

    $(document).ready(function() {
        var table = $('#standards').DataTable({
            scrollX: true,
            scrollCollapse: true,
            filter: false,
            dom: 'rt<"bottom"lip><"clear">',
            ordering: false,
            ajax: {
                url: '/getAudit',
            },
            columns: [{
                    className: 'dt-control',
                    orderable: false,
                    data: null,
                    defaultContent: '',
                },
                {
                    data: 'table'
                },
                {
                    data: 'size'
                },
                {
                    data: 'result'
                },
            ],
        });

        // Add event listener for opening and closing details
        $('#standards tbody').addClass('bg-light-black text-light-white text-center text-sm')
        $('#standards tbody').on('click', 'td.dt-control', function() {
            var tr = $(this).closest('tr');
            var row = table.row(tr);

            if (row.child.isShown()) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            } else {
                // Open this row
                row.child(format(row.data())).show();
                tr.addClass('shown');
            }
        });
    });

    function format(d) {
        return (
            '<p class="text-left mb-2">Media state</p>' +
            '<table class="table table-stripped table-bordered display nowrap w-100 border-gray" cellpadding="5" cellspacing="0" border="0">' +
            '<thead class="bg-black">' +
            '<tr>' +
            '<th class="text-white text-center text-sm uppercase">Name</th>' +
            '</th>' +
            '<th class="text-white text-center text-sm uppercase">Naming conversation</th>' +
            '</th>' +
            '<th class="text-white text-center text-sm uppercase">Name standards</th>' +
            '</th>' +
            '<th class="text-white text-center text-sm uppercase">Data type</th>' +
            '</th>' +
            '</thead>' +
            '<tbody class="bg-black" >' +
            '<tr>' +
            '<td class="text-white text-center text-sm">' +
            d.name +
            '</td>' +
            '<td class="text-white text-center text-sm">' +
            d.conversation +
            '</td>' +
            '<td class="text-white text-center text-sm">' +
            d.standards +
            '</td>' +
            '<td class="text-white text-center text-sm">' +
            d.type +
            '</td>' +
            '</tr>' +
            '</tbody>' +
            '</table>'
        );
    }

    $(document).on('click','.custom-action',function(){
        $('#constraints').DataTable().draw();
        $('#standards').DataTable().draw();
    });
</script>
@endsection