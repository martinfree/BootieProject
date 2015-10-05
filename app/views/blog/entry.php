<div class="col-md-9">
    <h1 class="page-header"><?php echo $entry->title;?></h1>
    <blockquote><em><?php echo $entry->caption;?><br><?php echo date('Y M d',$entry->created);?></em></blockquote>
    <div class="">
        <?php foreach($entry->tags() as $tag) : if( ! isset($tag->tag)) continue;?>
            <a href="/blog/tag/<?php echo $tag->tag;?>" class="label label-success label-badge btn-tag-included"><?php echo $tag->tag;?></a>
        <?php endforeach;?>
        </div>
        <div class="group-control">&nbsp;</div>
        <div class="slick-dotted">
        <?php foreach($entry->files() as $file) : if( ! isset($file->name)) continue;?>
            <div class="image">
                <img class="img-responsive" src="/upload/posts/std/<?php echo $file->name;?>">
            </div>
        <?php endforeach;?>
    </div>
    <div class="group-control">&nbsp;</div>
    <div class="entry-content">
        <?php echo $entry->content;?>
    </div>
    <hr>
</div>
<div class="col-md-3">
    <ul class="ch-grid">
    <?php foreach($related as $post):?>
        <li>    
            <div class="ch-item" style="background-image: url(/upload/posts/thumb/<?php echo count($post->files()) ? $post->files()[0]->name : 'default';?>)">
                <div class="ch-title">
                    <h5>
                        <i class="ion-android-time"></i> <?php echo timespan($post->created);?>
                    </h5>
                    <h3><?php echo $post->title;?></h3>
                </div>
                <div class="ch-caption">
                    <h3><?php echo words($post->caption,20);?></h3>
                    <p>
                        <a href="/blog/<?php echo $post->slug;?>" class="btn btn-sm btn-success"><i class="ion-ios-glasses"></i> Read</a>
                    </p>
                </div>
            </div>
        </li>
    <?php endforeach;?>
    </ul>
</div>