
    function add() {
        var new_chq_no = parseInt($('#total_chq').val()) + 1;
        var new_input = "<input type='text' id='new_" + new_chq_no + "' class='form-control' name='namaBuku[]' placeholder='Judul Buku' autocomplete='off'  >";
        var new_function = "<script type='text/javascript'>$(function() {$('#new_"+new_chq_no+"').autocomplete({source: 'php/autocompletebuku.php'});})</script>";
        $('#new_chq').append(new_function,new_input);
        $('#total_chq').val(new_chq_no)
    }

    function remove() {
        var last_chq_no = $('#total_chq').val();
        if (last_chq_no > 1) {
            $('#new_' + last_chq_no).remove();
            $('#total_chq').val(last_chq_no - 1);
        }
    }
