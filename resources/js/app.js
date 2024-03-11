require('./bootstrap');


// On Window Ready
$(function() {
    console.log("Ready");

    var minSlider  = $(".filter-container").find('.min-value');
    var maxSlider  = $(".filter-container").find('.max-value');

    // jQuery UI range slider for the servers section
    $( ".storage-slider" ).slider({
        range: true,
        min: 30000,
        max: 2000000,
        values: [ 30000, 280000 ],
        step: 10000,
        slide: function( event, ui ) {

            console.log(ui.values[0]);
            console.log(ui.values[1]);

            minSlider.html(ui.values[0]);
            maxSlider.html(ui.values[1]);
    
            // Changing the price text on the placeholder
            // $( "#price-range-placeholder" ).html( "$" + ThousandSeparate( ui.values[0] ) + " - $" + ThousandSeparate(ui.values[ 1 ]) );
    
            // // Changing the values on the hidden price range input
            // $( "#price-range" ).val( ui.values[ 0 ] + "-" + ui.values[ 1 ] );
        }
    });

    // AJAX Request to get the Servers list
    $.ajax({
        // url: "/api/servers",
        url: "/ajax/servers",
        method: 'GET',
        success:function(response){

            console.log(response);

            if(response.status == "success")
            {

            }
            else
            {

            }

        },
        error: function(data){
            console.log('Error:', data);
        }
    });

    // Datatables into the container
    var table = $('.data-table').DataTable({
        processing: true,
        orderable: true,
        // serverSide: true,
        oLanguage: {
        sEmptyTable: "No data found.",
        sProcessing:'Loading..'
        },

        ajax: {
            // url: '/api/servers',
            url: '/ajax/servers',
            dataSrc: ''
        },

        columns: [
            { data: 'model', name: 'model' },
            { data: 'ram', name: 'ram' },
            { data: 'hdd', name: 'hdd' },
            { data: 'location', name: 'location', orderable: false },
            { data: 'price', name: 'price', orderable: true },
            // { name: 'action', orderable: false, searchable: false}
        ],
        order: [[0, 'desc']],
    });

    // Function to search servers according to the values passed in our form
    function filter_search(ram = '', hdd = '', location = ''){

        $('.data-table').DataTable().destroy();

        var table = $('.data-table').DataTable({

            processing: true,
            oLanguage: {
            sEmptyTable: "There are no servers matching your criteria."
            },
            ajax: {
                url:"/api/servers",
                dataSrc: '',
                data:{
                    ram:ram,
                    hdd:hdd,
                    location:location,
                }
            },
            columns: [
                { data: 'model', name: 'model' },
                { data: 'ram', name: 'ram' },
                { data: 'hdd', name: 'hdd' },
                { data: 'location', name: 'location', orderable: false },
                { data: 'price', name: 'price', orderable: true },
            ],
            order: [[0, 'desc']],
        });

    }

    // Run Search
    $("#search-button").on("click", function(){

        // Variables that will hold the values of each filter option
        var ram = [];
        var hdd = $("#hdd_type").val();
        var location = $("#location").val();

        // Iterate through each checkbox with class "ram_checkbox"
        $('.ram_checkbox').each(function() {
            // Check if the checkbox is checked
            if ($(this).prop('checked')) {
                // Retrieve the value of the checkbox and add it to the array
                var value = $(this).val();
                ram.push(value);
            }
        });


        console.log(ram);

        filter_search(ram, hdd, location);

    });


})