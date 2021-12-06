var TableManaged = function () {

    return {

        //main function to initiate the module
        init: function () {

            if (!jQuery().dataTable) {
                return;
            }

            // begin first table
            $('#sample_1').dataTable({
                "aoColumns": [
                    null,
                    {"bSortable":false},
                    { "bSortable": false},
                    null,
                    { "bSearchable": false, "bSortable": false},
                ],
                "aLengthMenu": [
                    [5, 15, 20, -1],
                    [5, 15, 20, "All"] // change per page values here
                ],
                // set the initial value
                "iDisplayLength": 5,
                "sPaginationType": "bootstrap",
                "oLanguage": {
                    "sLengthMenu": 'Mostrar <select>'+
                    '<option value="10">5</option>'+
                    '<option value="20">15</option>'+
                    '<option value="30">20</option>'+
                    '<option value="-1">Todos</option>'+
                    '</select> exámenes',
                    "sZeroRecords": "No hay exámenes para mostrar",
                    "oPaginate": {
                        "sPrevious": "Ant",
                        "sNext": "Sig"
                    },
                    "sSearch": "Buscar:",
                    "sInfo": "Mostrando exámenes desde el _START_ hasta el _END_ ( _TOTAL_ exámenes registrados)",
                    "sInfoFiltered": "(_MAX_ exámenes filtrados)",
                    "sInfoEmpty": "No hay exámenes para mostrar",


                },
            });


            jQuery('#sample_1_wrapper .dataTables_filter input').addClass("form-control input-medium input-inline"); // modify table search input
            jQuery('#sample_1_wrapper .dataTables_length select').addClass("form-control input-xsmall"); // modify table per page dropdown
            //jQuery('#sample_1_wrapper .dataTables_length select').select2(); // initialize select2 dropdown



            jQuery('#sample_2_wrapper .dataTables_filter input').addClass("form-control input-small input-inline"); // modify table search input
            jQuery('#sample_2_wrapper .dataTables_length select').addClass("form-control input-xsmall"); // modify table per page dropdown
            jQuery('#sample_2_wrapper .dataTables_length select').select(); // initialize select2 dropdown

        }

    };

}();