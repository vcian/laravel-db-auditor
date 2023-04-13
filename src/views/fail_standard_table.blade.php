<div class="mt-1">
    <span class="px-2 font-bold bg-blue-300 text-black"> {{ strtoupper($tableStatus['table']." TABLE")  }} </span>
    @if ($tableStatus['table_comment'])
    <div class="mt-1"><span class="text-yellow mt-1">Found {{ count($tableStatus['table_comment']) }} Suggestion</span></div>
    @foreach ($tableStatus['table_comment'] as $comment)
    <div class="mt-1">
        <span class="px-2 font-bold bg-red text-black"> 😳 </span>
        &nbsp;<span class="text-red">{{ $comment }}</span>
    </div>
    @endforeach
    @endif
    <div class="mt-1">
        <table>
            <thead>
                <tr>
                    <th> Field Name </th>
                    <th> Standard Status </th>
                    <th> Suggestion </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tableStatus['fields'] as $key => $field)
                <tr>
                    <td>{{ $key }}</td>
                    @if (!empty($field))
                    <td class="text-red">✗</td>
                    @foreach ($field as $fieldComment)
                <tr>
                    <td></td>
                    <td></td>
                    <td class="text-yellow">🤔 {{ $fieldComment  }} </td>
                </tr>
                @endforeach
                @else
                <td class="text-green">✓</td>
                <td> - </td>
                @endif
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>