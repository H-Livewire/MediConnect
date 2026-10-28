@forelse ($appointments as $appointment)
    <tr>
        <td><b>{{ $appointment->reference }}</b></td>
        <td>{{ $appointment->appointment_date->format('d M Y') }}<small>{{ $appointment->timeLabel() }}</small></td>
        <td>{{ $appointment->patient->name }}<small>{{ $appointment->patient->phone }}</small></td>
        <td>{{ $appointment->doctor->user->name }}@isset($appointment->doctor->specialty)<small>{{ $appointment->doctor->specialty->name }}</small>@endisset</td>
        <td>{{ $appointment->hospital->name }}</td>
        <td><span class="ad-badge {{ $appointment->status->value }}">{{ $appointment->status->label() }}</span></td>
    </tr>
@empty
    <tr><td colspan="6" class="ad-empty">No appointments found.</td></tr>
@endforelse
