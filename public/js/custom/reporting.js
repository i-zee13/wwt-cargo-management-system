var clients = [];
let date = '';
$(document).ready(function () {
 
});
 $(document).on('change','#filter_date',function(){
   if($(this).val() == 1){
    $('.dates_div').hide()
   } else{
    $('.dates_div').fadeIn()
   }

})
$(document).ready(function () {
    let isProcessing = false;  
    $('.all-select').on('change', function () {
        if (isProcessing) return;  
        isProcessing = true;

        let $select = $(this);
        let allOptionValue = "all";  

        if ($select.val() && $select.val().includes(allOptionValue)) { 
            $select.find('option').each(function () {
                if ($(this).val() !== allOptionValue) {
                    $(this).prop('disabled', true);
                }
            }); 
            $select.val([allOptionValue]);
        } else { 
            $select.find('option').prop('disabled', false);
        } 
        $select.fSelect('reload');  
        isProcessing = false; 
    });
});

$(document).on('click', '#generate_report', function () {
    $('.report_append_div').empty();
    $('.exportExcelBtn').hide();
    const currentRef = $(this); 
    const origins = $('#origins').val();
    const filter_date = $('#filter_date').val();
    const start_date = $('#start_date').val();
    const end_date = $('#end_date').val();
    const branches = $('#branches').val();
    const statuses = $('#statuses').val();
    const types = $('#types').val();
    const clients = $('#clients').val();

    console.log(origins != 0 && origins.length === 0); 
    const isInvalid = 
        (!filter_date) ||
        (filter_date == 2 && (!start_date || !end_date));

    if (isInvalid) { 
        $('#notifDiv')
            .fadeIn()
            .css('background', 'red')
            .text('Please provide all required information (*)');

        setTimeout(() => {
            $('#notifDiv').fadeOut();
        }, 3000);
        return;
    }

    currentRef.attr('disabled', true).text('Generating...'); 
    $.ajax({
        method: 'GET',
        url: '/admin/generate-report',
        cache: false,
        data: {
            origins,
            branches,
            statuses,
            types,
            clients,
            filter_date,
            start_date,
            end_date
        },
        success: function (response) {
            currentRef.attr('disabled', false).text('Generate Report');

            if (response.status === 'success') {
                $('.report_append_div').append(`
                    <table class="table table-hover dt-responsive nowrap" id="packagesTable" style="width:100%;">
                        <thead>
                            <tr>
                                <th>${Lang.get('fields.id')}</th>
                                <th>${Lang.get('fields.waybill')}</th>
                                <th>${Lang.get('fields.origin')}</th>    
                                <th>${Lang.get('fields.destination')}</th>    
                                <th>${Lang.get('fields.type')}</th>    
                                <th style="width:80px!important;">${Lang.get('fields.description')}</th>    
                                <th>${Lang.get('fields.original_tracking')}</th>    
                                <th>${Lang.get('fields.date')}</th>
                                <th>${Lang.get('fields.kg')}</th>    
                                <th>${Lang.get('fields.cbm')}</th>    
                                <th>${Lang.get('fields.client')}</th>     
                                <th>${Lang.get('fields.package_status')}</th>    
                                <th>${Lang.get('fields.grand_total')}</th>    
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                `);

                const packages = response.data;
                if(packages.length > 0) {
                    $('.exportExcelBtn').fadeIn();
                }
                packages.forEach(element => {
                    $('#packagesTable tbody').append(`
                        <tr>
                            <td>${truncateTextHtml(element['guide'] ?? '')}</td> 
                            <td>${truncateTextHtml(element['waybill'] ?? '')}</td>
                            <td>${truncateTextHtml(element['origin'] ?? '')}</td>
                            <td>${truncateTextHtml(element['branch'] ?? '')}</td>
                            <td>${truncateTextHtml(element['type'] ?? '')}</td>
                            <td>${truncateTextHtml(element['description'] ?? '')}</td>
                            <td>${truncateTextHtml(element['tracking'] ?? '')}</td>
                            <td>${element['date'] ?? ''}</td>
                            <td>${element['kg'] ?? ''}</td>
                            <td>${element['cbm'] ?? ''}</td>
                            <td>${truncateTextHtml(((element['client'] ?? '') + ' ' + (element['client_last'] ?? '') + ' (' + String(element.suite ?? '').replace(/^COMM/i, 'WWT') + ')').trim())}</td> 
                            <td>
                                ${
                                    element['status']
                                    ? element['status']
                                        .replace(/-/g, ' ') // Replace hyphens with spaces
                                        .replace(/\b\w/g, (c) => c.toUpperCase()) // Capitalize each word
                                    : ''
                                }
                                </td>
                            <td>${element['total'] ?? ''}</td> 
                        </tr>
                    `);
                });

                console.log(packages, 'i am packages');

                $('#packagesTable').DataTable({
                    responsive: false,
                    scrollX: true,
                    autoWidth: false,
                    searching: false,
                    lengthChange: false,
                    info: false,
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'pdfHtml5',
                            text: '<i class="bi bi-file-earmark-pdf"></i> Export to PDF',
                            className: 'btn btn-primary mx-1',
                            orientation: 'landscape', // Ensure landscape orientation for more space
                            title: '',
                            customize: function (doc) {
                                // Remove unnecessary content
                                const pdfWatermarkLabels = (window.brandPdfWatermarkLabels || ['wwc', 'wwt']).map((label) => label.toLowerCase());
                                doc.content = doc.content.filter((item) => {
                                    if (!item.text) {
                                        return true;
                                    }
                                    return !pdfWatermarkLabels.includes(String(item.text).toLowerCase());
                                });
                
                                // Add organization logo
                                if (organizationLogo) {
                                    doc.content.splice(0, 0, {
                                        columns: [
                                            {
                                                image: 'data:image/png;base64,' + organizationLogo,
                                                width: 200,
                                                height: 80,
                                                alignment: 'center',
                                            },
                                        ],
                                        margin: [0, 0, 0, 10],
                                    });
                                }
                
                                // Add organization name
                                doc.content.splice(1, 0, {
                                    text: organizationName,
                                    fontSize: 18,
                                    alignment: 'center',
                                    bold: true,
                                    margin: [0, 10, 0, 20],
                                });
                
                                // Customize table header style
                                doc.styles.tableHeader = {
                                    fillColor: '#eb973c',
                                    color: 'white',
                                    alignment: 'center',
                                    bold: true,
                                    fontSize: 10, // Adjust font size for table headers
                                }; 
                                if (doc.content[2] && doc.content[2].table) {
                                    doc.content[2].table.widths = [
                                        '5%',  // ID
                                        '10%', // Waybill
                                        '10%', // Origin
                                        '5%', // Destination
                                        '5%', // Type
                                        '15%', // Description
                                        '10%', // Tracking
                                        '5%', // Date
                                        '5%',  // KG
                                        '5%',  // CBM
                                        '15%', // Client
                                        '5%', // Package Status
                                        '10%'  // Grand Total
                                    ];
                                }
                
                                // Reduce font size for table body
                                const bodyFontSize = 8;
                                doc.styles.tableBodyEven = {
                                    fontSize: bodyFontSize,
                                };
                                doc.styles.tableBodyOdd = {
                                    fontSize: bodyFontSize,
                                };
                
                                // Add totals section
                                const totalsLabelStyle = {
                                    bold: true,
                                    alignment: 'right',
                                };
                
                                const totalsValueStyle = {
                                    bold: true,
                                    alignment: 'left',
                                };
                
                                const totalsTable = {
                                    table: {
                                        widths: ['85%', '15%'],
                                        body: [
                                            [
                                                {
                                                    text: Lang.get('fields.total_kg') + ':',
                                                    style: 'totalsLabelStyle',
                                                    border: [false, false, false, false],
                                                },
                                                {
                                                    text: response?.totalKg?.toFixed(2) || '0.00',
                                                    style: 'totalsValueStyle',
                                                    border: [true, true, true, true],
                                                    color: 'black',
                                                },
                                            ],
                                            [
                                                {
                                                    text: Lang.get('fields.grand_total') + ':',
                                                    style: 'totalsLabelStyle',
                                                    border: [false, false, false, false],
                                                },
                                                {
                                                    text: response?.totalGrandTotal?.toFixed(2) || '0.00',
                                                    style: 'totalsValueStyle',
                                                    border: [true, true, true, true],
                                                    color: 'black',
                                                },
                                            ],
                                        ],
                                    },
                                    margin: [0, 20, 0, 0], // [left, top, right, bottom]
                                };
                
                                doc.styles = doc.styles || {};
                                doc.styles.totalsLabelStyle = totalsLabelStyle;
                                doc.styles.totalsValueStyle = totalsValueStyle;
                                doc.content.push(totalsTable);
                            },
                        },
                    ],
                });
                
                
                $('.dt-buttons button').removeClass('btn-secondary');
                $('#notifDiv')
                    .fadeIn()
                    .css('background', 'green')
                    .text('Report generated successfully!')
                    .delay(3000)
                    .fadeOut();
            } else {
                $('#notifDiv')
                    .fadeIn()
                    .css('background', 'red')
                    .text('No data available for the selected filters.')
                    .delay(3000)
                    .fadeOut();
            }
        },
        error: function (error) { 
            currentRef.attr('disabled', false).text('Generate Report');
            console.error(error);

            $('#notifDiv')
                .fadeIn()
                .css('background', 'red')
                .text('Failed to generate the report. Please try again!')
                .delay(3000)
                .fadeOut();
        }
    });
    $('.exportExcelBtn').on('click', function() { 
         
        
    var origins = $('#origins').val();
    var filter_date = $('#filter_date').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var branches = $('#branches').val();
    var statuses = $('#statuses').val();
    var types = $('#types').val();
    var clients = $('#clients').val();
    console.log(origins != 0 && origins.length === 0); 
    const isInvalid = 
        (!filter_date) ||
        (filter_date == 2 && (!start_date || !end_date));

    if (isInvalid) { 
        $('#notifDiv')
            .fadeIn()
            .css('background', 'red')
            .text('Please provide all required information (*)');

        setTimeout(() => {
            $('#notifDiv').fadeOut();
        }, 3000);
        return;
    }
        var filters = {
            origins: origins,
            branches: branches,
            statuses: statuses,
            types: types,
            clients: clients,
            filter_date: filter_date,
            start_date: start_date,
            end_date: end_date
        }; 
        var query = $.param(filters); 
        var exportUrl = '/admin/export-packages-excel?' + query; 
        window.location.href = exportUrl;
    });
});


 



   