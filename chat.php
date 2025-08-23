<?php
include 'includes/auth.php';
include 'config.php';
?>
<?php include 'includes/common-header.php'; ?>

<div class="main-content">

    <div class="page-content">
        <div class="container-fluid">

            <div class="row">
                <div class="col-12">
                    <div class="page-title-box d-sm-flex align-items-center justify-content-between bg-galaxy-transparent">
                        <h4 class="mb-sm-0">Chat</h4>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <ul id="userList" class="list-group"></ul>
                </div>
                <div class="col-md-8">
                    <div id="chatBox" style="height:400px; overflow-y:auto; border:1px solid #dee2e6; padding:10px; margin-bottom:10px;"></div>
                    <div class="mb-2">
                        <input type="file" id="attachmentInput" class="form-control">
                    </div>
                    <div class="input-group">
                        <input type="text" id="messageInput" class="form-control" placeholder="Type a message">
                        <button class="btn btn-primary" id="sendBtn">Send</button>
                    </div>
                </div>
            </div>

        </div>
        <!-- container-fluid -->
    </div>
    <!-- End Page-content -->

    <?php include 'includes/footer.php'; ?>
</div>
<!-- end main content-->

<script>const myId = <?php echo (int)$_SESSION['user_id']; ?>;</script>
<script src="assets/js/chat.js"></script>
<?php include 'includes/common-footer.php'; ?>
