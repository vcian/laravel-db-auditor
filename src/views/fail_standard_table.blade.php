<div class="mt-1">
    TABLE NAME : <span class="px-2 font-bold bg-blue text-white"> {{ str_replace('_', ' ', $tableStatus['table']) }}
    </span>
    @if ($tableStatus['table_comment'])
        <div class="mt-0">
            <span class="text-white mt-1">suggestion(s)</span>
        </div>
        <ol class='mt-1 ml-1'>
            @foreach ($tableStatus['table_comment'] as $commentKey => $comment)
                <li>
                    <span class="text-yellow">{{ $comment }}</span>
                </li>
            @endforeach
        </ol>
    @endif
    <div class="mt-1">
        <table class="w-full">
            <thead>
                <tr>
                    <th> field name</th>
                    <th> standard check</th>
                    <th> datatype </th>
                    <th> size </th>
                    <th> suggestion(s)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tableStatus['fields'] as $key => $field)
                    <tr>
                        @if (!empty($field))
                            @if ((isset($field['suggestion']) && isset($field['datatype']) && count($field) === 2) || count($field) === 1)
                                <td>{{ $key }}</td>
                                <td class="text-green">✓</td>
                            @else
                                <td class="text-red">{{ $key }}</td>
                                <td class="text-red">✗</td>
                            @endif

                            <td> {{ $field['datatype']['data_type'] }} </td>
                            <td> {{ $field['datatype']['size'] }} </td>
                            @php
                                unset($field['datatype']);
                            @endphp
                            @foreach ($field as $key => $fieldComment)
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        @if ($key === 'suggestion')
                            <td class="text-yellow flex">{{ $fieldComment }} </td>
                        @else
                            <td class="text-red flex">{{ $fieldComment }} </td>
                        @endif
                    </tr>
                @endforeach
            @else
                <td>{{ $key }}</td>
                <td class="text-green">✓</td>
                <td> {{ $field['datatype'] }} </td>
                <td> {{ $field['datatype']['size'] }} </td>
                <td> - </td>
                @endif
                </tr>
                @php
                    unset($field['datatype']);
                @endphp
                @endforeach
            </tbody>
        </table>
    </div>
</div>
