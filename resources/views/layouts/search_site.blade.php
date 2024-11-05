 <form method="get" action="{{ asset('search_site.php') }}" target="_blank" id="key_form">
    <table>
        <tr>
            <td>
                <input type="text" name="key_word" class="form-control" id="key_word" required>
            </td>
            <td>
                <a href="#!" class="btn btn-primary btn-sm" onclick="clean()"><i class="fas fa-search"></i></a>
            </td>
        </tr>
    </table>    
 </form>
 <script>
    function clean(){
        if($('#key_word').val()==""){
            alert('沒有輸入關鍵字');
        }else{
            $('#key_form').submit();
            $('#key_word').val("");
        }        
    }
 </script>