@extends('DBAuditor::auditor.layouts.default')

@push('css')
    <style>
        #confDialog {
            padding: 20px;
            background-color: black;
            border: 1px solid #ccc;
            color: white;
            width: 500px;
        }

        #confDialog h2 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 20px;
            color: cadetblue;
        }

        #confDialog p {
            margin-bottom: 10px;
        }

        #confDialog button {
            margin-top: 10px;
            color: white;
        }

        /* Toast Container */
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            background-color: green;
            display: none;
        }

        /* Toast Message */
        .toastCustom {
            background-color: green;
            color: #fff;
            padding: 15px;
            border-radius: 5px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.3);
            transition: opacity 0.3s ease-in-out;
            width: 250px;
            text-align: center;
        }

        /* Toast Animation */
        .toastCustom.show {
            opacity: 1px !important;
        }

        .colum-value {
            display: none;
        }

        .constraint-value {
            display: none;
        }
    </style>
@endpush

@section('section')
    <div id="toasts"></div>
    <div class="tabs flex items-center pt-3 mb-3">
        <button data-tab-value="#tab_standard"
            class="active uppercase d-flex items-center me-3 text-[13px] pb-2 relative custom-action">
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="15" height="15"
                viewBox="0 0 24 24" class="me-2">
                <defs>
                    <clipPath id="clip-path">
                        <rect id="Rectangle_12463" data-name="Rectangle 12463" width="24" height="24" />
                    </clipPath>
                </defs>
                <g id="noun-grid-608343" transform="translate(-70.004 2)">
                    <g id="Mask_Group_26" data-name="Mask Group 26" transform="translate(70.004 -2)"
                        clip-path="url(#clip-path)">
                        <g id="Group_5253" data-name="Group 5253" transform="translate(2.4 2.4)">
                            <path id="Path_94449" data-name="Path 94449"
                                d="M78.731.873A.872.872,0,0,0,77.859,0H70.877A.872.872,0,0,0,70,.873V7.855a.872.872,0,0,0,.873.873h6.982a.872.872,0,0,0,.873-.873Z"
                                transform="translate(-70.004)" />
                            <path id="Path_94450" data-name="Path 94450"
                                d="M376.33,8.731h6.982a.872.872,0,0,0,.873-.873V.877A.872.872,0,0,0,383.312,0H376.33a.872.872,0,0,0-.873.873V7.859a.872.872,0,0,0,.873.873Z"
                                transform="translate(-364.984 -0.004)" />
                            <path id="Path_94451" data-name="Path 94451"
                                d="M78.731,313.308v-6.982a.872.872,0,0,0-.873-.873H70.877a.872.872,0,0,0-.873.873v6.982a.872.872,0,0,0,.873.873h6.982a.872.872,0,0,0,.873-.873Z"
                                transform="translate(-70.004 -294.98)" />
                            <path id="Path_94452" data-name="Path 94452"
                                d="M375.45,313.312a.872.872,0,0,0,.873.873H383.3a.872.872,0,0,0,.873-.873V306.33a.872.872,0,0,0-.873-.873h-6.982a.872.872,0,0,0-.873.873Z"
                                transform="translate(-364.977 -294.984)" />
                        </g>
                    </g>
                </g>
            </svg>

            Standards
        </button>
        <button data-tab-value="#tab_constraint"
            class="uppercase d-flex items-center me-3 text-[13px] pb-2 relative custom-action">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 20 20" class="me-2">
                <g id="noun-chain-1655550" transform="translate(-70.937 -14.141)">
                    <path id="Path_94447" data-name="Path 94447"
                        d="M96.886,211.746a4.451,4.451,0,0,0,.358-1.727,4.373,4.373,0,0,0-.147-1.159,5.475,5.475,0,0,0-.274-.758,4.179,4.179,0,0,0-.842-1.18,5.037,5.037,0,0,0-1.179-.842l-.632.632a1.467,1.467,0,0,0-.379,1.348,2.219,2.219,0,0,1,1.074,1.074,2.484,2.484,0,0,1,.189.779,2.155,2.155,0,0,1-.632,1.685l-4.505,4.423a2.174,2.174,0,0,1-3.074-3.075l3.474-3.475a5.065,5.065,0,0,1-.168-2.907c-.147.105-.274.232-.421.358l-4.442,4.444A4.367,4.367,0,0,0,84,214.484a4.319,4.319,0,0,0,1.284,3.1,4.408,4.408,0,0,0,6.211,0l4.463-4.465a3.447,3.447,0,0,0,.358-.421,4.349,4.349,0,0,0,.568-.948Z"
                        transform="translate(-13.065 -184.72)" />
                    <path id="Path_94448" data-name="Path 94448"
                        d="M277,18.517a4.319,4.319,0,0,0-1.284-3.1,4.408,4.408,0,0,0-6.211,0l-4.463,4.466a3.446,3.446,0,0,0-.358.421,4.359,4.359,0,0,0-.568.948,4.451,4.451,0,0,0-.358,1.727,4.373,4.373,0,0,0,.147,1.159,5.468,5.468,0,0,0,.274.758,4.179,4.179,0,0,0,.842,1.18,4.493,4.493,0,0,0,1.179.842l.632-.632a1.467,1.467,0,0,0,.379-1.348,2.219,2.219,0,0,1-1.074-1.074,2.484,2.484,0,0,1-.189-.779,2.154,2.154,0,0,1,.632-1.685l4.505-4.423a2.174,2.174,0,0,1,3.074,3.075l-3.495,3.5a5.065,5.065,0,0,1,.168,2.907c.147-.105.274-.232.421-.358l4.463-4.466A4.367,4.367,0,0,0,277,18.517Z"
                        transform="translate(-186.065 0)" />
                </g>
            </svg>

            Constraints
        </button>
    </div>
    <div class="tab-content rounded-sm bg-no-repeat bg-cover mt-3 p-3 bg-gray-bg">
        <div class="tabs__tab active" id="tab_standard" data-tab-info>
            <table id="standards" class="table table-stripped table-bordered display nowrap w-100 border-gray"
                style="width: 100%">
                <thead class="bg-black text-white">
                    <tr>
                        <th></th>
                        <th class="text-center uppercase text-sm">Table</th>
                        <th class="text-center uppercase text-sm">Size</th>
                        <th class="text-center uppercase text-sm">Status</th>
                    </tr>
                </thead>
            </table>
        </div>
        <div class="tabs__tab" id="tab_constraint" data-tab-info>

            <div class="dropdown mb-3">
                <label>Select Table</label>
                <select
                    class="btn dropdown-toggle bg-light-black border-0 rounded-2xl text-white w-64 text-start relative text-sm hover:bg-light-black focus:bg-light-black active:bg-light-black mr-1"
                    name="table" id="tbl-dropdown">

                    @foreach ($tables as $table)
                        <option class="text-gray-dark p-2" value="{{ $table }}"> {{ $table }} </option>
                    @endforeach

                </select>
            </div>
            <table id="constraints" class="table table-stripped table-bordered display nowrap w-100 border-gray constraints"
                style="width: 100%">

                <thead class="bg-black text-white">
                    <tr>
                        <th class="text-center uppercase text-sm">Columns</th>
                        <th class="text-center uppercase text-sm">Primary key</th>
                        <th class="text-center uppercase text-sm">Index Key</th>
                        <th class="text-center uppercase text-sm">Unique key</th>
                        <th class="text-center uppercase text-sm">Foreign key</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>

    <div class="toast-container">
        <div class="toastCustom"></div>
    </div>

    <dialog id="confDialog">
        <h2 class="title-dialog"></h2>
        <p class="description-dialog"></p>
        <button onclick="confirmDialog()" class="btn btn-success confirm-dialog-btn">Yes</button>
        <button onclick="closeDialog()" class="btn btn-dangers">Close</button>
    </dialog>

    <p class="colum-value"></p>
    <p class="table-value"></p>
    <p class="constraint-value"></p>
