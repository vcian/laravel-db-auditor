<div class="mx-2 my-1">
    <div class="space-x-1">
        TABLE NAME : <span
            class="px-1 bg-blue-500 text-black">{{ $data['table'] }}</span>
    </div>

    <div class="flex space-x-1 mt-1">
        <span class="font-bold text-green">Columns</span>
        <span class="flex-1 content-repeat-[.] text-gray"></span>
        <span class="font-bold">{{ $data['field_count'] }}</span>
    </div>

    <div class="flex space-x-1">
        <span class="font-bold text-green">Table Size</span>
        <span class="flex-1 content-repeat-[.] text-gray"></span>
        <span class="font-bold">{{ $data['size'] }}</span>
    </div>

    <div class="mt-1">
        <div class="flex space-x-1">
            <span class="font-bold text-green">Fields</span>
            <span class="flex-1 content-repeat-[.] text-gray"></span>
            <span class="font-bold">Data Type</span>
        </div>

        @foreach ($data['fields'] as $field)
            <div class="flex space-x-1">
                <span class="font-bold">{{ $field->name }}</span>
                <i class="text-blue">{{ $field->type }}</i>
                <span class="flex-1 content-repeat-[.] text-gray"></span>
                <span class="font-bold text-green">{{ $field->type }}</span>
            </div>
        @endforeach
    </div>

    <div class="mt-1">

        @foreach ($data['constraint'] as $key => $value)
            @if ($value)
                <div class="space-x-1 mt-1">
                    <span class="px-1 bg-green-500 text-black">{{ strtoupper($key) }}</span>
                </div>
                @foreach ($value as $constraintField)

                    @if ($key === 'foreign')
                        <div class="flex space-x-1">
                            <span class="font-bold">{{ $constraintField['from'] }}</span>
                            <span class="flex-1 content-repeat-[.] text-gray"></span>
                            <i class="text-blue">{{ $constraintField['table'] }}</i>
                            <span class="font-bold text-green">{{ $constraintField['to'] }}</span>
                        </div>
                    @else
                        <div class="flex space-x-1">
                            <span class="font-bold">{{ $constraintField['name'] }}</span>
                            <span class="flex-1 content-repeat-[.] text-gray"></span>
                        </div>
                    @endif
                @endforeach
            @endif
        @endforeach
    </div>
</div>
