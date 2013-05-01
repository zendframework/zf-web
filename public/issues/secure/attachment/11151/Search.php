<?php

class Uni_Search {
    public static function articlesIndex() {
        return "application/indexes/" . Zend_Registry::get('DEPLOYMENT_MODE') . "/articles-index";
    }
    
    public static function tipsIndex() {
        return "application/indexes/" . Zend_Registry::get('DEPLOYMENT_MODE') . "/tips-index";
    }

    public static function searchArticles( $query ) {
        $index = Zend_Search_Lucene::open(Uni_Search::articlesIndex());
        $results = $index->find($query);
        
        return $results;
    }
    
    public static function searchTips( $query ) {
        $index = Zend_Search_Lucene::open(Uni_Search::tipsIndex());
        $results = $index->find($query);
        
        return $results;
    }

    public static function buildIndex() {
        Zend_Registry::get('LOG')->log( "--------------------------------", Zend_Log::NOTICE);
        Zend_Registry::get('LOG')->log( "", Zend_Log::NOTICE);

        $articles_index = Uni_Search::articlesIndex();
        if ( !file_exists($articles_index) ) {
            Zend_Registry::get('LOG')->log( "Creating index: $articles_index", Zend_Log::NOTICE);
            $index = Zend_Search_Lucene::create($articles_index);
        } else {
            Zend_Registry::get('LOG')->log( "Updating index: $articles_index", Zend_Log::NOTICE);
            $index = Zend_Search_Lucene::open($articles_index);

            // Clear index
            Zend_Registry::get('LOG')->log( "Clearing index", Zend_Log::NOTICE);
            $pattern = new Zend_Search_Lucene_Index_Term('*', 'title');
            $query = new Zend_Search_Lucene_Search_Query_Wildcard($pattern);
            $hits  = $index->find($query);
            foreach ($hits as $hit) {
                $index->delete($hit->id);
            }
        }
        
        Zend_Registry::get('LOG')->log( "Loading Articles", Zend_Log::NOTICE);
        $table = Zend_Registry::get('DOCTRINE_CONNECTION')->getTable( 'Article' );
        $articles = $table->findAll();
        foreach( $articles as $article ) {
            Zend_Registry::get('LOG')->log( "  Adding article to index: $article->title", Zend_Log::NOTICE);
            $doc = Uni_Search::createDocFromArticle( $article );
            $index->addDocument( $doc );
        }
        Zend_Registry::get('LOG')->log( "", Zend_Log::NOTICE);
        Zend_Registry::get('LOG')->log( "--------------------------------", Zend_Log::NOTICE);
        
        
        $tips_index = Uni_Search::tipsIndex();
        if ( !file_exists($tips_index) ) {
            Zend_Registry::get('LOG')->log( "Creating index: $tips_index", Zend_Log::NOTICE);
            $index = Zend_Search_Lucene::create($tips_index);
        } else {
            Zend_Registry::get('LOG')->log( "Updating index: $tips_index", Zend_Log::NOTICE);
            $index = Zend_Search_Lucene::open($tips_index);

            // Clear index
            Zend_Registry::get('LOG')->log( "Clearing index", Zend_Log::NOTICE);
            $pattern = new Zend_Search_Lucene_Index_Term('*', 'title');
            $query = new Zend_Search_Lucene_Search_Query_Wildcard($pattern);
            $hits  = $index->find($query);
            foreach ($hits as $hit) {
                $index->delete($hit->id);
            }
        }
        
        Zend_Registry::get('LOG')->log( "Loading Tips", Zend_Log::NOTICE);
        $table = Zend_Registry::get('DOCTRINE_CONNECTION')->getTable( 'Tip' );
        $tips = $table->findAll();
        foreach( $tips as $tip ) {
            Zend_Registry::get('LOG')->log( "  Adding tip to index: $tip->title", Zend_Log::NOTICE);
            $index->addDocument( Uni_Search::createDocFromTip( $tip ) );
        }
        Zend_Registry::get('LOG')->log( "", Zend_Log::NOTICE);
        Zend_Registry::get('LOG')->log( "--------------------------------", Zend_Log::NOTICE);
    }
    
