# hook-manager
Simple class to help manage hooks when writing WordPress plugins

## Usage Example

AdminHooks.php
```php
<?php

use KTHolland\HookManager;
use KTHolland\HookManager\ActionHook;
use KTHolland\HookManager\FilterHook;

class AdminHooks {

    protected $hookManager;
    
    public function __construct(HookManager $hookManager){
    
        $this->hookManager = $hookManager;
        
    }
    
    public function load(){
    
        $this->hookManager
            ->addHook(new ActionHook('init', $this, 'registerPostType'))
            ->addHook(new ActionHook('wp_enqueue_scripts', $this, 'enqueueScripts', 10))
            ->addHook(new ActionHook('wp_insert_post', $this, 'appendUserSignatureToNewPosts', 10, 3))
            
            // This hook is being marked as removable
            ->addHook(new ActionHook('wp_update_post', $this, 'updatePostRevisionCount', 10, 2), true);
    }
    
    public function enqueueScripts(){
    
	      wp_enqueue_style( 'core', 'my-style.css', false );
	      wp_enqueue_script( 'my-js', 'my-script.js', false );
    
    }
    
    public function appendUserSignatureToNewPosts($post_id, $post, $update){
    
        $post->post_content = $post->post_content
          . get_user_meta($post->post_author, 'signature', true) ?: '';
        
        // We don't want updateRevisionCount to fire because of update.
        // Remove all hooks flagged as removable.
        $this->hookManager->unloadRemovableHooks();
        
        wp_update_post($post);
        
        // Restore removed hooks.
        $this->hookManager->loadRemovableHooks();
        
    }
    
    public function updatePostRevisionCount($post, $wp_error){
    
        $meta_key = 'revision_count';
        
        $prev_count = (int) get_postmeta($post->ID, $meta_key, true);
        
        $new_count = $prev_count + 1;
        
        update_post_meta( $post->ID, $meta_key, $new_count, $prev_count );
        
    }
    
}
```

PublicHooks.php
```php
<?php

use KTHolland\HookManager;
use KTHolland\HookManager\ActionHook;
use KTHolland\HookManager\FilterHook;


class PublicHooks {

    protected $hookManager;
    
    public function __construct(HookManager $hookManager){
    
        $this->hookManager = $hookManager;
        
    }
    
    public function load(){
    
        $this->hookManager
            ->addHook(new FilterHook('single_template', $this, 'singleTemplate', 99));
    
    }
    
    public function singleTemplate($single){
    
        $custom_single_template = 'path/to/single-template.php'
    
        if(file_exists($custom_single_template)){
        
            return $custom_single_template;
            
        }
        
        return $single
        
    }
}
```

Within your my-plugin.php file
```php
<?php

use MyPluginNamespace\AdminHooks;
use MyPluginNamespace\PublicHooks;
use KTHolland\HookManager;

if(is_admin()){

    (new AdminHooks( new HookManager() ))->load();
    
}

(new PublicHooks( new HookManager() ))->load();
```
