<?php
include("includes/header.php");
include("includes/classes/User.php");
include("includes/classes/Post.php");

if (isset($_POST['post'])) {
    $post = new Post($con, $userLoggedIn);
    $post->submitPost($_POST['post_text'], 'none');
    header("Location: index.php");
}

?>
<div class="feed-left">
    <div class="user-details column">
        <a href="<?= $userLoggedIn ?>"><img src="<?= $user['profile_pic'] ?>" alt="user profile picture"></a>
        <div class="user-details_left_right">
            <a href="<?= $userLoggedIn ?>">
                <?= $user['first_name'] . " " . $user['last_name'] ?>
            </a>
            <br />
            <?= "Posts: " . $user['num_posts'] . "<br />" ?>
            <?= "Likes: " . $user['num_likes'] . "<br />" ?>
        </div>
    </div>
    <div class="popular column">
    </div>
</div>
<div class="feed-right">
    <div class="main-column">
        <form class="post-form" action="index.php" method="POST">
            <div class="form-floating">
                <textarea class="form-control" placeholder="Teile deinen Freunden etwas cooles mit!" name="post_text" id="floatingTextarea"></textarea>
                <label for="floatingTextarea">Schreibe...</label>
            </div>
            <input type="submit" value="Posten" name="post" id="post_button">
            <hr />
        </form>

    </div>
    <div class="feed">
        <div class="posts_area"></div>
        <img id="loading" src="assets/img/icons/loading.gif" alt="loading icon">
    </div>

    <script>
        const userLoggedIn = '<?php echo $userLoggedIn; ?>';
        $(document).ready(function() {
            $('#loading').show();
            //original ajax request for loading first posts
            $.ajax({
                url: "includes/handlers/ajax_load_posts.php",
                type: "POST",
                data: "page=1&userLoggedIn=" + userLoggedIn,
                cache: false,

                success: function(data) {
                    $('#loading').hide();
                    $('.posts_area').html(data);
                }
            });
            $(window).scroll(function() {
                const height = $('.posts_area').height(); //div containing posts
                const scroll_top = $(this).scrollTop();
                const page = $('.posts_area').find('.nextPage').val();
                const noMorePosts = $('.posts_area').find('.noMorePosts').val();

                if ((document.body.scrollHeight === document.body.scrollTop + window.innerHeight) && noMorePosts === 'false') {
                    $('#loading').show();

                    const ajaxReq = $.ajax({
                        url: "includes/handlers/ajax_load_posts.php",
                        type: "POST",
                        data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
                        cache: false,

                        success: function(response) {
                            $('.posts_area').find('.nextPage').remove(); //removes current .nextpage
                            $('.posts_area').find('.noMorePosts').remove(); //removes current .noMorePosts
                            $('#loading').hide();
                            $('.posts_area').append(response);
                        }
                    });
                } //end if
                return false;
            }); //end $(window).scroll(function())
        });
    </script>



</div>
</body>

</html>