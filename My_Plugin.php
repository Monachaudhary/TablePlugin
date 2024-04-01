<?php
/**
 * Plugin Name: TablePlugin
 * Plugin URI: http://businessquantassignment.local
 * Description: Wordpress Plugin 
 * Version: 1.0
 * Author: Manu Chaudhary
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: my-awesome-plugin
 */

// Load CSV data
function load_csv_data() {
    $csv_data = array();
    $csv_file_path = plugin_dir_path(__FILE__) . 'Sample-Data-Screener.csv';
    if (($handle = fopen($csv_file_path, "r")) !== FALSE) {
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            $csv_data[] = $data;
        }
        fclose($handle);
    }
    return $csv_data; // Return loaded CSV data
}

// Enqueue necessary scripts and styles
function table_plugin_enqueue_scripts() {
    // Enqueue scripts and styles here
    // For example:
    wp_enqueue_script('select2', 'path-to-select2-js', array('jquery'), '4.1.0', true);
    wp_enqueue_style('select2-css', 'path-to-select2-css', array(), '4.1.0');
}
add_action('wp_enqueue_scripts', 'table_plugin_enqueue_scripts');

// Example shortcode with filtering options
function display_filtered_csv_data_table($atts, $content = null) {
    // Load CSV data
    $csv_data = load_csv_data();

    // Start HTML output
    $html = '';

    $html .= '<div class="plugin-header">';
    $html .= '<h2>Stock Scanner</h2>';
    $html .= ' <p>This plugin allows you to filter and display CSV data dynamically</p>';
    $html .= '</div>';

    // Apply filter button and filter dropdowns
    $html .= '<div>';
    $html .= '<select name="filter_option" id="filter_option">';
    $html .= '<option value="com">Select</option>';
    $html .= '<option value="revenue">Revenue(Annual)</option>';
    $html .= '<option value="company">Company</option>';
    $html .= '<option value="fcf">Total Asset(Annual)</option>';
    $html .= '</select>';

    // Additional dropdowns for specific filters like revenue greater/less than and units
    $html .= '<select name="filter_value" id="filter_value" style="display:none;">';
    $html .= '<option value="greater">Greater Than</option>';
    $html .= '<option value="less">Less Than</option>';
    $html .= '</select>';
    $html .= '<select name="filter_unit" id="filter_unit" style="display:none;">';
    $html .= '<option value="lakhs">Lakhs</option>';
    $html .= '<option value="millions">Millions</option>';
    $html .= '</select>';



    $html .= '<button id="apply_filter_btn">Apply Filter</button>';
    $html .= '</div>';
    
$html .= '<div id="Mydiv">';
$html .= '<button id="add_filter_btn">Add</button>';// Remove filter button
$html .= '</div>'; // End of filter options div

$html .= '</div>'; // End of filter options container

// Add Filter button
$html .= '<div id="No.ofRow">';
$html .= '<label for="num_rows">No. of Rows:</label>';
$html .= '<select name="num_rows" id="num_rows">';
$html .= '<option value="50">50</option>';
$html .= '<option value="100">100</option>';
$html .= '<option value="150">150</option>';
$html .= '</select>';
$html .= '</div>';

    $html .= '<div id="filtered_table"></div>';
    $html .= '<div id="filtered_table_wrapper">'; // Wrapper div for styling
    $html .= '</div>';


$html .= '<button id="download_btn">Download Data</button>'; // Download button




    

    $html .= '<style>
    /* Plugin header styles */
.plugin-header {
    margin-top:10px;
    margin-bottom: 20px;
    padding: 20px;
    background-color: #f2f2f2;
    border-radius: 4px;
}

/* Header text styles */
.plugin-header h2 {
    margin-bottom: 10px;
    font-size: 35px;
}

/* Header description styles */
.plugin-header p {
    margin: 0;
    font-size: 16px;
    color: #555;
}

    #apply_filter_btn {
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        border-radius: 4px;
    }
    #add_filter_btn {
        background-color: #4CAF50;
        border: none;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        cursor: pointer;
        border-radius: 4px;
        margin : 5px;
    }
    #num_rows{
        margin-top: 40px;
    }
    #filter_option,
    #filter_value,
    #filter_unit{
        margin-top: 30px;
    }
    #filter_option,
    #filter_value,
    #num_rows,
    #filter_unit {
        margin-right: 20px;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 4px;
        background-color: #f9f9f9;
        font-size: 14px;
        color: #333;
    }
   
</style>';
$html .= '<style>
    /* Style for the table */
    #filtered_table table {
        margin-top: 70px;
        width: 100%;
        border-collapse: collapse;
        border: 1px solid #ddd;
        font-size: 14px;
    }

    /* Style for table header */
    #filtered_table table th {
        background-color: #f2f2f2;
        border: 1px solid #ddd;
        padding: 20px;
        text-align: left;
    }

    /* Style for table cells */
    #filtered_table table td {
        border: 1px solid #ddd;
        padding: 12px;
    }

    /* Style for table rows */
    #filtered_table table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    #filtered_table_wrapper{
        position: relative;
    }

    /* Hover effect for table rows */
    #filtered_table table tr:hover {
        background-color: #f2f2f2;
    }
</style>';
$html .= '<style>
    /* Style for download button */
    #download_btn {
        background-color: #008CBA;
        border: none;
        color: white;
        padding: 10px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        position: absolute;
        top: 80px;
        right: 30px;
        margin-top: 20px;
        cursor: pointer;
        border-radius: 4px;
    }
