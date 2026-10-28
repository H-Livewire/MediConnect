{{-- $appointments, $actor: patient|staff --}}
<div class="mc-table-wrap">
    <table class="mc-table">
        <thead>
            <tr>
                <th>Ref</th>
                <th>Date &amp; time</th>
                @if ($actor === 'patient')
                    <th>Doctor</th>
                @else
                    <th>Patient</th>
                    <th>Doctor</th>
                @endif
                <th>Hospital</th>
                <th>Status</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @forelse ($appointments as $appointment)
                <tr>
                    <td>{{ $appointment->reference }}</td>
                    <td>{{ $appointment->appointment_date->format('D, d M Y') }}<br>{{ $appointment->timeLabel() }}</td>
                    @if ($actor !== 'patient')
                        <td>{{ $appointment->patient->name }}<br><small>{{ $appointment->patient->phone }}</small></td>
                    @endif
                    <td>{{ $appointment->doctor->user->name }}<br><small>{{ $appointment->doctor->specialty->name }}</small></td>
                    <td>{{ $appointment->hospital->name }}</td>
                    <td><span class="mc-badge {{ $appointment->status->badgeClass() }}">{{ $appointment->status->label() }}</span></td>
                    <td class="mc-actions">
                        @if ($actor === 'patient' && $appointment->isCancellable())
                            <form method="POST" action="{{ route('appointments.cancel', $appointment) }}">
                                @csrf @method('PATCH')
                                <button class="mc-btn-sm mc-btn-danger" type="submit">Cancel</button>
                            </form>
                        @elseif ($actor === 'staff')
                            @if ($appointment->status === \App\Enums\AppointmentStatus::Pending)
                                <form method="POST" action="{{ route('appointments.status', $appointment) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="confirmed">
                                    <button class="mc-btn-sm" type="submit">Confirm</button>
                                </form>
                            @endif
                            @if ($appointment->status === \App\Enums\AppointmentStatus::Confirmed)
                                <form method="POST" action="{{ route('appointments.status', $appointment) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button class="mc-btn-sm" type="submit">Complete</button>
                                </form>
                            @endif
                            @if (in_array($appointment->status, [\App\Enums\AppointmentStatus::Pending, \App\Enums\AppointmentStatus::Confirmed], true))
                                <form method="POST" action="{{ route('appointments.status', $appointment) }}">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="cancelled">
                                    <button class="mc-btn-sm mc-btn-danger" type="submit">Cancel</button>
                                </form>
                            @endif
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="mc-empty">No appointments yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
@if (method_exists($appointments, 'links'))
    <div class="mc-pagination">{{ $appointments->links('partials.pagination') }}</div>
@endif
