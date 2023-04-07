
<table class="w-16 md:w-32 lg:w-48">
  <thead>
    <tr>
      <th>Table Name</th>
      <th>Column Name</th>
      <th>Constraints</th>
      <th>Reference Table Name</th>
      <th>Reference Column Name</th>
    </tr>
  </thead>
  <tbody>
  @foreach ($tables as $table)
    <tr>
      <td>{{ $table[0] }}</td>
      <td>{{ $table[1] }}</td>
      <td>{{ $table[2] }}</td>
      <td>{{ $table[3] ?? "-" }}</td>
      <td>{{ $table[4] ?? "-" }}</td>
    </tr>  
  @endforeach
    
  </tbody>
</table>