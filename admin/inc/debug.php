<h2>Debug</h2>
<p>Doc:
  <a target=wppus_doc href=https://github.com/froger-me/wp-plugin-update-server/blob/master/README.md>Readme</a>
  <a target=wppus_doc href=https://github.com/froger-me/wp-plugin-update-server/blob/master/licenses.md>licenses</a>
  <a target=wppus_doc href=https://github.com/froger-me/wp-plugin-update-server/blob/master/packages.md>packages</a>
</p>
<div class='debugzone'>
  <pre><?php
    $test_product_id = 230;
    $result = woopus_update_product_details( [ 'ID' => $test_product_id ] );
    echo "result: $result";

    // $content_post = get_post($test_product_id);
    // $content = $content_post->post_content;
    // $content = apply_filters('the_content', $content);
    // $content = str_replace(']]>', ']]&gt;', $content);

$content = apply_filters('the_content', get_post_field('post_content', $test_product_id));

    echo "</pre>" . $content . "<pre>";
    // foreach ($sections as $meta_key => $section) {
    //   echo "$meta_key";
    //   echo "</pre>";
    //   echo "<h2>" . $section['title'] . "</h2>";
    //   echo $section['content'];
    //   echo "<pre>";
    // }
?></pre>
</div>
