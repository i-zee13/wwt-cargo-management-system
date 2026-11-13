var clients = [];
let date = '';
const REPORT_COLUMNS_STORAGE_KEY = 'wwc_report_export_columns';
const REPORT_COLUMN_DEFS = [
    { key: 'guide', labelKey: 'fields.id', width: '5%', cell: (el) => truncateTextHtml(el['guide'] ?? '') },
    { key: 'waybill', labelKey: 'fields.waybill', width: '10%', cell: (el) => truncateTextHtml(el['waybill'] ?? '') },
    { key: 'origin', labelKey: 'fields.origin', width: '10%', cell: (el) => truncateTextHtml(el['origin'] ?? '') },
    { key: 'branch', labelKey: 'fields.destination', width: '5%', cell: (el) => truncateTextHtml(el['branch'] ?? '') },
    { key: 'type', labelKey: 'fields.type', width: '5%', cell: (el) => truncateTextHtml(el['type'] ?? '') },
    { key: 'description', labelKey: 'fields.description', width: '15%', cell: (el) => truncateTextHtml(el['description'] ?? '') },
    { key: 'tracking', labelKey: 'fields.original_tracking', width: '10%', cell: (el) => truncateTextHtml(el['tracking'] ?? '') },
    { key: 'date', labelKey: 'fields.date', width: '5%', cell: (el) => el['date'] ?? '' },
    { key: 'kg', labelKey: 'fields.kg', width: '5%', cell: (el) => el['kg'] ?? '' },
    { key: 'cbm', labelKey: 'fields.cbm', width: '5%', cell: (el) => el['cbm'] ?? '' },
    {
        key: 'client',
        labelKey: 'fields.client',
        width: '15%',
        cell: (el) => truncateTextHtml(
            ((el['client'] ?? '') + ' ' + (el['client_last'] ?? '') + ' (' + String(el.suite ?? '').replace(/^(COMM|WWT)/i, (typeof brandSuitePrefix !== 'undefined' ? brandSuitePrefix : 'WWC')) + ')').trim()
        ),
    },
    {
        key: 'status',
        labelKey: 'fields.package_status',
        width: '5%',
        cell: (el) => el['status']
            ? el['status'].replace(/-/g, ' ').replace(/\b\w/g, (c) => c.toUpperCase())
            : '',
    },
    { key: 'total', labelKey: 'fields.grand_total', width: '10%', cell: (el) => el['total'] ?? '' },
];

function getSelectedReportColumns() {
    const selected = $('.export-column-cb:checked').map(function () {
        return $(this).val();
    }).get();
    if (selected.length === 0) {
        return REPORT_COLUMN_DEFS.map((col) => col.key);
    }
    return REPORT_COLUMN_DEFS.map((col) => col.key).filter((key) => selected.includes(key));
}

function getActiveReportColumnDefs() {
    const selected = getSelectedReportColumns();
    return REPORT_COLUMN_DEFS.filter((col) => selected.includes(col.key));
}

function persistReportColumns() {
    const selected = $('.export-column-cb:checked').map(function () {
        return $(this).val();
    }).get();
    try {
        localStorage.setItem(REPORT_COLUMNS_STORAGE_KEY, JSON.stringify(selected));
    } catch (e) {}
}

function restoreReportColumns() {
    let saved = null;
    try {
        saved = JSON.parse(localStorage.getItem(REPORT_COLUMNS_STORAGE_KEY) || 'null');
    } catch (e) {
        saved = null;
    }
    if (!Array.isArray(saved) || saved.length === 0) {
        return;
    }
    $('.export-column-cb').each(function () {
        $(this).prop('checked', saved.includes($(this).val()));
    });
}

$(document).ready(function () {
    restoreReportColumns();
});

$(document).on('change', '#filter_date', function () {
    if ($(this).val() == 1) {
        $('.dates_div').hide();
    } else {
        $('.dates_div').fadeIn();
    }
});