    public static function createDocFromArticle( $article ) {
        
        $category_names = array();
        $category_url_names = array();
        
        $present_id = 0;
        foreach( $article->Categories as $category ) {
            if ( $category->parent_id == $present_id ) {
                $category_names[] = $category->name;
                $category_url_names[] = $category->url_name;
                $present_id = $category->id;
            }
        }
                
        // Create new Search document
        $doc = new Zend_Search_Lucene_Document();

        // Document fields
        $doc->addField(Zend_Search_Lucene_Field::UnIndexed('id',         $article->id));
        $doc->addField(Zend_Search_Lucene_Field::UnIndexed('created_at', $article->created_at));
        $doc->addField(Zend_Search_Lucene_Field::UnIndexed('updated_at', $article->updated_at));
        $doc->addField(Zend_Search_Lucene_Field::UnIndexed('live_at',    $article->live_at));
        $doc->addField(Zend_Search_Lucene_Field::UnIndexed('expired_at', $article->expired_at));
        $doc->addField(Zend_Search_Lucene_Field::UnIndexed('type',       'Article'));
        if ( sizeof($category_names) > 0 ) {
            $doc->addField(Zend_Search_Lucene_Field::UnIndexed('category_url_name', $category_url_names[0]));
            $doc->addField(Zend_Search_Lucene_Field::Text(     'category_name',     $category_names[0]));        
        } else {
            $doc->addField(Zend_Search_Lucene_Field::UnIndexed('category_url_name', 'main'));
            $doc->addField(Zend_Search_Lucene_Field::Text(     'category_name',     'Main')); 
        }
        
        if ( sizeof($category_names) > 1 ) {
            $doc->addField(Zend_Search_Lucene_Field::UnIndexed('subcategory_url_name', $category_url_names[1]));
            $doc->addField(Zend_Search_Lucene_Field::Text(     'subcategory_name',     $category_names[1]));        
        } else {
            $doc->addField(Zend_Search_Lucene_Field::UnIndexed('subcategory_url_name', 'main'));
            $doc->addField(Zend_Search_Lucene_Field::Text(     'subcategory_name',     'Main')); 
        }
        $doc->addField(Zend_Search_Lucene_Field::Text(     'title',      strip_tags($article->title)));
        $doc->addField(Zend_Search_Lucene_Field::Text(     'teaser',     strip_tags($article->teaser)));
        $doc->addField(Zend_Search_Lucene_Field::Text(     'url_name',   $article->url_name));
        $doc->addField(Zend_Search_Lucene_Field::UnStored( 'body',       strip_tags($article->body)));

        return $doc;
    }
    
    public static function createDocFromTip( $tip ) {
        
        $category_names = array();
        $category_url_names = array();
        
        $present_id = 0;
        foreach( $tip->Categories as $category ) {
            if ( $category->parent_id == $present_id ) {
                $category_names[] = $category->name;
                $category_url_names[] = $category->url_name;
                $present_id = $category->id;
            }
        }
                
        // Create new Search document
        $doc = new Zend_Search_Lucene_Document();

        // Document fields
        $doc->addField(Zend_Search_Lucene_Field::UnIndexed('id',         $tip->id));
        $doc->addField(Zend_Search_Lucene_Field::UnIndexed('created_at', $tip->created_at));
        $doc->addField(Zend_Search_Lucene_Field::UnIndexed('updated_at', $tip->updated_at));
        $doc->addField(Zend_Search_Lucene_Field::UnIndexed('live_at',    $tip->live_at));
        $doc->addField(Zend_Search_Lucene_Field::UnIndexed('expired_at', $tip->expired_at));
        $doc->addField(Zend_Search_Lucene_Field::UnIndexed('type',       'Tip'));
        if ( sizeof($category_names) > 0 ) {
            $doc->addField(Zend_Search_Lucene_Field::UnIndexed('category_url_name', $category_url_names[0]));
            $doc->addField(Zend_Search_Lucene_Field::Text(     'category_name',     $category_names[0]));        
        } else {
            $doc->addField(Zend_Search_Lucene_Field::UnIndexed('category_url_name', 'Main'));
            $doc->addField(Zend_Search_Lucene_Field::Text(     'category_name',     'main')); 
        }
        
        if ( sizeof($category_names) > 1 ) {
            $doc->addField(Zend_Search_Lucene_Field::UnIndexed('subcategory_url_name', $category_url_names[1]));
            $doc->addField(Zend_Search_Lucene_Field::Text(     'subcategory_name',     $category_names[1]));        
        } else {
            $doc->addField(Zend_Search_Lucene_Field::UnIndexed('subcategory_url_name', 'Main'));
            $doc->addField(Zend_Search_Lucene_Field::Text(     'subcategory_name',     'main')); 
        }
        $doc->addField(Zend_Search_Lucene_Field::Text(     'title',      strip_tags($tip->title)));
        $doc->addField(Zend_Search_Lucene_Field::Text(     'url_name',   $tip->url_name));
        $doc->addField(Zend_Search_Lucene_Field::UnStored( 'body',       strip_tags($tip->body)));

        return $doc;
    }
}
