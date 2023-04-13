<div class="w-auto m-1">
    <div class="mt-1"> 
        <span class="font-bold text-green">Table Standard Check</span>
        @foreach ($tableStatus as $table)
            <div class="flex space-x-1"> <span>{{ $table['name'] }}</span> <i
                    class="text-blue">({{ $table['size'] }} MB)</i> <span
                    class="flex-1 content-repeat-[.] text-gray"></span>
                @if ($table['status'])
                    <b><span class="font-bold text-green">✓</span></b>
                @else
                    <b><span class="font-bold text-red">✗</span></b>
                @endif
            </div>
        @endforeach
        
    </div>
</div>
