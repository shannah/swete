<?php
class actions_tm_update_word_index {
    function handle($params){
    
        header('Content-type: text/plain; charset="'.Dataface_Application::getInstance()->_conf['oe'].'"');
        
        $res = df_q("create table if not exists xf_tm_word_index (
            string_id int(11) unsigned not null,
            word varchar(32) not null,
            num_words int(10) ,
            language varchar(2),
            primary key (word, string_id))");
            
        
        df_q('delete from xf_tm_word_index');
        
        import('Dataface/ResultReader.php');
        $results = new Dataface_ResultReader('select string_id, string_value, language, num_words from xf_tm_strings', df_db());
        foreach ( $results as $row ){
            echo "Indexing [".$row->string_id."]\n";
            $str = $row->string_value;
            $str = strip_tags($str);
            if ( !in_array($row->language, array('zh','zt','ko','ja')) ){
                $str = html_entity_decode($str, ENT_COMPAT, 'UTF-8');
                $str = preg_replace('/  /', ' ', $str);
                $str = preg_replace('/[^a-zA-Z ]/', '', $str);
                $words = explode(' ', $str);
                foreach ( $words as $i=>$w ){
                    $words[$i] = substr($w, 0, 32);
                }
            } else {
                $words = preg_split('/(?<!^)(?!$)/u', $string ); 
            }
            
            $words = array_unique(array_map('strtolower',$words));
            
            $sql = "insert into xf_tm_word_index (string_id, word, num_words, language) values ";
            $tuples = array();
            foreach ( $words as $word ){
                $tuples[] = sprintf("(%d,'%s', %d, '%s')",
                    $row->string_id,
                    addslashes($word),
                    $row->num_words,
                    addslashes($row->language)
                );
            }
            
            $sql  .= implode(', ', $tuples);
            
            $res = df_q($sql);
            
        }
        echo "Indexing complete.";
        
        
            
            
    }
}