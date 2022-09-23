<?php
class Post
{
    private $user_obj;
    private $con;

    public function __construct($con, $user)
    {
        $this->con = $con;
        $this->user_obj = new User($con, $user);
    }

    public function submitPost($body, $user_to)
    {
        $body = strip_tags($body); //remove html tags
        $body = mysqli_real_escape_string($this->con, $body);
        $body = str_replace('\r\n', '\n', $body); //replace CR + LF  with LF
        $body = nl2br($body); //new line to line break
        $check_empty = preg_replace('/\s+/', '', $body); //delete all spaces


        if ($check_empty !== "") {

            //current date and time
            $date_added = date("Y-m-d H:i:s");

            //Get username
            $added_by = $this->user_obj->getUsername();

            //if user is on own profile, user_to is 'none'
            if ($user_to === $added_by) {
                $user_to = 'none';
            }

            //insert post
            $query = mysqli_query($this->con, "INSERT INTO posts VALUES(NULL, '$body', '$added_by', '$user_to', '$date_added', 'no', 'no', '0')");
            $returned_id = mysqli_insert_id($this->con);

            //Insert notification

            //Update post count for user
            $num_posts = $this->user_obj->getNumPosts();
            $num_posts++;
            $update_query = mysqli_query($this->con, "UPDATE users SET num_posts='$num_posts' WHERE username='$added_by'");
        }
    }

    public function loadPostsFriends($data, $limit)
    {
        $page = $data['page'];
        $userLoggedIn = $this->user_obj->getUsername();

        if ($page === 1)
            $start = 0;
        else
            $start = ($page - 1) * $limit;

        $str = ""; //to be returned
        $data_query = mysqli_query($this->con, "SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");

        if (mysqli_num_rows($data_query) > 0) {

            $num_iterations = 0; //number of results checked (not necessarly posted)
            $count = 1;

            while ($row = mysqli_fetch_array($data_query)) {
                $id = $row['id'];
                $body = $row['body'];
                $added_by = $row['added_by'];
                $date_time = $row['date_added'];

                if ($row['user_to'] === 'none') {
                    $user_to = ""; //include posts where user_to = 'none'
                } else {
                    $user_to_obj = new User($this->con, $row['user_to']);
                    $user_to_name = $user_to_obj->getFirstAndLastName();
                    $user_to = "<a href='" . $row['user_to'] . "'>" . $user_to_name . "</a>";
                }

                //check if account of posting user is closed
                $added_by_obj = new User($this->con, $added_by);
                if ($added_by_obj->isClosed()) {
                    continue;
                }

                if ($num_iterations++ < $start) {
                    continue;
                }

                //Once 10 posts have been loaded, break
                if ($count > $limit) {
                    break;
                } else {
                    $count++;
                }

                $user_details_query = mysqli_query($this->con, "SELECT first_name, last_name, profile_pic FROM users WHERE username='$added_by'");
                $user_row = mysqli_fetch_array($user_details_query);
                $first_name = $user_row['first_name'];
                $last_name = $user_row['last_name'];
                $profile_pic = $user_row['profile_pic'];

                //Timeframe
                $date_time_now = date("Y-m-d H:i:s");
                $start_date = new DateTime($date_time); //Time of post
                $end_date = new DateTime($date_time_now); //Current time
                $interval = $start_date->diff($end_date); //Difference between dates
                if ($interval->y >= 1) {
                    if ($interval === 1)
                        $time_message = "vor " . $interval->y . " Jahr";
                    else $time_message = "vor " . $interval->y . " Jahren";
                } else if ($interval->m >= 1) {
                    if ($interval->d === 0) {
                        $days = "";
                    } else if ($interval->d === 1) {
                        $days = "und " . $interval->d . " Tag";
                    } else {
                        $days = "und " . $interval->d . " Tagen";
                    }

                    if ($interval->m === 1) {
                        $time_message = "vor " . $interval->m . " Monat " . $days;
                    } else {
                        $time_message = "vor " . $interval->m . " Monaten " . $days;
                    }
                } else if ($interval->d >= 1) {
                    if ($interval->d === 1) {
                        $time_message = "gestern";
                    } else {
                        $time_message = "vor " . $interval->d . " Tagen";
                    }
                } else if ($interval->h >= 1) {
                    if ($interval->h === 1) {
                        $time_message = "vor " . $interval->h . " Stunde";
                    } else {
                        $time_message = "vor " . $interval->h . " Stunden";
                    }
                } else if ($interval->i >= 1) {
                    if ($interval->i === 1) {
                        $time_message = "vor " . $interval->i . " Minute";
                    } else {
                        $time_message = "vor " . $interval->i . " Minuten";
                    }
                } else {
                    if ($interval->s < 20) {
                        $time_message = "gerade eben";
                    } else {
                        $time_message = "vor " . $interval->s . " Sekunden";
                    }
                }
                $str .= "<div class='status_post'>
                        <div class='post_top_area'>
                            <div class='post_profile_pic'>
                                <img src='$profile_pic'>
                            </div>
                            <div class='post_top_area_right'>
                                <div class='post_top_area_right_top'>
                                    <div class='posted_by'>
                                        <a href='$added_by'>$first_name $last_name</a>
                                        <div>$user_to</div>
                                        </div>
                                    <div class='time-message'>$time_message</div>
                                    </div>
                                    <div id='post_body'>
                                        $body
                                    </div>
                            </div>
                        </div>
                        <hr />
                    </div>
            ";
            } //end while
            if ($count > $limit) {
                $str .= "<input type ='hidden' class='nextPage' value='" . ($page + 1) . "'>
                        <input type='hidden' class='noMorePosts' value='false'>";
            } else {
                $str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align: center;'>Keine weiteren Posts</p>";
            }
        }
        echo $str;
    }
}
