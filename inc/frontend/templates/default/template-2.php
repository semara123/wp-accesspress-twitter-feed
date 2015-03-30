<div class="aptf-tweets-wrapper aptf-template-2"><?php
    if (is_array($tweets)) {

// to use with intents
        //echo '<script type="text/javascript" src="//platform.twitter.com/widgets.js"></script>';

        foreach ($tweets as $tweet) {
            //$this->print_array($tweet);
            ?>

            <div class="aptf-single-tweet-wrapper">
                <div class="aptf-tweet-content">
                    <?php if ($aptf_settings['display_username'] == 1) { ?><a href="http://twitter.com/<?php echo $username; ?>" class="aptf-tweet-name" target="_blank"><?php echo $display_name; ?></a> <span class="aptf-tweet-username"><?php echo $username; ?></span> <?php } ?>
                    <div class="clear"></div>
                    <?php
                    if ($tweet['text']) {
                        $the_tweet = $tweet['text'] . ' '; //adding an extra space to convert hast tag into links
                        /*
                          Twitter Developer Display Requirements
                          https://dev.twitter.com/terms/display-requirements

                          2.b. Tweet Entities within the Tweet text must be properly linked to their appropriate home on Twitter. For example:
                          i. User_mentions must link to the mentioned user's profile.
                          ii. Hashtags must link to a twitter.com search with the hashtag as the query.
                          iii. Links in Tweet text must be displayed using the display_url
                          field in the URL entities API response, and link to the original t.co url field.
                         */

                        // i. User_mentions must link to the mentioned user's profile.
                        if (is_array($tweet['entities']['user_mentions'])) {
                            foreach ($tweet['entities']['user_mentions'] as $key => $user_mention) {
                                $the_tweet = preg_replace(
                                        '/@' . $user_mention['screen_name'] . '/i', '<a href="http://www.twitter.com/' . $user_mention['screen_name'] . '" target="_blank">@' . $user_mention['screen_name'] . '</a>', $the_tweet);
                            }
                        }

                        // ii. Hashtags must link to a twitter.com search with the hashtag as the query.
                        if (is_array($tweet['entities']['hashtags'])) {
                            foreach ($tweet['entities']['hashtags'] as $hashtag) {
                                $the_tweet = str_replace(' #' . $hashtag['text'] . ' ', ' <a href="https://twitter.com/search?q=%23' . $hashtag['text'] . '&src=hash" target="_blank">#' . $hashtag['text'] . '</a> ', $the_tweet);
                            }
                        }

                        // iii. Links in Tweet text must be displayed using the display_url
                        //      field in the URL entities API response, and link to the original t.co url field.
                        if (is_array($tweet['entities']['urls'])) {
                            foreach ($tweet['entities']['urls'] as $key => $link) {
                                $the_tweet = preg_replace(
                                        '`' . $link['url'] . '`', '<a href="' . $link['url'] . '" target="_blank">' . $link['url'] . '</a>', $the_tweet);
                            }
                        }

                        echo $the_tweet . ' ';
                        ?>
                    </div><!--tweet content-->
                    <div class="aptf-tweet-date">
                        <?php
                        // 3. Tweet Actions
                        //    Reply, Retweet, and Favorite action icons must always be visible for the user to interact with the Tweet. These actions must be implemented using Web Intents or with the authenticated Twitter API.
                        //    No other social or 3rd party actions similar to Follow, Reply, Retweet and Favorite may be attached to a Tweet.
                        // get the sprite or images from twitter's developers resource and update your stylesheet
                        //  echo '
//        <div class="twitter_intents">
//            <p><a class="reply" href="https://twitter.com/intent/tweet?in_reply_to='.$tweet['id_str'].'">Reply</a></p>
//            <p><a class="retweet" href="https://twitter.com/intent/retweet?tweet_id='.$tweet['id_str'].'">Retweet</a></p>
//            <p><a class="favorite" href="https://twitter.com/intent/favorite?tweet_id='.$tweet['id_str'].'">Favorite</a></p>
//        </div>';  
                        // 4. Tweet Timestamp
                        //    The Tweet timestamp must always be visible and include the time and date. e.g., “3:00 PM - 31 May 12”.
                        // 5. Tweet Permalink
                        //    The Tweet timestamp must always be linked to the Tweet permalink.
                        ?>

                        <p class="aptf-timestamp">
                            <a href="https://twitter.com/<?php echo $username; ?>/status/<?php echo $tweet['id_str']; ?>" target="_blank"> -
                                <?php echo $this->get_date_format($tweet['created_at'], $aptf_settings['time_format']); ?>
                            </a>
                        </p>

                        <?php
                    } else {
                        ?>

                        <p><a href="http://twitter.com/'<?php echo $username; ?> " target="_blank"><?php _e('Click here to read ' . $username . '\'S Twitter feed', APTF_TD); ?></a></p>
                        <?php
                    }
                    ?>
                </div><!--tweet_date-->
                <?php if (isset($aptf_settings['display_twitter_actions']) && $aptf_settings['display_twitter_actions'] == 1) { ?>
                    <!--Tweet Action -->
                    <?php include(plugin_dir_path(__FILE__) . '../tweet-actions.php'); ?>
                    <!--Tweet Action -->
                <?php } ?>
            </div><!-- single_tweet_wrap-->
            <?php
        }
    }
    ?>
</div>
<?php if(isset($aptf_settings['display_follow_button']) && $aptf_settings['display_follow_button']==1){
    include(plugin_dir_path(__FILE__) . '../follow-btn.php');
}
?>