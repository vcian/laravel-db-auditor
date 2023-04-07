<div class="sm:m-1">


    @foreach ($tables as $key => $table)
        {{-- Tables --}}

        <div class="w-100 p-1 text-center sm:bg-green-400 text-black">
            Table Name : <b>{{ $table['name'] }}</b>
        </div>
        @if (!empty($table['status']))
            <table>
                <thead>
                    <tr>
                        <th>Table Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table['status'] as $status)
                        <tr>
                            <td class='text-red'>{{ $status }} </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif

        {{-- End Tables --}}


        {{-- Fields --}}

        <table>
            <thead>
                <tr>
                    <th>Field Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($table['fields'] as $field)
                    <tr>
                        @if ($field['status'])
                            <td class='text-red'>{{ $field['name'] }}</td>
                        @else
                            <td>{{ $field['name'] }}</td>
                        @endif
                        @if ($field['status'])
                            @foreach ($field['status'] as $status)
                    <tr>
                        <td></td>
                        <td class='text-red'>{{ $status }}</td>
                    </tr>
                @endforeach
            @else
                <td class='text-green'> ✓ </td>
    @endif
    </tr>
    @endforeach
    </tbody>
    </table>

    {{-- End Fields --}}
    @endforeach


</div>
