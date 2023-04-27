<div class="w-auto m-1">
    @php
        $success = 0;
        $error = 0;
    @endphp
    <div class="mt-1">
        <div class="flex space-x-1">
            <span class="font-bold text-green">TABLE NAME</span>
            <span class="flex-1 content-repeat-[.] text-gray"></span>
            <span class="font-bold">Standardization</span>
        </div>
        @foreach ($tableStatus as $table)
            <div class="flex space-x-1">
                <span>{{ $table['name'] }}</span>
                <i class="text-blue">({{ $table['size'] }} MB)</i>
                <span class="flex-1 content-repeat-[.] text-gray"></span>
                @if ($table['status'])
                    @php $success++; @endphp
                    <b><span class="font-bold text-green">✓</span></b>
                @else
                    @php $error++; @endphp
                    <b><span class="font-bold text-red">✗</span></b>
                @endif
            </div>
        @endforeach
        <div class="mt-1">
            <span class="px-2 bg-green ml-5 text-black">{{ $success }} </span> <span class="text-green ml-1"> TABLE
                PASSED ✓</span>
        </div>
        <div class="mt-1">
            <span class="px-2 bg-red ml-5 text-white">{{ $error }}</span> <span class="text-red ml-1"> TABLE
                FAILED ✗</span>
        </div>
    </div>
</div>