$(document).ready(function () {
    let isProcessing = false;
    $('.all-select').on('change', function () {
        if (isProcessing) return;
        isProcessing = true;

        let $select = $(this);
        let allOptionValue = 'all';

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

$(document).on('change', '.export-column-cb', function () {
    if ($('.export-column-cb:checked').length === 0) {
        $(this).prop('checked', true);
        $('#notifDiv')
            .fadeIn()
            .css('background', 'red')
            .text('Select at least one column')
            .delay(2500)
            .fadeOut();
    }
    persistReportColumns();
});

$(document).on('click', '#export_columns_all', function () {
    $('.export-column-cb').prop('checked', true);
    persistReportColumns();
});

$(document).on('click', '#export_columns_none', function () {
    $('.export-column-cb').prop('checked', false);
    $('.export-column-cb[value="guide"]').prop('checked', true);
    persistReportColumns();
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
    const selectedColumns = getSelectedReportColumns();
    const activeColumns = getActiveReportColumnDefs();

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
                const headerHtml = activeColumns.map((col) => {
                    const style = col.key === 'description' ? ' style="width:80px!important;"' : '';
                    return `<th${style}>${Lang.get(col.labelKey)}</th>`;
                }).join('');

                $('.report_append_div').append(`
                    <table class="table table-hover dt-responsive nowrap" id="packagesTable" style="width:100%;">
                        <thead>
                            <tr>${headerHtml}</tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                `);

                const packages = response.data;
                if (packages.length > 0) {
                    $('.exportExcelBtn').fadeIn();
                }
                packages.forEach((element) => {
                    const cells = activeColumns.map((col) => `<td>${col.cell(element)}</td>`).join('');
                    $('#packagesTable tbody').append(`<tr>${cells}</tr>`);
                });

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
                            orientation: 'landscape',
                            title: '',
                            exportOptions: {
                                columns: ':visible'
                            },
                            customize: function (doc) {
                                const pdfWatermarkLabels = (window.brandPdfWatermarkLabels || ['wwc', 'wwt']).map((label) => label.toLowerCase());
                                doc.content = doc.content.filter((item) => {
                                    if (!item.text) {
                                        return true;
                                    }
                                    return !pdfWatermarkLabels.includes(String(item.text).toLowerCase());
                                });

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

                                doc.content.splice(1, 0, {
                                    text: organizationName,
                                    fontSize: 18,
                                    alignment: 'center',
                                    bold: true,
                                    margin: [0, 10, 0, 20],
                                });

                                doc.styles.tableHeader = {
                                    fillColor: '#eb973c',
                                    color: 'white',
                                    alignment: 'center',
                                    bold: true,
                                    fontSize: 10,
                                };
                                if (doc.content[2] && doc.content[2].table) {
                                    const widths = activeColumns.map((col) => col.width);
                                    const totalWeight = widths.reduce((sum, w) => sum + parseFloat(w), 0) || 1;
                                    doc.content[2].table.widths = widths.map((w) => ((parseFloat(w) / totalWeight) * 100).toFixed(2) + '%');
                                }

                                const bodyFontSize = 8;
                                doc.styles.tableBodyEven = {
                                    fontSize: bodyFontSize,
                                };
                                doc.styles.tableBodyOdd = {
                                    fontSize: bodyFontSize,
                                };

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
                                    margin: [0, 20, 0, 0],
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
});

$(document).on('click', '.exportExcelBtn', function () {
    var origins = $('#origins').val();
    var filter_date = $('#filter_date').val();
    var start_date = $('#start_date').val();
    var end_date = $('#end_date').val();
    var branches = $('#branches').val();
    var statuses = $('#statuses').val();
    var types = $('#types').val();
    var clients = $('#clients').val();
    var columns = getSelectedReportColumns();

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
        end_date: end_date,
        columns: columns
    };
    var query = $.param(filters);
    var exportUrl = '/admin/export-packages-excel?' + query;
    window.location.href = exportUrl;
});
