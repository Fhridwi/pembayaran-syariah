@extends('back.layouts.app')

@section('content')
<section class="section">
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Update Status Santri</h5>
        </div>
        <div class="card-body">
            <div id="statusMessage"></div> <!-- Div untuk menampilkan pesan status -->

            <div class="table-responsive">
                <table class="table table-striped" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama</th>
                            <th>Status</th>
                            <th>Update</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($santris as $santri)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $santri->nis }}</td>
                                <td>{{ $santri->nama }}</td>
                                <td>
                                    @php
                                        $badgeClass = match($santri->status) {
                                            'aktif' => 'badge bg-success',
                                            'alumni' => 'badge bg-primary',
                                            'keluar' => 'badge bg-danger',
                                            default => 'badge bg-secondary'
                                        };
                                    @endphp
                                    <span class="status-badge {{ $badgeClass }}" id="status-badge-{{ $santri->id }}">
                                        {{ ucfirst($santri->status) }}
                                    </span>
                                </td>
                                <td>
                                    <select class="form-select status-dropdown" data-id="{{ $santri->id }}">
                                        <option value="aktif" {{ $santri->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="alumni" {{ $santri->status === 'alumni' ? 'selected' : '' }}>Alumni</option>
                                        <option value="keluar" {{ $santri->status === 'keluar' ? 'selected' : '' }}>Keluar</option>
                                    </select>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.status-dropdown').forEach(select => {
        select.addEventListener('change', function () {
            const santriId = this.getAttribute('data-id');
            const status = this.value;

            fetch("{{ route('update.statusSantri') }}", {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    santri_id: santriId,
                    status: status
                })
            })
            .then(res => res.json())
            .then(data => {
                // Update the status badge color directly
                const statusBadge = document.getElementById('status-badge-' + santriId);
                const badgeClass = status === 'aktif' ? 'bg-success' :
                                   status === 'alumni' ? 'bg-primary' : 
                                   status === 'keluar' ? 'bg-danger' : 'bg-secondary';
                statusBadge.textContent = ucfirst(status);
                statusBadge.className = 'status-badge ' + badgeClass;

                // Display success message
                const msgBox = document.getElementById('statusMessage');
                msgBox.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                setTimeout(() => msgBox.innerHTML = '', 3000); // Remove message after 3 seconds
            })
            .catch(err => {
                console.error(err);
                const msgBox = document.getElementById('statusMessage');
                msgBox.innerHTML = `<div class="alert alert-danger">Terjadi kesalahan saat mengubah status. Silakan coba lagi.</div>`;
                setTimeout(() => msgBox.innerHTML = '', 3000); // Remove message after 3 seconds
            });
        });
    });

    // Helper function to capitalize the first letter
    function ucfirst(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
</script>
@endpush
