<div class="mt-1">
    TABLE NAME : <span
        class="px-2 font-bold bg-blue text-white"> {{ str_replace("_", ' ', strtoupper($tableStatus['table'])) }} </span>
    @if ($tableStatus['table_comment'])
        <div class="mt-0">
            <span class="text-yellow mt-1">Suggestion(s)</span>
        </div>
        <ol class='mt-1 ml-1'>
            @foreach ($tableStatus['table_comment'] as $comment)
                <li>
                    <span class="text-red">{{ $comment }}</span>
                </li>
            @endforeach
        </ol>
    @endif
    <div class="mt-1">
        <table class="w-full">
            <thead>
            <tr>
                <th> Field Name</th>
                <th> Standard Status</th>
                <th> Suggestion(s)</th>
            </tr>
            </thead>
            <tbody>
                @foreach ($tableStatus['fields'] as $key => $field)
                <tr>
                    @if (!empty($field))
                        <td class="text-red">{{ $key }}</td>
                        <td class="text-red">✗</td>
                        @foreach ($field as $fieldComment)
                            <tr>
                                <td></td>
                                <td></td>
                                <td class="text-yellow">👉 {{ $fieldComment }} </td>
                            </tr>
                        @endforeach
                    @else
                        <td>{{ $key }}</td>
                        <td class="text-green">✓</td>
                        <td> -</td>
                    @endif
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
