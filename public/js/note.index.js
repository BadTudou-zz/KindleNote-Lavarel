/**
 * Created by BadTudou on 20/07/2017.
 */
$(document).ready(function() {
    $('#div-batch').hide();
    $('.batch').toggle();

    $('input[name="batch[]"]').click(function(){
        var count = $('input[name="batch[]"]:checked').length;
        console.log('ddd'+count);
        $('#span-chooseNoteNumber').text(count);
    });
});

var isCheckBoxShow = false;

function batch(action) {
    var url = $('#batchForm').attr('action');
    var id_array = new Array();

    event.preventDefault();
    $('input[name="batch[]"]:checked').each(function(){
        id_array.push($(this).attr('id').substr(5));
    });

    var ids = id_array.join(',');
    var token = $('meta[name="csrf-token"]').attr('content');
    console.log(id_array.join(','));

    $.ajax({
        type: "POST",
        url: url,
        data: {"action":action, "ids":ids, "_token":token},
        dataType: "json",
        success: function(result){
            console.log(result);
            if(result.state){

                $('.batch').hide();
                $('#div-batch').hide();

                switch (action){
                    case 'download':
                        window.location.href = result.data+'&_token='+token;
                        break;
                    case 'delete':
                        window.location.href = result.data;
                        break;
                }
            }else{
                console.log('error');
            }
        },
        error:function(xhr){
            console.log(xhr.responseText);
        }
    });

}
