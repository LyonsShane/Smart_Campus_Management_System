<?php
error_reporting(0);
include '../Includes/dbcon.php';
include '../Includes/session.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link href="img/logo/attnlg.png" rel="icon">
    <?php include 'includes/title.php'; ?>
    <link href="../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="../vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="css/ruang-admin.min.css" rel="stylesheet">
</head>

<body id="page-top">
<div id="wrapper">
    <!-- Sidebar -->
    <?php include "Includes/sidebar.php"; ?>
    <!-- Sidebar -->
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <!-- TopBar -->
            <?php include "Includes/topbar.php"; ?>
            <!-- Topbar -->

            <!-- Container Fluid-->
            <div class="container-fluid" id="container-wrapper">
                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                    <h1 class="h3 mb-0 text-gray-800">Chat</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="./">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Courses</li>
                    </ol>
                </div>

                <div class="row">

                    <div class="col-lg-2" style="padding: 10px">

                        <!-- Online/Offline Users Window -->
                        <div class="card mb-4" style="height: 100%">
                            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                                <h6 class="m-0 font-weight-bold text-primary">Online Users</h6>
                            </div>
                            <div class="card-body" id="online-users" style="background-color: #fff; padding: 10px">
                                <ul id="users-list"></ul>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-10" style="padding-top: 10px">

                        <!-- Chat Window -->
                        <div class="card mb-4" style="height: 70vh; overflow: hidden">
                            <div class="card-body" style="height: 100%; overflow: hidden">
                                <div class="" id="chat-box"
                                     style="height: 100%; background-color: #fff; box-sizing: border-box; overflow-y: scroll"></div>
                            </div>
                        </div>

                        <!-- Chat Form -->
                        <div class="card mb-4">
                            <div class="card-body"
                                 style="box-sizing: border-box; padding: 10px !important; margin: 0 !important">
                                <form method="post"
                                      style="box-sizing: border-box; padding: 0 !important; margin: 0 !important">
                                    <div class="form-group row mb-3"
                                         style="box-sizing: border-box; padding: 0 !important; margin: 0 !important">
                                        <div class="col-lg-10"
                                             style="box-sizing: border-box; padding: 0 !important; margin: 0 !important">
                                            <textarea class="form-control custom-font" required name="message"
                                                      id="message">Type Your Message Here...</textarea>
                                        </div>
                                        <div class="col-lg-2"
                                             style="box-sizing: border-box; padding: 10px 0 10px 0 !important; margin: 0 !important; display: flex; align-items: center; justify-content: flex-end;">
                                            <button id="sendBtn" type="submit" name="send" class="btn btn-primary">Send
                                                &nbsp; <i class="fa fa-paper-plane"></i></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            <!-- Footer -->
            <?php include "Includes/footer.php"; ?>
            <!-- Footer -->
        </div>
    </div>

    <!-- Scroll to top -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <style>
        .msg {
            margin: 7px 5px;
            padding: 12px;
            box-shadow: 2px 2px 5px rgba(0,0,0,0.3);
        }

        .left {
            background: #f7f7f7;
            float: left;
            clear: both;
            text-align: left !important;
            border-radius: 10px 10px 10px 0;
        }

        .right {
            background: #f3ffec;
            float: right;
            text-align: right !important;
            clear: both;
            border-radius: 10px 10px 0 10px;
        }
        
        .msg-sender {
            font-size: 13px;
            font-weight: bold;
            margin-top: 5px;
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .left .msg-sender {
            flex-direction: row-reverse;
            justify-content: flex-end;
        }

        .right .msg-sender {
            flex-direction: row;
            justify-content: flex-end;
        }
        
        .msg-bottom {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            font-size: 12px;
            gap: 5px;
        }

        .left .msg-bottom {
            flex-direction: row-reverse;
        }

        .right .msg-bottom {
            flex-direction: row;
        }

        .custom-font {
            font-family: Nunito, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji" !important;
            font-size: 15px !important;
        }
    </style>

    <script src="../vendor/jquery/jquery.min.js"></script>
    <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/ruang-admin.min.js"></script>
    <!-- Page level plugins -->
    <script src="../vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="../vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script>

        // Fetch messages
        function fetchMessages() {
            $.getJSON('chat/get_messages.php', function (messages) {
                $('#chat-box').html(messages.map(msg => `
                    <div class="msg ${msg.user_id == <?php echo $_SESSION['userId']; ?> && msg.user_type == '<?php echo $_SESSION['userType']; ?>' ? 'right' : 'left'}">
                        <div class="msg-message">${msg.message}</div>
                        <div class="msg-sender">
                            <div>${msg.firstName} ${msg.lastName}</div>
                            <div>(${msg.user_type})</div>
                        </div>
                        <div class="msg-bottom">
                            <div>${msg.created_at}</div>
                            <div>${msg.seen ? '✅✅' : '✅'}</div>
                        </div>
                    </div>
        `).join(''));
                $('#chat-box').scrollTop($('#chat-box')[0].scrollHeight);
            });
        }

        // Fetch online users
        function fetchOnlineUsers() {
            $.getJSON('chat/get_online_users.php', function (users) {
                $('#users-list').html(users.map(user => `<li>${user.name} (${user.type}) &nbsp; <i class="fa fa-user fa-1x" style="color: #1cc88a"></i> </li>`).join(''));
            });
        }

        // Send message
        $('#sendBtn').on('click', function () {
            const message = $('#message').val().trim();
            if (message) {
                $.post('chat/send_message.php', {message}, function () {
                    fetchMessages();
                    $('#message').val('');
                });
            }
        });

        // Update last seen and fetch data
        setInterval(fetchMessages, 2000);
        setInterval(fetchOnlineUsers, 2000);
        setInterval(() => $.post('chat/update_last_seen.php'), 2000);

        // Initial load
        fetchMessages();
        fetchOnlineUsers();

    </script>
    
    <script>
        $(document).ready(function() {
            $('textarea').focus(function() {
                $(this).val('');
            });
            $('textarea').keypress(function(e) {
                if (e.which === 13) { // 13 is the Enter key
                    e.preventDefault(); // Prevent new line
                    $('#sendBtn').click(); // Trigger click event
                }
            });
        });
    </script>

</body>

</html>