@endsection

@section('script')
    <script>
        // tabs
        const tabs = document.querySelectorAll("[data-tab-value]");
        const tabInfos = document.querySelectorAll("[data-tab-info]");

        tabs.forEach((tab) => {
            tab.addEventListener("click", () => {
                const target = document.querySelector(tab.dataset.tabValue);

                const targetValue = tab.dataset.tabValue;
                tabs.forEach((otherTab) => {
                    otherTab.classList.toggle("active", otherTab === tab);
                });

                tabInfos.forEach((tabInfo) => {
                    tabInfo.classList.toggle(
                        "active",
                        tabInfo.dataset.tabInfo === targetValue
                    );
                });
                target.classList.add("active");
            });
        });

        $(document).ready(function() {

            // Standards
            var table = $('#standards').DataTable({
                scrollX: true,
                scrollCollapse: true,
                filter: false,
                dom: 'rt<"bottom"lip><"clear">',
                ordering: false,
                "ajax": {
                    "url": "api/getAudit",
                },
                columns: [{
                        className: 'dt-control',
                        orderable: false,
                        data: null,
                        defaultContent: '',
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'size'
                    },
                    {
                        data: 'status'
                    },
                ],
            });

            // Add event listener for opening and closing details
            $('#standards tbody').addClass('bg-light-black text-gray-dark text-center text-sm')
            $('#standards tbody').on('click', 'td.dt-control', function() {
                var tr = $(this).closest('tr');
                var row = table.row(tr);
                var tableName = row.data().name;

                if (row.child.isShown()) {
                    // This row is already open - close it
                    row.child.hide();
                    tr.removeClass('shown');
                } else {
                    $.ajax({
                        type: 'GET',
                        dataType: "json",
                        url: 'api/getTableData/' + tableName,
                        success: function(data, status, xhr) {
                            row.child(format(data.data)).show();
                        }
                    });
                    tr.addClass('shown');
                }
            });

            changeTable($('#tbl-dropdown').val());

            // Constraint
            $('#tbl-dropdown').on('change', function() {
                changeTable(this.value);
            });

        });

        function format(d) {

            var tableComment = '';
            if (d.table_comment.length > 0) {

                tableComment +=
                    '<table class="table table-stripped table-bordered display nowrap w-100 border-gray" cellpadding="5" cellspacing="0" border="0">' +
                    '<thead class="bg-black">' +
                    '<tr>' +
                    '<th class="text-white text-center text-sm uppercase">Table Name Suggestion(s)</th>' +
                    '</th>' +
                    '<th class="text-white text-center text-sm uppercase">Name standards</th>' +
                    '</th>' +
                    '</thead>' +
                    '<tbody class="bg-light-black" >';

                $.each(d.table_comment, function(key, value) {
                    comment = value.split('(')[1];
                    tableComment += '<tr>' +
                        '<td class="text-white text-center text-sm">' + value.split('(')[0] + '</td>' +
                        '<td class="text-white text-center text-sm">' + comment.slice(0, -1) + '</td>' + '</tr>';

                });
                tableComment += '</tbody>' + '</table>';
            }

            var table =
                '<table class="table table-stripped table-bordered display nowrap w-100 border-gray" cellpadding="5" cellspacing="0" border="0">' +
                '<thead class="bg-black">' +
                '<tr>' +
                '<th class="text-white text-center text-sm uppercase">Column Name</th>' +
                '</th>' +
                '<th class="text-white text-center text-sm uppercase">Suggestion(s)</th>' +
                '</th>' +
                '<th class="text-white text-center text-sm uppercase">Name standards</th>' +
                '</th>' +
                '<th class="text-white text-center text-sm uppercase">Data type</th>' +
                '</th>' +
                '</thead>' +
                '<tbody class="bg-light-black" >';
            var column = [];
            var nameStandard = [];
            var dataType = [];
            $.each(d.fields, function(i, v) {
                table += '<tr>' +
                    '<td class="text-white text-center text-sm">' + i + '</td>' +
                    '<td class="text-white text-center text-sm">';

                $.each(v, function(k, value) {
                    if (typeof(value) === "string") {
                        column.push(i);
                        table += '<ul>';
                        table += '<li class="text-yellow">' + value.split('(')[0] + '</li>';
                        table += '</ul>';
                    }
                });

                if ($.inArray(i, column) == -1) {
                    table += '-';
                }

                table += '</td>' + '<td class="text-white text-center text-sm">';
                $.each(v, function(k, value) {
                    if (typeof(value) === "string") {
                        standard = value.split('(')[1];
                        if (standard !== undefined) {
                            nameStandard.push(i);
                            table += '<ul>';
                            table += '<li class="text-yellow">' + standard.slice(0, -1) + '</li>'
                            table += '</ul>';
                        }
                    }
                });

                if ($.inArray(i, nameStandard) == -1) {
                    table += '-';
                }

                table += '</td>' + '<td class="text-white text-center text-sm">';
                $.each(v, function(k, value) {
                    if (value.data_type !== undefined || value.size !== undefined) {
                        dataType.push(i);
                        table += value.data_type + "(" + value.size + ")";
                    }
                });

                if ($.inArray(i, dataType) == -1) {
                    table += '-';
                }

                table += '</td>' + '</tr>';
            });

            table += '</tbody>' + '</table>';

            return (tableComment + table);
        }

        function changeTable(tableName) {
            if ($.fn.DataTable.isDataTable('#constraints')) {
                $('#constraints').DataTable().destroy();
            }

            var constraintTable = $('#constraints').DataTable({
                scrollX: true,
                scrollCollapse: true,
                filter: false,
                dom: 'rt<"bottom"lip><"clear">',
                ordering: false,
                "ajax": {
                    "url": "api/gettableconstraint/" + tableName,
                },
                columns: [{
                        data: 'column'
                    },
                    {
                        data: 'primaryKey'
                    },
                    {
                        data: 'indexing'
                    },
                    {
                        data: 'uniqueKey'
                    },
                    {
                        data: 'foreignKey'
                    },
                ],
            });
        }

        function openDialog() {
            var dialog = document.getElementById("confDialog");
            dialog.showModal();
        }

        function closeDialog() {
            var dialog = document.getElementById("confDialog");
            dialog.close();
        }

        function confirmDialog() {
            var dialog = document.getElementById("confDialog");
            dialog.close();
            addConstraint();
        }

        function add(columnName, constraint, tableName) {

            if(constraint.toLowerCase() === "foreign") {
                $('.colum-value').replaceWith("<p class='colum-value'>" + columnName + "</p>");
                $('.table-value').replaceWith("<p class='table-value'>" + tableName + "</p>");
                $('.constraint-value').replaceWith("<p class='constraint-value'>" + constraint + "</p>");
                getForeignTables();
            } else {
                console.log($(".main-dialog")[0]);
                if ( $(".main-dialog")[0] ) {  
                    $('#confDialog h2').replaceWith("<h2>ADD " + constraint.toUpperCase() + " KEY</h2>");
                    $('.main-dialog').replaceWith('<p>Do you want to add ' + constraint.toLowerCase() +' in <span style="color:red;">' + columnName + '</span> field?</p>');
                    $('.confirm-dialog-btn').replaceWith('<button onclick="confirmDialog()" class="btn btn-success confirm-dialog-btn">Yes</button>');
        
                } else {
                    $('#confDialog h2').replaceWith("<h2>ADD " + constraint.toUpperCase() + " KEY</h2>");
                    $('#confDialog p').replaceWith('<p>Do you want to add ' + constraint.toLowerCase() +' in <span style="color:red;">' + columnName + '</span> field?</p>');
                }
                $('.colum-value').replaceWith("<p class='colum-value'>" + columnName + "</p>");
                $('.constraint-value').replaceWith("<p class='constraint-value'>" + constraint + "</p>");
                openDialog();
            }
        }

        function addConstraint() {
            columnName = $('.colum-value').text();
            constraint = $('.constraint-value').text();
            $.ajax({
                url: 'api/change-constraint',
                type: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: JSON.stringify({
                    "colum_name": columnName,
                    "table_name": $('#tbl-dropdown').val(),
                    "constraint": constraint
                }),
                success: function(response) {
                    if (response) {
                        var key = "";
                        
                        if(constraint.toLowerCase() === "primary") {
                            key = '<img src="auditor/icon/green-key.svg" alt="key" class="m-auto" />';
                        } else {
                            key = '<img src="auditor/icon/gray-key.svg" alt="key" class="m-auto" />';
                        }
                        
                        $(".add-constraint-" + columnName + "-" + constraint).replaceWith(key);
                        $(".toast-container").css("display", "block");
                        $(".toastCustom").replaceWith("<p class='toastCustom'>" + constraint.toLowerCase() + " key added successfully</p>");

                        setTimeout(function() {
                            $(".toast-container").css("display", "none");;
                        }, 1000);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        function getForeignTables()
        {
            $.ajax({
                url: 'api/foreign-key-table',
                type: 'get',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response) {
                        $('#confDialog h2').replaceWith("<h2>Add Foreign Key</h2>");    
                       
                        var html = "<div class='row main-dialog' style='margin-top: 30px;margin-bottom: 20px;'>";
                            
                            // foreign table    
                            html += "<div class='col-md-6 sub-dialog'><select class='form-control select-foreign-table' id='select-foreign-tbl'>";
                                html += "<option disabled selected>Select Foreign Table</option>";
                            $.each(response, function(key, value) {
                                html += "<option value="+value+">"+value+"</option>";
                            });
                            html += "</select></div>";

                            html += "<div class='field-list'></div>"

                        html += "</div>";

                        $('#confDialog p').replaceWith(html);
                        $('#confDialog .confirm-dialog-btn').replaceWith('<button onclick="addforeignKey()" class="btn btn-success confirm-dialog-btn">Add</button>');
                        openDialog();
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        $(document).on('change','#select-foreign-tbl', function() {
            
            $.ajax({
                url: 'api/foreign-key-field/'+this.value,
                type: 'get',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response) {
                        // foreign column
                        var html = "<div class='col-md-6 field-list'><select class='form-control' id='select-foreign-field'>";
                            html += "<option disabled selected>Select Foreign Column</option>";
                            $.each(response, function(key, value) {
                                html += "<option value="+value.COLUMN_NAME+">"+value.COLUMN_NAME+"</option>";
                            });
                        html += "</select></div>";

                        $('.field-list').replaceWith(html);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });

           
        });

        function addforeignKey() 
        {
            var foreignTable = document.getElementById('select-foreign-tbl');
            var foreignField = document.getElementById('select-foreign-field');
            var columnName = $('.colum-value').text();
            var tableName = $('.table-value').text();
            $.ajax({
                url: 'api/add-foreign-constraint',
                type: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: JSON.stringify({
                    "table_name": tableName,
                    "select_field": columnName,
                    "reference_table": foreignTable.value,
                    "reference_field": foreignField.value
                }),
                success: function(response) {
                    if (response) {
                        $(".add-constraint-" + columnName + '-FOREIGN').replaceWith('<img src="auditor/icon/gray-key.svg" alt="key" class="m-auto" />');
                        closeDialog();
                        $(".toast-container").css("display", "block");
                        $(".toastCustom").replaceWith("<p class='toastCustom'>foreign key added successfully</p>");

                        setTimeout(function() {
                            $(".toast-container").css("display", "none");;
                        }, 1000);
                    }
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }
        
        $(document).on("click", ".custom-action", function() {
            $("#constraints").DataTable().draw();
            $("#standards").DataTable().draw();
        });
    </script>
@endsection
