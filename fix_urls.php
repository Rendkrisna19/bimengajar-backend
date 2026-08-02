<?php

$articles = App\Models\Article::all();
foreach($articles as $a) {
    if($a->image) {
        $imgs = [];
        foreach($a->image as $i) {
            $imgs[] = str_replace('http://localhost/storage', 'http://localhost:8000/storage', $i);
        }
        $a->image = $imgs;
        $a->save();
    }
}

$news = App\Models\News::all();
foreach($news as $n) {
    if($n->image) {
        $imgs = [];
        foreach($n->image as $i) {
            $imgs[] = str_replace('http://localhost/storage', 'http://localhost:8000/storage', $i);
        }
        $n->image = $imgs;
        $n->save();
    }
}

echo "Database URLs updated.\n";
