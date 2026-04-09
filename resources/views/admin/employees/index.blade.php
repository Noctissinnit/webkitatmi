<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Karyawan') }}
        </h2>
    </x-slot>

    <!-- Alert Messages -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>{{ __('Whoops!') }}</strong> {{ __('There were some problems with your input.') }}<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (count($errors) == 0 && Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Table Container -->
    <div class="table-container">
        <!-- Header with Controls -->
        <div class="table-header">
            <h3 class="table-title">{{ __('Daftar Karyawan') }}</h3>
            <div class="table-actions">
                <button class="btn btn-secondary" id="importBtn" type="button">
                    <i class="fas fa-upload"></i> {{ __('Import') }}
                </button>
                <a href="{{ route('employees.export') }}" class="btn btn-secondary">
                    <i class="fas fa-download"></i> {{ __('Export') }}
                </a>
                <a href="{{ route('employees.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> {{ __('Tambah Karyawan') }}
                </a>
            </div>
        </div>

        <!-- DataTables Search -->
        <div class="table-search-wrapper">
            <div class="table-search">
                <i class="fas fa-search"></i>
                <input 
                    type="text" 
                    id="searchInput" 
                    placeholder="{{ __('Search...') }}" 
                    class="form-control"
                >
            </div>
        </div>

        <!-- Table -->
        <table id="employeesTable" class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>{{ __('Photo') }}</th>
                    <th>{{ __('Nama') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Departemen') }}</th>
                    <th>{{ __('Jabatan') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>

    <!-- Import Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">{{ __('Import Karyawan') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('employees.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">{{ __('Select Excel File') }}</label>
                            <input 
                                type="file" 
                                name="file" 
                                id="file" 
                                class="form-control" 
                                accept=".xlsx,.xls,.csv" 
                                required
                            >
                            <small class="form-text text-muted">{{ __('Supported: xlsx, xls, csv') }}</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Import') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('css/employees-table.css') }}">
    @endpush

    @push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable
            const table = $('#employeesTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('employees.getData') }}",
                    type: 'GET'
                },
                columns: [
                    {data: 'photo', name: 'photo', searchable: false, orderable: false, render: function(data) {
                        if (data) {
                            return '<img src="/storage/' + data + '" alt="photo" class="table-avatar">';
                        }
                        return '<div class="table-avatar-placeholder"><i class="fas fa-user"></i></div>';
                    }},
                    {data: 'nama', name: 'nama'},
                    {data: 'email', name: 'email'},
                    {data: 'departemen', name: 'departemen'},
                    {data: 'jabatan', name: 'jabatan'},
                    {data: 'action', name: 'action', searchable: false, orderable: false}
                ],
                order: [[1, 'asc']],
                lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
                pageLength: 10,
                language: {
                    processing: '<i class="fas fa-spinner fa-spin"></i> Loading...',
                    emptyTable: '{{ __("No data available") }}',
                    zeroRecords: '{{ __("No matching records found") }}',
                    info: '{{ __("Showing _START_ to _END_ of _TOTAL_ entries") }}',
                    infoEmpty: '{{ __("Showing 0 to 0 of 0 entries") }}',
                    infoFiltered: '{{ __("(filtered from _MAX_ total entries)") }}',
                    lengthMenu: '{{ __("Show _MENU_ entries") }}',
                    search: '{{ __("Search:") }}'
                }
            });

            // Search functionality
            $('#searchInput').on('keyup', function() {
                table.search(this.value).draw(false);
            });

            // Import button
            $('#importBtn').on('click', function() {
                $('#importModal').modal('show');
            });

            // Delete confirmation
            $(document).on('click', '.btn-delete', function(e) {
                e.preventDefault();
                const deleteUrl = $(this).attr('href');
                
                if (confirm('{{ __("Are you sure?") }}')) {
                    $.ajax({
                        url: deleteUrl,
                        type: 'POST',
                        data: {
                            _method: 'DELETE',
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            table.ajax.reload();
                            alert('{{ __("Employee deleted successfully") }}');
                        },
                        error: function() {
                            alert('{{ __("Error deleting employee") }}');
                        }
                    });
                }
            });
        });
    </script>
    @endpush
</x-app-layout>
