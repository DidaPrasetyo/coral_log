// Call the dataTables jQuery plugin
$(document).ready(function() {
    $('#tabelData').DataTable({
        "ajax": {
            "url": "get_data.php",
            "type": "GET",
            "dataSrc": "data"
        },
        "columns": [
            { 
                "data": null,
                "render": function (data, type, row, meta) {
                    // Auto-incrementing number based on row index
                    return meta.row + 1;
                }
            },
            { "data": "timestamp" },
            { "data": "person detected" },
            { 
                "data": "image",
                "render": function (data, type, row, meta) {
                    return '<img src="data:image/jpeg;base64,' + data + '" alt="Image">';
                }
            }
        ],
        "searching": true,
        "processing": true,
        "serverSide": true,
        "paging": true,
        "pageLength": 10,
        "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
        "order": [[0, "desc"]],
        "initComplete": function(settings, json) {
            console.log("Data from server:", json);
        },
        "error": function(xhr, error, thrown) {
            console.log("Ajax error:", error);
        }
    });
});
