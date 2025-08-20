



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dynamic Table with Search and Pagination</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="table-container">
        <table id="dataTable">
            <thead>
                <tr>
                    <th>Date Time</th>
                    <th>Site</th>
                    <th>Building</th>
                    <th>Floor</th>
                    <th>Sewing Line</th>
                    <th>Work Order</th>
                    <th>Customer</th>
                    <th>Style</th>
                    <th>Style Description</th>
                    <th>Working Hour</th>
                    <th>Target Efficiency</th>
                    <th>Actual Working Hour</th>
                    <th>Quantity</th>
                    <th>Document Number</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <script>
        $(document).ready(function() {
            // Initial page load
            loadData();

            // Function to load data
            function loadData() {
                $.ajax({
                    url: 'fetch_data.php',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        start: 0, // Starting index
                        length: 10, // Number of records per page
                        search: { value: '' }, // Initial search value
                        draw: 1 // Initial draw value
                    },
                    success: function(response) {
                        var tableData = response.data;
                        var tableBody = $('#dataTable tbody');
                        tableBody.empty();

                        $.each(tableData, function(index, rowData) {
                            var row = $('<tr>');
                            $.each(rowData, function(key, value) {
                                row.append($('<td>').text(value));
                            });
                            tableBody.append(row);
                        });
                    }
                });
            }
        });
    </script>
</body>
</html>
