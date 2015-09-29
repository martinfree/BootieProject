<?php namespace App\Controllers;

require SP . 'app/models/PostTag.php';
require SP . 'app/models/Post.php';
require SP . 'app/models/File.php';
require SP . 'app/models/Tag.php';

class BlogController  {
	
	public function index(){

		$posts_ids = $tags_ids = $tag_obs = $tags =  [];

		/* posts all */
		$posts = \App\Models\Post::fetch([
			'lang' => "en"
		],0,0,[
			'id' => 'DESC'
		]);

		/* all tags */
		foreach($posts as $post){
			$posts_ids[] = $post->id;
		}

		if(count($posts_ids)){
			$tag_obs = \App\Models\PostTag::fetch([
				'post_id IN(' . implode(',',$posts_ids) . ')'
			]);

			foreach($tag_obs as $tag){
				$tags_ids[] = $tag->tag_id;
			}
		}

		if(count($tags_ids)){
			$tags = \App\Models\Tag::fetch([
				'id IN(' . implode(',',array_unique($tags_ids)) . ')'
			]);
		}

		return \App::view('blog.index',[
			'posts'	=> $posts,
			'tags'	=> $tags
		]);
	}

	public function tag($path,$tag){

		$posts_ids = $tag_obs = $tags_ids = $tags =  [];

		$tag_id = \App\Models\Tag::column([
			'tag' => $tag
		]);

		if(is_numeric($tag_id)){

			/* find posts by tag */

			$tag_obs = \App\Models\PostTag::fetch([
				'tag_id' => $tag_id
			]);

			foreach($tag_obs as $tag2){
				$posts_ids[] = $tag2->post_id;
			}

			if(count($posts_ids)){
				$posts = \App\Models\Post::fetch([
					'id IN(' . implode(',',array_unique($posts_ids)) . ')'
				]);
			}


			$posts_ids = [];
			/* posts all */
			$posts_all = \App\Models\Post::fetch([
				'lang' => "en"
			],0,0,[
				'id' => 'DESC'
			]);

			/* all tags except this */
			foreach($posts_all as $post){
				$posts_ids[] = $post->id;
			}

			if(count($posts_ids)){
				$tag_obs = \App\Models\PostTag::fetch([
					'post_id IN(' . implode(',',$posts_ids) . ')'
				]);

				foreach($tag_obs as $tag2){
					if($tag2->tag_id == $tag_id) continue;
					$tags_ids[] = $tag2->tag_id;
				}
			}

			if(count($tags_ids)){
				$tags = \App\Models\Tag::fetch([
					'id IN(' . implode(',',array_unique($tags_ids)) . ')'
				]);
			}

			return \App::view('blog.tags',[
				'posts'	=> $posts,
				'tags'	=> $tags,
				'tag'	=> $tag
			]);
		}

		return \App::view('errors.missing');			
	}

	public function show($path,$slug){

		$tags_ids = [];

		$entry = \App\Models\Post::row([
			'slug' => urldecode($slug),
			'lang' => "en"
		]);

		if($entry) {

			$posts_ids = $related = [];

			foreach($entry->tags() as $tag){
				if( isset($tag->id)){
					$tags_ids[] = $tag->id;
				}
			}

			if(count($tags_ids)){
				$tag_obs = \App\Models\PostTag::fetch([
					'tag_id IN(' . implode(',',array_unique($tags_ids)) . ')'
				]);

				foreach($tag_obs as $tag){
					if($tag->post_id == $entry->id) continue;
					$posts_ids[] = $tag->post_id;
				}

				if(count($posts_ids)){
					$related = \App\Models\Post::fetch([
						'id IN(' . implode(',',array_unique($posts_ids)) . ')'
					]);
				}
			}

			return \App::view('blog.entry',[
				'entry'	=> $entry,
				'related' => $related
			]);
		} 

		return \App::view('errors.missing');
	}

	public function files($path,$id){
		$files = [];

		if($id){
			$files = \App\Models\File::select('fetch','*',null,[
				'post_id' => $id
			]);
		}

		return $files;
	}	
}