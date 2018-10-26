<?php
class actions_word_count_by_page {
    function handle($params) {
    
        $script = <<<END
jQuery(document).ready(function($) {

    function updateTotal() {
        console.log('update total');
        var count = 0;
        var filter = $('input[name="filter"]').val();
        $('table > tbody > tr').each(function() {
            var path = $(this).find('td.path').text();
            if (path.indexOf(filter) === 0) {
                count += parseInt($(this).find('td.count').text());
                $(this).css('display', '');
            } else {
                $(this).css('display','none');
            }
        });
        
        $('.total').text(''+count);
    }
    
    $('input[name="filter"]').change(updateTotal);
    updateTotal();
});
   
END;
        echo "<!doctype html>\n<html><body><table><thead><tr><th>Page</th><th>Words</th></tr><tr><td>Filter: <input type='search' size='100' name='filter'/></td><td></td></tr><tr><td>Total</td><th class='total'></th></tr> </thead><tbody>";
        $res = xf_db_query("select request_url, sum(num_words) from swete_strings group by request_url");
        while ($row = xf_db_fetch_row($res)) {
            $path = preg_replace('#^https?\://[^/]+#', '', $row[0]);
            echo "<tr><td class='path'><a title='".$row[0]."' href='".$row[0]."'>".substr($path,0, 120)."</a></td><td class='count'>".$row[1]."</td></tr>";
        }
        echo "</tbody></table>
        
        <script   src=\"https://code.jquery.com/jquery-1.12.4.min.js\"   integrity=\"sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=\"   crossorigin=\"anonymous\"></script>
        <script>$script</script>
        </body>
        
        
        </html>";
    }
}