<?php
    echo $handlebars->render("new_post", [
        'fullname' => $CUR_USER['fullname'],
        'nickname' => $CUR_USER['nickname'],
    ]);
?>

<div id="feed-posts">
    <?php
        $posts_query = "SELECT * FROM `posts` ORDER BY `date` DESC LIMIT 20";
        $posts_stmt = $GLOBALS['link']->query($posts_query);
    ?>

    <?php
        while ($post = $posts_stmt->fetch()) {
            $poster = get_user_row_by_id($post['user_id']);
            $post_id = $post['id'];
            $num_hearts = $GLOBALS['link']->query("SELECT * FROM `posts_hearts` WHERE `post_id` = {$post_id}")->rowCount();
            $num_comments = $GLOBALS['link']->query("SELECT * FROM `posts_comments` WHERE `post_id` = {$post_id}")->rowCount();

            echo $handlebars->render("post", [
                'postid' => $post['id'],
                'userid' => $post['user_id'],
                'fullname' => $poster['fullname'],
                'text' => nl2br($post['text']),
                'time' => $post['date'],
                'num_hearts' => $num_hearts,
                'num_comments' => $num_comments,
                'hearted' => $GLOBALS['link']->query("SELECT * FROM `posts_hearts` WHERE `post_id` = {$post_id} AND `user_id` = {$_SESSION['user_id']}")->rowCount() > 0
            ]);
        }
    ?>
</div>

<script id="post-template" type="text/x-handlebars-template">
    <?php include 'templates/post.hbs'; ?>
</script>

<script src="<?php echo $URL; ?>/js/feed.js"></script>