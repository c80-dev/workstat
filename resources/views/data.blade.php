<tbody id="demo">
    <?php $i = 1;?>
    @foreach ($records as $record )
        <tr>
            <th scope="row">{{ $i++ }}</th>
            <td>{{ $record->employee_id }} </td>
            <td>
                {{ isset($record->employee)? $record->employee->name :'Not' }}
            </td>
            <td>{{ $record->auth_date }}</td>
            <td>{{ $record->clock_in }}</td>
            <td>{{ $record->clock_out }}</td>
        </tr>
    @endforeach
</tbody>