</style>';


    // JavaScript for handling filter application and displaying the table
    $html .= '<style>
    .filter_options {
        margin-bottom: 10px;
    }
</style>';

$html .= '<script>
    jQuery(document).ready(function($) {
        // Add Filter button click handler
        $("#add_filter_btn").click(function() {
            // Clone the filter options container
            var $newFilterOptions = $("#filter_option").clone();
            
            // Reset select values
            $newFilterOptions.find("select").val("");
            
            // Append the cloned filter options to the container
            $newFilterOptions.appendTo("#Mydiv");
    
            // Show the filter options and apply filter button
            $newFilterOptions.find("select").show();
            $newFilterOptions.find("#apply_filter_btn").show();
        });
    
        // Apply filter button click handler
        $(document).on("click", "#apply_filter_btn", function() {
            var filterOption = $(this).siblings("#filter_option").val();
            var filterValue = $(this).siblings("#filter_value").val();
            var filterUnit = $(this).siblings("#filter_unit").val();
            var numRows = $("#num_rows").val(); // Get selected number of rows

            // AJAX call to retrieve filtered data
            $.ajax({
                url: "' . admin_url('admin-ajax.php') . '",
                type: "POST",
                data: {
                    action: "get_filtered_data",
                    filter_option: filterOption,
                    filter_value: filterValue,
                    filter_unit: filterUnit,
                    num_rows: numRows // Pass selected number of rows
                },
                success: function(response) {
                    $("#filtered_table").html(response);
                }
            });
        });

        // Remove filter button click handler
        $(document).on("click", ".remove_filter", function() {
            // Remove the parent div of the clicked button
            $(this).parent(".filter_options").remove();
        });
    });
</script>';









$html .= '<script>
    jQuery(document).ready(function($) {
        $("#download_btn").click(function() {
            // Function to convert table data to CSV and trigger download
            var csv = $("#filtered_table table").map(function() {
                return $(this).find("tr").map(function() {
                    return $(this).find("th, td").map(function() {
                        return $(this).text();
                    }).get().join(",");
                }).get().join("\n");
            }).get().join("\n");

            var blob = new Blob([csv], { type: "text/csv;charset=utf-8" });
            var url = URL.createObjectURL(blob);
            var a = document.createElement("a");
            a.href = url;
            a.download = "table_data.csv";
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
        });
    });
</script>';

    // JavaScript for handling filter application and displaying the table
    $html .= '<script>
        jQuery(document).ready(function($) {
            $("#apply_filter_btn").click(function() {
                var filterOption = $("#filter_option").val();
                var filterValue = $("#filter_value").val();
                var filterUnit = $("#filter_unit").val();
                var numRows = $("#num_rows").val(); // Get selected number of rows

                // AJAX call to retrieve filtered data
                $.ajax({
                    url: "' . admin_url('admin-ajax.php') . '",
                    type: "POST",
                    data: {
                        action: "get_filtered_data",
                        filter_option: filterOption,
                        filter_value: filterValue,
                        filter_unit: filterUnit,
                        num_rows: numRows // Pass selected number of rows
                    },
                    success: function(response) {
                        $("#filtered_table").html(response);
                    }
                });
            });

            // Show additional dropdowns based on selected filter option
            $("#filter_option").change(function() {
                var selectedOption = $(this).val();
                if (selectedOption === "revenue") {
                    $("#filter_value, #filter_unit").show();
                } else {
                    $("#filter_value, #filter_unit").hide();
                }
            });
        });
    </script>';

    return $html;
}
add_shortcode('filtered_csv_data_table', 'display_filtered_csv_data_table');

// AJAX handler to retrieve filtered data
// AJAX handler to retrieve filtered data
add_action('wp_ajax_get_filtered_data', 'get_filtered_data_callback');
add_action('wp_ajax_nopriv_get_filtered_data', 'get_filtered_data_callback');
function get_filtered_data_callback() {
    $filterOption = $_POST['filter_option'];
    $filterValue = $_POST['filter_value'];
    $filterUnit = $_POST['filter_unit'];
    $rowCount = isset($_POST['num_rows']) ? intval($_POST['num_rows']) : 5; // Default to 5 rows if not specified

    // Load CSV data
    $csv_data = load_csv_data();

    // Process CSV data based on filtering criteria
    // Example filtering logic for revenue
    $filteredData = array();
    if ($filterOption === "revenue") {
        foreach ($csv_data as $row) {
            // Assuming revenue is in the first column
            $revenue = (float) str_replace(['$', ','], '', $row[0]); // Assuming revenue is formatted as currency
            if ($filterValue === "greater" && $revenue > 0 && $revenue > 100000) {
                $filteredData[] = $row;
            } elseif ($filterValue === "less" && $revenue > 0 && $revenue < 100000) {
                $filteredData[] = $row;
            }
        }
    } else {
        // No specific filtering for other options, just return all data
        $filteredData = $csv_data;
    }

    // Limit the number of rows based on user selection
    $filteredData = array_slice($filteredData, 0, $rowCount);

    // Generate HTML table with filtered data
    $html = '<table>';
    foreach ($filteredData as $row) {
        $html .= '<tr>';
        foreach ($row as $cell) {
            $html .= '<td>' . esc_html($cell) . '</td>';
        }
        $html .= '</tr>';
    }
    $html .= '</table>';

    // Return HTML table
    echo $html;

    // Always exit to avoid further execution
    wp_die();
}
