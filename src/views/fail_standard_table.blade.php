<div class="mt-1">
    TABLE NAME : <span
        class="px-2 font-bold bg-blue text-white"> {{ str_replace("_", ' ', $tableStatus['table']) }} </span>
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
                <th> standard status</th>
                <th> suggestion(s)</th>
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
                        <td class="text-yellow flex">👉 {{ $fieldComment }} </td>
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
