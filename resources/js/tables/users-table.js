export function initUsersTable(ajaxUrl) {
    return {
        processing: true,
        serverSide: true,
        ajax: ajaxUrl,
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, width: '5%'},
            {data: 'name', name: 'name'},
            {data: 'email', name: 'email'},
            {data: 'roles', name: 'roles', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false, width: '15%'}
        ],
        columnDefs: [
            {
                targets: -1,
                render: function(data, type, row) {
                    return data;
                }
            }
        ],
        language: {
            "processing": "Memproses...",
            "lengthMenu": "Tampilkan _MENU_ data",
            "zeroRecords": "Tidak ditemukan data",
            "info": "Menampilkan _START_ hingga _END_ dari _TOTAL_ data",
            "infoEmpty": "Menampilkan 0 hingga 0 dari 0 data",
            "infoFiltered": "(difilter dari _MAX_ data total)",
            "search": "Cari:",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        },
        dom: '<"row"<"col-sm-6"l><"col-sm-6"f>>t<"row"<"col-sm-5"i><"col-sm-7"p>>',
        pageLength: 10,
        lengthMenu: [[10, 25, 50, 100], [10, 25, 50, 100]],
        drawCallback: function() {
            $('.dataTables_paginate').addClass('mt-4 pt-4 border-t border-gray-200');
        }
    };
}
